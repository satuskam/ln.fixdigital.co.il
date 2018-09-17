jQuery(function(){
    var $ = jQuery;
    var $menuBar = $('.menuBarToBeSticked');
console.log('vvv');
    if (!$menuBar.length) return;

	var $window = $(window);
	var $wpAdminBar = $('#wpadminbar');
    var wpAdminBarHeight;

    $menuBar.each(function(){
        var $menuBarT = $(this);
        if($menuBarT.is(':hidden'))return;
        var menuBarInitialTopOffset = $menuBarT.offset().top;


        $(window).scroll(function(e){
            var currTopOffset = $window.scrollTop();
            // var menuBarHeight=$menuBarT.height();
            // calculate height of wp-adminbar
            wpAdminBarHeight = 0;
            if ($wpAdminBar.length && $window.width() > 768) {
                wpAdminBarHeight = $wpAdminBar.outerHeight(true);
            }

            if ( (currTopOffset + wpAdminBarHeight) > menuBarInitialTopOffset) {
                if (!$menuBarT.hasClass('sticked'))
                {
                    console.log("height "+$menuBarT.height());
                    console.log($menuBarT);
                    // var barClone=$menuBar.clone();
                    var menuBarHeight=$menuBarT.height();
                    $menuBarT.addClass('sticked').after('<div class="menuplace" ></div>');
                    // $menuBarT.after('<div class="menuplace" ></div>');
                    $menuBarT.next('.menuplace').css('height', $menuBarT.height());
                    console.log("height1 "+$menuBarT.height());
                    // barClone.addClass('menuplace').addClass('sticked').css('position', 'static!important');
                   // $menuBarT.after('<div class="menuplace" style="height:'+menuBarHeight+'px"></div>');
                    // $menuBar.after(barClone);
                    $menuBarT.css({top: wpAdminBarHeight + 'px'});
                }
                return;
            }
             if ( (currTopOffset + wpAdminBarHeight) <= (menuBarInitialTopOffset))
            {
                 if ($menuBarT.hasClass('sticked'))
                 {
                   $menuBarT.css({top:'0px'}).removeClass('sticked').next('.menuplace').remove();
                 }
            }
        });
    });

});


