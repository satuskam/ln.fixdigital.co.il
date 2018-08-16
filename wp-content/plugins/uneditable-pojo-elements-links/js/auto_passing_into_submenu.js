/*
 *  This script provides auto passing into submenu on Pojo theme cusomization admin page
 */

(function(){
    
    var $ = jQuery;
    var regex = new RegExp( '[\?&]clickOn=([a-z\-_]+)[&]*' );
    var result = (location.href).match(regex);

    if (!(result && result[1])) return;
    
    var $sidebarContent = $('.wp-full-overlay-sidebar-content');
    
    $sidebarContent.hide();

    $(function(){
        
        var selector= result[1];
        var $item = $('#' + selector + ' h3');

        $item.click();

        var $previewBlock = $('#customize-preview');

        var attempts = 0;
        var intervalID = setInterval(function(){
            var $frame = $('iframe', $previewBlock);

            if ($frame.length || attempts++ > 50) {
                clearInterval(intervalID);

                $sidebarContent.show();
            }
        }, 50);

    });
    
}());
