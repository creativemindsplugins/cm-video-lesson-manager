<?php

namespace com\cminds\videolesson\helper;

use com\cminds\videolesson\model\Settings;

class SettingsMicropayments {
	
	static $currency = '';
	static $currencyStep = 1;
	
	static function render($name, $option) {
		if (isset($option['currency'])) {
			static::$currency = $option['currency'];
		}
		if (isset($option['currencyStep'])) {
			static::$currencyStep = $option['currencyStep'];
		}
		$output = '';
		$value = Settings::getOption($name);
		if (!empty($value) AND is_array($value)) foreach ($value as $groupIndex => $group) {
			$output .= self::renderMicropaymentsGroup($name, $groupIndex, $group);
		}
		$output = sprintf('<div class="cmmp-groups">%s</div>', $output);
		$output .= '<p><input type="button" value="Add group" class="cmmp-group-add" data-template="'.
			self::templateAttr(self::renderMicropaymentsGroup($name, '__group_index__', array())) .'" /></p>';
		return $output;
	}
	
	
	static function templateAttr($value) {
		return htmlspecialchars(strtr($value, array("\n" => '', "\r" => '', "\t" => '')));
	}
	
	
	static function renderMicropaymentsGroup($optionName, $groupIndex, $group) {
		$output = '';
		if (!empty($group['prices']) AND is_array($group['prices'])) foreach ($group['prices'] as $priceIndex => $price) {
			$output .= self::renderMicropaymentsPrice($optionName, $groupIndex, $priceIndex, $price);
		}
		$output = sprintf('<label>
				<input type="text" name="%s" value="%s" class="cmmp-group-name" placeholder="Group name" />
				<input type="button" value="Remove group" class="cmmp-group-remove" />
			</label>
			<div class="cmmp-prices">%s</div>',
			esc_attr($optionName .'['. $groupIndex .'][name]'),
			esc_attr(!empty($group['name']) ? $group['name'] : ''),
			$output);
		$output .= sprintf('<input type="button" value="Add price" data-template="%s" class="cmmp-price-add" />',
			self::templateAttr(self::getMicropaymentsItem($optionName, $groupIndex, '__item_index__')));
		return sprintf('<div class="cmmp-group" data-group-index="%s">%s</div>', $groupIndex, $output);
	}
	
	
	protected static function getMicropaymentsItem($optionName, $groupIndex, $priceIndex) {
		$name = $optionName . '[' . $groupIndex .'][prices]['. $priceIndex .']';
		return '<div class="cmmp-price" data-price-index="'. $priceIndex .'">
			<label><input type="number" name="'. $name .'[number]" value="%d" placeholder="Time" />
				<select name="'. $name .'[unit]">
					<option value="min"%s>minutes</option>
					<option value="h"%s>hours</option>
					<option value="d"%s>days</option>
					<option value="w"%s>weeks</option>
					<option value="m"%s>months</option>
					<option value="y"%s>years</option>
				</select>
			</label>
			<label> for <input type="number" name="'. $name .'[price]" value="'. (static::$currencyStep == 1 ? '%d' : '%.2f') .'" step="'. static::$currencyStep .'" /> '. static::$currency .'</label>
			<input type="button" value="Remove" class="cmmp-price-remove" />
		</div>';
	}
	
	
	protected static function renderMicropaymentsPrice($optionName, $groupIndex, $priceIndex, $price) {
		$name = $optionName . sprintf('[%d][%d]', $groupIndex, $priceIndex);
		$template = self::getMicropaymentsItem($optionName, $groupIndex, $priceIndex);
		return sprintf($template,
			$price['number'],
			selected($price['unit'], 'min', false),
			selected($price['unit'], 'h', false),
			selected($price['unit'], 'd', false),
			selected($price['unit'], 'w', false),
			selected($price['unit'], 'm', false),
			selected($price['unit'], 'y', false),
			$price['price']
		);
	}
	
	
}
