jQuery(document).ready(function($) {
  var ai_debug = AI_DATAB_AI_JS_DEBUGGING;
  var ai_advanced_click_detection = AI_ADVANCED_CLICK_DETECTION;
  var ai_data_id = "AI_NONCE";
  var ajax_url = "AI_SITE_URL/wp-admin/admin-ajax.php";

  function ai_click (block, code_version, click_type) {
    if (Number.isInteger (code_version))
      $.ajax ({
          url: ajax_url,
          type: "post",
          data: {
            action: "ai_ajax",
            ai_check: ai_data_id,
            click: block,
            version: code_version,
            type: click_type,
          },
          async: true
      }).done (function (data) {
          if (ai_debug) {
            if (data != "") {
              var db_record = JSON.parse (data);
              if (typeof db_record == "string")
                console.log ("AI CLICK " + block, code_version == 0 ? "" : "[" + code_version + "]", "(" + db_record + ")"); else
                  console.log ("AI CLICK " + block, code_version == 0 ? "" : "[" + code_version + "]", "(Views: " + db_record [4] + ", Clicks: " + db_record [5] + (click_type == "" ? "" : ", " + click_type) + ")");
            } else console.log ("AI CLICK " + block, code_version == 0 ? "" : "[" + code_version + "]", "(NO DATA" + (click_type == "" ? "" : ", " + click_type) + ")");
          }
      });
  }

  function ai_install_click_trackers () {
    if (ai_advanced_click_detection) {
      elements = $("div[data-ai]:visible");

      elements.iframeTracker ({
        blurCallback: function(){
          if (this.block != null && this.version != null && wraper != null) {
            if (ai_debug) console.log ("AI blurCallback for block: " + this.block);
            if (!wraper.hasClass ("clicked")) {
              wraper.addClass ("clicked");
              ai_click (this.block, this.version, "blurCallback");
            }
          }
        },
        overCallback: function(element){
          var closest = $(element).closest ("div[data-ai]");
          if (typeof closest.data ("ai") != "undefined") {
            var data = JSON.parse (atob (closest.data ("ai")));
            if (typeof data !== "undefined" && data.constructor === Array && Number.isInteger (data [1])) {
              wraper = closest;
              this.block   = data [0];
              this.version = data [1];
              if (ai_debug) console.log ("AI overCallback for block: " + this.block);
            } else {
                if (wraper != null) wraper.removeClass ("clicked");
                wraper = null;
                this.block = null;
                this.version = null;
              }
          }
        },
        outCallback: function (element){
          if (ai_debug && this.block != null) console.log ("AI outCallback for block: " + this.block);
          if (wraper != null) wraper.removeClass ("clicked");
          wraper = null;
          this.block = null;
          this.version = null;
        },
        focusCallback: function(element){
          if (this.block != null && this.version != null && wraper != null) {
            if (ai_debug) console.log ("AI focusCallback for block: " + this.block);
            if (!wraper.hasClass ("clicked")) {
              wraper.addClass ("clicked");
              ai_click (this.block, this.version, "focusCallback");
            }
          }
        },
        wraper: null,
        block: null,
        version: null
      });

      if (ai_debug) {
        elements.each (function (){
          var closest = $(this).closest ("div[data-ai]");
          if (typeof closest.data ("ai") != "undefined") {
            var data = JSON.parse (atob (closest.data ("ai")));
            if (typeof data !== "undefined" && data.constructor === Array) {
              if (Number.isInteger (data [1])) {
                console.log ("AI ADVANCED CLICK TRACKER installed on block", data [0]);
              } else console.log ("AI ADVANCED CLICK TRACKER NOT installed on block", data [0], "- version not set");
            }
          }
        });
      }

      elements = $("div[data-ai]:visible a");

      elements.click (function () {
        var wraper = $(this).closest ("div[data-ai]");
        if (typeof wraper.data ("ai") != "undefined") {
          var data = JSON.parse (atob (wraper.data ("ai")));
          if (typeof data !== "undefined" && data.constructor === Array && Number.isInteger (data [1])) {
            if (!wraper.hasClass ("clicked")) {
              wraper.addClass ("clicked");
              ai_click (data [0], data [1], "a.click");
            }
          }
        }
      });
    } else {
        elements = $("div[data-ai]:visible a");

        elements.click (function () {
          if (typeof $(this).closest ("div[data-ai]").data ("ai") != "undefined") {
            var data = JSON.parse (atob ($(this).closest ("div[data-ai]").data ("ai")));
            if (typeof data !== "undefined" && data.constructor === Array && Number.isInteger (data [1])) {
              ai_click (data [0], data [1], "a.click");
            }
          }
        });

        if (ai_debug) {
          elements.each (function (){
            var closest = $(this).closest ("div[data-ai]");
            if (typeof closest.data ("ai") != "undefined") {
              var data = JSON.parse (atob (closest.data ("ai")));
              if (typeof data !== "undefined" && data.constructor === Array) {
                if (Number.isInteger (data [1])) {
                  console.log ("AI STANDARD CLICK TRACKER installed on block", data [0]);
                } else console.log ("AI STANDARD CLICK TRACKER NOT installed on block", data [0], "- version not set");

              }
            }
          });
        }
      }
  }

  function ai_log_impressions () {
    var blocks = [];
    var versions = [];
    $("div[data-ai]:visible").each (function (){
      if (typeof $(this).data ("ai") != "undefined") {
        var data = JSON.parse (atob ($(this).data ("ai")));
        if (typeof data !== "undefined" && data.constructor === Array) {
          if (ai_debug) console.log ("AI TRACKING DATA:", data);
          if (Number.isInteger (data [1])) {
            blocks.push (data [0]);
            versions.push (data [1]);
          } else console.log ("AI TRACKING block", data [0], "- version not set");
        }
      }
    });
    if (blocks.length) {
      if (ai_debug) {
        console.log ("AI IMPRESSION blocks:", blocks);
        console.log ("            versions:", versions);
      }
      $.ajax ({
          url: ajax_url,
          type: "post",
          data: {
            action: "ai_ajax",
            ai_check: ai_data_id,
            views: blocks,
            versions: versions,
          },
          async: true
      }).done (function (data) {
          if (ai_debug) {
            if (data != "") {
              var db_records = JSON.parse (data);
              console.log ("AI DB RECORDS: ", db_records);
            }
          }
      });
    }
  }

  setTimeout (ai_log_impressions, 600);
  setTimeout (ai_install_click_trackers, 800);
});


(function($){
  // Tracking handler manager
  $.fn.iframeTracker = function(handler){
    var target = this.get();
    if (handler === null || handler === false) {
      $.iframeTracker.untrack(target);
    } else if (typeof handler == "object") {
      $.iframeTracker.track(target, handler);
    } else {
      throw new Error("Wrong handler type (must be an object, or null|false to untrack)");
    }
  };

  // Iframe tracker common object
  $.iframeTracker = {
    // State
    focusRetriever: null,  // Element used for restoring focus on window (element)
    focusRetrieved: false, // Says if the focus was retrived on the current page (bool)
    handlersList: [],      // Store a list of every trakers (created by calling $(selector).iframeTracker...)
    isIE8AndOlder: false,  // true for Internet Explorer 8 and older

    // Init (called once on document ready)
    init: function(){
      // Determine browser version (IE8-) ($.browser.msie is deprecated since jQuery 1.9)
      try {
        if ($.browser.msie == true && $.browser.version < 9) {
          this.isIE8AndOlder = true;
        }
      } catch(ex) {
        try {
          var matches = navigator.userAgent.match(/(msie) ([\w.]+)/i);
          if (matches[2] < 9) {
            this.isIE8AndOlder = true;
          }
        } catch(ex2) {}
      }

      // Listening window blur
      $(window).focus();
      $(window).blur(function(e){
        $.iframeTracker.windowLoseFocus(e);
      });

      // Focus retriever (get the focus back to the page, on mouse move)
      $("body").append("<div style=\"position:fixed; top:0; left:0; overflow:hidden;\"><input style=\"position:absolute; left:-300px;\" type=\"text\" value=\"\" id=\"focus_retriever\" readonly=\"true\" /></div>");
      this.focusRetriever = $("#focus_retriever");
      this.focusRetrieved = false;
      var instance = this;
      $(document).mousemove(function(e){
        if (document.activeElement && document.activeElement.tagName == "IFRAME") {
          $.iframeTracker.focusRetriever.focus();
          $.iframeTracker.focusRetrieved = true;
        }
        if (document.activeElement && document.activeElement.tagName == "A") {
          for (var i in instance.handlersList) {
            if (instance.handlersList[i].over == true) {
              try {instance.handlersList[i].focusCallback(document.activeElement);} catch(ex) {}
            }
          }

          $.iframeTracker.focusRetriever.focus();
          $.iframeTracker.focusRetrieved = true;
        }
      });

      // Special processing to make it work with my old friend IE8 (and older) ;)
      if (this.isIE8AndOlder) {
        // Blur doesn\'t works correctly on IE8-, so we need to trigger it manually
        this.focusRetriever.blur(function(e){
          e.stopPropagation();
          e.preventDefault();
          $.iframeTracker.windowLoseFocus(e);
        });

        // Keep focus on window (fix bug IE8-, focusable elements)
        $("body").click(function(e){ $(window).focus(); });
        $("form").click(function(e){ e.stopPropagation(); });

        // Same thing for "post-DOMready" created forms (issue #6)
        try {
          $("body").on("click", "form", function(e){ e.stopPropagation(); });
        } catch(ex) {
          console.log("[iframeTracker] Please update jQuery to 1.7 or newer. (exception: " + ex.message + ")");
        }
      }
    },


    // Add tracker to target using handler (bind boundary listener + register handler)
    // target: Array of target elements (native DOM elements)
    // handler: User handler object
    track: function(target, handler){
      // Adding target elements references into handler
      handler.target = target;

      // Storing the new handler into handler list
      $.iframeTracker.handlersList.push(handler);

      // Binding boundary listener
      $(target)
        .bind("mouseover", {handler: handler}, $.iframeTracker.mouseoverListener)
        .bind("mouseout",  {handler: handler}, $.iframeTracker.mouseoutListener);
    },

    // Remove tracking on target elements
    // target: Array of target elements (native DOM elements)
    untrack: function(target){
      if (typeof Array.prototype.filter != "function") {
        console.log("Your browser doesn\'t support Array filter, untrack disabled");
        return;
      }

      // Unbinding boundary listener
      $(target).each(function(index){
        $(this)
          .unbind("mouseover", $.iframeTracker.mouseoverListener)
          .unbind("mouseout", $.iframeTracker.mouseoutListener);
      });

      // Handler garbage collector
      var nullFilter = function(value){
        return value === null ? false : true;
      };
      for (var i in this.handlersList) {
        // Prune target
        for (var j in this.handlersList[i].target) {
          if ($.inArray(this.handlersList[i].target[j], target) !== -1) {
            this.handlersList[i].target[j] = null;
          }
        }
        this.handlersList[i].target = this.handlersList[i].target.filter(nullFilter);

        // Delete handler if unused
        if (this.handlersList[i].target.length == 0) {
          this.handlersList[i] = null;
        }
      }
      this.handlersList = this.handlersList.filter(nullFilter);
    },

    // Target mouseover event listener
    mouseoverListener: function(e){
      e.data.handler.over = true;
      try {e.data.handler.overCallback(this);} catch(ex) {}
    },

    // Target mouseout event listener
    mouseoutListener: function(e){
      e.data.handler.over = false;
      $.iframeTracker.focusRetriever.focus();
      try {e.data.handler.outCallback(this);} catch(ex) {}
    },

    // Calls blurCallback for every handler with over=true on window blur
    windowLoseFocus: function(event){
      for (var i in this.handlersList) {
        if (this.handlersList[i].over == true) {
          try {this.handlersList[i].blurCallback();} catch(ex) {}
        }
      }
    }
  };

  // Init the iframeTracker on document ready
  $(document).ready(function(){
    $.iframeTracker.init();
  });
})(jQuery);
