jQuery.noConflict();
(function ($) {
	$(function () {
	
		var rtl = $('html').attr('dir');
		console.log(rtl);
		var dir = (typeof rtl !== typeof undefined && rtl !== false) && rtl=='rtl';
		$('.top_slider').slick({
			rtl: dir,
			arrows: false
		});
		
		// fancybox
		$('.catalog_link').fancybox();
		
		
		
	});
})(jQuery);
