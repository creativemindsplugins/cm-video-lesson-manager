<?php

namespace com\cminds\videolesson\controller;

use com\cminds\videolesson\model\Channel;

use com\cminds\videolesson\model\Vimeo;

use com\cminds\videolesson\model\Labels;

use com\cminds\videolesson\App;

use com\cminds\videolesson\model\Settings;

class SettingsController extends Controller {
	
	const ACTION_CLEAR_CACHE = 'clear-cache';
	
	const PAGE_ABOUT_URL = 'https://www.cminds.com/wordpress-plugins-library/video-lessons-manager-plugin-for-wordpress/';
	const PAGE_USER_GUIDE_URL = 'https://creativeminds.helpscoutdocs.com/category/354-cm-video-lessons-manager-cmvlm';
	
	protected static $actions = array(
		array('name' => 'admin_menu', 'priority' => 15),
		'admin_notices',
		'cmvl_display_supported_shortcodes',
		'shutdown',
	);
	protected static $filters = array(array('name' => 'cmvl-settings-category', 'args' => 2, 'method' => 'settingsLabels'));
	protected static $ajax = array(
		'cmvl_test_configuration',
	);
	
	static function admin_menu() {
		add_submenu_page(App::MENU_SLUG, App::getPluginName() . ' Settings', 'Settings', 'manage_options', self::getMenuSlug(), array(get_called_class(), 'render'));
	}
	
	
	static function getMenuSlug() {
		return App::MENU_SLUG . '-settings';
	}
	
	
	static function admin_notices() {
		if (!get_option('permalink_structure')) {
			printf('<div class="error"><p><strong>%s:</strong> to make the plugin works properly
				please enable the <a href="%s">Wordpress permalinks</a>.</p></div>', App::getPluginName(), admin_url('options-permalink.php'));
		}
	}
	
	
	static function render() {
		wp_enqueue_style('cmvl-backend');
		wp_enqueue_style('cmvl-frontend');
		wp_enqueue_style('cmvl-settings');
		wp_enqueue_script('cmvl-backend');
		echo self::loadView('backend/template', array(
			'title' => App::getPluginName() . ' Settings',
			'nav' => self::getBackendNav(),
			'content' => self::loadBackendView('help') . self::loadBackendView('licensing-box') . self::loadBackendView('settings', array(
				'clearCacheUrl' => self::createBackendUrl(self::getMenuSlug(), array('action' => self::ACTION_CLEAR_CACHE), self::ACTION_CLEAR_CACHE),
			)),
		));
	}
	
	
	static function settingsLabels($result, $category) {
		if ($category == 'labels') {
			$result = self::loadBackendView('labels');
		}
		return $result;
	}
	
	
	static function processRequest() {
		$fileName = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
		if (is_admin() AND $fileName == 'admin.php' AND !empty($_GET['page']) AND $_GET['page'] == self::getMenuSlug()) {
			
			if (!empty($_POST)) {
				
				// CSRF protection
		        if ((empty($_POST['nonce']) OR !wp_verify_nonce($_POST['nonce'], self::getMenuSlug()))) {
		        	// Do nothing
		        } else {
			        Settings::processPostRequest($_POST);
			        Labels::processPostRequest();
			        $response = array('status' => 'ok', 'msg' => 'Settings have been updated.');
			        wp_redirect(self::createBackendUrl(self::getMenuSlug(), $response));
			        exit;
		        }
	            
			}
			else if (!empty($_GET['action']) AND !empty($_GET['nonce']) AND wp_verify_nonce($_GET['nonce'], $_GET['action'])) switch ($_GET['action']) {
				case self::ACTION_CLEAR_CACHE:
					Vimeo::clearCache();
					wp_redirect(self::createBackendUrl(self::getMenuSlug(), array('status' => 'ok', 'msg' => 'Cache has been removed.')));
					exit;
					break;
			}
	        
		}
	}
	
	
	static function getSectionExperts() {
		return self::loadBackendView('experts');
	}
	
	
	static function cmvl_test_configuration() {
		if (is_user_logged_in()) {
			
			$errorMsg = null;
			if (!Settings::getOption(Settings::OPTION_VIMEO_CLIENT_ID)) {
				$errorMsg = 'You must provide the Vimeo Client ID.';
			}
			if (!Settings::getOption(Settings::OPTION_VIMEO_CLIENT_SECRET)) {
				$errorMsg = 'You must provide the Vimeo Client Secret.';
			}
			if (!Settings::getOption(Settings::OPTION_VIMEO_ACCESS_TOKEN)) {
				$errorMsg = 'You must provide the Vimeo Access Token.';
			}
			if (!empty($errorMsg)) {
				echo self::loadBackendView('test-configuration-error', compact('errorMsg'));
				exit;
			}
			
			$vimeo = Vimeo::getInstance();
			$vimeo->disableCacheOnce();
			$channels = $vimeo->request('/me/channels', array('per_page' => 50, 'filter' => 'moderated'));
			$vimeo->disableCacheOnce();
			$albums = $vimeo->request('/me/albums', array('per_page' => 50, 'filter' => 'moderated'));
			echo self::loadBackendView('test-configuration', compact('channels', 'albums'));
			exit;
		}
	}
	
	
	static function cmvl_display_supported_shortcodes() {
		echo self::loadBackendView('shortcodes');
	}
	
	
	
	static function shutdown() {
		if (filter_input(INPUT_GET, 'cmvimeodebug')) {
			if (!empty(Vimeo::$log) AND is_array(Vimeo::$log)) {
// 				var_dump(Vimeo::$log);return;
				echo '<table class="cmvl-debug"><caption>CM Video Lessons - Vimeo requests</caption>
					<thead><tr><th>No.</th><th>Type</th><th>URL</th><th>Params</th><th>Method</th><th>JSON Body</th></tr></thead>';
				foreach (Vimeo::$log as $i => $row) {
					echo '<tr><td>'. ($i+1) .'</td>';
					foreach ($row as $key => $value) {
						echo '<td>';
						if (is_string($value) OR is_numeric($value)) echo $value;
						else var_dump($value);
						echo '</td>';
					}
					echo '</tr>';
				}
				echo '</table><style>.cmvl-debug {margin: 2em auto;}
					.cmvl-debug tr:nth-child(even), .cmvl-debug thead {background: #f0f0f0;}
					.cmvl-debug td {vertical-align: top; border: solid 1px #f0f0f0; padding: 5px 10px;}</style>';
			}
		}
	}
	
	
}

