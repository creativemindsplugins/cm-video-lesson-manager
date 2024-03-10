<?php

namespace com\cminds\videolesson\shortcode;

use com\cminds\videolesson\model\ChannelSubscription;

use com\cminds\videolesson\controller\ChannelController;

use com\cminds\videolesson\model\Channel;

class ChannelsListShortcode extends Shortcode {
	
	const SHORTCODE_NAME = 'cmvl-channels-list';
	
	
	static function shortcode($atts = array()) {
		
		$atts = shortcode_atts(array(
			'subscription' => '',
		), $atts);
		
		$channels = Channel::getAll();
		
		if ($atts['subscription'] == 'active' OR $atts['subscription'] == 'inactive') {
			$channels = array_filter($channels, function(Channel $channel) use ($atts) {
				$subscription = new ChannelSubscription($channel);
				return ($subscription->isPayed() AND ($atts['subscription'] == 'active') == $subscription->isSubscriptionActive());
			});
		}
		
		ChannelController::loadAssets();
		return ChannelController::loadFrontendView('channels-list', compact('channels', 'atts'));
	}
	
	
}
