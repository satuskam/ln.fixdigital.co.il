<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;

$attachment_ids = $product->get_gallery_image_ids();

if ( $attachment_ids && has_post_thumbnail() ) {
	foreach ( $attachment_ids as $attachment_id ) {
		$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
		$thumbnail       = wp_get_attachment_image_src( $attachment_id, 'woocommerce_thumbnail' );
		$attributes      = array(
			'data-zoom-image'         => $full_size_image[0],
		);

		$html  = '<a data-fancybox="gallery" href="' . esc_url( $full_size_image[0] ) . '">';
		$html .= wp_get_attachment_image( $attachment_id, 'woocommerce_single', false, $attributes );
 		$html .= '</a>';

		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
	}
}