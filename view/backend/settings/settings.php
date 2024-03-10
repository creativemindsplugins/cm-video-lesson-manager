<?php

use com\cminds\videolesson\controller\SettingsController;
use com\cminds\videolesson\view\SettingsView;
use com\cminds\videolesson\App;
use com\cminds\videolesson\model\Settings;


if (!empty($_GET['status']) AND !empty($_GET['msg'])) {
	printf('<div id="message" class="%s"><p>%s</p></div>', ($_GET['status'] == 'ok' ? 'updated' : 'error'), esc_html($_GET['msg']));
}


?>

<form method="post" id="settings">


<ul class="cmvl-settings-tabs"><?php

$tabs = apply_filters('cmvl_settings_pages', Settings::$categories);
foreach ($tabs as $tabId => $tabLabel) {
	if ($tabId == 'labels' OR $options = Settings::getOptionsConfigByCategory($tabId)) {
		printf('<li><a href="#tab-%s">%s</a></li>', $tabId, $tabLabel);
	}
}

?></ul>

<div class="inner"><?php

$settingsView = new SettingsView();
echo $settingsView->render();

?></div>

<p class="form-finalize">
	<a href="<?php echo esc_attr($clearCacheUrl); ?>" class="right button">Clear cache</a>
	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce(SettingsController::getMenuSlug()); ?>" />
	<input type="submit" value="Save" class="button button-primary" />
</p>

</form>

