jQuery.noConflict();
(function ($) {
	$(function () {
		console.log($('.menu-main-container'));
    
    	var $window = $(window);
    
		$('.menu-main-container').find('.sub-menu').hide();
		$('.menu-main-container li').hover(
			function () {
            	if ($window.width() <= 769) return;
            
				$(this).children('.sub-menu').stop().slideDown(500);
			},
			function () {
            if ($window.width() <= 769) return;
            
				$(this).children('.sub-menu').stop().slideUp(500);
			}
		);

	});
})(jQuery);
