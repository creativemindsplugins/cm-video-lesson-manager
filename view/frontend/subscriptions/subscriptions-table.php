<?php

use com\cminds\videolesson\model\ChannelSubscription;

use com\cminds\videolesson\model\Labels;

?>
<div class="cmvl-subscriptions-shortcode">
	<?php if (!empty($rows)): ?>
		<table>
			<thead><tr>
				<th><?php echo Labels::getLocalized('channel_name'); ?></th>
				<th><?php echo Labels::getLocalized('subscription_period'); ?></th>
				<th class="narrow"><?php echo Labels::getLocalized('subscription_amount_payed'); ?></th>
				<?php if (is_user_logged_in() AND $atts['status'] != 'active'): ?>
					<th><?php echo Labels::getLocalized('channel_purchase'); ?></th>
				<?php endif; ?>
			</tr></thead>
			<tbody>
				<?php foreach ($rows as $row): ?>
					<?php $channel = $row['channel']; ?>
					<tr>
						<td data-col="channel-name">
							<div data-field="channel-name"><a href="<?php echo esc_attr($channel->getPermalink()); ?>"><?php echo esc_html($channel->getTitle()); ?></a></div>
							<div data-field="channel-total-videos"><strong><?php echo Labels::getLocalized('channel_videos_num'); ?>:</strong>
								<span><?php echo $channel->getTotalVideos(); ?></span></div>
							<div data-field="channel-duration-min"><strong><?php echo Labels::getLocalized('channel_duration'); ?>:</strong>
								<span><?php echo sprintf(Labels::getLocalized('channel_duration_val'), $channel->getDurationMin()); ?></span></div>
						</td>
						<td data-col="subscription-period">
							<div data-field="subscription-start"><strong><?php echo Labels::getLocalized('subscription_start'); ?>:</strong>
								<span><?php echo Date('Y-m-d H:i:s', $row['start']); ?></span></div>
							<div data-field="subscription-end"><strong><?php echo Labels::getLocalized('subscription_end'); ?>:</strong>
								<span><?php echo Date('Y-m-d H:i:s', $row['end']); ?></span></div>
							<div data-field="subscription-status"><strong><?php echo Labels::getLocalized('subscription_status'); ?>:</strong>
								<span><?php echo (($row['end'] <= time()) ? 'past' : 'active'); ?></span></div>
						</td>
						<td data-col="subscription-amount-payed"><?php echo ($row['amount'] ? apply_filters('cmvl_format_amount_payed', $row['amount'], $row['paymentplugin']) : Labels::getLocalized('free')); ?></td>
						<?php if (is_user_logged_in() AND $atts['status'] != 'active'): ?>
							<?php do_action('cmvl_subscriptions_table_row', $row); ?>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<p><?php echo Labels::getLocalized('msg_no_subscriptions'); ?></p>
	<?php endif; ?>
</div>