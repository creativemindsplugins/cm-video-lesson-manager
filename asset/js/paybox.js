function cmvl_paybox_submit(ev, callback) {
	ev.stopPropagation();
	ev.preventDefault();
	var $ = jQuery;
	var form = $(this);
	if (form.find('input.cmvl-price[type=radio]:checked').length != 0 || form.find('input.cmvl-price[type=hidden]').length == 1) {
		var data = form.serialize();
		$.post(form.data('ajaxUrl'), data, function(response) {
			if (response.success) {
				CMVL.Utils.toast(response.msg, 'success');
			} else {
				CMVL.Utils.toast(response.msg, 'error');
			}
			if (response.redirect) {
				location.href = response.redirect;
			}
			if (typeof callback == 'function') {
				callback(response);
			}
		});
	}
}


jQuery(function($) {
	$('form.cmvl-channel-paybox-form').submit(function(ev) {
		var form = $(this);
		if (form.parents('.cmvl-widget-playlist').length == 0) {
			cmvl_paybox_submit.call(this, ev, function(response) {
				if (response.success && response.channelUrl) {
					location.href = response.channelUrl;
				}
			});
		}
	});
});