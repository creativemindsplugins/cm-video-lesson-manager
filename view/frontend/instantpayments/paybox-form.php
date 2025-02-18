<?php

use com\cminds\videolesson\model\ChannelSubscription;

use com\cminds\videolesson\model\Labels;
use com\cminds\videolesson\model\ChannelInstantPayment;

if (count($costs) == 1) {
	$singleCost = reset($costs);
	$submitLabel = sprintf(
		Labels::getLocalized('eddpay_subscription_checkout_button_period_for_amount'),
		ChannelSubscription::period2date($singleCost['period']),
		(float)$singleCost['cost']
	);
} else {
	$submitLabel = Labels::getLocalized('eddpay_subscription_checkout_button');
}

?>

<form class="cmvl-channel-paybox-form" data-ajax-url="<?php echo esc_attr(admin_url('admin-ajax.php')); ?>">
	<?php if (count($costs) > 1): ?>
		<div class="cmvl-channel-paybox-costs"><?php foreach ($costs as $cost): ?>
			<label><input type="radio" name="edd_download_id" value="<?php echo $cost['edd_download_id']; ?>" class="cmvl-price"><?php
			
			printf(Labels::getLocalized('eddpay_period_for_amount'), ChannelSubscription::period2date($cost['period']), $cost['cost']);
			
			?></label>
		<?php endforeach; ?></div>
	<?php else:
		printf('<input type="hidden" name="edd_download_id" value="%d" class="cmvl-price">', $singleCost['edd_download_id']);
	endif; ?>
	<div class="cmvl-channel-payment-buttons">
		<input type="hidden" name="channelId" value="<?php echo esc_attr($channelId); ?>" />
		<input type="hidden" name="nonce" value="<?php echo esc_attr($nonce); ?>" />
		<input type="hidden" name="action" value="cmvl_channel_eddpay" />
		<input type="hidden" name="callbackUrl" value="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>" />
		<input type="submit" value="<?php echo esc_attr($submitLabel); ?>" />
	</div>
</form>