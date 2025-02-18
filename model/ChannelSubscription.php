<?php

namespace com\cminds\videolesson\model;

class ChannelSubscription extends Model {
	
	const META_SUBSCRIPTION = 'cmvl_mp_subscription';
	const META_SUBSCRIPTION_START = 'cmvl_mp_subscription_start';
	const META_SUBSCRIPTION_END = 'cmvl_mp_subscription_end';
	const META_SUBSCRIPTION_DURATION = 'cmvl_mp_subscription_duration';
	const META_SUBSCRIPTION_AMOUNT_PAID = 'cmvl_mp_subscription_points';
	const META_SUBSCRIPTION_PAYMENT_PLUGIN = 'cmvl_mp_subscription_payment_plugin';
	
	const PAYMENT_PLUGIN_ADMIN = 'admin';
	
	protected $channel;
	
	function __construct(Channel $channel) {
		$this->channel = $channel;
	}
	
	
	/**
	 * 
	 * @return \com\cminds\videolesson\model\IPaymentMethod
	 */
	function getPaymentModel() {
		$micropayments = new Micropayments($this->channel);
		if ($micropayments->isAvailable() AND $micropayments->isPayed()) return $micropayments;
		$instantPayments = new ChannelInstantPayment($this->channel);
		if ($instantPayments->isAvailable() AND $instantPayments->isPayed()) return $instantPayments;
	}
	
	
	function isPayed() {
		$paymentModel = $this->getPaymentModel();
		return (!empty($paymentModel));
	}
	
	
	
	static function isAvailable() {
		return (Micropayments::isAvailable() OR ChannelInstantPayment::isAvailable());
	}
	
	

	function addSubscription($userId, $periodSeconds, $amountPaid, $paymentPlugin, $start = null) {
	
		if (empty($start)) $start = time();
		$end = $start + $periodSeconds;
	
		$postId = $this->channel->getId();
		$metaId = add_post_meta($postId, self::META_SUBSCRIPTION, $userId, $unique = false);
		if ($metaId) {
				
			add_post_meta($postId, self::META_SUBSCRIPTION_START .'_'. $metaId, $start, $unique = true);
			add_post_meta($postId, self::META_SUBSCRIPTION_END .'_'. $metaId, $end, $unique = true);
			add_post_meta($postId, self::META_SUBSCRIPTION_DURATION .'_'. $metaId, $periodSeconds, $unique = true);
			add_post_meta($postId, self::META_SUBSCRIPTION_PAYMENT_PLUGIN .'_'. $metaId, $paymentPlugin, $unique = true);
			add_post_meta($postId, self::META_SUBSCRIPTION_AMOUNT_PAID .'_'. $metaId, $amountPaid, $unique = true);
				
			$this->notifyAdmin($userId, $start, $periodSeconds, $amountPaid, $paymentPlugin);
				
		}
	
	}
	
	
	function notifyAdmin($userId, $start, $periodSeconds, $amountPaid, $paymentPlugin) {
		if (Settings::getOption(Settings::OPTION_NEW_SUB_ADMIN_NOTIF_ENABLE) AND $user = get_userdata($userId)) {
			$end = $start + $periodSeconds;
			Email::send(
				$receivers = Settings::getOption(Settings::OPTION_NEW_SUB_ADMIN_NOTIF_EMAILS),
				$subject = Settings::getOption(Settings::OPTION_NEW_SUB_ADMIN_NOTIF_SUBJECT),
				$body = Settings::getOption(Settings::OPTION_NEW_SUB_ADMIN_NOTIF_TEMPLATE),
				array(
					'[blogname]' => get_option('blogname'),
					'[home]' => get_option('home'),
					'[channelname]' => $this->channel->getTitle(),
					'[permalink]' => $this->channel->getPermalink(),
					'[username]' => $user->display_name,
					'[userlogin]' => $user->user_login,
					'[startdate]' => Date('Y-m-d H:i:s', $start),
					'[enddate]' => Date('Y-m-d H:i:s', $end),
					'[duration]' => ChannelSubscription::seconds2period($periodSeconds),
					'[points]' => $points,
					'[amount]' => $amountPaid,
					'[paymentPlugin]' => $paymentPlugin,
					'[reportlink]' => SubscriptionsController::getUrl(),
				)
			);
		}
	}
	
	

	function isSubscriptionActive($userId = null) {
		global $wpdb;
		if (is_null($userId)) $userId = get_current_user_id();
		
		$where = '';
		switch (Settings::getOption(Settings::OPTION_EDD_PAYMENT_MODEL)) {
			case Settings::EDDPAY_MODEL_ALL_CHANNELS:
				$where .= ' 1=1 ';
				break;
			case Settings::EDDPAY_MODEL_PER_CHANNEL:
			default:
				$where .= $wpdb->prepare("s.post_id = %d", $this->channel->getId());
		}
		
		$sql = $wpdb->prepare("
			SELECT COUNT(*)
			FROM $wpdb->postmeta s
			JOIN $wpdb->postmeta start ON start.meta_key = CONCAT(%s, s.meta_id)
			JOIN $wpdb->postmeta end ON end.meta_key = CONCAT(%s, s.meta_id)
			WHERE $where
			AND s.meta_key = %s
			AND s.meta_value = %d
			AND start.meta_value <= UNIX_TIMESTAMP() AND end.meta_value > UNIX_TIMESTAMP()",
			self::META_SUBSCRIPTION_START .'_',
			self::META_SUBSCRIPTION_END .'_',
			self::META_SUBSCRIPTION,
			$userId
		);
		$subscriptions = $wpdb->get_var($sql);
		
		return ($subscriptions > 0);
	
	}
	
	


	static function period2seconds($period) {
		$period = preg_replace('/\s/', '', $period);
		$units = array('min' => 60, 'h' => 3600, 'd' => 3600*24, 'w' => 3600*24*7, 'm' => 3600*24*30, 'y' => 3600*24*365);
		$unit = preg_replace('/[0-9]/', '', $period);
		if (isset($units[$unit])) {
			$number = preg_replace('/[^0-9]/', '', $period);
			return $number * $units[$unit];
		}
	}
	
	
	static function seconds2period($seconds) {
		$units = array('minute' => 60, 'hour' => 3600, 'day' => 3600*24, 'week' => 3600*24*7, 'month' => 3600*24*30, 'year' => 3600*24*365);
		$result = $seconds;
		$lastUnit = 'second';
		foreach ($units as $unit => $sec) {
			if ($seconds/$sec < 1) {
				break;
			} else {
				$result = $seconds/$sec;
				$lastUnit = $unit;
			}
		}
		return $result .' '. \__($lastUnit . ($result == 1 ? '' : 's'));
	}
	
	
	static function period2date($period) {
		$units = array('min' => 'minute', 'h' => 'hour', 'd' => 'day', 'w' => 'week', 'm' => 'month', 'y' => 'year');
		$unit = preg_replace('/[0-9\s]/', '', $period);
		if (isset($units[$unit])) {
			$number = preg_replace('/[^0-9]/', '', $period);
			return $number .' '. \__($units[$unit] . ($number == 1 ? '' : 's'));
		}
	}
	
	
	
}