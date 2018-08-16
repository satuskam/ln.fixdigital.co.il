<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

/*
 * Elementor header
 */
function leader_header_widget(){
    register_sidebar( array(
        'name'          => 'Elementor header',
        'id'            => 'elementor_header_leader',
        'before_widget' => '<div class="elementor_sidebar_wrap">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="elementor-sidebar-title">',
        'after_title'   => '</div>',
    ) );
    
}
add_action( 'widgets_init', 'leader_header_widget' );

/*
 * Elementor header with banner
 */
function leader_header_widget_with_banner(){
    register_sidebar( array(
        'name'          => 'Elementor header with banner',
        'id'            => 'elementor_header_leader_with_banner',
        'before_widget' => '<div class="elementor_sidebar_wrap">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="elementor-sidebar-title">',
        'after_title'   => '</div>',
    ) );
    
}
add_action( 'widgets_init', 'leader_header_widget_with_banner' );

/*
 * Elementor footer
 */
function leader_footer_widget(){
    register_sidebar( array(
        'name'          => 'Elementor footer',
        'id'            => 'elementor_footer_leader',
        'before_widget' => '<div class="elementor_sidebar_wrap">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="elementor-sidebar-title">',
        'after_title'   => '</div>',
    ) );
    
}
add_action( 'widgets_init', 'leader_footer_widget' );


/*
 * Add Menu
 */
function register_leader_menu() {
  register_nav_menu('primary',__( 'Header Menu' ));
}
add_action( 'init', 'register_leader_menu' );

/*
 * Add Theme Style
 */
function add_leader_styles(){
	wp_enqueue_style( 'leader-fix-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'leader-fix-bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css' );
    wp_enqueue_style( 'leader-fix-main', get_template_directory_uri() . '/assets/css/style.min.css' );
}
add_action( 'wp_enqueue_scripts', 'add_leader_styles' );