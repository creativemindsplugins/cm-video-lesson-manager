<?php
namespace com\cminds\videolesson\model;

class Video extends Model {
	
	const META_POST_VIDEO_DURATION = 'cmvl_video_duration';
	
	const SORT_MANUAL = 'manual';
	const SORT_DATE = 'date';
	const SORT_ALPHABETICAL = 'alphabetical';
	const SORT_PLAYS = 'plays';
	const SORT_LIKES = 'likes';
	const SORT_COMMENTS = 'comments';
	const SORT_DURATION = 'duration';
	const SORT_MODIFIED_TIME = 'modified_time';
	
	const DIR_ASC = 'asc';
	const DIR_DESC = 'desc';
	
	protected $video;
	protected $channel;
	protected $searchScore;
	
	function __construct($video, Channel $channel) {
		
		$this->video = $video;
		$this->channel = $channel;
		
		$this->cacheDuration();
		
	}
	
	protected function cacheDuration() {
		$channelId = $this->getChannel()->getId();
		$metaKey = self::META_POST_VIDEO_DURATION .'_'. $this->getId();
		if (!get_post_meta($channelId, $metaKey, $single = true)) {
			update_post_meta($channelId, $metaKey, $this->getDuration());
		}
		return $this;
	}
	
	function getTitle() {
		return $this->video['name'];
	}

	function getDescription($stripMarkupTags = true) {
		$desc = $this->video['description'];
		if ($stripMarkupTags) {
			$tags = $this->getDescriptionMarkupTags();
			foreach ($tags as $tag) {
				$desc = str_replace($tag[0], '', $desc);
			}
		}
		return $desc;
	}

	function getDescriptionMarkupTags() {
		preg_match_all('~\[([^\]]+)\]\(([^\)]+)\)~', $this->getDescription($strip = false), $match, PREG_SET_ORDER);
		return $match;
	}
	
	function getDuration() {
		return $this->video['duration'];
	}
	
	function getDurationMin() {
		return round($this->getDuration()/60);
	}
	
	function getDurationFormatted() {
		$duration = $this->getDuration();
		if ($duration > 3600) return Date('H:i:s', $duration);
		else return Date('i:s', $duration);
	}
	
	function getThumbUri($minWidth = 100) {
		if (!empty($this->video['pictures']['sizes'])) {
			foreach ($this->video['pictures']['sizes'] as $picture) {
				if ($picture['width'] >= $minWidth) {
					return $picture['link'];
				}
			}
		}
	}
	
	function getScreenshot() {
		if (!empty($this->video['pictures']['sizes'])) {
			$picture = end($this->video['pictures']['sizes']);
			return $picture['link'];
		}
	}
	
	function getChannel() {
		return $this->channel;
	}
	
	function getPermalink() {
		return add_query_arg('video', urlencode($this->getId()), $this->getChannel()->getPermalink());
	}
	
	function getPlayerUrl(array $options = array()) {
		$options = shortcode_atts(array(
			'api' => 1,
			'h' => $this->getHash(),
			'player_id' => null,
			'autoplay' => 0,
			'badge' => 0,
			'byline' => 0,
			'portrait' => 0,
			'title' => 0,
			'wmode' => 'opaque',
		), $options);
		return add_query_arg(urlencode_deep($options), 'https://player.vimeo.com/video/' . urlencode($this->getId()));
	}
	
	function getPlayer(array $options = array()) {
		if (empty($options['player_id'])) $options['player_id'] = 'cmvl-player-' . rand(0, 99999);
		return '<iframe id="'. esc_attr($options['player_id']) .'" src="'. esc_attr($this->getPlayerUrl($options))
			. '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
	}
	
	function getId() {
		if (isset($this->video['uri'])) {
			return preg_replace('/[^0-9]/', '', $this->video['uri']);
		}
	}

	function gethash() {
		if (isset($this->video['link'])) {
			
			$hash = '';
			$video_link = $this->video['link'];
			if($video_link != '') {
				$video_link_arr = explode('vimeo.com/', $video_link);
				if(count($video_link_arr) == 2) {
					$video_link_arr_1 = explode('/', $video_link_arr[1]);
					if(count($video_link_arr_1) == 2) {
						if(isset($video_link_arr_1[1])) {
							$hash = $video_link_arr_1[1];
						}
					}
				}
			}
			return $hash;

		}
	}
	
	function getVimeoUri() {
		return '/videos/'. $this->getId();
	}
	
	static function getAll($onlyVisible = true) {
		$channels = Channel::getAll($onlyVisible);
		$results = array();
		foreach ($channels as $channel) {
			$videos = $channel->getAllVideos();
			foreach ($videos as $video) {
				$results[$video->getId()] = $video;
			}
		}
		return $results;
	}

	function setSearchScore($score) {
		$this->searchScore = $score;
		return $this;
	}
	
	function getSearchScore() {
		return $this->searchScore;
	}

	static function search($str, $context) {
	
		$videosResults = array();
		$page = 1;
		do {
			$resposne = Vimeo::getInstance()->request('/me/videos', array('query' => $str, 'per_page' => Channel::MAX_PER_PAGE));
			if (!empty($resposne['body']['data'])) foreach ($resposne['body']['data'] as $video) {
				$videoId = Channel::parseId($video['uri']);
				$videosResults[$videoId] = $videoId;
			}
			$page++;
		} while (!empty($resposne['body']['data']));
	
		// Filter videos which are associated with context
		foreach ($videosResults as $videoId => &$video) {
			if (isset($context[$videoId])) {
				$video = $context[$videoId];
			} else {
				$video = null;
			}
		}
	
		return array_filter($videosResults);
	
	}
	
	function getChannelId() {
		return $this->getChannel()->getId();
	}
	
	static function getSortingOptions() {
		return array(
			Video::SORT_MANUAL => 'manual',
			Video::SORT_DATE => 'date',
			Video::SORT_ALPHABETICAL => 'alphabetical',
			Video::SORT_PLAYS => 'plays number',
			Video::SORT_LIKES => 'likes number',
			Video::SORT_COMMENTS => 'comments number',
			Video::SORT_DURATION => 'duration',
			Video::SORT_MODIFIED_TIME => 'modification time',
		);
	}
	
	function isProgressNotificationEnabled() {
		$status = $this->getChannel()->getVideosProgressNotificationStatus();
		if ($status == Channel::NOTIFICATION_STATUS_DISABLED) {
			return false;
		}
		else if ($status == Channel::NOTIFICATION_STATUS_ENABLED) {
			return true;
		} else {
			return Settings::getOption(Settings::OPTION_LESSONS_PROGRESS_NOTIF_VIDEO_ENABLE);
		}
	}
	
	function clearCache() {
		$vimeo = Vimeo::getInstance();
		$vimeo->disableCacheOnce();
		$result = $vimeo->request($this->getVimeoUri());
		if (!empty($result['body']['data'])) {
			$this->video = $result['body']['data'];
		}
		return $this;
	}

	function getRawVideo() {
		return $this->video;
	}
	
}