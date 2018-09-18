// thumbs animation with the delay
var $time = 200;
function addAnimClass(i) {
  setTimeout(function() { $('.woocommerce-product-gallery__wrapper div').eq(i).addClass('animate'); }, $time);
};

jQuery( document ).ready( function( $ ) {
		// thumbs animation with the delay
		for (var i = 0; i <= $('.woocommerce-product-gallery__wrapper div').length; i++){
  			$time = $time + 200;
  			addAnimClass(i);
		};
		// main image animation from thumbs
		var $first = $( '.woocommerce-product-gallery__image:first' );
		$( '.woocommerce-product-gallery__image:not(:first)' ).mouseover(function() {	 
		 	var $link = $(this).children().children().attr("data-src");
		 		$toolt = $(this).children();		 	 	
		 	 	$thumbsImg = $(this);	

		 	$first.stop().animate({
          		opacity: 0
      	 	}, 300, function() {
         		$first.empty();
         		$thumbsImg.children().clone().appendTo(".woocommerce-product-gallery__image:first");
         		$(".woocommerce-product-gallery__image:first .tooltips").remove();
		 		$(".woocommerce-product-gallery__image:first a img").attr("src", $link);
		 		$(".woocommerce-product-gallery__image:first a img").removeAttr("sizes");
          		$first.animate({opacity:1}, 300);
       		});  	
	});
	// tooltips generating from title
	$('.woocommerce-product-gallery__image:not(:first)').each(function(){
			var $titleThumb = $(this).children().children().attr("title");
			$(this).html('<span class="tooltips"> ' + $titleThumb + '</span>' + $(this).html());
		});
	$( '.woocommerce-product-gallery__image:not(:first)' ).hover(function() {	
       		$(this).children('.tooltips').toggleClass("tooltipsVisible"); 
       	});
});