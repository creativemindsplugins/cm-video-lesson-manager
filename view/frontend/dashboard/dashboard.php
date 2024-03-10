<?php 


use com\cminds\videolesson\shortcode\BookmarksShortcode;

use com\cminds\videolesson\shortcode\StatsShortcode;

?><div class="cmvl-dashboard-shortcode cmvl-tabs-outer">
	<div class="cmvl-tabs-menu-outer">
		<ul class="cmvl-tabs-menu"><?php foreach ($tabs as $tab):
			printf('<li data-tab="%s">%s</li>', sanitize_title($tab['label']), $tab['label']);
		endforeach; ?></ul>
	</div>
	<div class="cmvl-tab-content-outer"><?php foreach ($tabs as $tab):
		printf('<div class="cmvl-tab-content" data-tab="%s">', esc_attr(sanitize_title($tab['label'])));
			echo do_shortcode($tab['content']);
		echo '</div>';
	endforeach; ?></div>
</div>

<script type="text/javascript">
jQuery(function($) {
	$('.cmvl-tabs-menu li').click(function() {
		var item = $(this);
		var wrapper = item.parents('.cmvl-tabs-outer');
		wrapper.find('.cmvl-tab-content').hide();
		wrapper.find('.cmvl-tab-content[data-tab='+ item.data('tab') +']').show();
		wrapper.find('.cmvl-tabs-menu li.current').removeClass('current');
		item.addClass('current');
	});
	$('.cmvl-tabs-menu li').first().click();
});
</script>