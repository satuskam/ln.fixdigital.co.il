
	var widthSlider = jQuery('.slick-slide').outerHeight();


	jQuery('#zoom-product > .zoom-in').click(function() {

		jQuery('#zoom-product > a.zoom-out').show();

		jQuery('#zoom-product > a.zoom-in').hide();
		
		jQuery('.slick-active > div > a > img').addClass("product_zoom");



		jQuery(".product_zoom").elevateZoom({
		  zoomType: "lens",
		  lensShape: "round",
		  scrollZoom : true,
		  lensSize: 200,
		  containLensZoom: true,
		  constrainSize: widthSlider,
		  constrainType:"height",
		});


		
	});

	jQuery('#zoom-product > a.zoom-out').click(function() {

		jQuery('#zoom-product > a.zoom-in').show();

		jQuery('#zoom-product > a.zoom-out').hide();
		
		jQuery('.slick-active > div > a > img').removeClass("product_zoom");

		jQuery('.zoomContainer').remove();
		
	});

	jQuery('.slick-slider').on({
    beforeChange: function (event, slick, current_slide_index, next_slide_index) {
      	jQuery('.zoomContainer').remove();
      	jQuery('.slick-slide > div > a > img').removeClass("product_zoom");
		jQuery('#zoom-product > a.zoom-out').hide();
		jQuery('#zoom-product > a.zoom-in').show();
    }
	});



	jQuery('.slick-slider').slick({
	  dots: true,
	  infinite: true,
	  speed: 500,
	  cssEase: 'linear',
	  rtl: true
	});

