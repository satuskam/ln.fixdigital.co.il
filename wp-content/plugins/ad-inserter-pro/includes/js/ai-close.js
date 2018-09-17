jQuery(document).ready(function($) {
  $('.ai-close-button').click (function () {
    $(this).closest ('.ai-close').remove ();
  });
  setTimeout (function() {
    $('.ai-close').each (function() {
      if ($(this).outerHeight () !== 0) {
        $(this).css ('width', '').addClass ('ai-close-fit').find ('.ai-close-button').fadeIn (50);
      }
    });
   }, 2000);
});
