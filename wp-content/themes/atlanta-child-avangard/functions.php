<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function pojo_add_builder_in_posts() {
    add_post_type_support( 'post', array( 'pojo-page-format' ) );
}
add_action( 'init', 'pojo_add_builder_in_posts' );


/**
 * Enqueue scripts
 *
 * @param string $handle Script name
 * @param string $src Script url
 * @param array $deps (optional) Array of script names on which this script depends
 * @param string|bool $ver (optional) Script version (used for cache busting), set to null to disable
 * @param bool $in_footer (optional) Whether to enqueue the script before </head> or before </body>
 */
function avangard_scripts() {
	wp_enqueue_script( 'elevatezoom', get_stylesheet_directory_uri() . '/assets/js/jquery.elevatezoom.js', array( 'jquery' ), false, false );

	wp_enqueue_script( 'slick-slider', get_stylesheet_directory_uri() . '/includes/slick/slick.js',  array( 'jquery' ), false, false );

	wp_enqueue_script( 'elevatezoom-main', get_stylesheet_directory_uri() . '/assets/js/main.js', array( 'jquery' ), false, true );

	wp_enqueue_style( 'avangard-css', get_stylesheet_directory_uri() . '/assets/css/main.css' );

	wp_enqueue_style( 'slick-css', get_stylesheet_directory_uri() . '/includes/slick/slick.css' );

	wp_enqueue_style( 'slick-theme-css', get_stylesheet_directory_uri() . '/includes/slick/slick-theme.css' );
}
add_action( 'wp_enqueue_scripts', 'avangard_scripts' );


function cf7_add_permalink(){
 
    global $post;
    return $product_link = get_permalink( $post->ID);
}
 
add_shortcode('CF7_ADD_POST_ID', 'cf7_add_permalink');


/* delete cart */
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );

/* add title and alt to product images */
add_filter('wp_get_attachment_image_attributes', 'change_attachement_image_attributes', 20, 2);
function change_attachement_image_attributes($attr, $attachment) {
    global $post;
    if ($post->post_type == 'product') {
        $title = $post->post_title;
        $attr['alt'] = $title;
        $attr['title'] = $title;
    }
    return $attr;
}  

add_action( 'dynamic_sidebar_before', 'widget_title_h5_p' );
function widget_title_h5_p( $sidebar_id ) {
 global $wp_registered_sidebars;
 if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) {
    if ( isset($wp_registered_sidebars[$sidebar_id]['before_title']) ) {
      $now = $wp_registered_sidebars[$sidebar_id]['before_title'];
      $now_after = $wp_registered_sidebars[$sidebar_id]['after_title'];
      $h5 = str_ireplace( '<h5', '<p', $now );
      $p = str_ireplace( '</h5', '</p', $now_after );
      $wp_registered_sidebars[$sidebar_id]['before_title'] = $h5;
      $wp_registered_sidebars[$sidebar_id]['after_title'] = $p;
    }
 }
}

