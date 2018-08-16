jQuery(function(){
    var $ = jQuery;
    var $menuBar = $('.menuBarToBeSticked');
console.log('vvv');    
    if (!$menuBar.length) return;
     
	var $window = $(window);
	var $wpAdminBar = $('#wpadminbar');
    var wpAdminBarHeight;
    
    $menuBar.each(function(){
        var $menuBar = $(this);
        var menuBarInitialTopOffset = $menuBar.offset().top;

        $(window).scroll(function(e){
            var currTopOffset = $window.scrollTop();

            // calculate height of wp-adminbar
            wpAdminBarHeight = 0;
            if ($wpAdminBar.length && $window.width() > 768) {
                wpAdminBarHeight = $wpAdminBar.outerHeight(true);
            }

            if ( (currTopOffset + wpAdminBarHeight) > menuBarInitialTopOffset) {
                $menuBar.addClass('sticked');
                $menuBar.css({top: wpAdminBarHeight + 'px'});
            } else {
                $menuBar.removeClass('sticked');
                $menuBar.css({top: '0px'});
            }
        });
    });
        
});


