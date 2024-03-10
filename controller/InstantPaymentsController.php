<?php

namespace com\cminds\videolesson\controller;

use com\cminds\videolesson\model\Settings;
use com\cminds\videolesson\lib\InstantPayment;
use com\cminds\videolesson\model\Labels;
use com\cminds\videolesson\App;
use com\cminds\videolesson\model\ChannelSubscription;
use com\cminds\videolesson\model\Channel;
use com\cminds\videolesson\model\ChannelInstantPayment;

class InstantPaymentsController extends Controller {

    const NONCE_ACTIVATE         = 'cmvl_eddpay_init';
    const NONCE_SET_COSTS        = 'cmvl_eddpay_costs_nonce';
    const EDDPAY_CALLBACK_ACTION = 'cmvl_eddpay_payment_completed';

    protected static $filters = array(
        'cmvl_format_amount_payed' => array( 'args' => 2 ),
        'cmvl_options_config',
        'cmvl_settings_pages',
        'cmvl_settings_pages_groups',
    );
    protected static $actions = array(
// 		'add_meta_boxes',
        'admin_init',
// 		array('name' => 'save_post', 'args' => 1),
        array( 'name' => 'cmvl_labels_init', 'priority' => 20 ),
        array( 'name' => 'cmvl_channel_can_view', 'args' => 3 ),
        'cmvl_access_denied_content'   => array( 'method' => 'displayPaybox', 'args' => 1 ),
        'cmvl_channels_list_row'       => array( 'args' => 1 ),
        'cmvl_subscriptions_table_row' => array( 'args' => 1 ),
        'cmvl_channels_list_header',
        self::EDDPAY_CALLBACK_ACTION   => array( 'args' => 1 ),
    );
    protected static $ajax = array( 'cmvl_channel_eddpay' );
    protected static $suspendActions = 0;

    static function admin_init() {
// 		var_dump(ChannelInstantPayment::getGlobalCosts());exit;
    }

    static function cmvl_labels_init() {
        if ( ChannelInstantPayment::isAvailable() ) {
            Labels::loadLabelFile( App::path( 'asset/labels/instantpayments.tsv' ) );
        }
    }

// 	static function add_meta_boxes() {
// 		if (ChannelInstantPayment::isAvailable()) {
// 			add_meta_box( App::prefix('-instantpayments-costs'), 'CM Instant EDD Payments Costs', array(get_called_class(), 'channel_eddpay_costs_meta_box'),
// 			Channel::POST_TYPE, 'normal', 'high' );
// 		}
// 	}
// 	static function channel_eddpay_costs_meta_box($post) {
// 		if ($channel = Channel::getInstance($post)) {
// 			$instantPayment = new ChannelInstantPayment($channel);
// 			$costs = $instantPayment->getChannelCosts();
// 		} else {
// 			$costs = array();
// 		}
// 		wp_enqueue_script('cmvl-backend');
// 		echo self::loadBackendView('channel-costs-meta-box', compact('costs'));
// 	}
// 	static function save_post($post_id) {
// 		if (!static::$suspendActions AND $channel = Channel::getInstance($post_id)) {
// 			static::$suspendActions++;
// 			self::savePostCosts($channel);
// 			static::$suspendActions--;
// 		}
// 	}
// 	static protected function savePostCosts(Channel $channel) {
// 		$nonceField = self::NONCE_SET_COSTS;
// 		if (!empty($_POST[$nonceField]) AND wp_verify_nonce($_POST[$nonceField], $nonceField)) {
// 			$costs = array();
// 			if (!empty($_POST['cmvl-eddpay-number']) AND is_array($_POST['cmvl-eddpay-number'])) {
// 				foreach ($_POST['cmvl-eddpay-number'] as $i => $number) {
// 					if (!empty($_POST['cmvl-eddpay-cost'][$i]) AND !empty($_POST['cmvl-eddpay-unit'][$i])) {
// 						$unit = $_POST['cmvl-eddpay-unit'][$i];
// 						if ($seconds = ChannelSubscription::period2seconds($number . $unit)) { // valid period
// 							$costs[$seconds] = array(
// 								'period' => $number .' '. $unit,
// 								'number' => $number,
// 								'unit' => $unit,
// 								'seconds' => $seconds,
// 								'cost' => $_POST['cmvl-eddpay-cost'][$i]
// 							);
// 						}
// 					}
// 				}
// 			}
// 			$instantPayments = new ChannelInstantPayment($channel);
// 			$result = $instantPayments->setCosts($costs);
// 		}
// 	}



    static function cmvl_channel_can_view( $result, Channel $channel, $userId ) {
        if ( $result AND ChannelInstantPayment::isAvailable() ) {
            $instantPayment = new ChannelInstantPayment( $channel );
            if ( $instantPayment->isPayed() ) {
                $subscription = new ChannelSubscription( $channel );
                $result       = $subscription->isSubscriptionActive( $userId );
            }
        }
        return $result;
    }

    static function displayPaybox( Channel $channel = null ) {
        if ( $channel AND ChannelInstantPayment::isAvailable() AND $instantPayment = new ChannelInstantPayment( $channel ) AND $instantPayment->isPayed() ) {
            ChannelController::loadAssets();
            if ( is_user_logged_in() ) {
                if ( $subscription = new ChannelSubscription( $channel ) AND ! $subscription->isSubscriptionActive() ) {
                    $form = self::getPayboxForm( $channel );
                    echo self::loadFrontendView( 'paybox', compact( 'form' ) );
                }
            } else {
                echo self::loadFrontendView( 'paybox-guest' );
            }
        }
    }

    static function getPayboxForm( Channel $channel = null ) {
        if ( $channel AND ChannelInstantPayment::isAvailable() AND $instantPayment = new ChannelInstantPayment( $channel ) AND $instantPayment->isPayed() ) {
            if ( is_user_logged_in() ) {
                ChannelController::loadAssets();
                $costs        = $instantPayment->getCosts();
// 				var_dump($costs);
// 				var_dump(ChannelInstantPayment::getGlobalCosts());
                $channelId    = $channel->getId();
                $nonce        = wp_create_nonce( self::NONCE_ACTIVATE );
                if ( $subscription = new ChannelSubscription( $channel ) AND ! $subscription->isSubscriptionActive() ) {
                    return self::loadFrontendView( 'paybox-form', compact( 'costs', 'channelId', 'nonce' ) );
                }
            }
        }
    }

    static function cmvl_channel_eddpay() {
        header( 'content-type: application/json' );

        try {

            if ( !is_user_logged_in() )
                throw new \Exception( 'User is not logged in.' );
            if ( empty( $_POST[ 'callbackUrl' ] ) )
                throw new \Exception( 'Missing callback URL.' );
            if ( empty( $_POST[ 'channelId' ] ) )
                throw new \Exception( 'Missing channel ID.' );
            $channel         = Channel::getInstance( $_POST[ 'channelId' ] );
            if ( !$channel )
                throw new \Exception( 'Missing channel.' );
            $subscription    = new ChannelSubscription( $channel );
            if ( !$subscription )
                throw new \Exception( 'Invalid ChannelSubscription instance.' );
            $instantPayments = new ChannelInstantPayment( $channel );
            if ( !$instantPayments )
                throw new \Exception( 'Invalid InstantPayments instance.' );
            if ( !$instantPayments->isPayed() )
                throw new \Exception( 'Channel is not payed.' );
            if ( empty( $_POST[ 'nonce' ] ) )
                throw new \Exception( 'Missing nonce.' );
            if ( !wp_verify_nonce( $_POST[ 'nonce' ], self::NONCE_ACTIVATE ) )
                throw new \Exception( 'Invalid nonce.' );
            $costs           = $instantPayments->getCosts();
            if ( !$costs )
                throw new \Exception( 'Missing costs data.' );
            if ( empty( $_POST[ 'edd_download_id' ] ) )
                throw new \Exception( 'Missing EDD product ID param.' );
            if ( $subscription->isSubscriptionActive() )
                throw new \Exception( 'Subscription is already active.' );

            if ( $url = $instantPayments->initPayment( $_POST[ 'edd_download_id' ], $_POST[ 'callbackUrl' ] ) ) {
                $response = array( 'success' => true, 'msg' => Labels::getLocalized( 'eddpay_checkout_redirection' ), 'redirect' => $url );
            } else {
                throw new \Exception( 'Failed to initialize transaction.' );
            }
        } catch ( \Exception $e ) {
            $response = array( 'success' => false, 'msg' => Labels::getLocalized( $e->getMessage() ) );
        }

        echo json_encode( $response );
        exit;
    }

    static function cmvl_eddpay_payment_completed( $args ) {
        if ( isset( $args[ 'channelId' ] ) AND isset( $args[ 'subscriptionTimeSec' ] ) AND isset( $args[ 'userId' ] ) AND $channel = Channel::getInstance( $args[ 'channelId' ] ) ) {
            $subscriptionModel = new ChannelSubscription( $channel );
            try {
                $subscriptionModel->addSubscription(
                $args[ 'userId' ], $args[ 'subscriptionTimeSec' ], $args[ 'price' ], ChannelInstantPayment::PAYMENT_PLUGIN_NAME
                );
            } catch ( Exception $e ) {

            }
        }
    }

    static function cmvl_eddpay_callback( $params ) {
        if ( isset( $params[ 'transactionId' ] ) AND $transactionData = ChannelInstantPayment::getTransaction( $params[ 'transactionId' ] ) ) {
            $requestedSubscription = $transactionData[ 'transaction' ][ 'subscription' ];
            $subscriptionModel     = new ChannelSubscription( $transactionData[ 'channel' ] );
            try {
                $subscriptionModel->addSubscription(
                $requestedSubscription[ 'userId' ], $requestedSubscription[ 'cost' ][ 'seconds' ], $transactionData[ 'transaction' ][ 'request' ][ 'amount' ], ChannelInstantPayment::PAYMENT_PLUGIN_NAME
                );
            } catch ( Exception $e ) {

            }
        }
    }

    static function cmvl_channels_list_header() {
        if ( ChannelInstantPayment::isAvailable() ) {
            printf( '<th>%s</th>', Labels::getLocalized( 'channel_purchase' ) );
        }
    }

    static function cmvl_channels_list_row( Channel $channel ) {
        if ( $channel AND ChannelInstantPayment::isAvailable() AND $instantPayment = new ChannelInstantPayment( $channel ) AND $instantPayment->isPayed() ) {
            if ( is_user_logged_in() ) {
                echo '<td>' . self::getPayboxForm( $channel ) . '</td>';
            }
        }
    }

    static function cmvl_subscriptions_table_row( array $row ) {
        self::cmvl_channels_list_row( $row[ 'channel' ] );
    }

    static function cmvl_format_amount_payed( $amount, $plugin ) {
        if ( $plugin == ChannelInstantPayment::PAYMENT_PLUGIN_NAME ) {
            $amount = sprintf( Labels::getLocalized( 'eddpay_amount_payed_format' ), $amount );
        }
        return $amount;
    }

    static function cmvl_settings_pages( $categories ) {
        if ( ChannelInstantPayment::isAvailable() ) {
            $categories[ 'eddpay' ] = 'EDD Payments';
        }
        return $categories;
    }

    static function cmvl_settings_pages_groups( $subcategories ) {
        if ( ChannelInstantPayment::isAvailable() ) {
            $subcategories[ 'eddpay' ][ 'eddpay' ]  = 'EDD Payments';
            $subcategories[ 'eddpay' ][ 'pricing' ] = 'Price for all channels at once';
        }
        return $subcategories;
    }

    static function cmvl_options_config( $config ) {

        if ( !ChannelInstantPayment::isAvailable() ) {
            return $config;
        }

        if ( function_exists( '\edd_get_currency' ) ) {
            $currency = \edd_get_currency();
        } else {
            $currency = '';
        }

        $config[ Settings::OPTION_EDD_PAYMENT_MODEL ] = array(
            'type'        => Settings::TYPE_RADIO,
            'category'    => 'eddpay',
            'subcategory' => 'eddpay',
            'options'     => array(
                Settings::EDDPAY_MODEL_ALL_CHANNELS => 'All channel at once',
                Settings::EDDPAY_MODEL_PER_CHANNEL  => 'Per each channel',
            ),
            'default'     => Settings::EDDPAY_MODEL_PER_CHANNEL,
            'title'       => 'Payments model',
            'desc'        => 'Choose if user is paying for each channel separately or for all channels at once.',
        );

        return $config;
    }

}
