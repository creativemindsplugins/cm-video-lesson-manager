<?php

if (!empty($data)): ?>

	<table class="wp-list-table widefat fixed">
		<thead>
			<tr>
				<th>Channel name</th>
				<th>Duration [min]</th>
				<th>Videos number</th>
				<th>Users viewed</th>
				<th>Total minutes viewed</th>
				<th>Videos Details</th>
			</tr>
		</thead>
		<tbody><?php foreach ($data as $row): ?>
			<tr>
				<td><a href="<?php echo esc_attr(admin_url('post.php?action=edit&post='. $row['ID']));
					?>" title="Edit channel"><?php echo $row['post_title']; ?></a></td>
				<td><?php echo $row['duration_min']; ?></td>
				<td><?php echo $row['videos_num']; ?></td>
				<td><?php echo $row['users_num']; ?></td>
				<td><?php echo $row['minutes_watched']; ?></td>
				<td><a href="<?php echo esc_attr(add_query_arg('channel_id', $row['ID'], $showVideosUrl)); ?>">Videos Details</a></td>
			</tr>
		<?php endforeach; ?></tbody>
	</table>

<?php else: ?>
	<p>No data.</p>
<?php endif; ?>