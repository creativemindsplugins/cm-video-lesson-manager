<?php

namespace com\cminds\videolesson\model;

class ChannelReport extends Model {
	
	static function getIndexData() {
		global $wpdb;
		
		$sql = $wpdb->prepare("SELECT p.ID,
				p.post_title,
				IFNULL(pmd.meta_value, 0) AS duration_min,
				COUNT(DISTINCT ums.user_id) users_num,
				SUM(ROUND(IFNULL(ums.meta_value, 0)/60)) AS minutes_watched
			FROM $wpdb->posts p
			LEFT JOIN $wpdb->usermeta ums ON ums.meta_key LIKE CONCAT(%s, '|_', p.ID, '|_%%') ESCAPE '|' AND ums.meta_key LIKE %s
			LEFT JOIN $wpdb->postmeta pmd ON pmd.post_id = p.ID AND pmd.meta_key LIKE %s
			WHERE p.post_type = %s AND p.post_status = 'publish'
			GROUP BY p.ID
			ORDER BY minutes_watched DESC
			",
			Stats::META_USER_STATS_WATCHED_SECONDS,
			Stats::META_USER_STATS_WATCHED_SECONDS . '_%',
			Channel::META_DURATION_MIN,
			Channel::POST_TYPE
		);
		$data = $wpdb->get_results($sql, ARRAY_A);
		
		$data = array_map(function($row) {
			$row['videos_num'] = 0;
			if ($channel = Channel::getInstance($row['ID'])) {
				$row['videos_num'] = $channel->getTotalVideos();
			}
			return $row;
		}, $data);
		
		return $data;
		
	}
	
	
	static function getChannelData($channelId) {
		global $wpdb;
	
		$sql = $wpdb->prepare("SELECT
				SUBSTRING_INDEX(ums.meta_key, '_', -1) AS video_id,
				ROUND(IFNULL(pmd.meta_value, 0)/60) AS duration_min,
				COUNT(DISTINCT ums.user_id) users_num,
				SUM(ROUND(ums.meta_value/60)) AS minutes_watched
			FROM $wpdb->usermeta ums
			JOIN $wpdb->posts p ON ums.meta_key LIKE CONCAT(%s, '|_', p.ID, '|_%%') ESCAPE '|'
			JOIN $wpdb->postmeta pmd ON pmd.post_id = p.ID AND pmd.meta_key LIKE CONCAT(%s, SUBSTRING_INDEX(ums.meta_key, '_', -1))
			WHERE p.ID = %d AND ums.meta_key LIKE %s AND p.post_status = 'publish'
			GROUP BY SUBSTRING_INDEX(ums.meta_key, '_', -1)
			ORDER BY minutes_watched DESC
			",
			Stats::META_USER_STATS_WATCHED_SECONDS,
			Video::META_POST_VIDEO_DURATION .'_',
			$channelId,
			Stats::META_USER_STATS_WATCHED_SECONDS . '_%'
		);
// 		var_dump($sql);
		$data = $wpdb->get_results($sql, ARRAY_A);
		
// 		var_dump($data);
		
		$result = array();
		foreach ($data as $row) {
			$result[$row['video_id']] = $row;
		}
	
// 		$data = array_map(function($row) {
// 			$row['videos_num'] = 0;
// 			if ($channel = Channel::getInstance($row['ID'])) {
// 				$row['videos_num'] = $channel->getTotalVideos();
// 		}
// 		return $row;
// 		}, $data);
	
		return $result;
	
	}
	
	
}