jQuery(function($) {
	
	if (wp && wp.heartbeat) {
		wp.heartbeat.interval( 'slow' );
//		wp.heartbeat.interval( 'fast' );
	}
	
	$(document).on('heartbeat-send', function(e, data) {
		var channels = [];
		var videos = $('figure.cmvl-video');
		for (var i=0; i<videos.length; i++) {
			var channelId = $(videos[i]).data('channelId');
			if (!jQuery.inArray(channelId, channels) > -1) {
				channels.push(channelId);
			}
		}
        data['cmvl_check_post'] = channels;
    });
	
	$(document).on( 'heartbeat-tick', function(e, data) {
//		console.log(data);
		if (typeof data['cmvl_check_post'] == 'object' && data['cmvl_check_post'].length && data['cmvl_check_post'].length > 0) {
			for (var i=0; i<data['cmvl_check_post'].length; i++) {
				var row = data['cmvl_check_post'][i];
//				console.log(row);
				var container = $('figure.cmvl-video[data-channel-id='+ row.channelId +']').first().parents('.cmvl-widget-playlist').first();
//				console.log(container.length)
				if (container.length > 0 && container[0].cmvlPlaylist) {
					container[0].cmvlPlaylist.loadURL(row.url);
				}
			}
		}
	});
	
});