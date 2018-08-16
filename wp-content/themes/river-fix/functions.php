<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

/*
 * Elementor simple header
 */
function headerWidgetInit()
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
add_action( 'widgets_init', 'headerWidgetInit' );


function headerWithSliderWidgetInit()
{
    register_sidebar( array(
        'name'          => 'Elementor header with slider',
        'id'            => 'elementor_header_with_slider',
        'before_widget' => '<div class="elementor_sidebar_wrap">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="elementor-sidebar-title">',
        'after_title'   => '</div>',
    ) );
    
}
add_action( 'widgets_init', 'headerWithSliderWidgetInit' );


function headerWithTitleWidgetInit()
{
    register_sidebar( array(
        'name'          => 'Elementor header with title',
        'id'            => 'elementor_header_with_title',
        'before_widget' => '<div class="elementor_sidebar_wrap">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="elementor-sidebar-title">',
        'after_title'   => '</div>',
    ) );
    
}
add_action( 'widgets_init', 'headerWithTitleWidgetInit' );


function sidebarWidgetInit()
{
    register_sidebar( array(
        'name'          => 'Elementor sidebar',
        'id'            => 'elementor_sidebar',
        'before_widget' => '<div class="elementor_sidebar_wrap">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="elementor-sidebar-title">',
        'after_title'   => '</div>',
    ) );
    
}
add_action( 'widgets_init', 'sidebarWidgetInit' );


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
	'bootstrap-css',
	get_template_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css'
);

wp_enqueue_style(
	'river-fix-style',
	get_template_directory_uri() . '/assets/css/style.css'
);


wp_enqueue_style(
	'river-fix-rtl-style',
	get_template_directory_uri() . '/assets/css/rtl.css'
);
}
add_action( 'wp_enqueue_scripts', 'addCustomStyles' );


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

	return "<h3 class='elementor-heading-title elementor-size-default'>$currTitle</h3>";
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