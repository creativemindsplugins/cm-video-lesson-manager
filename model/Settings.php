<?php

namespace com\cminds\videolesson\model;

class Settings extends SettingsAbstract {

    const TYPE_MP_PRICE_GROUPS = 'mp_price_groups';
    const TYPE_LIST_KEY_VALUE  = 'list_key_value';
    const OPTION_PERMALINK_PREFIX = 'cmvl_permalink_prefix';
    const OPTION_PLAYLIST_VIEW               = 'cmvl_playlist_view';
    const OPTION_PLAYLIST_LAYOUT             = 'cmvl_playlist_layout';
    const OPTION_PLAYLIST_MAX_WIDTH          = 'cmvl_playlist_max_width';
// 	const OPTION_VIDEO_SORT_METHOD = 'cmvl_video_sort_method';
// 	const OPTION_VIDEO_SORT_DIRECTION = 'cmvl_video_sort_direction';
    const OPTION_PAGINATION_LIMIT            = 'cmvl_pagination_limit';
    const OPTION_DASHBOARD_PAGE              = 'cmvl_dashboard_page';
    const OPTION_LOGIN_REDIRECT_DASHBOARD    = 'cmvl_login_redirect_dashboard';
    const OPTION_DASHBOARD_TABS              = 'cmvl_dashboard_tabs';
    const OPTION_CUSTOM_CSS                  = 'cmvl_custom_css';
    const OPTION_RELOAD_EXPIRED_SUBSCRIPTION = 'cmvl_reload_expired_subscription';
    const OPTION_UNLOCK_PRIVATE_VIDEOS       = 'cmvl_unlock_private_videos';
    const OPTION_VIMEO_CLIENT_ID     = 'cmvl_vimeo_client_id';
    const OPTION_VIMEO_CLIENT_SECRET = 'cmvl_vimeo_client_secret';
    const OPTION_VIMEO_ACCESS_TOKEN  = 'cmvl_vimeo_access_token';
    const OPTION_VIMEO_CACHE_SEC     = 'cmvl_vimeo_cache_sec';

    /**
     * @deprecated
     */
    const OPTION_VIMEO_PRIVACY_CACHE_SEC = 'cmvl_vimeo_privacy_cache_sec';
    const OPTION_NEW_SUB_ADMIN_NOTIF_ENABLE   = 'cmvl_new_sub_admin_nofif_enable';
    const OPTION_NEW_SUB_ADMIN_NOTIF_EMAILS   = 'cmvl_new_sub_admin_nofif_emails';
    const OPTION_NEW_SUB_ADMIN_NOTIF_SUBJECT  = 'cmvl_new_sub_admin_nofif_subject';
    const OPTION_NEW_SUB_ADMIN_NOTIF_TEMPLATE = 'cmvl_new_sub_admin_nofif_template';
    const OPTION_LESSONS_PROGRESS_NOTIF_CHANNEL_ENABLE = 'cmvl_course_finish_nofif_channel_enable';
    const OPTION_LESSONS_PROGRESS_NOTIF_VIDEO_ENABLE   = 'cmvl_course_finish_nofif_video_enable';
    const OPTION_LESSONS_PROGRESS_NOTIF_LACK_SECONDS   = 'cmvl_lesson_progress_notif_lack_sec';
    const OPTION_LESSONS_PROGRESS_ROUND_UP_SECONDS     = 'cmvl_lesson_progress_round_up_sec';
    const OPTION_LESSONS_PROGRESS_NOTIF_EMAILS         = 'cmvl_course_finish_nofif_emails';
    const OPTION_LESSONS_PROGRESS_NOTIF_SUBJECT        = 'cmvl_course_finish_nofif_subject';
    const OPTION_LESSONS_PROGRESS_NOTIF_TEMPLATE       = 'cmvl_course_finish_nofif_template';
    const OPTION_MP_WALLET_PAGE   = 'cmvl_mp_wallet_page';
    const OPTION_MP_CHECKOUT_PAGE = 'cmvl_mp_checkout_page';
    const OPTION_EDD_PAYMENT_MODEL  = 'cmvl_edd_payment_model';
    const OPTION_EDD_PRICING_GROUPS = 'cmvl_edd_pricing_groups';
    const OPTION_ACCESS_VIEW          = 'cmvl_access_view';
    const OPTION_DISABLE_CHANNEL_PAGE = 'cmvl_disable_channel_page';
    const OPTION_UPGRADE = 'cmvl_upgrade_option';
    const ACCESS_EVERYONE        = 'everyone';
    const ACCESS_LOGGED_IN_USERS = 'users';
    const PAGE_CREATE_KEY = '--create--';
    const PAGE_DEFINITION = 'newPage';
    const LAYOUT_PLAYLIST = 'playlist';
    const LAYOUT_TILES    = 'tiles';
    const PLAYLIST_VIDEOS_LIST_BOTTOM = 'bottom';
    const PLAYLIST_VIDEOS_LIST_LEFT   = 'left';
    const PLAYLIST_VIDEOS_LIST_RIGHT  = 'right';
    const EDDPAY_MODEL_PER_CHANNEL  = 'per_channel';
    const EDDPAY_MODEL_ALL_CHANNELS = 'all_channels';

    public static $categories = array(
        'general'       => 'General',
        'dashboard'     => 'Dashboard',
        'notifications' => 'Notifications',
        'labels'        => 'Labels',
    );
    public static $subcategories = array(
        'general'       => array(
            'navigation' => 'Navigation',
            'appearance' => 'Appearance',
            'vimeo'      => 'Vimeo',
            'access'     => 'Access',
        ),
        'notifications' => array(
            'progress' => 'Lessons progress',
            'sub'      => 'New subscription',
        ),
        'dashboard'     => array(
            'navigation' => 'Navigation',
            'tabs'       => 'Dashboard tabs',
        ),
    );

    public static function getOptionsConfig() {

        return apply_filters( 'cmvl_options_config', array(
            // Main
            self::OPTION_PERMALINK_PREFIX => array(
                'type'        => self::TYPE_STRING,
                'default'     => 'video-lesson',
                'category'    => 'general',
                'subcategory' => 'navigation',
                'title'       => 'Permalink prefix',
                'desc'        => 'Enter the prefix of the channels and categories permalinks, eg. <kbd>video-lesson</kbd> '
                . 'will give permalinks such as: <kbd>/<strong>video-lesson</strong>/category/channel</kbd>.',
            ),
            // Appearance
// 			self::OPTION_VIDEO_SORT_METHOD => array(
// 				'type' => self::TYPE_RADIO,
// 				'options' => array(
// 					Video::SORT_MANUAL => 'manual',
// 					Video::SORT_DATE => 'date',
// 					Video::SORT_ALPHABETICAL => 'alphabetical',
// 					Video::SORT_PLAYS => 'plays number',
// 					Video::SORT_LIKES => 'likes number',
// 					Video::SORT_COMMENTS => 'comments number',
// 					Video::SORT_DURATION => 'duration',
// 					Video::SORT_MODIFIED_TIME => 'modification time',
// 				),
// 				'default' => Video::SORT_MANUAL,
// 				'category' => 'general',
// 				'subcategory' => 'appearance',
// 				'title' => 'Videos sorting method',
// 				'desc' => 'Choose the videos sorting method.',
// 			),
// 			self::OPTION_VIDEO_SORT_DIRECTION => array(
// 				'type' => self::TYPE_RADIO,
// 				'options' => array(
// 					Video::DIR_ASC => 'ascending',
// 					Video::DIR_DESC => 'descending',
// 				),
// 				'default' => Video::DIR_ASC,
// 				'category' => 'general',
// 				'subcategory' => 'appearance',
// 				'title' => 'Videos sorting direction',
// 				'desc' => 'Choose the videos sorting direction.',
// 			),
            self::OPTION_PAGINATION_LIMIT => array(
                'type'        => self::TYPE_INT,
                'default'     => 10,
                'category'    => 'general',
                'subcategory' => 'appearance',
                'title'       => 'Videos per page',
                'desc'        => 'Limit the videos per page number in the tiles view. Max is 50.',
            ),
            self::OPTION_CUSTOM_CSS       => array(
                'type'        => self::TYPE_TEXTAREA,
                'category'    => 'general',
                'subcategory' => 'appearance',
                'title'       => 'Custom CSS',
            ),
            // Vimeo
            self::OPTION_VIMEO_CLIENT_ID     => array(
                'type'        => self::TYPE_STRING,
                'category'    => 'general',
                'subcategory' => 'vimeo',
                'title'       => 'App Client Identifier',
                'desc'        => 'Enter the client identifier of the Vimeo App.<br /><a href="https://developer.vimeo.com/apps/new" class="button" target="_blank">'
                . 'Generate new identifier</a>',
            ),
            self::OPTION_VIMEO_CLIENT_SECRET => array(
                'type'        => self::TYPE_STRING,
                'category'    => 'general',
                'subcategory' => 'vimeo',
                'title'       => 'App Client Secret',
                'desc'        => 'Enter the client secret of the Vimeo App.',
            ),
            self::OPTION_VIMEO_ACCESS_TOKEN  => array(
                'type'        => self::TYPE_STRING,
                'category'    => 'general',
                'subcategory' => 'vimeo',
                'title'       => 'Access token',
                'desc'        => 'Enter the access token with the public, private, edit and interact priviliges.<br />'
                . '<a href="#" class="button cmvl-test-configuration">Test Configuration</a>',
            ),
            self::OPTION_VIMEO_CACHE_SEC     => array(
                'type'        => self::TYPE_INT,
                'default'     => 3600,
                'category'    => 'general',
                'subcategory' => 'vimeo',
                'title'       => 'Cache lifetime for Vimeo API',
                'desc'        => 'Enter the number of seconds to cache the results of the Vimeo API video requests. Caching will increase the load times. Set 0 to disable.',
            ),
// 			self::OPTION_VIMEO_PRIVACY_CACHE_SEC => array(
// 				'type' => self::TYPE_INT,
// 				'default' => 3600,
// 				'category' => 'general',
// 				'subcategory' => 'vimeo',
// 				'title' => 'Cache lifetime for Vimeo API privacy checks',
// 				'desc' => 'Enter the number of seconds to cache the results of the Vimeo API requests which are checking the privacy settings. '
// 						. 'This is important to often check the privacy settings in order to add the current website to the video domains\' whitelist. '
// 						. 'Caching will increase the load times. Set 0 to disable.',
// 			),
        ) );
    }

    public static function processPostRequest( $data ) {

        // Create new pages
        $options = static::getOptionsConfig();
        foreach ( $data as $key => &$value ) {
            if ( $value == self::PAGE_CREATE_KEY AND ! empty( $options[ $key ][ self::PAGE_DEFINITION ] ) ) {
                $post   = array_merge( array(
                    'post_author'    => get_current_user_id(),
                    'post_status'    => 'publish',
                    'post_type'      => 'page',
                    'comment_status' => 'closed',
                    'ping_status'    => 'closed',
                ), $options[ $key ][ self::PAGE_DEFINITION ] );
                $result = wp_insert_post( $post );
                if ( is_numeric( $result ) ) {
                    $value = $result;
                }
            } else {
				if(!is_array($value)) {
					$value = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $value);
					$value = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $value);
					$value = sanitize_text_field($value);
				}
			}
        }

        do_action( 'cmvl_settings_save', $data );

        parent::processPostRequest( $data );
    }

}
