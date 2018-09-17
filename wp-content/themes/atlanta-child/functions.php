<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function pojo_add_builder_in_posts() {
    add_post_type_support( 'post', array( 'pojo-page-format' ) );
}
add_action( 'init', 'pojo_add_builder_in_posts' );


// Put your custom code here.

add_action( 'dynamic_sidebar_before', 'widget_title_h5_p' );
function widget_title_h5_p( $sidebar_id ) {
 global $wp_registered_sidebars;
 if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) {
    if ( isset($wp_registered_sidebars[$sidebar_id]['before_title']) ) {
      $now = $wp_registered_sidebars[$sidebar_id]['before_title'];
      $now_after = $wp_registered_sidebars[$sidebar_id]['after_title'];
      $h5 = str_ireplace( '<h5', '<p', $now );
      $p = str_ireplace( '</h5', '</p', $now_after );
      $wp_registered_sidebars[$sidebar_id]['before_title'] = $h5;
      $wp_registered_sidebars[$sidebar_id]['after_title'] = $p;
    }
 }
}

