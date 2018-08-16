var ai_sticky_delay = 200;
var ai_process_sticky_elements_on_ready = true;

function ai_process_sticky_elements ($) {

  $('[data-ai-position-pc]').each (function() {
    var scroll_height = $('body').height () - document.documentElement.clientHeight;
    if (scroll_height <= 0) return true;
    $(this).css ('top', scroll_height * $(this).data ('ai-position-pc'));
  });

  var ai_debug = typeof ai_debugging !== 'undefined';
  var client_width = document.documentElement.clientWidth;
  var main_content_element = 'AI_FUNCH_GET_MAIN_CONTENT_ELEMENT'.trim ();

  var main_element = element = $('.ai-content').first ();
  var default_margin = 0;
  var sticky_content = $('.ai-sticky-content');

  if (ai_debug) console.log ('');
  if (ai_debug) console.log ("AI STICKY CLIENT WIDTH:", client_width, 'px');
  if (ai_debug) console.log ("AI STICKY CONTENT:", sticky_content.length, 'elements');

  var main_width = 0;
  if (sticky_content.length != 0) {
    if (main_content_element == '' || $('body').hasClass ('ai-preview')) {
      if (ai_debug) console.log ("AI STICKY CONTENT:", $('.ai-content').length, 'markers');

      if (element.length != 0) {
        while (element.prop ("tagName") != "BODY") {
          var outer_width = element.outerWidth ();

          if (ai_debug) console.log ("AI STICKY CONTENT ELEMENT:", element.prop ("tagName"), element.attr ("id"), element.attr ("class"), outer_width, 'px');

          if (outer_width != 0 && outer_width <= client_width && outer_width >= main_width) {
            main_element = element;
            main_width = outer_width;
          }

          element = element.parent ();
        }
      }
    } else {
        if (parseInt (main_content_element) != main_content_element) {
          main_element = $(main_content_element);
          var outer_width = main_element.outerWidth ();

          if (ai_debug) console.log ("AI STICKY CUSTOM MAIN CONTENT ELEMENT:", main_element.prop ("tagName"), main_element.attr ("id"), main_element.attr ("class"), outer_width, 'px');

          if (outer_width != 0 && outer_width <= client_width && outer_width >= main_width) {
            main_width = outer_width;
          }
        }
      }
  }

  if (main_width != 0) {
    if (ai_debug)console.log ("AI STICKY MAIN CONTENT ELEMENT:", main_element.prop ("tagName"), main_element.attr ("id"), main_element.attr ("class"), main_element.width (), 'px');
    var shift = main_width / 2 + default_margin;
    if (ai_debug) console.log ('AI STICKY shift:', shift, 'px');

    sticky_content.each (function () {
      if (ai_debug) console.log ('');

      if (main_width != 0) {

        var block_width = $(this).width ();
        var block_height = $(this).height ();

        if (ai_debug) console.log ('AI STICKY BLOCK:', block_width, 'x', block_height);

        if ($(this).hasClass ('ai-sticky-left')) {
          var margin = parseInt ($(this).css ('margin-right'));

          if (ai_debug) console.log ('AI STICKY left  ', $(this).attr ("class"), '=> SPACE LEFT: ', main_element.offset().left - margin - block_width, 'px');

          if (main_element.offset().left - margin - block_width >= - block_width / 2) {
//            $(this).css ('left', 'calc(50% - ' + (shift + block_width + margin) + 'px)');
            $(this).css ('right', 'calc(50% + ' + shift + 'px)');
            $(this).show ();
          } else $(this).removeClass ('ai-sticky-scroll'); // prevent showing if it has sticky scroll class

        } else
        if ($(this).hasClass ('ai-sticky-right')) {
          var margin = parseInt ($(this).css ('margin-left'));

          if (ai_debug) console.log ('AI STICKY right ', $(this).attr ("class"), '=> SPACE RIGHT: ', client_width - (main_element.offset().left + main_width + margin + block_width), 'px');

          if (main_element.offset().left + main_width + margin + block_width <= client_width + block_width / 2) {
            $(this).css ('right', '').css ('left', 'calc(50% + ' + shift + 'px)');
            $(this).show ();
          } else $(this).removeClass ('ai-sticky-scroll'); // prevent showing if it has sticky scroll class
        }

        if ($(this).hasClass ('ai-sticky-scroll')) {

          if (ai_debug) console.log ('AI STICKY scroll', $(this).attr ("class"), '=> MARGIN BOTTOM:', - block_height, 'px');

          $(this).css ('margin-bottom', - block_height).show ();
        }
      }
    });
  }

}

jQuery(document).ready(function($) {
  if (ai_process_sticky_elements_on_ready) {
    setTimeout (function() {ai_process_sticky_elements (jQuery);}, ai_sticky_delay);
    setTimeout (function() {AOS.init();}, ai_sticky_delay + 10);
  }
});
