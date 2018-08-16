<?php

add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

function register_my_menu() {
  register_nav_menu('header-menu',__( 'Header Menu' ));
}
add_action( 'init', 'register_my_menu' );

/**
 * Elementor sidebar
 *
 */
function esidebar_widgets_init() {
    
    register_sidebar( array(
        'name'          => 'Elementor sidebar',
        'id'            => 'elementor_sidebar',
        'before_widget' => '<div class="elementor_sidebar_wrap">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="elementor-sidebar-title">',
        'after_title'   => '</div>',
    ) );
    
}
add_action( 'widgets_init', 'esidebar_widgets_init' );


function   addCustomStyles() {      
wp_enqueue_style(
	'bootstrap-css',
	get_template_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css'
);

wp_enqueue_style(
	'superstar-fix-style',
	get_template_directory_uri() . '/assets/css/style.css'
);


wp_enqueue_style(
	'superstar-fix-rtl-style',
	get_template_directory_uri() . '/assets/css/rtl.css'
);
}
add_action( 'wp_enqueue_scripts', 'addCustomStyles' );


function my_scripts_method() {
    wp_enqueue_script( 'child-script', get_stylesheet_directory_uri() . '/assets/js/script.js', array('jquery') );
}
add_action( 'wp_enqueue_scripts', 'my_scripts_method' );
