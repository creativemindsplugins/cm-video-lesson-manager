<?php

use com\cminds\videolesson\controller\ChannelController;

if ($total_pages > 1): ?>
	<ul class="cmvl-pagination"><?php
	
	$writeItem = function($pageNumber, $label = null, $class = '', $disabled = false) use ($total_pages, $page, $base_url) {
		if (is_scalar($class)) {
			$class = array($class);
		}
		if (is_null($label)) $label = $pageNumber;
		if ($pageNumber == $page) $class[] = 'cmvl-pagination-current';
		if ($disabled) {
			$class[] = 'cmvl-pagination-disabled';
			$template = '<li%s>%s</li>';
		} else {
			if ($pageNumber > 1) {
				$url = add_query_arg(ChannelController::PARAM_PAGE, $pageNumber, $base_url);
			} else {
				$url = $base_url;
			}
			$template = '<li%s><a href="'. esc_attr($url) .'">%s</a></li>';
		}
		if (!empty($class)) {
			$classStr = ' class="'. implode(' ', $class) .'"';
		} else {
			$classStr = '';
		}
		printf($template, $classStr, $label);
	};
	
	$writeItem($page-1, '&laquo;', 'cmvl-pagination-prev', $page == 1);
	
	if ($total_pages < 6): // show all pages
		for ($i=1; $i<=$total_pages; $i++):
			$writeItem($i);
		endfor;
	else: // show only neighbors
		$writeItem(1);
		if ($page > 2) $writeItem($page-1);
		if ($page > 1 AND $page < $total_pages) $writeItem($page);
		if ($page < $total_pages-1) $writeItem($page+1);
		$writeItem($total_pages);
	endif;
	
	$writeItem($page+1, '&raquo;', 'cmvl-pagination-next', $page == $total_pages);
	
	?></ul>
<?php endif;?>