<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * Add Menu
 */
function register_firma_menu() {
  register_nav_menu('primary',__( 'Header Menu' ));
}
add_action( 'init', 'register_firma_menu' );

/**
 * Elementor header with banner
 *
 */
function TitaniumHeaderWithBannerWidgetInit(){
    register_sidebar( array(
        'name'          => 'Elementor header',
        'id'            => 'elementor_header_with_banner',
        'before_widget' => '<div class="elementor_sidebar_wrap">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="elementor-sidebar-title">',
        'after_title'   => '</div>',
    ) );

}
add_action( 'widgets_init', 'TitaniumHeaderWithBannerWidgetInit' );



function TitaniumfooterWidgetInit()
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
add_action( 'widgets_init', 'TitaniumfooterWidgetInit' );



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
    if (is_single())
    {
        $postname = get_the_title();
        $currTitle=$postname;
    }

	return "<span>$currTitle</span>";
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

add_theme_support( 'post-thumbnails' );

add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
