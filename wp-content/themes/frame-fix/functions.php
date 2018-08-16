<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

/*
 * Elementor header
 */
function frame_header_widget(){
    register_sidebar( array(
        'name'          => 'Elementor header',
        'id'            => 'elementor_header_frame',
        'before_widget' => '<div class="elementor_sidebar_wrap">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="elementor-sidebar-title">',
        'after_title'   => '</div>',
    ) );
    
}
add_action( 'widgets_init', 'frame_header_widget' );

/*
 * Elementor footer
 */
function frame_footer_widget(){
    register_sidebar( array(
        'name'          => 'Elementor footer',
        'id'            => 'elementor_footer_frame',
        'before_widget' => '<div class="elementor_sidebar_wrap">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="elementor-sidebar-title">',
        'after_title'   => '</div>',
    ) );
    
}
add_action( 'widgets_init', 'frame_footer_widget' );


/*
 * Add Menu
 */
function register_my_menu() {
  register_nav_menu('primary',__( 'Header Menu' ));
}
add_action( 'init', 'register_my_menu' );

/*
 * Add Theme Style
 */
function add_frame_styles(){
	wp_enqueue_style( 'frame-fix-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'frame-fix-bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css' );
	wp_enqueue_style( 'frame-fix-main', get_template_directory_uri() . '/assets/css/style.min.css' );
}
add_action( 'wp_enqueue_scripts', 'add_frame_styles' );
