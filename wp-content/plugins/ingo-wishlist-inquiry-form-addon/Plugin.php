<?php
/*
	Plugin Name: Ingo Wishlist Inquery POJO Form Addon
	Plugin URI: http://someurl.com/
	Description: Add Inquery POJO form to Ingo theme wishlist
	Version: 0.1.0
	Author: nikoleti
	Author URI: http://someurl.com/
	License:     GPL2
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain: ingo-wishlist-inquiry-form-addon
	Domain Path: /languages
 */


namespace IngoWishlistAddon;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



class Plugin {

    public $version = '0.1.2';

    private $_wishlistProducts = [];

    protected $assetsUrl;

    public function __construct()
    {
        $this->assetsUrl = plugins_url( '/assets', __FILE__ );

        add_action( 'woocommerce_after_cart', [ $this, 'enqueueStyles' ] );
        add_action( 'woocommerce_after_cart', [ $this, 'enqueueScripts' ], 10, 0 );
        
        add_action( 'woocommerce_after_cart', [ $this, 'afterWcCartContent' ], 10, 0 );
        
        $this->initwishlistProductsData();
    }
    
    
    public function initwishlistProductsData()
    {
        global $woocommerce;
        $items = $woocommerce->cart->get_cart();

        foreach($items as $values) {
            
            $_product = $values['data']->post;
            
            $product = wc_get_product( $_product->ID);

            $this->_wishlistProducts[$_product->ID] = [
                'sku' => $product->get_sku(),
                'name' => $product->get_title()
            ];
        }
    }
        
        
    public function changeLinkForWishlistProducts( $product_get_title, $product )
    { 
        $link = get_permalink( $product->id ) ;

        return "<a href='$link' >$product_get_title</a>";
    }
    
        
    public function afterWcCartContent()
    {
        global $post;

        $pojoFormId = trim( get_post_meta($post->ID, 'inquiry_form_id', true) );
        
        $form = get_post($pojoFormId);

        $html = join('', [
            '<div class="inquiryWrapper contaner-flow clearfix">',
                '<div class="formWrapper col-sm-12 pull-right clearfix">',
                    '<h4 >' . __($form->post_title, 'ingo') . '</h4>',
                    do_shortcode("[pojo-form id='$pojoFormId']"),
                '</div>',
            '</div>'
        ]);

        echo $html;
    }


    public function enqueueStyles()
    {
        wp_enqueue_style(
            'ingo-wishlist-inquiry-form-addon-css',
            $this->assetsUrl . '/main.css',
            null,
            $this->version
        );
    }
        
        
    public function enqueueScripts()
    {
        // Register the script
        wp_register_script(
            'ingo-wishlist-inquiry-form-addon-css-js',
            $this->assetsUrl . '/main.js', 
            ['jquery'],
            $this->version,
            true
        );

        wp_localize_script( 'ingo-wishlist-inquiry-form-addon-css-js', 'productsInIngoWishlist', $this->_wishlistProducts );

        wp_enqueue_script(
            'ingo-wishlist-inquiry-form-addon-css-js'
        );
    }
    
    
}

//new Plugin();

add_action('woocommerce_before_cart', function(){
    new Plugin();
    
});
