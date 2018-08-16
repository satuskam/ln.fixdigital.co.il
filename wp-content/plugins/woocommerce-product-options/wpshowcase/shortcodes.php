<?php

class WPShowCase_Shortcodes {

    function __construct() {
        add_shortcode( 'wpshowcase_accordion',
                array( $this, 'wpshowcase_accordion' ) );
        add_shortcode( 'wpshowcase_accordion_element',
                array( $this, 'wpshowcase_accordion_element' ) );
        add_shortcode( 'wpshowcase_accordion_header',
                array( $this, 'wpshowcase_accordion_header' ) );
        add_shortcode( 'wpshowcase_accordion_content',
                array( $this, 'wpshowcase_accordion_content' ) );
    }

    function wpshowcase_accordion( $atts, $content ) {
        $this->enqueue_scripts();
        return '<div class="wpshowcase-accordion">' . do_shortcode( $content ) .
                '</div>';
    }

    function wpshowcase_accordion_element( $atts, $content ) {
        return '<div class="wpshowcase-accordion-element">' . do_shortcode(
                        $content ) . '</div>';
    }

    function wpshowcase_accordion_header( $atts, $content ) {
        return '<h2 class="wpshowcase-accordion-header">'
                . '<input type="hidden" class="wpshowcase-accordion-header-status" value="closed" />'
                . '<span class="wpshowcase-accordion-header-expander">'
                . __( '+', 'woocommerce-product-options' ) . '</span>'
                .
                do_shortcode( $content ) . '</h2>';
    }

    function wpshowcase_accordion_content( $atts, $content ) {
        return '<div class="wpshowcase-accordion-content">'
                . do_shortcode( $content ) . '</div>';
    }

    function enqueue_scripts() {
        wp_enqueue_script( 'wpshowcase-shortcodes',
                plugins_url( 'shortcodes.js', __FILE__ ) );
        wp_localize_script(
                'wpshowcase-shortcodes', 'wpshowcase_shortcodes_settings',
                array(
            'plus' => __( '+', 'woocommerce-product-options' ),
            'minus' => __( '-', 'woocommerce-product-options' ),
        ) );
        wp_enqueue_style( 'wpshowcase-shortcodes-css',
                plugins_url( 'shortcodes.css', __FILE__ ) );
    }

}

$wpshowcase_shortcodes = new WPShowCase_Shortcodes();
