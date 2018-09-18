<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * Elementor simple header
 */

// Put your custom code here.

function   addCustomStyles1() {
    wp_enqueue_style(
        'atlanta-fix-style4',
        get_stylesheet_directory_uri() . '/assets/css/style.css'
    );

}
if( !is_admin() )
{
    add_action( 'wp_enqueue_scripts', 'addCustomStyles1' );
}

add_action( 'after_setup_theme', 'woocommerce_support' );
add_theme_support( 'wc-product-gallery-zoom' );
add_theme_support( 'wc-product-gallery-lightbox' );
//add_theme_support( 'wc-product-gallery-slider' ); /*default slider for single product*/

function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
/* Load Scripts */
function addCustomScripts()
{
  wp_deregister_script( 'jquery' );
  wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', array(), NULL, false);
  wp_enqueue_script( 'jquery' );
  wp_enqueue_script('common-js',  get_stylesheet_directory_uri() . '/assets/js/common.js', array('jquery'), NULL, true); /*custom slider for single product*/
}
add_action('wp_enqueue_scripts', 'addCustomScripts', 20); /*it should be 20th for custom slider normal work*/

add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function( $size ) {
    return array(
        'width'  => 100,
        'height' => 150,
        'crop'   => 0,
    );
} );

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
// If need Gallery_thumbnail to WP Thumbnails Size
/*
add_filter( 'woocommerce_gallery_thumbnail_size', 'custom_woocommerce_gallery_thumbnail_size' );
function custom_woocommerce_gallery_thumbnail_size() {
    return 'thumbnail';
}
*/


remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_taxonomy_archive_description', 20 );

// for thumbnail change
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);


if ( ! function_exists( 'woocommerce_template_loop_product_thumbnail' ) ) {
    function woocommerce_template_loop_product_thumbnail() {
        echo woocommerce_get_product_thumbnail();
    }
}
if ( ! function_exists( 'woocommerce_get_product_thumbnail' ) ) {
    function woocommerce_get_product_thumbnail( $size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0  ) {
        global $post, $woocommerce, $product;;
        $attr = array(
          'alt'   => trim(strip_tags(Get_the_title())),
        );
        if ( has_post_thumbnail() ) {
            $output .= get_the_post_thumbnail( $post->ID, $size, $attr );
        }
        return $output;
    }
}
