<?php

namespace com\cminds\videolesson\shortcode;

use com\cminds\videolesson\controller\SubscriptionsController;

use com\cminds\videolesson\model\SubscriptionReport;

use com\cminds\videolesson\model\ChannelSubscription;

use com\cminds\videolesson\controller\ChannelController;

use com\cminds\videolesson\model\Channel;

class SubscriptionsShortcode extends Shortcode {
	
	const SHORTCODE_NAME = 'cmvl-subscriptions';
	
	
	static function shortcode($atts = array()) {
		
		if (!is_user_logged_in()) return;
		
		$atts = shortcode_atts(array(
			'status' => '',
			'limit' => 999999999,
			'page' => 1,
		), $atts);
		
		$filter = array(
			'user_id' => get_current_user_id(),
			'status' => $atts['status'],
		);
		
		$rows = SubscriptionReport::getData($filter, $atts['limit'], $atts['page']);
		foreach ($rows as &$row) {
			$row['channel'] = Channel::getInstance($row['post_id']);
			if (empty($row['channel'])) {
				$row = null;
			}
		}
		$rows = array_filter($rows);
		
		ChannelController::loadAssets();
		return SubscriptionsController::loadFrontendView('subscriptions-table', compact('rows', 'atts'));
		
	}
	
	
}
