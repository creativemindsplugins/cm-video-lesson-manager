<?php
namespace com\cminds\videolesson\controller;

use com\cminds\videolesson\helper\VideoPrivacyHelper;
use com\cminds\videolesson\model\Labels;
use com\cminds\videolesson\model\Settings;
use com\cminds\videolesson\shortcode\PlaylistShortcode;
use com\cminds\videolesson\App;
use com\cminds\videolesson\model\Category;
use com\cminds\videolesson\model\Video;
use com\cminds\videolesson\model\Vimeo;
use com\cminds\videolesson\model\Channel;

class ChannelController extends Controller {
	
	const DEFAULT_VIEW = Settings::LAYOUT_TILES;
	const PARAM_PAGE = 'cmvl_page';
	const NONCE_EDIT_CHANNEL = 'cmvl_channel_edit_nonce';
	const NONCE_AJAX_CHANNEL = 'cmvl_channel_ajax_nonce';
	const NONCE_AJAX_BACKEND = 'cmvl_ajax_backend';

	protected static $actions = array(
		'add_meta_boxes', array('name' => 'save_post', 'args' => 1),
		'template_redirect'
	);
	
	protected static $filters = array(
		'the_content',
		'cmvl_playlist_shortcode_content' => array('args' => 2, 'priority' => 30),
	);
	
	protected static $ajax = array(
		'cmvl_unlock_private_videos'
	);
	
	protected static $suspendActions = 0;
	
	static function init() {
		parent::init();
		add_rewrite_tag('%video%', '(\d+)');
		add_rewrite_tag('%'. Category::TAXONOMY .'%', '([^/]+)');
	}
	
	static function add_meta_boxes() {
		global $pagenow;
		if ('post.php' == $pagenow) {
			wp_enqueue_style('cmvl-backend');
		}
		add_meta_box( App::prefix('-choose-channel'), 'Choose Vimeo Album', array(get_called_class(), 'choose_channel_meta_box'), Channel::POST_TYPE, 'normal', 'high' );
	}

	static function choose_channel_meta_box($post) {
		
		$channels = Channel::getAllFromVimeo('channels');
		$albums = Channel::getAllFromVimeo('albums');
		
		/*
 		var_dump($channels);var_dump($albums);return;
 		$vimeo = Vimeo::getInstance();
 		$vimeo->disableCacheOnce();
 		$channels = $vimeo->request('/me/channels', array('per_page' => 50, 'filter' => 'moderated'));
 		$vimeo->disableCacheOnce();
 		$albums = $vimeo->request('/me/albums', array('per_page' => 50, 'filter' => 'moderated'));
		*/
		
		if ($channel = Channel::getInstance($post)) {
			$currentChannelUri = $channel->getVimeoUri();
			$sort = $channel->getSort();
			$sortDirection = $channel->getSortDirection();
		} else {
			$currentChannelUri = 0;
			$sort = Video::SORT_MANUAL;
			$sortDirection = Video::DIR_ASC;
		}
		$nonce = wp_create_nonce(self::NONCE_EDIT_CHANNEL);
		wp_enqueue_script('cmvl-backend');
		echo self::loadBackendView('choose-channel-meta-box', compact('channels', 'albums', 'currentChannelUri', 'sort', 'sortDirection', 'nonce'));
	}
	
	static function save_post($post_id) {
		if (!empty($_POST[self::NONCE_EDIT_CHANNEL]) AND wp_verify_nonce($_POST[self::NONCE_EDIT_CHANNEL], self::NONCE_EDIT_CHANNEL) AND $channel = Channel::getInstance($post_id)) {
			//static::$suspendActions++;
			
			if (!empty($_POST['cmvl_channel_uri'])) {
				$res = $channel->setVimeoUri( sanitize_text_field($_POST['cmvl_channel_uri']) );
			}
			
			if (!empty($_POST['cmvl_channel_sort'])) {
				$channel->setSort( sanitize_text_field($_POST['cmvl_channel_sort']) );
			}
			if (!empty($_POST['cmvl_channel_sort_direction'])) {
				$channel->setDirection( sanitize_text_field($_POST['cmvl_channel_sort_direction']) );
			}
			
			if (!$channel->getCategories()) {
				$channel->addDefaultCategory();
			}
			
			do_action('cmvl_save_channel', $channel);
			
			//static::$suspendActions--;
		}
	}
	
	static function the_content($content) {
		if (is_main_query() AND (is_single() AND get_post_type() == Channel::POST_TYPE)) {
			
			global $post;
			$channel = Channel::getInstance($post);
			$view = '';
			
			
			if (!empty($_POST['nonce']) AND wp_verify_nonce($_POST['nonce'], self::NONCE_AJAX_CHANNEL)) {
				$view = (isset($_POST['view']) ? sanitize_text_field($_POST['view']) : '');
			} else {
				if (Settings::getOption(Settings::OPTION_DISABLE_CHANNEL_PAGE)) {
					return self::loadFrontendView('access_denied', compact('channel'));
				}
			}
			
			$category = get_query_var(Category::TAXONOMY);
			if (empty($category)) {
				if ($categories = $channel->getCategories()) {
					$cat = reset($categories);
					$category = $cat->getId();
				}
			}
			$playlist = PlaylistShortcode::shortcode(array(
				'view' => $view,
				'channel' => $post->ID,
				'category' => $category,
				'ajax' => 0,
			));
			
			self::loadAssets();
			return self::loadFrontendView('single_content', compact('content', 'playlist'));
			
		} else {
			return $content;
		}
	}
	
	static function loadAssets() {
		wp_enqueue_style('dashicons');
		wp_enqueue_style('cmvl-frontend');
		wp_enqueue_script('cmvl-utils');
		wp_enqueue_script('jquery');
		wp_enqueue_script('cmvl-paybox');
		wp_enqueue_script('cmvl-playlist');
		do_action('cmvl_load_assets_frontend');
		add_action('wp_footer', array(__CLASS__, 'footerCustomCSS'));
	}
	
	static function footerCustomCSS() {
		echo '<style type="text/css">/* CMVL Custom CSS */' . PHP_EOL . Settings::getOption(Settings::OPTION_CUSTOM_CSS) . PHP_EOL . '</style>';
	}
	
	static function loadAccessDeniedView(Channel $channel = null) {
		self::loadAssets();
		return self::loadFrontendView('access_denied', compact('channel'));
	}
	
	static function loadNotFoundView() {
		self::loadAssets();
		return self::loadFrontendView('not_found');
	}
	
	static function playlist($post, $pagination = array(), $view = null, $layout = null, $videoId = null) {
		
		$view = self::checkView($view);
		$pagination = shortcode_atts(array(
			'page' => ((isset($_GET[self::PARAM_PAGE]) AND is_numeric($_GET[self::PARAM_PAGE])) ? $_GET[self::PARAM_PAGE] : 1),
			'per_page' => ($view == Settings::LAYOUT_PLAYLIST ? Vimeo::MAX_PER_PAGE : Settings::getOption(Settings::OPTION_PAGINATION_LIMIT)),
		), $pagination);
		
		if ($channel = Channel::getInstance($post)) {
			do_action('cmvl_channel_playlist_load', $channel);
			if ($channel->canView()) {
				if (empty($videoId)) {
					$videos = $channel->getVideos($pagination);
					$pagination['total'] = $channel->getTotalVideos();
				} else {
					$videos = array($channel->getVideo($videoId));
					$pagination['total'] = 1;
				}
				$videos = array_filter($videos);
				$categories = $channel->getCategories();
				if($categories) {
					if(isset($categories[0])) {
						if(is_object($categories[0])) {
							$pagination['base_url'] = $channel->getPermalinkForCategory($categories[0]);
						}
					}
				}
				return self::renderPlaylist($videos, $pagination, $view, $layout, $channel);
			} else {
				return self::loadAccessDeniedView($channel);
			}
		} else {
			return self::loadNotFoundView();
		}
	}
	
	static function renderPlaylist($videos, $pagination = array(), $view = null, $layout = null, $channel = null) {
		
		self::loadAssets();
		
		$view = self::checkView($view);
		$pagination = shortcode_atts(array(
			'page' => 1,
			'per_page' => ($view == Settings::LAYOUT_PLAYLIST ? Vimeo::MAX_PER_PAGE : Settings::getOption(Settings::OPTION_PAGINATION_LIMIT)),
			'total' => 0,
			'base_url' => null,
		), $pagination);
		
		$currentVideo = reset($videos);
		if ($currentVideoId = get_query_var('video')) {
			foreach ($videos as $v) {
				if ($v->getId() == $currentVideoId) {
					$currentVideo = $v;
					break;
				}
			}
		}
		
		$playerOptions = array('autoplay' => false /*self::isAjax()*/ );
		
		if ($pagination['per_page'] > 0) {
			$pagination['total_pages'] = ceil($pagination['total'] / $pagination['per_page']);
		}
		if (empty($pagination['total_pages'])) {
			$pagination['total_pages'] = 1;
		}
		if (!empty($pagination['base_url']) AND $pagination['total_pages'] > 1) {
			$paginationView = self::loadView('frontend/playlist/pagination', $pagination);
		} else $paginationView = '';
		
		$layout = (empty($layout) ? Settings::getOption(Settings::OPTION_PLAYLIST_LAYOUT) : $layout);
		
		$params = compact('videos', 'currentVideo', 'playerOptions', 'paginationView', 'layout', 'channel');
		$content = self::loadView('frontend/playlist/' . $view, $params);
		return apply_filters('cmvl_render_playlist', $content, $params);
		
	}
	
	protected static function checkView($view) {
		if ($availableViews = Settings::getOptionConfig(Settings::OPTION_PLAYLIST_VIEW)) {
			$availableViews = array_keys($availableViews['options']);
			if (!in_array($view, $availableViews)) {
				$view = Settings::getOption(Settings::OPTION_PLAYLIST_VIEW);
				if (empty($view)) {
					$view = self::DEFAULT_VIEW;
				}
			}
			return $view;
		} else {
			return self::DEFAULT_VIEW;
		}
	}
	
	static function cmvl_playlist_shortcode_content($content, $atts) {
		if (!empty($atts['linksbar']) AND $linksbar = apply_filters('cmvl_playlist_links_bar', '')) {
			$content = sprintf('<ul class="cmvl-inline-nav">%s</ul>', $linksbar) . $content;
		}
		return $content;
	}
	
	static function template_redirect() {
		global $wp;
		if (is_main_query() AND is_single() AND get_post_type() == Channel::POST_TYPE) {
			
		}
		if(!is_404()) {
			return;
		}
		if(isset( $_GET['cm-flush'])) { // WPCS: CSRF ok.
			return;
		}
		if(false === get_transient('cmvlf_refresh_404_permalinks')) {
			$slug = Settings::getOption(Settings::OPTION_PERMALINK_PREFIX);
		    $parts = explode('/', $wp->request);
			if($slug !== $parts[0]) {
				return;
			}
			flush_rewrite_rules(false);
			set_transient('cmvlf_refresh_404_permalinks', 1, HOUR_IN_SECONDS * 12);
			$redirect_url = home_url(add_query_arg(array('cm-flush'=> 1), $wp->request));
			wp_safe_redirect(esc_url_raw($redirect_url), 302);
			exit();
		}
	}
	
	static function cmvl_unlock_private_videos() {
		if ($nonce = filter_input(INPUT_POST, 'nonce') AND wp_verify_nonce($nonce, static::NONCE_AJAX_BACKEND)) {
			
			$videos = Video::getAll($onlyVisible = false);
			foreach ($videos as $video) {
				echo 'Checking video: '. $video->getTitle();
				$privacy = new VideoPrivacyHelper($video);
				if ($privacy->unlock()) {
					echo ' ... unlocked';
				} else {
					echo ' ... no need';
				}
				echo '<br>';
				ob_flush();
				flush();
			}
			
			if (empty($videos)) {
				echo 'No videos found.<br>';
			}
			
			echo '<br>END.';
			exit;
			
		}
	}

}