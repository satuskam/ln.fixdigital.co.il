<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	// vaa changes beg
	// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
	// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );


remove_theme_support( 'wc-product-gallery-zoom' );
remove_theme_support( 'wc-product-gallery-lightbox' );
remove_theme_support( 'wc-product-gallery-slider' );
	// vaa changes end

function pojo_add_builder_in_posts() {
    add_post_type_support( 'post', array( 'pojo-page-format' ) );
}
add_action( 'init', 'pojo_add_builder_in_posts' );


// Put your custom code here.
