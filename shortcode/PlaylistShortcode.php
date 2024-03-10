<?php

namespace com\cminds\videolesson\shortcode;

use com\cminds\videolesson\App;

use com\cminds\videolesson\model\Settings;

use com\cminds\videolesson\model\Category;
use com\cminds\videolesson\model\Channel;
use com\cminds\videolesson\controller\ChannelController;


class PlaylistShortcode extends Shortcode {
	
	const SHORTCODE_NAME = 'cmvl-playlist';
	
	
	static function shortcode($atts) {
		
		$atts = shortcode_atts(array(
			'navbar' => 1,
			'searchbar' => 1,
			'linksbar' => 1,
			'ajax' => 1,
			'view' => '',
			'layout' => '',
			'category' => null,
			'channel' => null,
			'urlsearch' => 0,
			'video' => '',
			'maxwidth' => Settings::getOption(Settings::OPTION_PLAYLIST_MAX_WIDTH),
		), $atts);
		
		// Check if other controller has injected a different content for this shortcode:
		$result = apply_filters('cmvl_playlist_shortcode_content_preprocess', '', $atts);
		
		if (empty($result)) {
			
			$categoriesTree = Category::getTree(array('hide_empty' => 1));
			$currentCategory = null;
			if (!empty($atts['category']) AND $currentCategory = Category::getInstance($atts['category'])) {
				// ok
			} else {
				$atts['category'] = key($categoriesTree);
				$currentCategory = Category::getInstance($atts['category']);
			}
			
			$channels = array();
			if (!empty($currentCategory)) {
				$channels = $currentCategory->getChannels();
			}
			
			$currentChannel = null;
			if ($atts['channel'] == 'bookmarks') {
				// do nothing
			}
			else if (!empty($atts['channel']) AND $currentChannel = Channel::getInstance($atts['channel'])) {
				// ok
			}
			else if ($channels) {
				$atts['channel'] = $channels[0]->getId();
				$currentChannel = Channel::getInstance($atts['channel']);
			}
			else $atts['channel'] = null;
			
			$displayOptions = array();
			
			// Navbar
			if (!empty($atts['navbar']) AND $atts['channel'] != 'bookmarks' AND Channel::checkViewAccess()) {
				$currentChannelId = ($currentChannel ? $currentChannel->getId() : null);
				$currentCategoryId = ($currentCategory ? $currentCategory->getId() : null);
				$result .= ChannelController::loadView('frontend/playlist/navbar',
					compact('currentChannel', 'currentChannelId', 'currentCategory', 'currentCategoryId', 'categoriesTree', 'channels'));
			}
			
			// Playlist
			if (!empty($atts['channel'])) {
				$bookmarkClass = App::namespaced('controller\BookmarkController');
				if ($atts['channel'] == 'bookmarks' AND class_exists($bookmarkClass)) {
					$result .= $bookmarkClass::render($atts['view']);
				} else {
					
					// Check if other controller has injected a different content for this channel:
					$channelContent = apply_filters('cmvl_playlist_shortcode_channel_content', '', $atts, $currentChannel);
					if (empty($channelContent)) {
						$channelContent = ChannelController::playlist($atts['channel'], $pagination = array(), $atts['view'], $atts['layout'], $atts['video']);
					}
					$result .= $channelContent;
					
				}
			} else {
				$result .= ChannelController::loadNotFoundView();
			}
			
		}
		
		$extra = '';
		if ($atts['ajax']) {
			$extra .= ' data-use-ajax="1"';
		}
		
		$result = apply_filters('cmvl_playlist_shortcode_content', $result, $atts);
		
		if (!empty($atts['maxwidth'])) {
// 			$result .= '<style type="text/css">.cmvl-widget-playlist {max-width: '. intval($atts['maxwidth']) .'px;}</style>';
			$extra .= ' style="max-width:'. intval($atts['maxwidth']) .'px;"';
		}
		
		return self::wrap($result, $extra);
	}

	
}
