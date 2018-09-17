<?php
/**
 * The main WC template file.
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$view = 'archive';
if ( is_singular() )
	$view = 'single';
elseif ( is_search() )
	$view = 'search';

do_action( 'pojo_setup_body_classes', $view, get_post_type(), '' );

get_header();



do_action( 'pojo_get_start_layout', $view, get_post_type(), '' );

if ( po_breadcrumbs_need_to_show() ) {
	pojo_breadcrumbs();
}
	// vaa changes

    if ( is_product_category()){
    	$image='';
	    global $wp_query;
	    $cat = $wp_query->get_queried_object();
	    $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
	    $image = wp_get_attachment_url( $thumbnail_id );
	    ?>
	    <style>
	    	h1.page-title
	    	{
	    		background-image:linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ),url('<?php echo $image ?>');
	    	}
	    </style>
	    <?php
	}
	else
	{
		if (is_shop() )
		{
			// $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full')[0];
			$image = wp_get_attachment_image_src( get_post_thumbnail_id(get_option( 'woocommerce_shop_page_id' )), 'full-size');
		}
		?>
		<style>
			h1.page-title
			{
				background-image:linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ),url('<?php echo $image[0] ?>');
				margin-bottom:80px;
			}
		</style>
		<?php
	}

woocommerce_content();
?>

<script>
	// script for band switch
	jQuery('.pwb-filter-products>ul>li input').click(function (e) {
		e.preventDefault();
	});
	jQuery('.pwb-filter-products>ul>li label').click(function (e) {
		e.preventDefault();
	});

	jQuery('.pwb-filter-products>ul').prepend('<li class="showall"> <label> הצג הכל     </label> </li>');


	jQuery( document ).ready(function() {
		setTimeout(function(){
			jQuery('.pwb-filter-products>ul>li input:checked').parent("label").addClass('checkflag');
		},10);

	});

	jQuery('.pwb-filter-products>ul>li').click(function(event) {
		var el=jQuery(this);
		if (el.hasClass('className'))
		{
			jQuery('.pwb-filter-products>ul>li input').prop("checked",false);
			setTimeout(function(){
				jQuery('.pwb-filter-products>button').click();
			},100);
		}
		if (el.find("input").prop("checked"))
		{
			el.find("input").prop("checked",false);
			setTimeout(function(){
				jQuery('.pwb-filter-products>button').click();
			},100);
		}
		else
		{
			jQuery('.pwb-filter-products>ul>li input').prop("checked",false);
			// jQuery('.pwb-filter-products>ul>li label').removeClass('checkflag');

			el.find("input").prop("checked",true);
			// el.find("label").addClass('checkflag');
			setTimeout(function(){
				jQuery('.pwb-filter-products>button').click();
			},100);
		}
	});
</script>
<?php

do_action( 'pojo_get_end_layout', $view, get_post_type(), '' );

get_footer();
