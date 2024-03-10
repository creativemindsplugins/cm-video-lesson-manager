<?php

namespace com\cminds\videolesson\controller;

use com\cminds\videolesson\App;
use com\cminds\videolesson\model\Settings;
use com\cminds\videolesson\model\Video;
use com\cminds\videolesson\model\Labels;
use com\cminds\videolesson\model\Channel;

class FreeController extends Controller {

	protected static $actions = array(array('name' => 'admin_menu', 'priority' => 16));
	static $filters = array(
		array('name' => 'cmvl_playlist_shortcode_content', 'method' => 'displayReferralLink'),
	);
	
	
	static function bootstrap() {
		if (!App::isPro()) parent::bootstrap();
	}
	
	
	static function admin_menu() {
// 		add_submenu_page(App::MENU_SLUG, 'About ' . App::getPluginName(), 'About', 'manage_options', self::getMenuSlug('about'), array(get_called_class(), 'about'));
// 		add_submenu_page(App::MENU_SLUG, App::getPluginName() . ' User Guide', 'User Guide', 'manage_options', self::getMenuSlug('user-guide'),
// 			array(get_called_class(), 'userGuide'));
// 		add_submenu_page(App::MENU_SLUG, 'Upgrade to '. App::getPluginName() .' Pro', 'Upgrade to Pro', 'manage_options', self::getMenuSlug('upgrade'),
// 			array(get_called_class(), 'upgradeToPro'));
	}
	
	
	static function getMenuSlug($slug) {
		return App::MENU_SLUG . '-' . $slug;
	}
	
	static function about() {}
	
	static function userGuide() {}
	
	
	static function upgradeToPro() {
		wp_enqueue_style('cmvl-backend');
		echo self::loadView('backend/template', array(
			'title' => 'Upgrade to Pro',
			'nav' => self::getBackendNav(),
			'content' => self::loadBackendView('upgrade') . SettingsController::getSectionExperts(),
		));
	}
	
	
	static function processRequest() {
		$fileName = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
		if (is_admin() AND $fileName == 'admin.php' AND !empty($_GET['page'])) switch ($_GET['page']) {
			case self::getMenuSlug('about'):
				wp_redirect(SettingsController::PAGE_ABOUT_URL);
				exit;
			case self::getMenuSlug('user-guide'):
				wp_redirect(SettingsController::PAGE_USER_GUIDE_URL);
				exit;
		}
	}
	
	
	static function displayReferralLink($content) {
		ob_start();
		echo do_shortcode('[cminds_free_author id="'.App::PREFIX.'"]');
		$out = ob_get_clean();
		return $content . $out;
	}
	
	
	
}
