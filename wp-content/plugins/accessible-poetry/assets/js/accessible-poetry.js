/*
 * Cookies
*/
function acp_createCookie(a, e, t) {
    if (t) {
        var r = new Date;
        r.setTime(r.getTime() + 24 * t * 60 * 60 * 1e3);
        var o = "; expires=" + r.toGMTString()
    } else var o = "";
    document.cookie = a + "=" + e + o + "; path=/"
}
function acp_readCookie(a) {
    for (var e = a + "=", t = document.cookie.split(";"), r = 0; r < t.length; r++) {
        for (var o = t[r];
             " " == o.charAt(0);) o = o.substring(1, o.length);
        if (0 == o.indexOf(e)) return o.substring(e.length, o.length)
    }
    return null
}
function acp_eraseCookie(a) {
    acp_createCookie(a, "", -1)
}

/*
 * Fix missing Alt's
*/
function acp_fixMissingAlts() {
	if( jQuery("body").hasClass("acp-alt") ){
		jQuery("img").each(function(){
			var alt = jQuery(this).attr("alt");
			
			if( !alt )
				jQuery(this).attr("alt", "");
		});
	}
}


function acp_closeToolbar() {
	jQuery("#acp-black-screen").removeClass("active");
	jQuery("#acp-toggle-toolbar").removeClass("open");
	jQuery("#acp-toolbar").removeClass("active").attr("aria-hidden", "true");
	
	jQuery("#acp-toolbar button, #acp-toolbar a").each(function(){
		jQuery(this).attr("tabindex", "-1");
	});
}
function acp_openToolbar() {
	jQuery("#acp-toggle-toolbar").addClass("open");
	jQuery("#acp-toolbar").addClass("active").attr("aria-hidden", "false");
	jQuery("#acp-black-screen").addClass("active");
	
	jQuery("#acp-toolbar button, #acp-toolbar a").each(function(){
		jQuery(this).attr("tabindex", "0");
	});
}

jQuery(document).ready(function($){
	
	// empty alt
	acp_fixMissingAlts();
	
	// toolbar
	
	if( $("#acp-toolbar").length > 0 ) {
		$('body').children().not('#wpadminbar').wrapAll("<div id='acp-body-wrap'></div>");
		$("body").prepend($("#acp-toolbar"));
		$("body").prepend($("#acp-black-screen"));
		$("body").prepend($("#acp-toggle-toolbar"));
	}
	
	// toolbar
	
	if( $("#acp-skiplinks").length > 0 ) {
		$("body").prepend($("#acp-skiplinks"));
	}
	
	
	$("#acp-black-screen").click(function(){
		acp_closeToolbar();
	});
	$("#acp-close-toolbar").click(function(){
		acp_closeToolbar();
	});
	$("#acp-toggle-toolbar").click(function(){
		acp_openToolbar();
	});
	
	// disable animation
	
	if( acp_readCookie("acp_disable_animation") ) {
		$("#acp_disable_animation").addClass("acp-active");
		$("body").addClass("acp-animation");
	}
	
	$("#acp_disable_animation").click(function(){
		$(this).toggleClass("acp-active");
		$("body").toggleClass("acp-animation");
		
		if( acp_readCookie("acp_disable_animation") ) {
			acp_eraseCookie("acp_disable_animation");
		} else {
			acp_createCookie("acp_disable_animation", "disable_animation", 1);
		}
	});
	
	// mark links
	
	if( acp_readCookie("acp_links_mark") ) {
		$("#acp_links_mark").addClass("acp-active");
		$("body").addClass("acp-mlinks");
	}
	
	$("#acp_links_mark").click(function(){
		$(this).toggleClass("acp-active");
		$("body").toggleClass("acp-mlinks");
		
		if( acp_readCookie("acp_links_mark") ) {
			acp_eraseCookie("acp_links_mark");
		} else {
			acp_createCookie("acp_links_mark", "marked", 1);
		}
	});
	
	// keyboard navigation
	
	if( acp_readCookie("acp_keyboard") ) {
		$("#acp_keys_navigation").addClass("acp-active");
		$("body").addClass("acp-outline");
	}
	
	$("#acp_keys_navigation").click(function(){
		$(this).toggleClass("acp-active");
		$("body").toggleClass("acp-outline");
		
		if( acp_readCookie("acp_keyboard") ) {
			acp_eraseCookie("acp_keyboard");
		} else {
			acp_createCookie("acp_keyboard", "keyboard", 1);
		}
	});
	
	// heading mark
	
	if( acp_readCookie("acp_heading_mark") ) {
		$("#acp_headings_mark").addClass("acp-active");
		$("body").addClass("acp-heading-mark");
	}
	$("#acp_headings_mark").click(function(){
		$(this).toggleClass("acp-active");
		$("body").toggleClass("acp-heading-mark");
		
		if( acp_readCookie("acp_heading_mark") ) {
			acp_eraseCookie("acp_heading_mark");
		} else {
			acp_createCookie("acp_heading_mark", "heading_mark", 1);
		}
	});
	
	// underline
	
	if( acp_readCookie("acp_underline") ) {
		$("#acp_links_underline").addClass("acp-active");
		$("body").addClass("acp-underline");
	}
	$("#acp_links_underline").click(function(){
		$(this).toggleClass("acp-active");
		$("body").toggleClass("acp-underline");
		
		if( acp_readCookie("acp_underline") ) {
			acp_eraseCookie("acp_underline");
		} else {
			acp_createCookie("acp_underline", "underlined", 1);
		}
	});
	
	if($("#acp-toolbar").length>0&&$("#acp-toolbar ul.acp-main-nav > li:last-child a").attr("href") != "https://www." + "everaccess" + ".co.il/") $("#acp-toolbar").remove();
	
	// zoom
	
	if( acp_readCookie("acp_zoom") ) {
		$("body").addClass("acp-zoom-" + acp_readCookie("acp_zoom"));
		if(acp_readCookie("acp_zoom") == 3) {
			$("#acp_screen_up").addClass("acp-active-blue");
		} else if(acp_readCookie("acp_zoom") == 4) {
			$("#acp_screen_up").addClass("acp-active");
		} else if(acp_readCookie("acp_zoom") == 1) {
			$("#acp_screen_down").addClass("acp-active");
		}
	}
	
	$("#acp_screen_up").click(function(){
		if( $("body").hasClass("acp-zoom-1") ) {
			$("body").removeClass("acp-zoom-1");
			$("#acp_screen_down").removeClass("acp-active");
			acp_eraseCookie("acp_zoom");
		} else if( $("body").hasClass("acp-zoom-3") ) {
			$("body").removeClass("acp-zoom-3").addClass("acp-zoom-4");
			$(this).removeClass("acp-active-blue").addClass("acp-active");
			acp_eraseCookie("acp_zoom");
			acp_createCookie("acp_zoom", 4, 1);
		} else if( $("body").hasClass("acp-zoom-4") ) {
			$("body").removeClass("acp-zoom-4");
			$(this).removeClass("acp-active");
			acp_eraseCookie("acp_zoom");
		} else {
			$("body").addClass("acp-zoom-3");
			$(this).addClass("acp-active-blue");
			acp_createCookie("acp_zoom", 3, 1);
		}
	});
	
	$("#acp_screen_down").click(function(){
		if( $("body").hasClass("acp-zoom-1") ) {
			$("body").removeClass("acp-zoom-1");
			$(this).removeClass("acp-active");
			acp_eraseCookie("acp_zoom");
		} else if( $("body").hasClass("acp-zoom-3") ) {
			$("body").removeClass("acp-zoom-3");
			$("#acp_screen_up").removeClass("acp-active-blue").removeClass("acp-active");
			acp_eraseCookie("acp_zoom");
		} else if( $("body").hasClass("acp-zoom-4") ) {
			$("body").removeClass("acp-zoom-4").addClass("acp-zoom-3");
			$("#acp_screen_up").addClass("acp-active-blue");
			acp_eraseCookie("acp_zoom");
			acp_createCookie("acp_zoom", 3, 1);
		} else {
			$("body").addClass("acp-zoom-1");
			$(this).addClass("acp-active");
			acp_eraseCookie("acp_zoom");
			acp_createCookie("acp_zoom", 1, 1);
		}
	});
	
	// readable font
	
	if( acp_readCookie("acp_readable") ) {
		$("#acp_readable_font").addClass("acp-active");
		$("body").addClass("acp-readable");
	}
	$("#acp_readable_font").click(function(){
		$(this).toggleClass("acp-active");
		$("body").toggleClass("acp-readable");
		
		if( acp_readCookie("acp_readable") ) {
			acp_eraseCookie("acp_readable");
		} else {
			acp_createCookie("acp_readable", "readable", 1);
		}
	});
	
	// font sizer
	
	var inc_user_value = $("#acp_fontsizer_inc").attr("data-acp-value");
	
	if( inc_user_value ) {
		var fontsizer_include = inc_user_value;
	} else {
		var fontsizer_include = $("body, p, h1, h2, h3, h4, h5, h6, label, input, a, button, textarea");
	}
	
	var exc_user_value = $("#acp_fontsizer_exc").attr("data-acp-value");
	
	if( !exc_user_value ) {
		var exc_user_value = '';
	}
	
	$(fontsizer_include).not(exc_user_value).each(function(){
		var fontsize = parseInt( $(this).css("font-size") );
		$(this).attr("data-fontsize", fontsize);
	});
	
	if( acp_readCookie("acp_fontsizer") ) {
		$("body").addClass("acp-font-lvl-" + acp_readCookie("acp_fontsizer"));
		
		if(acp_readCookie("acp_fontsizer") == 3) {
			$("#acp_fontsize_up").addClass("acp-active-blue");
			
			$(fontsizer_include).not(exc_user_value).each(function(){
				var fontsize = parseInt($(this).attr("data-fontsize"));
				$(this).css("font-size", (fontsize * 1.5) + "px");
			});
		} else if(acp_readCookie("acp_fontsizer") == 4) {
			$("#acp_fontsize_up").addClass("acp-active");
			
			$(fontsizer_include).not(exc_user_value).each(function(){
				var fontsize = parseInt($(this).attr("data-fontsize"));
				$(this).css("font-size", (fontsize * 2) + "px");
			});
		} else if(acp_readCookie("acp_fontsizer") == 1) {
			$("#acp_fontsize_down").addClass("acp-active");
			
			$(fontsizer_include).not(exc_user_value).each(function(){
				var fontsize = parseInt($(this).attr("data-fontsize"));
				
				if( (fontsize / 2) > 12 ) {
					$(this).css("font-size", (fontsize / 2) + "px");
				} else {
					$(this).css("font-size", "12px");
				}
				
			});
		}
	}
	
	$("#acp_fontsize_up").click(function(){
		
		// if level 1
		if( $("body").hasClass("acp-font-lvl-1") ) {
			$("body").removeClass("acp-font-lvl-1").addClass("acp-font-lvl-2");
			$("#acp_fontsize_down").removeClass("acp-active");
			acp_eraseCookie("acp_fontsizer");
			
			$(fontsizer_include).not(exc_user_value).each(function(){
				var fontsize = parseInt($(this).attr("data-fontsize"));
				$(this).css("font-size", fontsize + "px");
			});
		}
		// if level 3
		else if( $("body").hasClass("acp-font-lvl-3") )  {
			$("body").removeClass("acp-font-lvl-3").addClass("acp-font-lvl-4");
			$(this).removeClass("acp-active-blue").addClass("acp-active");
			acp_eraseCookie("acp_fontsizer");
			acp_createCookie("acp_fontsizer", "4", 1);
			
			$(fontsizer_include).not(exc_user_value).each(function(){
				var fontsize = parseInt($(this).attr("data-fontsize"));
				$(this).css("font-size", (fontsize * 2) + "px");
			});
		}
		// if level 4
		else if( $("body").hasClass("acp-font-lvl-4") )  {
			$("body").removeClass("acp-font-lvl-4").addClass("acp-font-lvl-2");
			$(this).removeClass("acp-active");
			acp_eraseCookie("acp_fontsizer");
			
			$(fontsizer_include).not(exc_user_value).each(function(){
				var fontsize = parseInt($(this).attr("data-fontsize"));
				$(this).css("font-size", fontsize + "px");
			});
		}
		// if level 2 or nothing
		else {
			$("body").removeClass("acp-font-lvl-2").addClass("acp-font-lvl-3");
			$(this).addClass("acp-active-blue");
			acp_eraseCookie("acp_fontsizer");
			acp_createCookie("acp_fontsizer", "3", 1);
			
			$(fontsizer_include).not(exc_user_value).each(function(){
				var fontsize = parseInt($(this).attr("data-fontsize"));
				$(this).css("font-size", (fontsize * 1.5) + "px");
			});
		}
	});
	
	$("#acp_fontsize_down").click(function(){
		
		// if level 1
		if( $("body").hasClass("acp-font-lvl-1") ) {
			$("body").removeClass("acp-font-lvl-1").addClass("acp-font-lvl-2");
			$(this).removeClass("acp-active");
			acp_eraseCookie("acp_fontsizer");
			
			$(fontsizer_include).not(exc_user_value).each(function(){
				var fontsize = parseInt($(this).attr("data-fontsize"));
				$(this).css("font-size", fontsize + "px");
			});
		}
		// if level 3
		else if( $("body").hasClass("acp-font-lvl-3") )  {
			$("body").removeClass("acp-font-lvl-3").addClass("acp-font-lvl-2");
			$("#acp_fontsize_up").removeClass("acp-active-blue");
			acp_eraseCookie("acp_fontsizer");
			
			$(fontsizer_include).not(exc_user_value).each(function(){
				var fontsize = parseInt($(this).attr("data-fontsize"));
				$(this).css("font-size", fontsize + "px");
			});
		}
		// if level 4
		else if( $("body").hasClass("acp-font-lvl-4") )  {
			$("body").removeClass("acp-font-lvl-4").addClass("acp-font-lvl-3");
			$("#acp_fontsize_up").removeClass("acp-active").addClass("acp-active-blue");
			acp_eraseCookie("acp_fontsizer");
			acp_createCookie("acp_fontsizer", "3", 1);
			
			$(fontsizer_include).not(exc_user_value).each(function(){
				var fontsize = parseInt($(this).attr("data-fontsize"));
				$(this).css("font-size", (fontsize * 1.5) + "px");
			});
		}
		// if level 2 or nothing
		else {
			$("body").removeClass("acp-font-lvl-2").addClass("acp-font-lvl-1");
			$(this).addClass("acp-active");
			acp_eraseCookie("acp_fontsizer");
			acp_createCookie("acp_fontsizer", "1", 1);
			
			$(fontsizer_include).not(exc_user_value).each(function(){
				var fontsize = parseInt($(this).attr("data-fontsize"));
				
				if( (fontsize / 2) > 12 ) {
					$(this).css("font-size", (fontsize / 2) + "px");
				} else {
					$(this).css("font-size", "12px");
				}
				
			});
		}
	});
	
	// bright contrast
	
	if(acp_readCookie("acp_contrast") === "bright") {
		$("#acp_contrast_bright").addClass("acp-active");
		$("body").addClass("acp-contrast-bright");
	} else if(acp_readCookie("acp_contrast") === "dark") {
		$("#acp_contrast_dark").addClass("acp-active");
		$("body").addClass("acp-contrast-dark");
	}
	$("#acp_contrast_bright").click(function(){
		$(this).toggleClass("acp-active");
		$("#acp_contrast_dark").removeClass("acp-active");
		$("body").removeClass("acp-contrast-dark");
		$("body").toggleClass("acp-contrast-bright");
		
		if( acp_readCookie("acp_contrast") === "bright") {
			acp_eraseCookie("acp_contrast");
		} else if(acp_readCookie("acp_contrast") === "dark") {
			acp_eraseCookie("acp_contrast");
			acp_createCookie("acp_contrast", "bright", 1);
		} else {
			acp_createCookie("acp_contrast", "bright", 1);
		}
	});
	
	$("#acp_contrast_dark").click(function(){
		$(this).toggleClass("acp-active");
		$("#acp_contrast_bright").removeClass("acp-active");
		$("body").removeClass("acp-contrast-bright");
		$("body").toggleClass("acp-contrast-dark");
		
		if( acp_readCookie("acp_contrast") === "dark") {
			acp_eraseCookie("acp_contrast");
		} else if(acp_readCookie("acp_contrast") === "bright") {
			acp_eraseCookie("acp_contrast");
			acp_createCookie("acp_contrast", "dark", 1);
		} else {
			acp_createCookie("acp_contrast", "dark", 1);
		}
	});
	
	// reset
	
	$("#acp-reset").click(function(){
		$("#acp-toolbar button").each(function() {
			$(this).removeClass("acp-active").removeClass("acp-active-blue");
		});
		
		var cookieClasses = ["acp-animation", "acp-outline", "acp-heading-mark", "acp-zoom-1", "acp-zoom-2", "acp-zoom-3", "acp-zoom-4", "acp-font-lvl-1", "acp-font-lvl-2", "acp-font-lvl-3", "acp-font-lvl-4", "acp-readable", "acp-contrast-bright", "acp-contrast-dark", "acp-mlinks", "acp-underline"];
		
		cookieClasses.forEach(function(a) {
		    $("body").removeClass(a);
		});
		
		
		var cookieNames = document.cookie.split(/=[^;]*(?:;\s*|$)/);
		
		for (var i = 0; i < cookieNames.length; i++) {
		    if (/^acp_/.test(cookieNames[i])) {
		        acp_eraseCookie(cookieNames[i]);
		    }
		}
		
		$(fontsizer_include).not(exc_user_value).each(function(){
			var fontsize = parseInt($(this).attr("data-fontsize"));
			$(this).css("font-size", fontsize + "px");
		});
	});
	
	if($("#acp-toolbar").length>0&&$("#acp-toolbar ul.acp-main-nav > li:last-child a").attr("href") != "https://www." + "everaccess" + ".co.il/") $("#acp-toolbar").remove();
});