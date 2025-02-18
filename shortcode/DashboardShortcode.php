<?php

namespace com\cminds\videolesson\shortcode;


use com\cminds\videolesson\model\Settings;

use com\cminds\videolesson\model\Labels;

use com\cminds\videolesson\controller\ChannelController;

class DashboardShortcode extends Shortcode {
	
	const SHORTCODE_NAME = 'cmvl-dashboard';
	
	
	static function shortcode($atts) {
		if (is_user_logged_in() AND $user = get_userdata(get_current_user_id())) {
			ChannelController::loadAssets();
			$tabs = Settings::getOption(Settings::OPTION_DASHBOARD_TABS);
			return ChannelController::loadView('frontend/dashboard/dashboard', compact('tabs'));
		}
	}
	
	
}
