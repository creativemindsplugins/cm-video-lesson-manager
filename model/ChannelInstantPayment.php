<?php

namespace com\cminds\videolesson\model;

use com\cminds\videolesson\controller\InstantPaymentsController;

class ChannelInstantPayment extends Model implements IPaymentMethod {
	
	const PAYMENT_PLUGIN_NAME = 'CM Instant EDD Payments';
	
// 	const META_COST = 'cmvl_eddpay_cost';
	const META_TRANSACTIONS_PREFIX = 'cmvl_eddpay_transaction_';
	
	protected $channel;
	

	function __construct(Channel $channel) {
		$this->channel = $channel;
	}
	
	
	function isPayed() {
		$cost = $this->getCosts();
		return !empty($cost);
	}
	
	
	function getCosts() {
		switch (Settings::getOption(Settings::OPTION_EDD_PAYMENT_MODEL)) {
			case Settings::EDDPAY_MODEL_ALL_CHANNELS:
				return static::getGlobalCosts();
				break;
			case Settings::EDDPAY_MODEL_PER_CHANNEL:
			default:
				return $this->getChannelCosts();
		}
	}
	
	
	function getChannelCosts() {
		$result = apply_filters('cmvl_eddpay_channel_prices', array(), $this->channel->getId());
		return $result;
		
// 		$result = get_post_meta($this->channel->getId(), self::META_COST, $single = true);
// 		if (empty($result) OR !is_array($result)) $result = array();
// 		return $result;
	}
	
	
	static function getGlobalCosts() {
// 		$costs = Settings::getOption(Settings::OPTION_EDD_PRICING_GROUPS);
		$result = apply_filters('cmvl_eddpay_global_prices', array());
		return $result;
// 		if (isset($costs[1]) AND !empty($costs[1]['prices']) AND is_array($costs[1]['prices'])) {
// 			$result = array();
// 			foreach ($costs[1]['prices'] as $price) {
// 				$period = $price['number'] . ' ' . $price['unit'];
// 				$seconds = ChannelSubscription::period2seconds($period);
// 				$result[$seconds] = array(
// 					'period' => $period,
// 					'number' => $price['number'],
// 					'unit' => $price['unit'],
// 					'seconds' => $seconds,
// 					'cost' => $price['price'],
// 				);
// 			}
// 			return $result;
// 		} else {
// 			return array();
// 		}
	}
	
	
	function setCosts($cost) {
// 		if (empty($cost)) {
// 			return delete_post_meta($this->channel->getId(), self::META_COST);
// 		} else {
// 			return update_post_meta($this->channel->getId(), self::META_COST, $cost);
// 		}
	}
	
	
	function initPayment($eddDownloadId, $callbackUrl) {
		$price = apply_filters('cmvl_eddpay_get_price_by_id', 0, $eddDownloadId);
		$subscriptionTime = apply_filters('cmvl_eddpay_get_subscription_time_by_download_id', '', $eddDownloadId);
		$periodLabel = ChannelSubscription::seconds2period($subscriptionTime);
		$subscription = array(
			'userId' => get_current_user_id(),
			'cost' => $price,
			'initTime' => time(),
			'channelId' => $this->channel->getId(),
		);
		$request = array(
			'channelId' => $this->channel->getId(),
			'userId' => get_current_user_id(),
			'edd_download_id' => $eddDownloadId,
			'label' => $this->getTransactionLabel($periodLabel),
			'callbackAction' => InstantPaymentsController::EDDPAY_CALLBACK_ACTION,
			'callbackUrl' => $callbackUrl,
			'backlinkUrl' => $callbackUrl,
			'backlinkText' => sprintf(Labels::getLocalized('eddpay_receipt_backlink'), $this->channel->getTitle()),
		);
		$response = apply_filters('cmvl_eddpay_init_transaction', false, $request);
		if ($response AND is_array($response) AND !empty($response['success']) AND !empty($response['redirectionUrl'])) {
// 			$this->registerTransaction($subscription, $request, $response);
			return $response['redirectionUrl'];
		}
	}
	
	
	protected function registerTransaction($subscription, $request, $response) {
		add_post_meta(
			$this->channel->getId(),
			self::META_TRANSACTIONS_PREFIX . $response['transactionId'],
			compact('request', 'response', 'subscription'),
			$unique = false
		);
	}
	
	
	static function getTransaction($transactionId) {
		global $wpdb;
		$meta = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE meta_key = %s", self::META_TRANSACTIONS_PREFIX . $transactionId), ARRAY_A);
		if ($meta AND $channel = Channel::getInstance($meta['post_id'])) {
			$transaction = unserialize($meta['meta_value']);
			return compact('meta', 'channel', 'transaction');
		}
	}
	
	
	function getTransactionLabel($periodLabel) {
		switch (Settings::getOption(Settings::OPTION_EDD_PAYMENT_MODEL)) {
			case Settings::EDDPAY_MODEL_ALL_CHANNELS:
				return sprintf(Labels::getLocalized('eddpay_transaction_all_channels_label'), $periodLabel);
				break;
			case Settings::EDDPAY_MODEL_PER_CHANNEL:
			default:
				return sprintf(Labels::getLocalized('eddpay_transaction_label'), $this->channel->getTitle(), $periodLabel);
		}
	}
	
	
	static function isAvailable() {
		return apply_filters('cm_edd_pay_available', false);
	}
	
}