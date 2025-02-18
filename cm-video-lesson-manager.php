<?php
/*
Plugin Name: CM Video Lesson Manager
Plugin URI: https://www.cminds.com/wordpress-plugins-library/video-lessons-manager-plugin-for-wordpress/
Description: Manage video lesson while allowing user and admin to track progress, leave notes and mark favourites. Support payment per viewing channel per a define time period.
Author: CreativeMindsSolutions
Version: 1.8.5
*/

if (version_compare('5.3', PHP_VERSION, '>')) {
	die(sprintf('We are sorry, but you need to have at least PHP 5.3 to run this plugin (currently installed version: %s)'
		. ' - please upgrade or contact your system administrator.', PHP_VERSION));
}

require_once dirname(__FILE__) . '/App.php';
com\cminds\videolesson\App::bootstrap(__FILE__);