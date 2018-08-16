jQuery(document).ready(function($) {
  var sticky_widget_margin = AI_FUNC_GET_STICKY_WIDGET_MARGIN;
  var document_width = $(document).width();

  $(".ai-sticky").each (function () {
    var widget = $(this);
    var widget_width = widget.width();
//        console.log ("WIDGET:", widget.width (), widget.prop ("tagName"), widget.attr ("id"));
    var sidebar = widget.parent ();
    while (sidebar.prop ("tagName") != "BODY") {
//          console.log ("SIDEBAR:", sidebar.width (), sidebar.prop ("tagName"), sidebar.attr ("id"));
      var parent_element = sidebar.parent ();
      var parent_element_width = parent_element.width();
      if (parent_element_width > widget_width * 1.2 || parent_element_width > document_width / 2) break;
      sidebar = parent_element;
    }
    var new_sidebar_top = sidebar.offset ().top - widget.offset ().top + sticky_widget_margin;
//        console.log ("NEW SIDEBAR TOP:", new_sidebar_top);
    if (sidebar.css ("position") != "sticky" || isNaN (parseInt (sidebar.css ("top"))) || sidebar.css ("top") < new_sidebar_top) {
      sidebar.css ("position", "sticky").css ("top", new_sidebar_top);
//          console.log ("SET SIDEBAR TOP:", new_sidebar_top);
    }
  });
});
