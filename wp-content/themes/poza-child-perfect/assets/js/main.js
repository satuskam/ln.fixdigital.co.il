jQuery( document ).ready( function(){
    jQuery('#menu-main-1 > li.menu-item-has-children > a').click(function (e) {
       jQuery(this).parent().find(".sub-menu").toggle();
    });


    jQuery('#menu-main-3 > li.menu-item-has-children > a').click(function (e) {
       jQuery(this).parent().find(".sub-menu").toggle();
    });
} );