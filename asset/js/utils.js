(function($) {

window.CMVL = {};
window.CMVL.Utils = {
		
	addSingleHandler: function(handlerName, selector, action, func) {
		var obj;
		if (typeof selector == 'string') obj = $(selector);
		else obj = selector;
		obj.each(function() {
			var obj = $(this);
			if (obj.data(handlerName) != '1') {
				obj.data(handlerName, '1');
				obj.on(action, func);
			}
		});
	},
	
	leftClick: function(func) {
		return function(e) {
			// Allow to use middle-button to open thread in a new tab:
			if (e.which > 1 || e.shiftKey || e.altKey || e.metaKey || e.ctrlKey) return;
			func.apply(this, [e]);
			return false;
		}
	},
	
	
	toast: function(msg, className, duration) {
		if (typeof className == 'undefined') className = 'info';
		if (typeof duration == 'undefined') duration = 5;
		var toast = $('<div/>', {"class":"cmvl-toast "+ className, "style":"display:none"});
		toast.text(msg);
		$('body').append(toast);
		toast.fadeIn(500, function() {
			setTimeout(function() {
				toast.fadeOut(500);
			}, duration*1000);
		});
	},
	
	overlay: function(content) {
		var overlay = $('#cmvl-overlay');
		if (overlay.length == 0) {
			overlay = $('<div id="cmvl-overlay"><div id="cmvl-overlay-inner"><div id="cmvl-overlay-close">&times;</div><div id="cmvl-overlay-content"></div></div></div>');
			$('body').append(overlay);
			overlay.click(function(ev) {
				if (ev.target == this) {
					overlay.fadeOut('fast');
				}
			});
			overlay.find('#cmvl-overlay-close').click(function(ev) {
				overlay.fadeOut('fast');
			});
		}
		if (typeof content != 'undefined') {
			overlay.find('#cmvl-overlay-content').html(content);
			overlay.fadeIn('fast');
		}
		return overlay;
	}
		
};

})(jQuery);
	


jQuery(function($) {
	$('.cmvl-stats-user-table .cmvl-details').click(function() {
		var obj = $(this);
		obj.parents('table').first().find('tr.cmvl-video[data-channel-id='+ obj.data('channelId') +'][data-category-id='+ obj.data('categoryId') +']').toggle(300);
		return false;
	});
});

