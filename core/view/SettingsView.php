<?php

namespace com\cminds\videolesson\view;
use com\cminds\videolesson\helper\SettingsMicropayments;
use com\cminds\videolesson\helper\AddSettingsTabs;

use com\cminds\videolesson\model\Settings;

require_once dirname(__FILE__) . '/SettingsViewAbstract.php';

class SettingsView extends SettingsViewAbstract {


	public function renderSubcategory($category, $subcategory) {
		$content = parent::renderSubcategory($category, $subcategory);
		if (strlen(strip_tags($content)) > 0) {
			return sprintf('<table><caption>%s</caption>%s</table>',
				esc_html($this->getSubcategoryTitle($category, $subcategory)),
				$content
			);
		}
	}


	public function renderOption($name, array $option = array()) {
		return sprintf('<tr>%s</tr>', parent::renderOption($name, $option));
	}

	public function renderOptionTitle($option) {
		return sprintf('<th scope="row" class="option-title">%s:</th>', parent::renderOptionTitle($option));
	}

// 	public function renderOptionControls($name, array $option = array()) {
// 		return sprintf('<td class="option-controls">%s</td>', parent::renderOptionControls($name, $option));
// 	}

	public function renderOptionDescription($option) {
		return sprintf('<td class="option-description">%s</td>', parent::renderOptionDescription($option));
	}


	protected function getSubcategoryTitle($category, $subcategory) {
		$subcategories = $this->getSubcategories();
		if (isset($subcategories[$category]) AND isset($subcategories[$category][$subcategory])) {
			return __($subcategories[$category][$subcategory]);
		} else {
			return $subcategory;
		}
	}


	protected function getCategories() {
		return apply_filters('cmvl_settings_pages', Settings::$categories);
	}


	protected function getSubcategories() {
		return apply_filters('cmvl_settings_pages_groups', Settings::$subcategories);
	}


	public function renderOptionControls($name, array $option = array()) {
		if (empty($option)) $option = Settings::getOptionConfig($name);
		switch ($option['type']) {
			case Settings::TYPE_MP_PRICE_GROUPS:
				$value = Settings::getOption($name);
				$result = SettingsMicropayments::renderMicropaymentsGroup($name, 1, isset($value[1]) ? $value[1] : array());
// 				$result = SettingsMicropayments::render($name, $option);
				break;
// 			case Settings::TYPE_LIST_KEY_VALUE:
// 				$result = SettingsListKeyValue::render($name, $option);
// 				break;
			default:
				$result = parent::renderOptionControls($name, $option);
		}
		return sprintf('<td class="option-controls">%s</td>', $result);
	}


}
