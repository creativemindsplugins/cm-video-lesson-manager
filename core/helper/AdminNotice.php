<?php

namespace com\cminds\videolesson\helper;

use com\cminds\videolesson\App;

abstract class AdminNotice {
	
	const NOTICE_TYPE = 'error';
	const NOTICE_DISMISS_SHOW = true;
	
	const NONCE_SUFFIX = '-admin-notice';
	const AJAX_ACTION = 'admin_notice_dismiss';
	const USER_META_NONCE_DISMISS_SUFFIX = '_admin_notice_dismiss';
	
	const TYPE_ERROR = 'error';
	const TYPE_SUCCESS = 'success';

	protected $id;
	protected $type;
	protected $msg;
	protected $dismiss;
	
	
	static function bootstrap() {
		if (static::shouldShowNotice()) {
			add_action('admin_notices', array(get_called_class(), 'admin_notices'));
			$ajaxAction = strtolower(App::prefix('_' . static::AJAX_ACTION));
			add_action('wp_ajax_' . $ajaxAction, array(get_called_class(), 'processAjaxDismiss'));
		}
	}

	
	static function admin_notices() {
		echo new static(
			$id = static::method2Id(get_called_class()),
			$type = static::NOTICE_TYPE,
			$msg = static::getNoticeMessage(),
			$dismiss = static::NOTICE_DISMISS_SHOW
		);
	}


	function __construct($id, $type, $msg, $dismiss = false) {
		$this->id = preg_replace('/[^a-z0-9]+/i', '_', $id);
		$this->type = $type;
		$this->msg = $msg;
		$this->dismiss = $dismiss;
	}
	
	
	static function shouldShowNotice() {
		return false;
	}
	
	static function getNoticeMessage() {
		return '';
	}



	static function getNonceAction() {
		return strtolower(App::prefix(static::NONCE_SUFFIX));
	}


	static function processAjaxDismiss() {
		if (is_user_logged_in() AND !empty($_POST['nonce']) AND wp_verify_nonce($_POST['nonce'], static::getNonceAction()) AND !empty($_POST['id'])) {
			$data = static::getDismissData();
			$data[] = sanitize_text_field($_POST['id']);
			static::setDismissData($data);
		}
	}


	static protected function getDismissDataKey() {
		return strtolower(App::prefix(static::USER_META_NONCE_DISMISS_SUFFIX));
	}

	static protected function getDismissData() {
		$data = get_user_meta(get_current_user_id(), static::getDismissDataKey(), true);
		if (!is_array($data)) $data = array();
		return $data;
	}


	static protected function setDismissData($data) {
		return update_user_meta(get_current_user_id(), static::getDismissDataKey(), $data);
	}


	protected function getDismissButton() {
		return sprintf('<a href="%s" data-nonce="%s" data-action="%s" data-id="%s" class="%s" title="%s">&times;</a>',
			$href = esc_attr(admin_url('admin-ajax.php')),
			$nonce = esc_attr(wp_create_nonce(static::getNonceAction($this->id))),
			$action = esc_attr(strtolower(App::prefix('_'. static::AJAX_ACTION))),
			$id = esc_attr($this->id),
			$class = esc_attr(strtolower(App::prefix('-dismiss'))),
			$title = esc_attr(__('Dismiss'))
		);
	}



	protected function getBody() {
		return $this->msg;
	}


	function isDismissed() {
		$data = static::getDismissData();
		return in_array($this->id, $data);
	}


	function __toString() {
		if ($this->isDismissed()) return '';
		wp_enqueue_script(strtolower(App::prefix('-backend')));
		wp_enqueue_style(strtolower(App::prefix('-backend')));
		$className = strtolower(App::prefix('-admin-notice'));
		return sprintf('<div class="%s %s" data-id="%s"><p><strong>%s:</strong> %s%s</p></div>',
			esc_attr($this->type),
			esc_attr($className),
			esc_attr($this->id),
			App::getPluginName(),
			$this->getBody(),
			($this->dismiss ? $this->getDismissButton() : '')
		);
	}


	static function method2Id($name) {
		$name = explode('\\', $name);
		return preg_replace('/[^a-z0-9]+/i', '_', end($name));
	}

	
}
