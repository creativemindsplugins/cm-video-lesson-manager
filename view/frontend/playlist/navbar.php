<?php

use com\cminds\videolesson\model\Labels;
use com\cminds\videolesson\model\Category;

?>
<nav class="cmvl-navbar">
	<?php if (count($categoriesTree) > 1 OR count($channels) > 0): ?>
		<form class="cmvl-navbar-navigation">
			<?php if (count($categoriesTree) > 1): ?>
				<label class="cmvl-navbar-categories"><span><?php echo esc_html(ucfirst(Labels::getLocalized('category'))); ?>:</span><select name="category">
					<?php foreach ($categoriesTree as $categoryId => $categoryLabel):
						$category = Category::getInstance($categoryId);
						?>
						<option value="<?php echo esc_html($category->getFirstChannelPermalink()); ?>"<?php selected($categoryId, $currentCategoryId);
							?>><?php echo esc_html($categoryLabel); ?></option>
					<?php endforeach; ?>
				</select></label>
			<?php endif; ?>
			<?php if (count($channels) > 0): ?>
				<label class="cmvl-navbar-channels"><span><?php echo esc_html(ucfirst(Labels::getLocalized('channel'))); ?>:</span><select name="channel">
					<?php foreach ($channels as $channel): ?>
						<option value="<?php echo esc_html($channel->getPermalinkForCategory(Category::getInstance($currentCategoryId)));
							?>"<?php selected($channel->getId(), $currentChannelId);
							?>><?php echo esc_html($channel->getTitle()); ?></option>
					<?php endforeach; ?>
				</select></label>
			<?php endif; ?>
		</form>
	<?php endif; ?>
	
	<?php /*
	<ul class="cmvl-breadcrumbs">
		<li class="cmvl-breadcrumbs-category">
			<span><?php if ($currentCategory) echo esc_html($currentCategory->getName()); ?></span>
			<?php if (!empty($categoriesTree)): ?>
			<ul><?php foreach ($categoriesTree as $categoryId => $categoryLabel): ?>
					<?php $category = Category::getInstance($categoryId); ?>
					<li><a href="<?php echo esc_attr($category->getFirstChannelPermalink()); ?>"><?php echo esc_html(trim($categoryLabel)); ?></a></li>
				<?php endforeach; ?>
			</ul><?php endif; ?>
		</li><li class="cmvl-breadcrumbs-channel">
			<span><?php echo esc_html($currentChannel->getTitle()); ?></span>
			<?php if (!empty($channels)): ?>
				<ul><?php foreach ($channels as $channel): ?>
					<li><a href="<?php echo esc_attr($channel->getPermalinkForCategory($currentCategory));
						?>"><?php echo esc_html(trim($channel->getTitle())); ?></a></li>
				<?php endforeach; ?></ul>
			<?php endif; ?>
		</li>
	</ul>
	*/ ?>
	
</nav>