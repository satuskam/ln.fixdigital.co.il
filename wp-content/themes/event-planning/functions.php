<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * Add Menu
 */
function register_everest_menu() {
  register_nav_menu('primary',__( 'Header Menu' ));
}
add_action( 'init', 'register_everest_menu' );

add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

/*
 * Elementor simple header
 */
function simpleHeaderWithBannerWidgetInit()
{
    register_sidebar( array(
        'name'          => 'Elementor header',
        'id'            => 'elementor_header',
        'before_widget' => '<div class="elementor_sidebar_wrap">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="elementor-sidebar-title">',
        'after_title'   => '</div>',
    ) );
    
}
add_action( 'widgets_init', 'simpleHeaderWithBannerWidgetInit' );


function footerWidgetInit()
{
    register_sidebar( array(
        'name'          => 'Elementor footer',
        'id'            => 'elementor_footer',
        'before_widget' => '<div class="elementor_sidebar_wrap">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="elementor-sidebar-title">',
        'after_title'   => '</div>',
    ) );
    
}
add_action( 'widgets_init', 'footerWidgetInit' );


function   addCustomStyles() {      
//wp_enqueue_style(
//	'bootstrap-css',
//	get_template_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css'
//);

wp_enqueue_style(
	'stream-fix-style',
	get_template_directory_uri() . '/assets/css/style.css'
);


wp_enqueue_style(
	'stream-fix-rtl-style',
	get_template_directory_uri() . '/assets/css/rtl.css'
);
}
add_action( 'wp_enqueue_scripts', 'addCustomStyles' );