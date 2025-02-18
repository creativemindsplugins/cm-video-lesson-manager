<?php
use com\cminds\videolesson\model\Vimeo;
use com\cminds\videolesson\model\Labels;
?>
<section class="cmvl-playlist"><?php
	if (empty($videos)): ?><p class="cmvl-no-videos"><?php echo Labels::getLocalized('msg_no_videos'); ?></p><?php
	else:
		echo $paginationView;
		?><div class="cmvl-tiles"><?php foreach ($videos as $video):
			printf('<figure class="cmvl-video" data-video-id="%s" data-channel-id="%s">', $video->getId(), $video->getChannel()->getId());
			?><div class="cmvl-player-outer fluid-width-video-wrapper"><?php echo $video->getPlayer();
			?></div><header><ul class="cmvl-controls"><?php echo apply_filters('cmvl_video_controls', '', $video); ?></ul><h2><?php
				echo esc_html($video->getTitle()); ?></h2></header><figcaption><div class="cmvl-description-inner"><?php echo nl2br($video->getDescription());
				?></div></figcaption><?php do_action('cmvl_video_bottom', $video);
			echo '</figure>';
		endforeach; ?></div><?php
		echo $paginationView;
	endif; ?>
</section>