<?php

?>

<div class="cmvl-test-configuration">

	<?php if ($albums['status'] == 200 AND $channels['status'] == 200): ?>
		<div class="cmvl-success">Test passed - configuration works fine</div>
	<?php else: ?>
		<div class="cmvl-error">Test failed - configuration doesn't work</div>
	<?php endif; ?>
	
	<a href="#" class="button cmvl-show-details">Show details</a>
	
	<div class="cmvl-hidden-details">
	
		<div class="cmvl-test-albums">
			<h3>Albums: <?php echo (isset($albums['body']['total']) ? $albums['body']['total'] : 0); ?></h3>
			<?php if ($albums['status'] == 200): ?>
				<div class="cmvl-success">Success</div>
			<?php endif; ?>
			<?php if (empty($albums['body']['data'])): ?>
				<p>No albums.</p>
			<?php else: ?>
				<ul class="cmvl-albums-list"><?php foreach ($albums['body']['data'] as $item): ?>
					<li><?php echo esc_html($item['name']); ?></li>
				<?php endforeach; ?></ul>
			<?php endif; ?>
			<?php if (!empty($albums['body']['error'])): ?>
				<p class="cmvl-error"><?php echo $albums['body']['error']; ?></p>
			<?php endif; ?>
			<textarea><?php var_export($albums); ?></textarea>
		</div>
		
		<div class="cmvl-test-channels">
			<h3>Channels: <?php echo (isset($channels['body']['total']) ? $channels['body']['total'] : 0); ?></h3>
			<?php if ($channels['status'] == 200): ?>
				<div class="cmvl-success">Success</div>
			<?php endif; ?>
			<?php if (empty($channels['body']['data'])): ?>
				<p>No channels.</p>
			<?php else: ?>
				<ul class="cmvl-channels-list"><?php foreach ($channels['body']['data'] as $item): ?>
					<li><?php echo esc_html($item['name']); ?></li>
				<?php endforeach; ?></ul>
			<?php endif; ?>
			<?php if (!empty($channels['body']['error'])): ?>
				<p class="cmvl-error"><?php echo $channels['body']['error']; ?></p>
			<?php endif; ?>
			<textarea><?php var_export($channels); ?></textarea>
		</div>
	
	</div>

</div>