
jQuery.noConflict();
(function ($) {
	$(function () {
		$('.menu-main-container').find('.sub-menu').hide();
		$('.menu-main-container li').hover(
			function () {
				$(this).children('.sub-menu').stop().slideDown(300);
			},
			function () {
				$(this).children('.sub-menu').stop().slideUp(300);
			}
		);




	});
})(jQuery);
