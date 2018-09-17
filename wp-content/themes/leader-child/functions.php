<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function pojo_add_builder_in_posts() {
    add_post_type_support( 'post', array( 'pojo-page-format' ) );
}
add_action( 'init', 'pojo_add_builder_in_posts' );


// Put your custom code here.
function atlanta_child_yaron2_enqueue_styles() {
    wp_enqueue_style( 'yaron2-parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'yaron2-child-aronot-style',  get_stylesheet_directory_uri() . '/style.css', array( 'yaron2-parent-style' ), wp_get_theme()->get('Version') );
}
add_action( 'wp_enqueue_scripts', 'atlanta_child_yaron2_enqueue_styles' );