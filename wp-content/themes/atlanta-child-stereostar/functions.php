<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly




function pojo_add_builder_in_posts() {
    add_post_type_support( 'post', array( 'pojo-page-format' ) );
}
add_action( 'init', 'pojo_add_builder_in_posts' );


// Put your custom code here.

add_filter( 'woocommerce_product_add_to_cart_text', 'woo_archive_custom_cart_button_text' );    // 2.1 +
function woo_archive_custom_cart_button_text() {
	return __( ' לפרטים נוספים', 'woocommerce' );
}


remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 40 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 10 );

add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );

// vaa for ategory changes beg
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );

add_action( 'woocommerce_after_shop_loop', 'woocommerce_taxonomy_archive_description', 20 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_product_archive_description', 30 );

// vaa for category changes end

// vaa for thumb changes beg
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail',10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_custom_template_loop_product_thumbnail',10 );
function woocommerce_custom_template_loop_product_thumbnail()
{
	global $product;

	$image_size = apply_filters( 'single_product_archive_thumbnail_size', 'woocommerce_thumbnail' );
	$image_attr=[
	    "title" => $product->get_title(),
	    "alt" => $product->get_title(),
	];
	$result= $product ? $product->get_image( $image_size,$image_attr) : '';

	echo $result;
}
// vaa for thumb changes end

function woo_rename_tabs( $tabs ) {

	$tabs['description']['title'] = __( 'תאור' );		// Rename the description tab
	// $tabs['reviews']['title'] = __( 'Ratings' );				// Rename the reviews tab
	$tabs['additional_information']['title'] = __( 'פרטים נוספים' );	// Rename the additional information tab
	$tabs['additional_information']['callback'] = 'woocommerce_product_additional_information_tab';	// Rename the additional information tab
	$tabs['additional_information']['priority'] = 11;	// Rename the additional information tab

	return $tabs;
}

add_filter( 'woocommerce_product_subcategories_hide_empty', 'hide_empty_categories', 10, 1 );
function hide_empty_categories ( $hide_empty ) {
    $hide_empty  =  FALSE;
    // You can add other logic here too
    return $hide_empty;
}


/*
 *
 * Removes products count after categories name
 *
 */
add_filter( 'woocommerce_subcategory_count_html', 'woo_remove_category_products_count' );

function woo_remove_category_products_count() {
  return;
}

// vaa for alt and title in header
function vaa_get_image_id_from_url($image_url) {
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
