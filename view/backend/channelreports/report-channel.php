<p>Report for <?php printf('<a href="%s">%s</a>', esc_attr($channel->getEditUrl()), $channel->getTitle()); ?></p>

<?php

if (!empty($videos)): ?>

	<table class="wp-list-table widefat fixed">
		<thead>
			<tr>
				<th>Video name</th>
				<th>Duration [min]</th>
				<th>Users viewed</th>
				<th>Total minutes viewed</th>
			</tr>
		</thead>
		<tbody><?php foreach ($videos as $video): ?>
			<?php
			$videoId = $video->getId();
			$row = (isset($data[$video->getId()]) ? $data[$video->getId()] : null);
			?>
			<tr>
				<td><?php echo $video->getTitle(); ?></td>
				<td><?php echo $video->getDurationMin(); ?></td>
				<td><?php echo (isset($row['users_num']) ? $row['users_num'] : 0); ?></td>
				<td><?php echo (isset($row['minutes_watched']) ? $row['minutes_watched'] : 0); ?></td>
			</tr>
		<?php endforeach; ?></tbody>
	</table>

<?php else: ?>
	<p>No data.</p>
<?php endif; ?>