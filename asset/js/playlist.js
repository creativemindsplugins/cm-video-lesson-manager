(function($) {

window.CMVL.Playlist = function(container) {
	
	this.playerState = null;
	this.statsAjaxSuccessCallback = null;
	this.container = container;
	container[0].cmvlPlaylist = this;
	
	this.initDescriptionHandler();
	this.initSearchHandler();
	this.initClearSearchHandler();
	this.initNavbarHandler();
	this.initBookmarkHandler();
	this.initVideoListHandler();
	this.initVideoHandlers();
	this.initMicropaymentsHandler();
	this.initPaginationHandler();
	
};



window.CMVL.Playlist.prototype.initDescriptionHandler = function() {
	
	// Video description
	var duration = 500;
	$('figcaption', this.container)
	.mouseenter(function() {
		var inner = $(this).find('.cmvl-description-inner');
		if (!inner.data('defaultMaxHeight')) {
			inner.data('defaultMaxHeight', inner.css('max-height'));
		}
		inner.animate({"max-height" : inner[0].scrollHeight + "px"}, duration);
	})
	.mouseleave(function() {
		var inner = $(this).find('.cmvl-description-inner');
		inner.animate({"max-height" : inner.data('defaultMaxHeight')}, duration);
	});
	
	// Channel description
	$('.cmvl-channel-info-btn').click(function(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		var playlist = $(this).parents('.cmvl-playlist').first();
		var overlay = window.CMVL.Utils.overlay(playlist.find('.cmvl-channel-description').html());
	});
	
};


window.CMVL.Playlist.prototype.initVideoHandlers = function() {
	this.initNotesHandler();
	var obj = this;
	setTimeout(function() {
		obj.initPlayerEventsHandler();
	}, 1000);
};



window.CMVL.Playlist.prototype.initMicropaymentsHandler = function() {
	var playlist = this;
	$('form.cmvl-channel-paybox-form', playlist.container).submit(function(ev) {
		cmvl_paybox_submit.call(this, ev, function(response) {
			if (response.success && response.channelUrl) {
				playlist.loadURL(response.channelUrl);
			}
		});
	});
};


window.CMVL.Playlist.prototype.initSearchHandler = function() {
	var playlist = this;
	window.CMVL.Utils.addSingleHandler('cmvl-search-submit', $('form.cmvl-search', playlist.container), 'submit', function(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		playlist.loaderShow();
		var obj = $(this);
		obj.find(':input').blur();
		obj.find('.cmvl-search-clear').show();
		var data = obj.serialize() +"&"+ $.param({action: "cmvl_video_search", nonce: CMVLSettings.ajaxNonce});
		$.post(CMVLSettings.ajaxUrl, data, function(response) {
			response = $(response);
//			playlist.container.find('.cmvl-navbar-navigation').remove();
//			playlist.container.find('.cmvl-playlist').html(response.html());
			playlist.container.find('.cmvl-search-results').remove();
			playlist.container.find('.cmvl-navbar-navigation').hide();
			playlist.container.find('.cmvl-playlist > *').hide();
			var resultsContainer = $('<div/>', {"class":"cmvl-search-results"});
			playlist.container.find('.cmvl-playlist').append(resultsContainer);
			resultsContainer.html(response.html());
			playlist.loaderHide();
			new window.CMVL.Playlist(playlist.container);
		});
	});
};


window.CMVL.Playlist.prototype.initClearSearchHandler = function() {
	var playlist = this;
	window.CMVL.Utils.addSingleHandler('cmvl-search-clear', $('.cmvl-search-clear', playlist.container), 'click', function(ev) {
		var obj = $(this);
		playlist.container.find('.cmvl-search-results').remove();
		playlist.container.find('.cmvl-navbar-navigation').show();
		playlist.container.find('.cmvl-playlist > *').show();
		playlist.container.find('form.cmvl-search input:text').val('');
		obj.hide();
	});
};


window.CMVL.Playlist.prototype.initNotesHandler = function() {
	var playlist = this;
	$('.cmvl-notes', playlist.container).focus(function() {
		var obj = $(this);
		obj.data('defaultHeight', obj.outerHeight());
		obj.animate({height: '10em'});
	}).blur(function() {
		var obj = $(this);
		obj.animate({height: obj.data('defaultHeight') + "px"});
	}).change(function() {
		var currentVideo = $(this).parents('.cmvl-video').first();
		var data = {
				action: 'cmvl_video_set_user_note',
				nonce: CMVLSettings.ajaxNonce,
				channelId: currentVideo.data('channelId'),
				videoId: currentVideo.data('videoId'),
				note: $(this).val()
		};
		$.post(CMVLSettings.ajaxUrl, data, function(response) {
			// ok
		});
	});
};


window.CMVL.Playlist.prototype.initNavbarHandler = function() {
	var playlist = this;
	$('.cmvl-navbar select[name=category], .cmvl-navbar select[name=channel]').change(function(e) {
		var obj = $(this);
		if (playlist.container.data('useAjax')) {
			playlist.loadURL(obj.val());
		} else {
			location.href = obj.val();
		}
	});
};


window.CMVL.Playlist.prototype.loadURL = function(url) {
	var playlist = this;
	playlist.loaderShow();
	var data = {nonce: CMVLSettings.ajaxNonce};
	$.post(url, data, function(response) {
		response = $(response);
		playlist.container.html(response.find('.cmvl-channel-main-query .cmvl-widget-playlist').html());
		playlist.loaderHide();
		new window.CMVL.Playlist(playlist.container);
		$('html, body').animate({
	        scrollTop: playlist.container.offset().top
	    }, 1000);
	});
};


window.CMVL.Playlist.prototype.pause = function() {
	var iframe = this.container.find('figure iframe')[0];
	if (iframe.froogaloopHandler) {
		iframe.froogaloopHandler.api('pause');
	}
};


window.CMVL.Playlist.prototype.removePlayerEventsHandler = function() {
	var iframe = this.container.find('figure iframe');
	for (var i=0; i<iframe.length; i++) {
		var frame = iframe[i];
		if (frame.froogaloopHandler) {
//			console.log('detaching');
			frame.froogaloopHandler.removeEvent('playProgress');
			frame.froogaloopHandler.removeEvent('ready');
			frame.froogaloopHandler.removeEvent('play');
			frame.froogaloopHandler.removeEvent('seek');
			frame.froogaloopHandler.removeEvent('finish');
			frame.froogaloopHandler.removeEvent('pause');
		}
	}
};

	
window.CMVL.Playlist.prototype.initVideoListHandler = function() {
	var playlist = this;
	$('.cmvl-video-list a', playlist.container).click(CMVL.Utils.leftClick(function(e) {
		var link = $(this);
		playlist.statsAjaxSuccessCallback = function() {
			playlist.statsAjaxSuccessCallback = null;
			playlist.removePlayerEventsHandler();
			var url = link.attr('href');
			var data = {nonce: CMVLSettings.ajaxNonce, view: 'playlist'};
			$.post(url, data, function(response) {
				var doc = $(response);
//				playlist.container.find('figure.cmvl-video').replaceWith(doc.find('.entry-content figure.cmvl-video'));
				playlist.container.find('.cmvl-ajax-content').html(doc.find('.cmvl-ajax-content').first().html());
				link.parents('nav').first().find('li.current').removeClass('current');
				link.parents('li').first().addClass('current');
				playlist.initVideoHandlers();
				playlist.loaderHide();
			});
		};
		playlist.loaderShow();
		if (playlist.playerState == 'play') {
			playlist.pause();
		} else {
			playlist.statsAjaxSuccessCallback();
		}
	}));
};
	
	
window.CMVL.Playlist.prototype.initBookmarkHandler = function() {
	var playlist = this;
	$('.cmvl-bookmark', playlist.container).click(function(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		var button = $(this);
		button.parents('a').first().blur();
		var video = button.parents('.cmvl-video').first();
		var bookmark = 'add';
		if (button.hasClass('on')) bookmark = 'remove';
		var data = {
				action: 'cmvl_video_set_user_bookmark',
				nonce: CMVLSettings.ajaxNonce,
				channelId: video.data('channelId'),
				videoId: video.data('videoId'),
				bookmark: bookmark
		};
		$.post(CMVLSettings.ajaxUrl, data, function(response) {
			if (response.status == 'ok') {
				if (bookmark == 'add') button.addClass('on');
				else button.removeClass('on');
			}
		});
	});
};


window.CMVL.Playlist.prototype.initPaginationHandler = function() {
	var playlist = this;
	$('.cmvl-pagination a').click(function(e) {
		var obj = $(this);
		if (playlist.container.data('useAjax')) {
			playlist.loadURL(obj.attr('href'));
			return false;
		}
	});
};


window.CMVL.Playlist.prototype.initPlayerEventsHandler = function() {
	if (typeof $f == 'undefined') return;
	var playlist = this;
	this.container.find('iframe').each(function() {
		window.intervals = [];
		var lastProgressPercent = 0;
		var intervalStartPercent = 0;
		var iframe = $(this);
		var player = $f(this);
		this.froogaloopHandler = player;
		var lastAddedTimestamp = (new Date()).getTime();
		var eventStack = [];
		var addInterval = function(start, stop) {
			start*=100;
			stop*=100;
//			console.log('add interval: '+ start +' - '+ stop);
			lastAddedTimestamp = (new Date()).getTime();
			var currentVideo = iframe.parents('.cmvl-video').first();
			var data = {
				action: 'cmvl_video_watching_stats',
				nonce: CMVLSettings.ajaxNonce,
				start: start,
				stop: stop,
				videoId: currentVideo.data('videoId'),
				channelId: currentVideo.data('channelId')
			};
			$.post(CMVLSettings.ajaxUrl, data, function(response) {
//				console.log('add success');
				if (playlist.statsAjaxSuccessCallback) {
					playlist.statsAjaxSuccessCallback();
				}
			});
		};
		player.addEvent('ready', function() {
//			console.log('ready');
			eventStack.push('ready');
			playlist.playerState = 'ready';
			player.addEvent('play', function(data) {
//				console.log('play start='+ lastProgressPercent);
//				console.log('intervalStartPercent='+lastProgressPercent);
				intervalStartPercent = lastProgressPercent;
				playlist.playerState = 'play';
			});
			var pauseHandler = function() {
//				console.log('pause add int');
				addInterval(intervalStartPercent, lastProgressPercent);
				if (lastProgressPercent == 1) {
					lastProgressPercent = 0;
				}
				intervalStartPercent = lastProgressPercent;
				playlist.playerState = 'pause';
				eventStack.push('pause');
			};
			player.addEvent('pause', pauseHandler);
			player.addEvent('finish', pauseHandler);
			player.addEvent('seek', function(data) {
				eventStack.push('seek');
//				console.log('seek playerState='+playlist.playerState);
//				console.log(data);
				if (playlist.playerState == 'play') {
//					console.log('seek add int');
					addInterval(intervalStartPercent, lastProgressPercent);
				}
//				console.log('set intervalStartPercent='+data.percent)
//				console.log('set lastProgressPercent='+data.percent)
				if (playlist.playerState != 'ready') {
					intervalStartPercent = data.percent;
					lastProgressPercent = data.percent;
				}
			});
			player.addEvent('playProgress', function(data, id) {
//				console.log('progress player state = '+playlist.playerState +' seconds='+ data.seconds);
//				console.log('set lastProgressPercent='+ data.percent)
				lastProgressPercent = data.percent;
				var intervalSeconds = 30;
				var now = (new Date()).getTime();
				if (eventStack[eventStack.length-1] == 'seek' && eventStack[eventStack.length-2] == 'ready') {
//					console.log('this case');
					intervalStartPercent = data.percent;
				}
				else if ((now-lastAddedTimestamp)/1000 >= intervalSeconds && data.seconds <= data.duration-intervalSeconds && intervalStartPercent!=lastProgressPercent) {
					// save progress after every x seconds
//					console.log('progress add int '+ intervalStartPercent +' -- '+ lastProgressPercent);
					addInterval(intervalStartPercent, lastProgressPercent);
					intervalStartPercent = lastProgressPercent;
				}
				if (eventStack[eventStack.length-1] != 'progress') {
					eventStack.push('progress');
				}
			});
		});
	});
};

window.CMVL.Playlist.prototype.loaderShow = function() {
	this.container.append($('<div/>', {"class":"cmvl-loader"}));
};


window.CMVL.Playlist.prototype.loaderHide = function() {
	this.container.find('.cmvl-loader').remove();
};




$(function() {
	setTimeout(function() {
		$('.cmvl-widget-playlist').each(function() {
			new window.CMVL.Playlist($(this));
		});
	}, 1000);
});


	
})(jQuery);
