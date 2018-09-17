<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );
function theme_name_scripts() {
    wp_enqueue_style( 'style-name', get_stylesheet_uri() );
}


// add_action( 'after_setup_theme', 'lavish_child_remove_woocommerce_support', 20 );
add_action( 'template_redirect', 'lavish_child_remove_woocommerce_support', 20 );
function lavish_child_remove_woocommerce_support() {
    remove_theme_support( 'wc-product-gallery-zoom' );
    remove_theme_support( 'wc-product-gallery-lightbox' );
    remove_theme_support( 'wc-product-gallery-slider' );
}

// remove_theme_support( 'wc-product-gallery-zoom' );
// remove_theme_support( 'wc-product-gallery-lightbox' );
// remove_theme_support( 'wc-product-gallery-slider' );

add_action( 'widgets_init', function (){
            register_sidebar( array(
                'name'          => 'single product form',
                'id'            => "single_product_form",
                'description'   => 'Single product contact area',
                'class'         => '',
                'before_widget' => '',
                'after_widget'  => "",
                'before_title'  => '',
                'after_title'   => "",
            ) );
        } );




