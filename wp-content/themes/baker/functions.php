<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

/*
 * Elementor header
 */
function everest_header_widget(){
    register_sidebar( array(
        'name'          => 'Elementor header',
        'id'            => 'elementor_header_everest',
        'before_widget' => '<div class="elementor_sidebar_wrap">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="elementor-sidebar-title">',
        'after_title'   => '</div>',
    ) );
    
}
add_action( 'widgets_init', 'everest_header_widget' );

/*
 * Elementor header with banner
 */
function everest_header_widget_with_banner(){
    register_sidebar( array(
        'name'          => 'Elementor header with banner',
        'id'            => 'elementor_header_everest_with_banner',
        'before_widget' => '<div class="elementor_sidebar_wrap">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="elementor-sidebar-title">',
        'after_title'   => '</div>',
    ) );
    
}
add_action( 'widgets_init', 'everest_header_widget_with_banner' );

/*
 * Elementor footer
 */
function everest_footer_widget(){
    register_sidebar( array(
        'name'          => 'Elementor footer',
        'id'            => 'elementor_footer_everest',
        'before_widget' => '<div class="elementor_sidebar_wrap">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="elementor-sidebar-title">',
        'after_title'   => '</div>',
    ) );
    
}
add_action( 'widgets_init', 'everest_footer_widget' );


/*
 * Add Menu
 */
function register_everest_menu() {
  register_nav_menu('primary',__( 'Header Menu' ));
}
add_action( 'init', 'register_everest_menu' );

/*
 * Add Theme Style
 */
function add_everest_styles(){
	wp_enqueue_style( 'everest-fix-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'everest-fix-bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css' );
	wp_enqueue_style( 'everest-fix-main', get_template_directory_uri() . '/assets/css/style.min.css' );
}
add_action( 'wp_enqueue_scripts', 'add_everest_styles' );


