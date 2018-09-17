<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function pojo_add_builder_in_posts() {
    add_post_type_support( 'post', array( 'pojo-page-format' ) );
}
add_action( 'init', 'pojo_add_builder_in_posts' );

add_action( 'init', 'jk_remove_wc_breadcrumbs' );
function jk_remove_wc_breadcrumbs() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}

require_once get_stylesheet_directory() . '/IngoApp/IngoApp.php';

$ingoThemeApp = new IngoApp();
$ingoThemeApp->init();

add_filter( 'add_to_cart_text', 'woo_custom_single_add_to_cart_text' );                // < 2.1
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_single_add_to_cart_text' );  // 2.1 +
  
function woo_custom_single_add_to_cart_text() {
  
    return __( 'הוסף לרשימת משאלות', 'woocommerce' );
  
}

function ingo_enqueue_styles() {
    
    // enqueue parent styles
    wp_enqueue_style('everest-theme', get_template_directory_uri() .'/style.css');
    
    // enqueue child styles
    wp_enqueue_style('ingo-theme', get_stylesheet_directory_uri() .'/style.css', array('parent-theme'));
    
}
add_action('wp_enqueue_scripts', 'ingo_enqueue_styles');

class WC_Settings_Tab_Custom {
    /**
     * Bootstraps the class and hooks required actions & filters.
     *
     */
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_settings_tab_custom', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_settings_tab_custom', __CLASS__ . '::update_settings' );
    }
    
    
    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_tab_custom'] = __( 'Custom settings', 'woocommerce-settings-tab-custom' );
        return $settings_tabs;
    }
    /**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }
    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public static function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }
    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public static function get_settings() {
        $settings = array(
         
            'btn_cart' => array(
                'name' => __( 'Butoon in cart', 'woocommerce-settings-tab-custom' ),
                'type' => 'text',
                'desc' => __( 'Translate for button in cart', 'woocommerce-settings-tab-custom' ),
                'id'   => 'wc_settings_tab_custom_title'
            ),
           
        );
        return apply_filters( 'wc_settings_tab_custom_settings', $settings );
    }
}
WC_Settings_Tab_Custom::init();


// var_dump(get_option('wc_settings_tab_custom_title'));