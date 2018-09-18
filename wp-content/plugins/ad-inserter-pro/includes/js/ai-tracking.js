jQuery(document).ready(function($) {
  var ai_debug = typeof ai_debugging !== 'undefined';
  var ai_internal_tracking = AI_INTERNAL_TRACKING;
  var ai_external_tracking = AI_EXTERNAL_TRACKING;
  var ai_track_pageviews = AI_TRACK_PAGEVIEWS;
  var ai_advanced_click_detection = AI_ADVANCED_CLICK_DETECTION;
  var ai_viewports = AI_VIEWPORTS;
  var ai_viewport_names = JSON.parse (atob ("AI_VIEWPORT_NAMES"));
  var ai_data_id = "AI_NONCE";
  var ajax_url = "AI_SITE_URL/wp-admin/admin-ajax.php";

  Number.isInteger = Number.isInteger || function (value) {
    return typeof value === "number" &&
           isFinite (value) &&
           Math.floor (value) === value;
  };

  function external_tracking (action, label, non_interaction) {
    var category  = "Ad Inserter Pro";

//        Google Analytics
    if (typeof window.gtag == 'function') {
      gtag ('event', 'impression', {
        'event_category': category,
        'event_action': action,
        'event_label': label,
        'non_interaction': non_interaction
      });

      if (ai_debug) console.log ("AI TRACKING Global Site Tag:", action, label, non_interaction);
    } else

    if (typeof window.ga == 'function') {
      ga ('send', 'event', {
        eventCategory: category,
        eventAction: action,
        eventLabel: label,
        nonInteraction: non_interaction
      });

      if (ai_debug) console.log ("AI TRACKING Google Universal Analytics:", action, label, non_interaction);
    } else

    if (typeof window.__gaTracker == 'function') {
      __gaTracker ('send', 'event', {
        eventCategory: category,
        eventAction: action,
        eventLabel: label,
        nonInteraction: non_interaction
      });

      if (ai_debug) console.log ("AI TRACKING Google Universal Analytics by MonsterInsights:", action, label, non_interaction);
    } else

    if (typeof _gaq == 'object') {
//      _gaq.push (['_trackEvent', category, action, label]);
      _gaq.push (['_trackEvent', category, action, label, undefined, non_interaction]);

      if (ai_debug) console.log ("AI TRACKING Google Legacy Analytics:", action, label, non_interaction);
    }

//        Piwik
    if (typeof _paq == 'object') {
      _paq.push (['trackEvent', category, action, label]);

      if (ai_debug) console.log ("AI TRACKING Piwik:", action, label);
    }
  }

  function ai_click (data, click_type) {

    var block         = data [0];
    var code_version  = data [1];

    if (Number.isInteger (code_version))

      if (ai_debug) console.log ("AI CLICK: ", data, click_type);

      if (ai_internal_tracking) {
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

      if (ai_external_tracking) {
        var block_name         = data [2];
        var code_version_name  = data [3];

        external_tracking ("click", block + ' - ' + block_name + (code_version == 0 ? '' : ' - ' + code_version_name), false);
      }
  }

  function ai_install_click_trackers () {
    if (ai_advanced_click_detection) {
      elements = $("div[data-ai]:visible");

      elements.iframeTracker ({
        blurCallback: function(){
          if (this.ai_data != null && wraper != null) {
            if (ai_debug) console.log ("AI blurCallback for block: " + this.ai_data [0]);
            if (!wraper.hasClass ("clicked")) {
              wraper.addClass ("clicked");
              ai_click (this.ai_data, "blurCallback");
            }
          }
        },
        overCallback: function(element){
          var closest = $(element).closest ("div[data-ai]");
          if (typeof closest.data ("ai") != "undefined") {
            var data = JSON.parse (atob (closest.data ("ai")));
            if (typeof data !== "undefined" && data.constructor === Array && Number.isInteger (data [1])) {
              wraper = closest;
              this.ai_data = data;
              if (ai_debug) console.log ("AI overCallback for block: " + this.ai_data [0]);
            } else {
                if (wraper != null) wraper.removeClass ("clicked");
                wraper        = null;
                this.ai_data  = null;
              }
          }
        },
        outCallback: function (element){
          if (ai_debug && this.ai_data != null) console.log ("AI outCallback for block: " + this.ai_data [0]);
          if (wraper != null) wraper.removeClass ("clicked");
          wraper = null;
          this.ai_data = null;
        },
        focusCallback: function(element){
          if (this.ai_data != null && wraper != null) {
            if (ai_debug) console.log ("AI focusCallback for block: " + this.ai_data [0]);
            if (!wraper.hasClass ("clicked")) {
              wraper.addClass ("clicked");
              ai_click (this.ai_data, "focusCallback");
            }
          }
        },
        wraper:  null,
        ai_data: null,
        block:   null,
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
              ai_click (data, "a.click");
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
              ai_click (data, "a.click");
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
    if (ai_track_pageviews) {
      var client_width = document.documentElement.clientWidth, inner_width =  window.innerWidth;
      var viewport_width = client_width < inner_width ? inner_width : client_width;

      var version = 0;
      $.each (ai_viewports, function (index, width) {
        if (viewport_width >= width) {
          version = index + 1;
          return (false);
        }
      });

      if (ai_debug) console.log ('AI TRACKING PAGEVIEW, viewport width:', viewport_width, '=>', ai_viewport_names [version - 1]);

      if (typeof ai_adb === "boolean" && ai_adb) {
        if (ai_external_tracking) {
          external_tracking ("ad blocking", ai_viewport_names [version - 1], true);
        }
        version |= 0x80;
      }

      if (ai_internal_tracking) {
        $.ajax ({
            url: ajax_url,
            type: "post",
            data: {
              action: "ai_ajax",
              ai_check: ai_data_id,
              views: [0],
              versions: [version],
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

    var blocks = [];
    var versions = [];
    var block_names = [];
    var version_names = [];
    $("div[data-ai]:visible").each (function (){
      if (typeof $(this).data ("ai") != "undefined") {
        var data = JSON.parse (atob ($(this).data ("ai")));
        if (typeof data !== "undefined" && data.constructor === Array) {
          if (ai_debug) console.log ("AI TRACKING DATA:", data);
          if (Number.isInteger (data [1])) {

            var no_tracking = false;
            if (typeof ai_adb === "boolean") {
              var outer_height = $(this).outerHeight ()

              var ai_code = $(this).find ('.ai-code');
              if (ai_code.length) {
                outer_height = 0;
                ai_code.each (function (){
                  outer_height += $(this).outerHeight ();
                });
              }

              no_tracking = $(this).hasClass ('ai-no-tracking');
              if (ai_debug) console.log ('AI ad blocking:', ai_adb, " outerHeight:", outer_height, 'no tracking:', no_tracking);
              if (ai_adb && outer_height === 0) {
                data [1] |= 0x80;
              }
            }

            if (!no_tracking) {
              blocks.push (data [0]);
              versions.push (data [1]);
              block_names.push (data [2]);
              version_names.push (data [3]);
            }
          } else console.log ("AI TRACKING block", data [0], "- version not set");
        }
      }
    });

    if (blocks.length) {
      if (ai_debug) {
        console.log ("AI IMPRESSION blocks:", blocks);
        console.log ("            versions:", versions);
      }

      if (ai_internal_tracking) {
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

      if (ai_external_tracking) {
        for (var i = 0; i < blocks.length; i++) {
          external_tracking ("impression", blocks [i] + ' - ' + block_names [i] + (versions [i] == 0 ? '' : ' - ' + version_names [i]), true);
        }
      }
    }
  }

  setTimeout (ai_log_impressions, 600);
  setTimeout (ai_install_click_trackers, 800);
});


(function (root, factory) {
  if (typeof define === 'function' && define.amd) {
    define(["jquery"], function (a0) {
      return (factory(a0));
    });
  } else if (typeof module === 'object' && module.exports) {
    module.exports = factory(require("jquery"));
  } else {
    factory(root["jQuery"]);
  }
}(this, function (jQuery) {

/*!
 * jQuery iframe click tracking plugin
 *
 * @author Vincent Pare
 * @copyright  2013-2018 Vincent Pare
 * @license http://opensource.org/licenses/Apache-2.0
 * @version 2.0.0
 */
(function($) {
  // Tracking handler manager
  $.fn.iframeTracker = function(handler) {
    // Building handler object from handler function
    if (typeof handler == "function") {
      handler = {
        blurCallback: handler
      };
    }

    var target = this.get();
    if (handler === null || handler === false) {
      $.iframeTracker.untrack(target);
    } else if (typeof handler == "object") {
      $.iframeTracker.track(target, handler);
    } else {
      throw new Error("Wrong handler type (must be an object, or null|false to untrack)");
    }
    return this;
  };

  // Iframe tracker common object
  $.iframeTracker = {
    // State
    focusRetriever: null,  // Element used for restoring focus on window (element)
    focusRetrieved: false, // Says if the focus was retrieved on the current page (bool)
    handlersList: [],      // Store a list of every trakers (created by calling $(selector).iframeTracker...)
    isIE8AndOlder: false,  // true for Internet Explorer 8 and older

    // Init (called once on document ready)
    init: function() {
      // Determine browser version (IE8-) ($.browser.msie is deprecated since jQuery 1.9)
      try {
        if ($.browser.msie === true && $.browser.version < 9) {
          this.isIE8AndOlder = true;
        }
      } catch (ex) {
        try {
          var matches = navigator.userAgent.match(/(msie) ([\w.]+)/i);
          if (matches[2] < 9) {
            this.isIE8AndOlder = true;
          }
        } catch (ex2) {}
      }

      // Listening window blur
      $(window).focus();
      $(window).blur(function(e) {
        $.iframeTracker.windowLoseFocus(e);
      });

      // Focus retriever (get the focus back to the page, on mouse move)
      $("body").append('<div style="position:fixed; top:0; left:0; overflow:hidden;"><input style="position:absolute; left:-300px;" type="text" value="" id="focus_retriever" readonly="true" /></div>');
      this.focusRetriever = $("#focus_retriever");
      this.focusRetrieved = false;

      // ### AI
      var instance = this;
      // ### /AI

      $(document).mousemove(function(e) {
        if (document.activeElement && document.activeElement.tagName === "IFRAME") {
          $.iframeTracker.focusRetriever.focus();
          $.iframeTracker.focusRetrieved = true;
        }

        // ### AI
        if (document.activeElement && document.activeElement.tagName == "A") {
          for (var i in instance.handlersList) {
            try {instance.handlersList[i].focusCallback(document.activeElement);} catch(ex) {}
          }
        }
        // ### /AI

      });

      // Special processing to make it work with my old friend IE8 (and older) ;)
      if (this.isIE8AndOlder) {
        // Blur doesn't works correctly on IE8-, so we need to trigger it manually
        this.focusRetriever.blur(function(e) {
          e.stopPropagation();
          e.preventDefault();
          $.iframeTracker.windowLoseFocus(e);
        });

        // Keep focus on window (fix bug IE8-, focusable elements)
        $("body").click(function(e) {
          $(window).focus();
        });
        $("form").click(function(e) {
          e.stopPropagation();
        });

        // Same thing for "post-DOMready" created forms (issue #6)
        try {
          $("body").on("click", "form", function(e) {
            e.stopPropagation();
          });
        } catch (ex) {
          console.log("[iframeTracker] Please update jQuery to 1.7 or newer. (exception: " + ex.message + ")");
        }
      }
    },

    // Add tracker to target using handler (bind boundary listener + register handler)
    // target: Array of target elements (native DOM elements)
    // handler: User handler object
    track: function(target, handler) {
      // Adding target elements references into handler
      handler.target = target;

      // Storing the new handler into handler list
      $.iframeTracker.handlersList.push(handler);

      // Binding boundary listener
      $(target)
        .bind("mouseover", { handler: handler }, $.iframeTracker.mouseoverListener)
        .bind("mouseout",  { handler: handler }, $.iframeTracker.mouseoutListener);
    },

    // Remove tracking on target elements
    // target: Array of target elements (native DOM elements)
    untrack: function(target) {
      if (typeof Array.prototype.filter != "function") {
        console.log("Your browser doesn't support Array filter, untrack disabled");
        return;
      }

      // Unbinding boundary listener
      $(target).each(function(index) {
        $(this)
          .unbind("mouseover", $.iframeTracker.mouseoverListener)
          .unbind("mouseout", $.iframeTracker.mouseoutListener);
      });

      // Handler garbage collector
      var nullFilter = function(value) {
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
        if (this.handlersList[i].target.length === 0) {
          this.handlersList[i] = null;
        }
      }
      this.handlersList = this.handlersList.filter(nullFilter);
    },

    // Target mouseover event listener
    mouseoverListener: function(e) {
      e.data.handler.over = true;
      try {
        e.data.handler.overCallback(this, e);
      } catch (ex) {}
    },

    // Target mouseout event listener
    mouseoutListener: function(e) {
      e.data.handler.over = false;
      $.iframeTracker.focusRetriever.focus();
      try {
        e.data.handler.outCallback(this, e);
      } catch (ex) {}
    },

    // Calls blurCallback for every handler with over=true on window blur
    windowLoseFocus: function(e) {
      for (var i in this.handlersList) {
        if (this.handlersList[i].over === true) {
          try {
            this.handlersList[i].blurCallback(e);
          } catch (ex) {}
        }
      }
    }
  };

  // Init the iframeTracker on document ready
  $(document).ready(function() {
    $.iframeTracker.init();
  });
})(jQuery);

}));
