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
        'appearance'    => 'Appearance',
        'dashboard'     => 'Dashboard',
        'notifications' => 'Notifications',
        'labels'        => 'Labels',
    );
    public static $subcategories = array(
        'general'       => array(
            'navigation' => 'Navigation',
            //'appearance' => 'Appearance',		
			'vimeo' => 'Vimeo API Account 1',
			'vimeo2' => 'Vimeo API Account 2',
			'youtube' => 'YouTube API',
			'wistia' => 'Wistia API',
			'hubspot' => 'HubSpot API',
			'access' => 'Access',
			's2memberpro' => 'S2Member Pro integration',
			'stats' => 'Statistics',
			'restrictions' => 'Restrictions',
			'badges' => 'Badges',
				
        ),
		'appearance' => array(
			'appearance' => 'Appearance',
			'progress' => 'Progress Indicator',
			'css' => 'Custom CSS',
		),
		'notifications' => array(
			'progress_general' => 'Progress General Options',
			'course_progress' => 'Course Progress',
			'lesson_progress' => 'Lesson Progress',
			'video_progress' => 'Video Progress',
			'notes_overview' => 'Notes Overview',
			'new_lesson_notifications' => 'New Lesson Notifications',
			'new_video_unlock_notifications' => 'Video Unlock Notifications',
			'sub' => 'New Subscription',
			'email' => 'Email Settings',
		),
		'dashboard' => array(
			'navigation' => 'Navigation',
			'tabs' => 'Dashboard Tabs',
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
			'_onlyinpro_a' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'general',
				'subcategory' => 'navigation',
				'title' => 'Lesson permalink part',
				'desc' => 'Enter the lesson permalink part: <kbd>/video-lessons/<strong>lesson</strong>/lesson-slug</kbd>.',
			),
			'_onlyinpro_b' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'general',
				'subcategory' => 'navigation',
				'title' => 'Bookmarks page',
				'desc' => 'Select page which will display the user\'s bookmarks (using the cmvl-bookmarks shortcode) or choose "-- create new page --" to create such page.',
			),
			'_onlyinpro_c' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'general',
				'subcategory' => 'navigation',
				'title' => 'Courses page',
				'desc' => 'Select page which will display the all courses (using the cmvl-courses-list shortcode) or choose "-- create new page --" to create such page.',
			),
			'_onlyinpro_d' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'general',
				'subcategory' => 'navigation',
				'title' => 'Your Courses page',
				'desc' => 'Select page which will display the all courses (using the cmvl-courses-list shortcode) or choose "-- create new page --" to create such page.',
			),
			'_onlyinpro_e' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'general',
				'subcategory' => 'navigation',
				'title' => 'Statistics page',
				'desc' => 'Select page which will display the user\'s statistics (using the cmvl-stats shortcode) or choose "-- create new page --" to create such page.',
			),
			'_onlyinpro_f' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'general',
				'subcategory' => 'navigation',
				'title' => 'Resume watching video',
				'desc' => '',
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
			'_onlyinpro_app1' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Default template',
				'desc' => 'Select the default template for frontend.',
			),
            self::OPTION_PAGINATION_LIMIT => array(
                'type'        => self::TYPE_INT,
                'default'     => 10,
                'category'    => 'appearance',
                'subcategory' => 'appearance',
                'title'       => 'Videos per page',
                'desc'        => 'Limit the videos per page number in the tiles view. Max is 50.',
            ),
			'_onlyinpro_app2' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Default videos layout',
				'desc' => 'Playlist and Tiles layout will work on lesson pages and List layout will work only on course pages.',
			),
			'_onlyinpro_app3' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Display video info position at',
				'desc' => 'Works only if using the Playlist videos layout. The video info will be placed in the chosen place.',
			),
			'_onlyinpro_app4' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Display videos list in playlist at',
				'desc' => 'Works only if using the Playlist videos layout. The videos list will be placed in the chosen place.',
			),
			'_onlyinpro_app5' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Thumbnail style',
				'desc' => 'Works only if using the Playlist videos layout. The videos will show with thumbnail style.',
			),
			'_onlyinpro_app6' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Playlist max width',
				'desc' => 'Set 0 to disable.',
			),
			'_onlyinpro_app7' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Show search field',
				'desc' => 'If enabled, the search bar will be displayed on each lesson page above the video.',
			),
			'_onlyinpro_app8' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Show links bar',
				'desc' => 'If enabled, the links bar (Bookmarks, Statistics etc.) will be displayed on each lesson page above the video.',
			),
			'_onlyinpro_app9' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Show navigation bar',
				'desc' => 'If enabled, the courses/lessons navigation menu will be displayed on each lesson page above the video.',
			),
			'_onlyinpro_app10' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Show bottom navigation bar',
				'desc' => 'If enabled, then previous/next navigation will be displayed on each lesson page below the video.',
			),
			'_onlyinpro_app11' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Show lesson name under the nav bar',
				'desc' => 'If enabled, the lesson name will be displayed under the navigation bar.',
			),
			'_onlyinpro_app12' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Show lesson description header',
				'desc' => 'If enabled, the header "Lesson description" will be displayed before the lesson\'s description text.',
			),
			'_onlyinpro_app13' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Show video description',
				'desc' => '',
			),
			'_onlyinpro_app14' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Show full video description',
				'desc' => 'If enabled, the complete description will be instantly displayed. If disabled, part of the text will be hidden until the mouse hovers it.',
			),
			'_onlyinpro_app15' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Show lesson description',
				'desc' => '',
			),
			'_onlyinpro_app16' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Show course description',
				'desc' => '',
			),
			'_onlyinpro_app17' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Show lesson note text area',
				'desc' => 'If enabled, then front user able to add personal note with each lesson and same notes visible to front user and admin also able to see under Lesson Notes section.',
			),
			'_onlyinpro_app18' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Show video note text area',
				'desc' => 'If enabled, then front user able to add personal note with each video and same notes visible to front user only.',
			),
			'_onlyinpro_app19' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Page template',
				'desc' => 'Select page template for the generic lesson pages.',
			),
			'_onlyinpro_app20' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Allow shortcodes in the content',
				'desc' => 'If enabled the shortcodes will be processed when displaying a video, lesson and course content.',
			),
			'_onlyinpro_app21' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Show course names on the lesson page',
				'desc' => 'If enabled the lesson\'s course names will be displayed on the lesson\'s page.',
			),
			'_onlyinpro_app22' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Show lesson author name',
				'desc' => 'If enabled the lesson\'s author name will be displayed on the lesson\'s page.',
			),
			'_onlyinpro_app23' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Show lesson updated date',
				'desc' => 'If enabled the lesson\'s updated date will be displayed on the lesson\'s page.',
			),
			'_onlyinpro_app24' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Show icons for documents',
				'desc' => 'Enable this, if document/file icons doesn\'t appear.',
			),
			'_onlyinpro_app25' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Open video in separate window',
				'desc' => '',
			),
			'_onlyinpro_app26' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Open each video in a new tab',
				'desc' => 'Should the video page to be opened in a new tab? (target="_blank")',
			),
			'_onlyinpro_app27' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Video autoplay',
				'desc' => 'If set Yes the main video will autoplay and this option will work only with videos playlist layout. Yes with mute your video on load automatically mute your video on load. Once your video plays, viewers can manually un-mute by clicking on the volume bar within the player.',
			),
			'_onlyinpro_app28' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Show playlist feature',
				'desc' => 'Enable this, if you want to show manage playlist feature.',
			),
			'_onlyinpro_app29' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'appearance',
				'subcategory' => 'appearance',
				'title' => 'Expand course videos',
				'desc' => 'Enable this, if you want to show entire course videos with lesson heading. Works only if using the Playlist videos layout.',
			),
			'_onlyinpro_app30' => array(
				'type' => Settings::TYPE_CUSTOM,
				'category' => 'appearance',
				'subcategory' => 'progress',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'title' => 'Enable progress indicator',
				'desc' => 'If enabled then the progress indicator flag will be added to each video in the playlist menu.',
			),
			'_onlyinpro_app31' => array(
				'type' => Settings::TYPE_CUSTOM,
				'category' => 'appearance',
				'subcategory' => 'progress',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'title' => 'Show percent value of the progress',
				'desc' => 'If enabled then the percent progress value will be displayed.',
			),
			'_onlyinpro_app32' => array(
				'type' => Settings::TYPE_CUSTOM,
				'category' => 'appearance',
				'subcategory' => 'progress',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'title' => 'Not started video color',
				'desc' => 'Color for the progress indicator when user hasn\'t started watching the video yet.',
			),
			'_onlyinpro_app33' => array(
				'type' => Settings::TYPE_CUSTOM,
				'category' => 'appearance',
				'subcategory' => 'progress',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'title' => 'Started video color',
				'desc' => 'Color for the progress indicator when user has started watching the video but hasn\'t completed yet.',
			),
			'_onlyinpro_app34' => array(
				'type' => Settings::TYPE_CUSTOM,
				'category' => 'appearance',
				'subcategory' => 'progress',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'title' => 'Completed video color',
				'desc' => 'Color for the progress indicator when the video is completed by the user.',
			),
			'_onlyinpro_app35' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'dashboard',
				'subcategory' => 'navigation',
				'title' => 'Dashboard page',
				'desc' => 'Select page which will display the user\'s dashboard (using the cmvl-dashboard shortcode) or choose '
							. '"-- create new page --" to create such page.',
			),
			'_onlyinpro_app36' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'dashboard',
				'subcategory' => 'navigation',
				'title' => 'Show dashboard after login',
				'desc' => 'If enabled, users after login to Wordpress will be redirected to the Dashboard page by default.',
			),
			'_onlyinpro_app37' => array(
				'type' => Settings::TYPE_CUSTOM,
				'category' => 'dashboard',
				'subcategory' => 'tabs',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'title' => 'Dashboard tabs',
				'desc' => 'Drag and drop to change the order.',
			),
			'_onlyinpro_app38' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'notes_overview',
				'title' => 'Enable notifications for notes overview',
				'desc' => 'Send notification of lessons notes overview at the end of the month.',
			),
			'_onlyinpro_app39' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'notes_overview',
				'title' => 'Emails to notify',
				'desc' => 'Enter comma separated email addresses to send the notification to.',
			),
			'_onlyinpro_app40' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'notes_overview',
				'title' => 'Email subject',
				'desc' => 'You can use following shortcode:<br />[blogname] - website\'s name',
			),
			'_onlyinpro_app41' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'notes_overview',
				'title' => 'Email body template',
				'desc' => 'You can use following shortcodes:<br />[blogname] - website\'s name<br />[home] - website\'s home url<br />[notes_overview] - lists of notes',
			),
			// New Lesson Notifications
			'_onlyinpro_app42' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'new_lesson_notifications',
				'title' => 'Enable notifications for new lesson',
				'desc' => 'Send notification when admin created new lesson.',
			),
			'_onlyinpro_app43' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'new_lesson_notifications',
				'title' => 'Email subject',
				'desc' => 'You can use following shortcodes:<br />[blogname] - website\'s name<br />'
							. '[lesson_name] - name of the lesson',
			),
			'_onlyinpro_app44' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'new_lesson_notifications',
				'title' => 'Email body template',
				'desc' => 'You can use following shortcodes:<br />[blogname] - website\'s name<br />[home] - website\'s home url'
					. '<br />[lesson_name] - name of the lesson<br />[lesson_permalink] - permalink to the lesson<br />[username]<br />[userlogin]<br />[useremail]',
			),
			// Video Unlock Notifications
			'_onlyinpro_app45' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'new_video_unlock_notifications',
				'title' => 'Enable notifications for video unlock',
				'desc' => 'Send notification when video unlock for user.',
			),
			'_onlyinpro_app46' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'new_video_unlock_notifications',
				'title' => 'Email subject',
				'desc' => 'You can use following shortcodes:<br />[blogname] - website\'s name<br />'
							. '[video_name] - name of the video',
			),
			'_onlyinpro_app47' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'new_video_unlock_notifications',
				'title' => 'Email body template',
				'desc' => 'You can use following shortcodes:<br />[blogname] - website\'s name<br />[home] - website\'s home url'
					. '<br />[lesson_name] - name of the lesson<br />[lesson_permalink] - permalink to the lesson<br />[video_name] - name of the video<br />[video_permalink] - permalink to the video<br />[username]<br />[userlogin]<br />[useremail]',
			),
			// Course progress
			'_onlyinpro_app48' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'course_progress',
				'title' => 'Enable notifications for courses',
				'desc' => 'Send notification once entire course has been completed by user.',
			),
			'_onlyinpro_app49' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'course_progress',
				'title' => 'Emails to notify',
				'desc' => 'Enter comma separated email addresses to send the notification to.',
			),
			'_onlyinpro_app50' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'course_progress',
				'title' => 'Email subject',
				'desc' => 'You can use following shortcodes:<br />[blogname] - website\'s name<br />'
							. '<br />[course_name] - name of the course<br />[username]<br />[userlogin]<br />[useremail]',
			),
			'_onlyinpro_app51' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'course_progress',
				'title' => 'Email body template',
				'desc' => 'You can use following shortcodes:<br />[blogname] - website\'s name<br />[home] - website\'s home url'
					. '<br />[course_name] - name of the course<br />[course_permalink] - permalink to the course<br />[username]<br />[userlogin]<br />[useremail]<br />[first_name]<br />[last_name]',
			),
			// Lesson progress
			'_onlyinpro_app52' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'lesson_progress',
				'title' => 'Enable notifications for lessons',
				'desc' => 'Send notification once entire lessons has been completed by user.',
			),
			'_onlyinpro_app53' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'lesson_progress',
				'title' => 'Emails to notify',
				'desc' => 'Enter comma separated email addresses to send the notification to.',
			),
			'_onlyinpro_app54' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'lesson_progress',
				'title' => 'Email subject',
				'desc' => 'You can use following shortcodes:<br />[blogname] - website\'s name'
				. '<br />[lesson_name] - name of the lesson<br />[username]<br />[userlogin]<br />[useremail]',
			),
			'_onlyinpro_app55' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'lesson_progress',
				'title' => 'Email body template',
				'desc' => 'You can use following shortcodes:<br />[blogname] - website\'s name<br />[home] - website\'s home url'
				. '<br />[lesson_name] - name of the lesson<br />[lesson_permalink] - permalink to the lesson<br />[username]<br />[userlogin]<br />[useremail]',
			),
			// Video progress
			'_onlyinpro_app56' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'video_progress',
				'title' => 'Enable notifications for single videos completed',
				'desc' => 'Send notification once a video has been completed by user.',
			),
			'_onlyinpro_app57' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'video_progress',
				'title' => 'Emails to notify',
				'desc' => 'Enter comma separated email addresses to send the notification to.',
			),
			'_onlyinpro_app58' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'video_progress',
				'title' => 'Email subject',
				'desc' => 'You can use following shortcodes:<br />[blogname] - website\'s name'
					. '<br />[video_name] - name of the video<br />[username]<br />[userlogin]<br />[useremail]',
			),
			'_onlyinpro_app59' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'video_progress',
				'title' => 'Email body template',
				'desc' => 'You can use following shortcodes:<br />[blogname] - website\'s name<br />[home] - website\'s home url'
					. '<br />[video_name] - name the video<br />[video_permalink] - permalink to the video<br />[username]<br />[userlogin]<br />[useremail]',
			),
			'_onlyinpro_app60' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'progress_general',
				'title' => 'Send notification even if lacking per video only [seconds]',
				'desc' => 'Set the number of lacking seconds per video under which the notifications will be send. '
					. 'Useful for the issues on the Internet Explorer.',
			),
			'_onlyinpro_app61' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'progress_general',
				'title' => 'Round up progress to 100% when lacking per video [seconds]',
				'desc' => 'Set the number of lacking seconds per video under which the video progress will be rounded up to the 100%. '
					. 'Useful for the issues on the Internet Explorer. It doesn\'t affect existing progress statistics.',
			),
			'_onlyinpro_app62' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'notifications',
				'subcategory' => 'email',
				'title' => 'Default email recipient when using BCC',
				'desc' => 'Some email servers do not accept the empty "To" header when sending the email to undisclosed recipients by BCC. '
					. 'You can define here the email address that will be the main receiver of that kind of emails, but your users\'s addressess '
					. 'will be still undisclosed.',
			),
            self::OPTION_CUSTOM_CSS       => array(
                'type'        => self::TYPE_TEXTAREA,
                'category'    => 'appearance',
                'subcategory' => 'css',
                'title'       => 'Custom CSS',
            ),
            // Vimeo
			'_onlyinpro_g' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'general',
				'subcategory' => 'vimeo',
				'title' => 'Account Name',
				'desc' => 'Enter account name to see in dropdown.',
			),
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
			// Vimeo 2
			'_onlyinpro_h' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'general',
				'subcategory' => 'vimeo2',
				'title' => 'Account Name',
				'desc' => 'Enter account name to see in dropdown.</a>',
			),
			'_onlyinpro_i' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'general',
				'subcategory' => 'vimeo2',
				'title' => 'App Client Identifier',
				'desc' => 'Enter the client identifier of the Vimeo App.<br /><a href="javascript:void(0);" class="button" disabled>'
						. 'Generate new identifier</a>',
			),
			'_onlyinpro_j' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'general',
				'subcategory' => 'vimeo2',
				'title' => 'App Client Secret',
				'desc' => 'Enter the client secret of the Vimeo App.',
			),
			'_onlyinpro_k' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'general',
				'subcategory' => 'vimeo2',
				'title' => 'Access Token',
				'desc' => 'Enter the access token with the public, private, edit and interact priviliges. '
						. 'Click "Save" and then<br /><a href="javascript:void(0);" class="button" disabled>Test Configuration</a>',
			),
			'_onlyinpro_l' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'general',
				'subcategory' => 'vimeo2',
				'title' => 'Cache lifetime for Vimeo API',
				'desc' => 'Enter the number of seconds to cache the results of the Vimeo API video requests. Caching will increase the load times. Set 0 to disable.',
			),
			// Wistia
			'_onlyinpro_m' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'general',
				'subcategory' => 'wistia',
				'title' => 'API Token',
				'desc' => 'Enter the API token. <a href="https://secure.wistia.com/login" target="_blank">Login to Wistia</a> '
						. 'then go to Account -> Settings -> API Access and copy your Password.<br>'
						. 'Click "Save" and then<br /><a href="javascript:void(0);" class="button" disabled>Test Configuration</a>',
			),
			// HubSpot
			'_onlyinpro_n' => array(
				'type' => self::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'general',
				'subcategory' => 'hubspot',
				'title' => 'Access Token',
				'desc' => 'Enter the HubSpot private app access token.',
			),
			'_onlyinpro_o' => array(
				'type' => self::TYPE_CUSTOM,
				'category' => 'general',
				'subcategory' => 'hubspot',
				'min' => '1',
				'max' => '100',
				'step' => '1',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'title' => 'Event fire after watching x% video',
				'desc' => 'Select the x value from here.',
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

			'_onlyinpro_p' => array(
				'type' => Settings::TYPE_CUSTOM,
				'category' => 'general',
				'subcategory' => 'access',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'title' => 'Who can watch videos',
				'desc' => 'Choose the access restriction for watching videos.<br><strong>Important:</strong> if you use the <a href="https://www.cminds.com/wordpress-plugins-library/video-lessons-manager-payment-addon-wordpress/" target="_blank">Payments add-on</a>, only registered and logged-in users are allowed to purchase the access to content. Learn <a href="https://creativeminds.helpscoutdocs.com/article/2959-cm-video-lessons-payments-cmeddpay-how-to-auto-register-guest-users-after-purchasing-the-content/" target="_blank">more</a> how to auto-register users while purchasing.',
			),
			'_onlyinpro_q' => array(
				'type' => Settings::TYPE_CUSTOM,
				'category' => 'general',
				'subcategory' => 'access',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'title' => 'Disable lesson page',
				'desc' => 'You can disable the lesson pages and then you\'ll be able to use only the playlist shortcodes.',
			),
			'_onlyinpro_r' => array(
				'type' => Settings::TYPE_CUSTOM,
				'category' => 'general',
				'subcategory' => 'access',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'title' => 'Disable video page',
				'desc' => 'You can disable the video pages and then you\'ll be able to use only the playlist shortcodes.',
			),
			'_onlyinpro_s' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'general',
				'subcategory' => 'access',
				'title' => 'Login page URL',
				'desc' => 'Enter an URL of the page which non logged in users will be redirected to when trying to access a page which has access restriction such as <kbd>'.get_site_url().'/login/</kbd>',
			),
			'_onlyinpro_t' => array(
				'type' => Settings::TYPE_CUSTOM,
				'category' => 'general',
				'subcategory' => 'access',
				'title' => 'Unlock private videos',
				'desc' => 'Use this button to add your domain name to the whitelist of each private video. '
					. '<strong>Warning: this can exceed your Vimeo API limits.</strong><br>'
					. 'You can do this manually in Vimeo - go to a specific video -> Settings -> Privacy -> Where can this video be embedded? -> Only on sites I choose.',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
			),
			'_onlyinpro_u' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'general',
				'subcategory' => 'access',
				'title' => 'Reload lesson page after subscription expires',
				'desc' => 'If enabled, script will check in the background if the subscription is still active and reload the lesson when it expires '
					. 'or user has been logged-out to disallow further watching lesson.',
			),
			'_onlyinpro_v' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'general',
				'subcategory' => 'access',
				'title' => 'Roles allowed to access the front-end progress report',
				'desc' => 'The selected roles will have full access to the progress report created with the shortcode <kbd>[cmvl-progress-report]</kbd>.<br>If roles not selected then progress report visible to all logged in users.',
			),
			// s2Member (Pro)
			'_onlyinpro_w' => array(
				'type' => Settings::TYPE_CUSTOM,
				'category' => 'general',
				'subcategory' => 's2memberpro',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'title' => 'Enable s2Member integration',
				'desc' => '',
			),
			'_onlyinpro_x' => array(
				'type' => Settings::TYPE_CUSTOM,
				'category' => 'general',
				'subcategory' => 's2memberpro',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'title' => 'Enable for all user roles',
				'desc' => '',
			),
			'_onlyinpro_y' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'general',
				'subcategory' => 's2memberpro',
				'title' => 'Allow particular user roles',
				'desc' => 'You must disable above option if you want to allow particular user roles',
			),
			'_onlyinpro_z' => array(
				'type' => Settings::TYPE_CUSTOM,
				'category' => 'general',
				'subcategory' => 's2memberpro',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'title' => 'Enable all courses',
				'desc' => '',
			),
			'_onlyinpro_a1' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'general',
				'subcategory' => 's2memberpro',
				'title' => 'Allow particular video courses',
				'desc' => 'You must disable above option if you want to allow particular video courses',
			),
			'_onlyinpro_a2' => array(
				'type' => self::TYPE_CUSTOM,
				'category' => 'general',
				'subcategory' => 's2memberpro',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'title' => 'Allow view time (in hours)',
				'desc' => 'Enter the number of hours',
			),
			'_onlyinpro_a3' => array(
				'type' => Settings::TYPE_CUSTOM,
				'category' => 'general',
				'subcategory' => 'stats',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'title' => 'Track date and time',
				'desc' => 'If enabled, each time user watched part of a video that event will be logged into the database with the date and time '
					. 'so admin can browse more detailed reports. If disabled then only aggregated data will be stored.',
			),
			'_onlyinpro_a4' => array(
				'type' => Settings::TYPE_CUSTOM,
				'category' => 'general',
				'subcategory' => 'stats',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'title' => 'Save the current progress each N [seconds]',
				'desc' => 'Setup the time in seconds in which plugin will automaticaly save the current video progress.',
			),
			'_onlyinpro_a5' => array(
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
                'type'        => Settings::TYPE_CUSTOM,
                'category'    => 'general',
                'subcategory' => 'stats',
                'text'        => 'Delete',
                'title'       => 'Delete all statistics',
                'desc'        => 'Deletes all website statistics.',
            ),
			'_onlyinpro_a6' => array(
				'type' => Settings::TYPE_CUSTOM,
				'category' => 'general',
				'subcategory' => 'restrictions',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'title' => 'Restrict video by',
				'desc' => 'If you will select <strong>Restrict video by: Lesson</strong> then you need to add minutes in lesson section',
			),
			'_onlyinpro_a7' => array(
				'type' => Settings::TYPE_CUSTOM,
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'category' => 'general',
				'subcategory' => 'restrictions',
				'title' => 'Restrict video time per user',
				'desc' => 'Enter the number of minutes if you want to restrict video as per user. This time will work if you will select <strong>Restrict video by: Global</strong>. Set 0 to disable.',
			),
			'_onlyinpro_a8' => array(
				'type' => Settings::TYPE_CUSTOM,
				'category' => 'general',
				'subcategory' => 'restrictions',
				'title' => 'Reset restrict video time',
				'desc' => 'Use this button to reset restrict video time for all users in one time.',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
			),
			'_onlyinpro_a9' => array(
				'type' => Settings::TYPE_CUSTOM,
				'category' => 'general',
				'subcategory' => 'restrictions',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'title' => 'Enable video sequence',
				'desc' => 'If enabled, then user able to view video only when last was completely viewed.<br><strong>Note:</strong> Lesson unlock attributes will work with this setting.',
			),
			'_onlyinpro_a10' => array(
				'type' => Settings::TYPE_CUSTOM,
				'category' => 'general',
				'subcategory' => 'restrictions',
				'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
				'title' => 'Enable videos mark as complete button',
				'desc' => 'If enabled, then user able to manually mark video as complete.<br><strong>Note:</strong> Works only if using the <strong>List</strong> videos layout',
			),
			'_onlyinpro_a11' => array(
                'type'        => Settings::TYPE_CUSTOM,
                'category'    => 'general',
                'subcategory' => 'restrictions',
                'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
                'title'       => 'Reset all courses/lessons',
                'desc'        => 'Reset all users courses/lessons.',
            ),
			'_onlyinpro_a12' => array(
                'type'        => Settings::TYPE_CUSTOM,
                'category'    => 'general',
                'subcategory' => 'badges',
                'content' => 'Available in Pro version and above.<br><a href="'.get_site_url().'/wp-admin/admin.php?page=CMVL_pro">UPGRADE NOW&nbsp;➤</a>',
                'title'       => 'Claim process',
                'desc'        => '',
            ),
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
