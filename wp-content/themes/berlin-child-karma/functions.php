<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function pojo_add_builder_in_posts() {
    add_post_type_support( 'post', array( 'pojo-page-format' ) );
}
add_action( 'init', 'pojo_add_builder_in_posts' );


// Put your custom code here.

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

// add_filter( 'woocommerce_single_product_image_gallery_classes', 'bbloomer_3_columns_product_gallery' );

// function bbloomer_3_columns_product_gallery( $wrapper_classes ) {
// 	$columns = 3; // change this to 2, 3, 5, etc. Default is 4.
// 	$wrapper_classes[2] = 'woocommerce-product-gallery--columns-' . absint( $columns );
// 	return $wrapper_classes;
// }

add_filter( 'woocommerce_product_thumbnails_columns', create_function( '', 'return 3;' ) );
/**
 * WooCommerce Extra Feature
 * --------------------------
 *
 * Change number of related products on product page
 * Set your own value for 'posts_per_page'
 *
 */
function woo_related_products_limit() {
  global $product;

	$args['posts_per_page'] = 5;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args' );
function jk_related_products_args( $args ) {
	$args['posts_per_page'] = 5;
	$args['columns'] = 5;
	return $args;
}



// Change number or products per row to 3
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 3; // 3 products per row
	}
}


add_action( 'after_setup_theme', 'wpdocs_theme_setup' );
function wpdocs_theme_setup() {
	add_image_size( 'category-thumb1', 500, 500,true);
}

