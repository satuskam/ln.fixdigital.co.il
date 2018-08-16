<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function register_my_menu() {
  register_nav_menu('header-menu',__( 'Header Menu' ));
}
add_action( 'init', 'register_my_menu' );

add_theme_support('menus');
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


/**
 * Elementor header with banner
 *
 */
function headerWithBannerWidgetInit()
{
    register_sidebar( array(
        'name'          => 'Elementor header with banner',
        'id'            => 'elementor_header_with_banner',
        'before_widget' => '<div class="elementor_sidebar_wrap">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="elementor-sidebar-title">',
        'after_title'   => '</div>',
    ) );
    
}
add_action( 'widgets_init', 'headerWithBannerWidgetInit' );



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
wp_enqueue_style(
	'berlin-fix-style',
	get_template_directory_uri() . '/assets/css/style.css'
);


wp_enqueue_style(
	'berlin-fix-style',
	get_template_directory_uri() . '/assets/css/rtl.css'
);
}
add_action( 'wp_enqueue_scripts', 'addCustomStyles' );



function my_scripts_method() {
    wp_enqueue_script( 'child-script', get_stylesheet_directory_uri() . '/assets/js/script.js', array('jquery') );
}
add_action( 'wp_enqueue_scripts', 'my_scripts_method' );



function addCurrentPageTitleShortcode()
{
	$currTitle = '';

	$pagename = get_query_var('pagename');

	if ($pagename) {  
		$page = get_page_by_slug($pagename);
    } else {
    	$frontpageId = get_option( 'page_on_front' );
    	$page = get_post($frontpageId);
    }

	if ($page && $page->post_title) {
    	$currTitle = $page->post_title;
    }

	return "<h1 class='elementor-heading-title elementor-size-default'>$currTitle</h1>";
}
add_shortcode('current-page-title', 'addCurrentPageTitleShortcode');


function get_page_by_slug($page_slug, $output = OBJECT, $post_type = 'page' )
{ 
  	global $wpdb; 
 	$page = $wpdb->get_var(
    	$wpdb->prepare(
            "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s AND post_status = 'publish'", $page_slug, $post_type 		  )
    );

   	if ( $page ) return get_post($page, $output);

    return null; 
}

// remove_action('shutdown', 'wp_ob_end_flush_all', 1);

