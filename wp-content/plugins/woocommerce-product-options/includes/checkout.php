<?php

class WooCommerce_Product_Options_Checkout {

    function __construct() {
        add_action( 'woocommerce_new_order_item',
                array( $this, 'woocommerce_new_order_item' ), 10, 3 );
        add_filter( 'woocommerce_order_item_display_meta_value',
              array( $this, 'woocommerce_order_item_display_meta_value' ) );
    }

    function woocommerce_order_item_display_meta_value( $display_value ) {
        if ( strpos( $display_value, '<img' ) !== false && strpos( $display_value,
                        'product-options-upload-image' ) !== false ) {
            $display_value = preg_replace( '/[\W\w]*src="/', '', $display_value );
            $display_value = preg_replace( '/"[\W\w]*/', '', $display_value );
        } elseif ( strpos( $display_value, '<img' ) !== false && strpos( $display_value,
                        'product-options-image-option' ) !== false ) {
            $display_images = explode( '<img', $display_value );
            unset( $display_images[ 0 ] );
            $display_value_array = array();
            foreach ( $display_images as $display_image ) {
                $new_display_value = preg_replace( '/[\W\w]*alt="/', '',
                        $display_image );
                $new_display_value = preg_replace( '/"[\W\w]*/', '',
                        $new_display_value );
                if ( empty( $new_display_value ) ) {
                    $new_display_value = preg_replace( '/[\W\w]*src="/', '',
                            $display_value );
                    $new_display_value = preg_replace( '/"[\W\w]*/', '',
                            $new_display_value );
                }
                $display_value_array[] = $new_display_value;
            }
            $display_value = implode( ', ', $display_value_array );
        }
        return $display_value;
    }

    /**
     * Adds options to order item meta
     */
    function woocommerce_new_order_item( $item_id, $item, $order_id ) {
        if ( !empty( $item->legacy_values[ '_product_options' ] ) ) {
            $product_options = $item->legacy_values[ '_product_options' ];
            foreach ( $product_options as $product_option ) {
                wc_add_order_item_meta( $item_id, $product_option[ 'name' ],
                        $product_option[ 'value' ] );
            }
        }
    }

}

$woocommerce_product_options_checkout = new WooCommerce_Product_Options_Checkout();
?>