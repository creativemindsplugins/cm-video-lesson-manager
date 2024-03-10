<?php


use com\cminds\videolesson\App;

?>
<?php if (App::isPro()): ?>
	<li><code>[cmvl-playlist view="playlist|tiles" layout="left|right|bottom|nomenu" category="id|slug" channel="id|slug" video=id navbar=1 searchbar=1 linksbar=1 ajax=1 urlsearch=0 maxwidth=0]</code> - Display playlist view.</li>
	<li><code>[cmvl-channels-list subscription=active|inactive]</code> - Display channels list. You can optionally filter current user's active or inactive subscriptions using the optional parameter "subscription". The inactive subscription will be displayed with the checkout option.</li>
	<li><kbd>[cmvl-bookmarks navbar=1 searchbar=1 linksbar=1 ajax=1 view="playlist|tiles"]</kbd> - Display user's bookmarks playlist view.</li>
	<li><code>[cmvl-subscriptions status=active|inactive]</code> - Display subscriptions history. You can optionally filter user's active or inactive subscriptions using the optional parameter "status". The inactive subscription will be displayed with the checkout option.</li>
	<li><code>[cmvl-dashboard]</code> - Display user's dashboard divided by tabs. You can modify and create new dashboard tabs in the plugin settings under the Dashboard tab.</li>
	<li><code>[cmvl-stats]</code> - Display user's video statistics.</li>
<?php else: ?>
	<li><code>[cmvl-playlist category="id|slug" channel="id|slug" video=id navbar=1 ajax=1]</code> - Display playlist view.</li>
<?php endif; ?>
