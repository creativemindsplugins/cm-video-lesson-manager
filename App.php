<?php
namespace com\cminds\videolesson;

use com\cminds\videolesson\controller\ChannelController;
use com\cminds\videolesson\controller\SettingsController;
use com\cminds\videolesson\controller\AddSettingsTabsController;
use com\cminds\videolesson\shortcode\BookmarksShortcode;
use com\cminds\videolesson\shortcode\PlaylistShortcode;
use com\cminds\videolesson\model\Settings;

class App {

	const VERSION = '1.8.5';
	const PREFIX = 'CMVL';
	const MENU_SLUG = 'cmvl';
	const BASE_NAMESPACE = 'com\cminds\videolesson';
	const PLUGIN_NAME = 'CM Video Lesson Manager';
	const PLUGIN_WEBSITE = 'https://www.cminds.com/wordpress-plugins-library/video-lessons-manager-plugin-for-wordpress/';
	const LICENSING_SLUG = 'c-m-video-lesson-manager-pro';

	const OPTION_TRIGGER_FLUSH_REWRITE = '_trigger_flush_rewrite';

	static private $licensingApi;
	static private $path;
	static private $pluginFile;

	static function bootstrap($pluginFile) {

		self::$pluginFile = $pluginFile;
		self::$path = dirname($pluginFile);

		add_action( 'activated_plugin', function() {
			update_option( App::prefix('plugin_error'),  ob_get_contents() );
		} );

		// Auto-load
		spl_autoload_register(array(__CLASS__, 'autoload'));

		// Licensing API
		if (self::isPro()) {
			require App::path('package/cminds-pro.php');
		} else {
			require App::path('package/cminds-free.php');
		}

		// Class bootstraping
		$classToBootstrap = array_merge(self::getClassNames('controller'), self::getClassNames('model'));
		if (self::isLicenseOk()) {
			$classToBootstrap = array_merge($classToBootstrap, self::getClassNames('shortcode'), self::getClassNames('helper'));
		}
		foreach ($classToBootstrap as $className) {
			$method = array($className, 'bootstrap');
			if (method_exists($className, 'bootstrap') AND is_callable($method)) {
				call_user_func($method);
			}
		}

		// Other actions
		add_action('init', array(get_called_class(), 'init'), 1);
		add_action('admin_menu', array(get_called_class(), 'admin_menu'));

		register_activation_hook($pluginFile, array(__CLASS__, 'install'));
		register_uninstall_hook($pluginFile, array(__CLASS__, 'uninstall'));

	}

	static function init() {

		if (get_option(App::prefix(self::OPTION_TRIGGER_FLUSH_REWRITE)) == 1) {
			flush_rewrite_rules(true);
			update_option(App::prefix(self::OPTION_TRIGGER_FLUSH_REWRITE), null);
		}

		wp_register_script('cmvl-utils', App::url('asset/js/utils.js'), array('jquery'), self::VERSION);
		wp_register_script('cmvl-backend', App::url('asset/js/backend.js'), array('jquery', 'jquery-ui-sortable', 'cmvl-utils'), self::VERSION);

		wp_register_script('cmvl-logout-heartbeat', App::url('asset/js/logout-heartbeat.js'), array('jquery', 'heartbeat'), self::VERSION);
		wp_register_script('cmvl-paybox', App::url('asset/js/paybox.js'), array('jquery', 'cmvl-utils'), self::VERSION);
		wp_register_script('cmvl-playlist', App::url('asset/js/playlist.js'), array('jquery', 'cmvl-utils', 'cmvl-paybox'), self::VERSION);
		wp_localize_script('cmvl-playlist', 'CMVLSettings', array(
			'ajaxUrl' => admin_url('admin-ajax.php'),
			'ajaxNonce' => wp_create_nonce(ChannelController::NONCE_AJAX_CHANNEL),
		));

		wp_localize_script('cmvl-backend', 'CMVLBackend', array(
			'ajaxUrl' => admin_url('admin-ajax.php'),
			'ajaxNonce' => wp_create_nonce(ChannelController::NONCE_AJAX_BACKEND),
		));

		wp_register_style('cmvl-settings', App::url('asset/css/settings.css'), null, self::VERSION);
		wp_register_style('cmvl-backend', App::url('asset/css/backend.css'), null, self::VERSION);
		wp_register_style('cmvl-frontend', App::url('asset/css/frontend.css'), null, self::VERSION);

	}

	static function install() {
		update_option(App::prefix(self::OPTION_TRIGGER_FLUSH_REWRITE), 1);
	}

	static function uninstall() {
		update_option(App::prefix(self::OPTION_TRIGGER_FLUSH_REWRITE), 1);
	}

	static function getClassNames($namespaceFragment) {
		$files = scandir(App::path($namespaceFragment));
		foreach ($files as &$name) {
			if (preg_match('/^([a-zA-Z0-9]+)\.php$/', $name, $match)) {
				$name = App::namespaced($namespaceFragment .'\\'. $match[1]);
			} else {
				$name = null;
			}
		}
		return array_filter($files);
	}

	static function autoload($name) {
		if (substr($name, 0, strlen(__NAMESPACE__)) == __NAMESPACE__) {
			$path = str_replace('\\', DIRECTORY_SEPARATOR, substr($name, strlen(__NAMESPACE__)+1, 9999));
			$check = array(App::path($path), App::path('core/'. $path));
			foreach ($check as $file) {
				$file .= '.php';
				if (file_exists($file) AND is_readable($file)) {
					require_once $file;
					return;
				}
			}
		}
	}

	static function admin_menu() {
		$name = App::getPluginName(true);
		$page = add_menu_page($name, $name, 'manage_options', App::MENU_SLUG, function($q){ return; }, 'dashicons-video-alt2');
	}

	static function path($path = '') {
		return self::$path . DIRECTORY_SEPARATOR . $path;
	}

	static function prefix($value) {
 		return self::PREFIX . $value;
	}

	static function url($url) {
		return trailingslashit(plugins_url('', static::$pluginFile)) . $url;
	}

	static function namespaced($name) {
		return self::BASE_NAMESPACE . '\\' . $name;
	}

	static function shortClassName($name, $suffix = '') {
		preg_match('#^(\w+\\\\)*(\w+)'. $suffix .'$#', $name, $match);
		if (!empty($match[2])) return $match[2];
	}

	static function isPro() {
		return file_exists(App::path('package/cminds-pro.php'));
	}

	static function isLicenseOk() {
		global $CMVL_isLicenseOk;
		return (!self::isPro() OR $CMVL_isLicenseOk);
	}

	static function getPluginName($full = false) {
		return self::PLUGIN_NAME . (($full && App::isPro()) ? ' Pro' : '');
	}

	static function getPluginFile() {
		return static::$pluginFile;
	}

	static function stripNamespace($name) {
		return str_replace(static::$baseNamespace .'\\', '', $name);
	}

}