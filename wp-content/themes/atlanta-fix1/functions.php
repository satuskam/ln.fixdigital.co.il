<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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
    wp_enqueue_style(
        'atlanta-fix-style2',
        get_template_directory_uri() . '/assets/css/rtl.css'
    );
    wp_enqueue_style(
        'atlanta-fix-style3',
        get_template_directory_uri() . '/assets/css/bootstrap.min.css'
    );
    wp_enqueue_style(
        'atlanta-fix-style1',
        get_template_directory_uri() . '/assets/css/style.css'
    );

}
if( !is_admin() )
{
    add_action( 'wp_enqueue_scripts', 'addCustomStyles' );
}



// function pojo_add_builder_in_posts() {
//     add_post_type_support( 'post', array( 'pojo-page-format' ) );
// }
// add_action( 'init', 'pojo_add_builder_in_posts' );


// Put your custom code here.
