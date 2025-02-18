<?php
use com\cminds\videolesson\App;
ob_start();
include plugin_dir_path(__FILE__) . 'views/plugin_compare_table.php';
$plugin_compare_table = ob_get_contents();
ob_end_clean();
$cminds_plugin_config = array(
	'plugin-is-pro'					 => App::isPro(),
	'plugin-has-addons'      => TRUE,
	'plugin-addons'        => array(
        array( 'title' => 'Video Lessons Direct Payments', 'description' => 'Allow users to pay for viewing video channels using Easy digital downloads cart.', 'link' => 'https://www.cminds.com/wordpress-plugins-library/video-lessons-edd-payments-add-on-for-wordpress-by-creativeminds/', 'link_buy' => 'https://www.cminds.com/checkout/?edd_action=add_to_cart&download_id=86324&wp_referrer=https://www.cminds.com/checkout/&edd_options[price_id]=1' ),
        array( 'title' => 'CM MicroPayment Platform', 'description' => 'Add your own “virtual currency“ and allow to charge for posting and answering questions.', 'link' => 'https://www.cminds.com/wordpress-plugins-library/micropayments/', 'link_buy' => 'https://www.cminds.com/checkout/?edd_action=add_to_cart&download_id=11388&wp_referrer=https://www.cminds.com/checkout/&edd_options[price_id]=0' ),
	),
	'plugin-version'				 => App::VERSION,
	'plugin-abbrev'					 => App::PREFIX,
	'plugin-parent-abbrev'			 => '',
	'plugin-affiliate'				 => '',
	'plugin-redirect-after-install'	 => admin_url( 'admin.php?page=cmvl-settings' ),
	'plugin-settings-url'		 	 => admin_url( 'admin.php?page=cmvl-settings' ),
	'plugin-file'					 => App::getPluginFile(),
	'plugin-dir-path'				 => plugin_dir_path( App::getPluginFile() ),
	'plugin-dir-url'				 => plugin_dir_url( App::getPluginFile() ),
	'plugin-basename'				 => plugin_basename( App::getPluginFile() ),
	'plugin-icon'					 => '',
	'plugin-name'					 => App::getPluginName( true ),
	'plugin-license-name'			 => App::getPluginName(),
	'plugin-slug'					 => App::LICENSING_SLUG,
	'plugin-short-slug'				 => App::PREFIX,
	'plugin-parent-short-slug'		 => '',
	'plugin-menu-item'				 => App::MENU_SLUG,
	'plugin-campign'          		   => '?utm_source=cmvlfree&utm_campaign=freeupgrade',
	'plugin-textdomain'				 => '',
	'plugin-show-shortcodes'	 => TRUE,
	'plugin-shortcodes'			 => '<p>You can use the following available shortcodes.</p>',
	'plugin-shortcodes-action'	 => 'cmvl_display_supported_shortcodes',
	'plugin-userguide-key'			 => '2738-cm-video-lessons-manager-cmclm-free-version-tutorial',
	'plugin-video-tutorials-url'			 => 'https://www.videolessonsplugin.com/video-lesson/lesson/video-lessons-manager-plugin/',
	'plugin-store-url'				 => 'https://www.cminds.com/wordpress-plugins-library/purchase-cm-video-lessons-manager-plugin-for-wordpress?utm_source=cmvlree&utm_campaign=freeupgrade&upgrade=1',
	'plugin-support-url'			 => 'https://www.cminds.com/contact/',
	'plugin-review-url'				 => 'https://www.cminds.com/wordpress-plugins-library/video-lessons-manager-plugin-for-wordpress/#reviews',
	'plugin-changelog-url'			 => 'https://www.cminds.com/wordpress-plugins-library/purchase-cm-video-lessons-manager-plugin-for-wordpress/#changelog',
	'plugin-licensing-aliases'		 => array( App::getPluginName( false ), App::getPluginName( true ) ),
	'plugin-show-guide'              => TRUE,
	'plugin-guide-text'              => '    <div style="display:block">
	<ol>
	<li>Go to the  <strong>"Plugin Settings"</strong></li>
	<li>Visit <strong>"Vimeo Develop Dashboard"</strong> to generate a new App</li>
	<li>Copy <strong>App keys and access tokens</strong> to plugin settings</li>
	<li>From the plugin admin menu Select <strong>"Channels" </strong>.</li>
	<li>Select <strong>Add Channel</strong> and define your first channel. Make sure to select the corresponding Vimeo album</li>
	<li>View channel or add a shortcode to embed in a post or page</li>
	</ol>
	<h3>Vimeo API instructions</h3>
	<ol>
		<li>To display more than one channel you need to have the Vimeo Plus or Pro account.
		When using the basic free account you can provide only one channel.</li>
		<li>Please go to <a href="https://developer.vimeo.com/apps/new" target="_blank">developer.vimeo.com/apps/new</a>
		(you must have a Vimeo account).</li>
		<li>Enter as the App URL: <kbd>'.get_home_url().'</kbd></li>
		<li>Leave blank the <em>App Callback URLs</em> field.</li>
		<li>When the new Application has been created, go to the <strong>Authentication</strong> tab.
		Copy the <strong>Client Identifier</strong> and <strong>Client Secret</strong> values and them put into the '.App::getPluginName().' Settings.</li>
		<li>On the <em>Authentication</em> tab scroll down to the <em>Generate an Access Token</em> section.
		Check the <strong>Edit</strong> and <strong>Interact</strong> permission scopes and press the <strong>Generate Token</strong> button.
		Copy <em>Your new Access token</em> value and put into the '.App::getPluginName().' Settings.</li>
		<br>
		For more please visit the <a href="https://creativeminds.helpscoutdocs.com/category/354-video-lessons-manager-cmvlm">user guide</a> and search for articles marked before ver 2
	</ol>
	</div>',
	'plugin-guide-video-height'      => 240,
	'plugin-guide-videos'            => array(
		array( 'title' => 'Installation tutorial', 'video_id' => '161022219' ),
	),
	'plugin-upgrade-text'           => 'Good Reasons to Upgrade to Pro',
    'plugin-upgrade-text-list'      => array(
        array( 'title' => 'Why you should upgrade to Pro', 'video_time' => '0:00' ),
        array( 'title' => 'Improved importing process from Vimeo and Wistla', 'video_time' => '0:03' ),
        array( 'title' => 'Use shortcodes', 'video_time' => '0:32' ),
        array( 'title' => 'Lessons and courses new structure', 'video_time' => '0:57' ),
        array( 'title' => 'Control lesson layout', 'video_time' => '1:25' ),
        array( 'title' => 'User statistics and student reports', 'video_time' => '2:05' ),
        array( 'title' => 'Notifications ', 'video_time' => '2:40' ),
        array( 'title' => 'Localization', 'video_time' => '3:03' ),
        array( 'title' => 'Access control', 'video_time' => '3:20' ),
        array( 'title' => 'User notes', 'video_time' => '3:57' ),
        array( 'title' => 'Bookmarks', 'video_time' => '4:35' ),
        array( 'title' => 'Student dashboard', 'video_time' => '5:08' ),
        array( 'title' => 'Search videos', 'video_time' => '5:47' ),
        array( 'title' => 'Payment support ', 'video_time' => '6:03' ),
        array( 'title' => 'Quiz support ', 'video_time' => '6:57' ),
        array( 'title' => 'Certificate support', 'video_time' => '7:40' ),
   ),
    'plugin-upgrade-video-height'   => 240,
    'plugin-upgrade-videos'         => array(
        array( 'title' => 'Video Lessons Premium Plugin Overview', 'video_id' => '271622775' ),
    ),
	'plugin-compare-table'			 => $plugin_compare_table,
);