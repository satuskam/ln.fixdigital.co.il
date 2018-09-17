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



function vaa_get_image_id_from_url($image_url) {
	global $wpdb;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
        return $attachment[0];
}
function pippin_get_image_id($image_url) {
	global $wpdb;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
        return $attachment[0];
}



function get_image_alt_from_url($image_url) {
	$img_id=vaa_get_image_id_from_url(esc_attr( $image_url ));
	$img_alt = get_post_meta($img_id, '_wp_attachment_image_alt', true);
	return esc_attr($img_alt);
}
function get_image_title_from_url($image_url) {
	$img_id=vaa_get_image_id_from_url(esc_attr( $image_url ));
	$img_title =get_the_title($img_id);
	return esc_attr($img_title);
}

function image_alt_from_url($image_url) {
	$img_id=vaa_get_image_id_from_url(esc_attr( $image_url ));
	$img_alt = get_post_meta($img_id, '_wp_attachment_image_alt', true);
	echo esc_attr($img_alt);
}
function image_title_from_url($image_url) {
	$img_id=vaa_get_image_id_from_url(esc_attr( $image_url ));
	$img_title =get_the_title($img_id);
	echo esc_attr($img_title);
}



