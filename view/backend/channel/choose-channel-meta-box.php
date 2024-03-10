<?php

use com\cminds\videolesson\App;

use com\cminds\videolesson\controller\ChannelController;

use com\cminds\videolesson\model\Video;

use com\cminds\videolesson\model\Channel;

// var_dump($currentChannelUri);
$printItem = function($name, $description, $uri) use ($currentChannelUri) {
// 	var_dump($uri);
	printf('<figure data-name="%s" data-description="%s">
				<label>
					%s
					<figcaption><input type="radio" name="cmvl_channel_uri" value="%s"%s />%s</figcaption>
				</label>
			</figure>',
		esc_attr($name),
		esc_attr(json_encode($description)),
		// 			(empty($channel['pictures']['sizes'][0]['link']) ? '' : sprintf('<img src="%s" alt="Thumb" />', esc_attr($channel['pictures']['sizes'][0]['link']))),
		'',
		$uri,
		($currentChannelUri == $uri ? ' checked="checked"' : ''),
		esc_html($name)
	);
};


if (!empty($channels) OR !empty($albums)) {
	
	echo '<h4>Vimeo Albums</h4>';
	echo '<section class="cmvl-tiles cmvl-channels" id="cmvl-choose-channel">';
	foreach ($albums as $item) {
		$printItem($item['name'], $item['description'], Channel::normalizeUri($item['uri']));
	}
	echo '</section>';
	
	echo '<h4>Vimeo Channels</h4>';
	echo '<section class="cmvl-tiles cmvl-channels" id="cmvl-choose-channel">';
	foreach ($channels as $item) {
		$printItem($item['name'], $item['description'], Channel::normalizeUri($item['uri']));
	}
	echo '</section>';
	
	if (App::isPro()) {
		echo '<div class="cmvl-channel-sort"><strong>Sorting:</strong> <select name="cmvl_channel_sort">';
		foreach (Video::getSortingOptions() as $key => $label) {
			printf('<option value="%s"%s />%s</option>',
				esc_attr($key), selected($key, $sort, false), $label);
		}
		echo '</select>
			<select name="cmvl_channel_sort_direction">
				<option value="'. Video::DIR_ASC .'"'. selected($sortDirection, Video::DIR_ASC, false) .'>ascending</option>
				<option value="'. Video::DIR_DESC .'"'. selected($sortDirection, Video::DIR_DESC, false) .'>descending</option>
			</select>
		</div>';
	}
	
	printf('<input type="hidden" name="%s" value="%s" />', esc_attr(ChannelController::NONCE_EDIT_CHANNEL), esc_attr($nonce));
	
} else {
	
	echo '<p>No albums or channels found. Please check if there are any albums/channels on your Vimeo account
		and you have provided the <a href="'. esc_attr('admin.php?page=cmvl-settings') .'">Vimeo API Settings</a>.</p>';
	
}
