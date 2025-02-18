<?php

namespace com\cminds\videolesson\helper;

use com\cminds\videolesson\controller\SettingsController;

use com\cminds\videolesson\model\Settings;

class ConfigureVimeoNotice extends AdminNotice {
	
	
	static function getNoticeMessage() {
		return sprintf('You need to configure the Vimeo Client ID, Secret and Access Token. <a href="%s" class="button">Go to Settings</a>',
			esc_attr(add_query_arg('page', SettingsController::getMenuSlug(), admin_url('admin.php')))
			);
	}
	
	
	static function shouldShowNotice() {
		$vimeoApiKey = Settings::getOption(Settings::OPTION_VIMEO_CLIENT_ID);
		$vimeoApiSecret = Settings::getOption(Settings::OPTION_VIMEO_CLIENT_SECRET);
		$vimeoApiToken = Settings::getOption(Settings::OPTION_VIMEO_ACCESS_TOKEN);
		return (empty($vimeoApiKey) OR empty($vimeoApiSecret) OR empty($vimeoApiToken));
	}
	
}