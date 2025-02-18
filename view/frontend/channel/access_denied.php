<div class="cmvl-info-block cmvl-access-denied">
	<p><?php echo com\cminds\videolesson\model\Labels::getLocalized('msg_access_denied'); ?></p>
	<?php do_action('cmvl_access_denied_content', $channel); ?>
</div>