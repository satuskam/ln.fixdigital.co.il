(function ($, elementor) {

    var onReady = function () {
        elementor.waypoint( $('.elementor-widget-briar-masonry-gallery .gallery-item'), onWayPointGalleryItem)
    },
        onWayPointGalleryItem = function () {
        var   $element = $(this).find('.animated'),
        animation = $element.data('animation');

            $element
            .removeClass('elementor-invisible')
            .addClass( animation );


            console.log($(this));

        };

        $(function () {
            onReady();
        });

})(jQuery, elementorFrontend)