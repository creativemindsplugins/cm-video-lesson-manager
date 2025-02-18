<?php

namespace com\cminds\videolesson\helper;

use com\cminds\videolesson\model\Vimeo;
use com\cminds\videolesson\model\Settings;
use com\cminds\videolesson\model\Video;

class VideoPrivacyHelper {
	
	protected $video;
	
	
	function __construct(Video $video) {
		$this->video = $video;
	}
	
	
	
	function getRawVideo() {
		return $this->video->getRawVideo();
	}
	


	function getPrivacyView() {
		$video = $this->video->getRawVideo();
		return $video['privacy']['view'];
	}
	
	
	function setPrivacyView($value) {
		Vimeo::getInstance()->request($this->video->getVimeoUri(), array('privacy' => array('view' => $value)), 'PATCH');
		return $this;
	}
	
	
	function getPrivacyEmbed() {
		$video = $this->video->getRawVideo();
		return $video['privacy']['embed'];
	}
	
	
	function setPrivacyEmbed($value) {
		Vimeo::getInstance()->request($this->video->getVimeoUri(), array('privacy' => array('embed' => $value)), 'PATCH');
// 		$this->clearCache();
		return $this;
	}
	
	
	function getPrivacyDomains() {
		$results = array();
		$vimeo = Vimeo::getInstance();
		// 		$vimeo->disableCacheOnce();
		$cacheExpiration = 0;
		$domains = $vimeo->request($this->video->getVimeoUri() . '/privacy/domains', $params = array(), $method = 'GET', $json_body = true, $cacheExpiration);
		if (!empty($domains['body']['data'])) foreach ($domains['body']['data'] as $domain) {
			$results[] = $domain['domain'];
		}
		return $results;
	}
	
	
	function addPrivacyDomain($domain = null) {
		if (is_null($domain)) {
			$domain = preg_replace('/^www./', '', $_SERVER['HTTP_HOST']);
		}
		$result = Vimeo::getInstance()->request($this->video->getVimeoUri() . '/privacy/domains/'. urlencode($domain), array(), 'PUT');
		Vimeo::getInstance()->removeCachedRequest($this->video->getVimeoUri() . '/privacy/domains');
		return $this;
	}
	
	
	function unlock() {
		$vimeo = Vimeo::getInstance();
		// This is no longer needed:
		// 		if ($this->video->getPrivacyView() != 'anybody') {
		// 			$this->video->setPrivacyView('anybody');
		// 		}
		$embedStatus = $this->getPrivacyEmbed();
		if ('private' == $embedStatus) {
			$this->setPrivacyEmbed('whitelist');
			$embedStatus = 'whitelist';
		}
		if ('whitelist' == $embedStatus) {
			$domain = preg_replace('/^www./', '', $_SERVER['HTTP_HOST']);
			if (!in_array($domain, $this->getPrivacyDomains())) {
				$this->addPrivacyDomain($domain);
				return true;
			}
		}
		return false;
	}

	
}
