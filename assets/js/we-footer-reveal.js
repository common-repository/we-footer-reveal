jQuery(function($) {
	if(we_footer_reveal_selector && $(we_footer_reveal_selector).length != 0) {
		if(!$('body').hasClass('elementor-editor-active')) {
			$(we_footer_reveal_selector).each(function(i,elem) {
				$(elem).footerReveal();
			});
		} else {
			$(we_footer_reveal_selector)
				.css({
					'position': 'relative',
					'z-index': '0',
				})
				.append('<div class="we-footer-reveal-elementor-indicator"></div>');
			var indicator = $(we_footer_reveal_selector).find('.we-footer-reveal-elementor-indicator');
			$(indicator).html(we_footer_reveal_elementor_indicator).css({
				'position': 'absolute',
				'top': '10px',
				'right': '10px',
				'padding': '5px 10px',
				'border-radius': '4px',
				'background': 'rgba(255,255,255,0.5)',
				'pointer-events': 'none'
			});
		}
	}
});