jQuery.noConflict();
(function ($) {
    $(function () {
        
        $('.products').on('click', '.productGalleryLink', function(){
            var data = $(this).attr('data-product_gallery_images');
            var links = data.split(' , ');
            var imgsData = [];
            var i = 0;
            
            for (i=0; i<links.length; i++) {
                imgsData.push({src: links[i]});
            }
            
            $.fancybox.open(
                imgsData,
                {
                    loop: true
                }
            );
        });
        
    });
    
    
    
})(jQuery);