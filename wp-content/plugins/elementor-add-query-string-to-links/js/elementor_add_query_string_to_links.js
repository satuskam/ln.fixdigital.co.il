/*
 *  Provide walking between pages in Elementor edit mode
 */

var PluginAddQueryStringToElementorLinks = {
    
    init: function(){
        var $ = jQuery;
        var host = location.host;
        
        $('iframe#elementor-preview-iframe').bind('load',function(){

            var $links = $(this).contents().find('a[href*="' + host + '"]');
                
            $links.click(function(){
                var href = $(this).attr('href');
                
                if (href.indexOf('?') === -1) {
                    href = href + '?elementor';
                } else {
                    href = href + '&elementor';
                }
                
                location.href = href;
            });
        });
    }
};



jQuery(function(){
    PluginAddQueryStringToElementorLinks.init();
});



        


