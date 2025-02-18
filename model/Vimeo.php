<?php

namespace com\cminds\videolesson\model;
use com\cminds\videolesson\App;

require_once App::path('lib/Vimeo/Vimeo.php');

class Vimeo extends \Vimeo\Vimeo {
	
	const TRANSIENT_PREFIX = 'cmvlvimeo';
	
	const CACHE_ENABLED = 1;
	const CACHE_DISABLED = 2;
	const CACHE_DISABLED_ONCE = 3;
	
	const MAX_PER_PAGE = 100;
	

	static public $log = array();
	static protected $instance;
	
	protected $cache = self::CACHE_ENABLED;
	
	
	/**
	 * Get instance.
	 * 
	 * @return \Vimeo\Vimeo
	 */
	static function getInstance() {
		if (empty(self::$instance)) {
			self::$instance = new self(
				Settings::getOption(Settings::OPTION_VIMEO_CLIENT_ID),
				Settings::getOption(Settings::OPTION_VIMEO_CLIENT_SECRET),
				Settings::getOption(Settings::OPTION_VIMEO_ACCESS_TOKEN)
			);
// 			register_shutdown_function(function() {
// 				echo '<pre>';
// 				print_r(Vimeo::$log);
// 			});
		}
		return self::$instance;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Vimeo\Vimeo::request()
	 */
	public function request($url, $params = array(), $method = 'GET', $json_body = true, $cacheExpiration = null) {
		if (is_null($cacheExpiration)) {
			$cacheExpiration = Settings::getOption(Settings::OPTION_VIMEO_CACHE_SEC);
		}
		$cache = ($cacheExpiration > 0 AND $this->isCacheEnabled() AND $method == 'GET');
		$transient = self::TRANSIENT_PREFIX . '_' . md5(implode('___', array($url, serialize($params), $method, $json_body)));
		
		if ($cache) {
			$result = get_transient($transient);
		}
		
		if (empty($result) OR empty($result['body'])) {
			
// 			var_dump($url);
// 			var_dump($method);
// 			var_dump($cacheExpiration);
// 			var_dump($cache);
// 			var_dump($transient);
// 			var_dump($result);
			
			$type = 'http';
			$result = parent::request($url, $params, $method, $json_body);
// 			var_dump($url);exit;
			$re = set_transient($transient, $result, $cacheExpiration);
			
		} else {
			$type = 'cache';
		}
		self::$log[] = compact('type', 'url', 'params', 'method', 'json_body');
		return $result;
	}
	
	
	public function removeCachedRequest($url, $params = array(), $method = 'GET', $json_body = true) {
		$transient = self::TRANSIENT_PREFIX . '_' . md5(implode('___', array($url, serialize($params), $method, $json_body)));
		delete_transient($transient);
	}
	
	
	static function clearCache() {
		global $wpdb;
		$wpdb->query($wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name LIKE %s", '_transient_'. self::TRANSIENT_PREFIX .'_%'));
	}
	
	
	function isCacheEnabled() {
		if ($this->cache == self::CACHE_DISABLED_ONCE) {
			$this->cache = self::CACHE_ENABLED;
			return false;
		} else {
			return ($this->cache == self::CACHE_ENABLED);
		}
	}
	
	function disableCacheOnce() {
		$this->cache = self::CACHE_DISABLED_ONCE;
		return $this;
	}
	
	function disableCache() {
		$this->cache = self::CACHE_DISABLED;
		return $this;
	}
	
	
	function enableCache() {
		$this->cache = self::CACHE_ENABLED;
		return $this;
	}
	

}
