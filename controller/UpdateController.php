<?php

namespace com\cminds\videolesson\controller;

use com\cminds\videolesson\model\Settings;

use com\cminds\videolesson\model\Vimeo;

use com\cminds\videolesson\model\Channel;

use com\cminds\videolesson\model\Micropayments;

use com\cminds\videolesson\App;

class UpdateController extends Controller {
	
	const OPTION_NAME = 'cmvl_update_methods';

	static function bootstrap() {
		global $wpdb;
		
		$updates = get_option(self::OPTION_NAME);
		if (empty($updates)) $updates = array();
		$count = count($updates);
		
		$methods = get_class_methods(__CLASS__);
		foreach ($methods as $method) {
			if (preg_match('/^update((_[0-9]+)+)$/', $method, $match)) {
				if (!in_array($method, $updates)) {
					call_user_func(array(__CLASS__, $method));
					$updates[] = $method;
				}
			}
		}
		
		if ($count != count($updates)) {
			update_option(self::OPTION_NAME, $updates);
		}
		
	}
	
	
	static function update_1_0_3() {
		global $wpdb;
		
		if (!App::isPro()) return;
		
		// Get subscription records in old format:
		$records = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE meta_key LIKE %s", 'cmvl_mp_subscription_%'), ARRAY_A);
		foreach ($records as $row) {
			if (preg_match('/^cmvl_mp_subscription_[0-9]+$/', $row['meta_key'])) {
				$value = @unserialize($row['meta_value']);
				if (!empty($value) AND is_array($value)) {
					
					// Create subscription records in new format
					$postId = $row['post_id'];
					$metaId = add_post_meta($postId, 'cmvl_mp_subscription', $value['userId'], $unique = false);
					add_post_meta($postId, 'cmvl_mp_subscription_start' .'_'. $metaId, $value['start'], $unique = true);
					add_post_meta($postId, 'cmvl_mp_subscription_end' .'_'. $metaId, $value['stop'], $unique = true);
					add_post_meta($postId, 'cmvl_mp_subscription_duration' .'_'. $metaId, $value['period'], $unique = true);
					add_post_meta($postId, 'cmvl_mp_subscription_points' .'_'. $metaId, 0, $unique = true);
					
					// Delete old record
					$wpdb->delete($wpdb->postmeta, array('meta_id' => $row['meta_id']));
					
				}
			}
		}
		
	}
	
	
	static function update_1_0_6() {
		$vimeo = Vimeo::getInstance();
		$vimeo->disableCacheOnce();
		$result = $vimeo->request('/me/channels', array('per_page' => 50, 'filter' => 'moderated'));
		$vimeoChannels = array();
		if ($result AND !empty($result['body']['total'])) {
			foreach ($result['body']['data'] as $row) {
				$vimeoChannels[Channel::parseId($row['uri'])] = Channel::normalizeUri($row['uri']);
			}
		}
		
		$channels = Channel::getAll($onlyVisible = false);
		
		foreach ($channels as $channel) {
			$channelId = get_post_meta($channel->getId(), App::prefix('_vimeo_id'), $single = true);
			if ($channelId AND isset($vimeoChannels[$channelId])) {
				$channel->setVimeoUri($vimeoChannels[$channelId]);
				delete_post_meta($channel->getId(), App::prefix('_vimeo_id'));
			}
		}
	}
	
	
	static function update_1_1_3() {
		update_option(App::prefix(App::OPTION_TRIGGER_FLUSH_REWRITE), 1);
	}
	
	
	static function update_1_6_1() {
		// Force to increase the cache lifetime
		update_option(Settings::OPTION_VIMEO_CACHE_SEC, 3600);
		update_option(Settings::OPTION_VIMEO_PRIVACY_CACHE_SEC, 3600);
	}
	
	
}
