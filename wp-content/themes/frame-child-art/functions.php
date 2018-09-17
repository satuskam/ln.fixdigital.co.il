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


// change "add to cart" button text
add_filter( 'add_to_cart_text', 'woo_custom_single_add_to_cart_text' );                // < 2.1
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_single_add_to_cart_text' );  // 2.1 +

function woo_custom_single_add_to_cart_text() {

    return __( 'הוסף לרשימת משאלות', 'woocommerce' );

}

add_action('widgets_init', 'topIconsBar');
function topIconsBar(){
	register_sidebar( array(
		'name'          => 'Top icons bar',
		'id'            => "top-icons",
		'description'   => '3 icons in right top position',
		'before_widget' => '<div class="col-lg-12">',
		'after_widget'  => "</div>",
		'before_title'  => '',
		'after_title'   => '',
	) );
}


add_action( 'after_setup_theme', 'themeRegisterSideMenu' );
function themeRegisterSideMenu() {
	register_nav_menu( 'sidemenu', 'Side menu for product categories' );
}

add_filter('woocommerce_checkout_fields', function($fields) {
	unset($fields['billing']['billing_country']);
	unset($fields['billing']['billing_state']);
	return $fields;
});
//Enqueue the Dashicons script
add_action( 'wp_enqueue_scripts', 'load_dashicons_front_end' );
function load_dashicons_front_end() {
wp_enqueue_style( 'dashicons' );
}

// add custom css for admin panel
add_action('admin_head', 'my_admin_stylesheet');
function my_admin_stylesheet(){
echo '<link href="'.get_bloginfo( 'stylesheet_directory' ).'/admin_css/vaa_admin.css" rel="stylesheet" type="text/css">';
}


//custom field old_price for the product

add_action( 'woocommerce_product_options_advanced', 'wc_custom_add_old_price' );
function wc_custom_add_old_price() {
    // Print a custom text field
    woocommerce_wp_text_input( array(
        'id' => '_old_price',
        'label' => 'Old price',
        'description' => 'This is field for old price of the product',
        'desc_tip' => 'true',
        'placeholder' => '0',
        'type' =>'number'
    ) );
}

add_action( 'woocommerce_process_product_meta', 'wc_custom_save_old_price' );
function wc_custom_save_old_price( $post_id ) {
    if ( ! empty( $_POST['_old_price'] ) ) {
        update_post_meta( $post_id, '_old_price', esc_attr( $_POST['_old_price'] ) );
    }
}

//custom widget areas for general information and socials
function general1_widgets_init() {
    register_sidebar( array(
        'name' => __( 'General product text 1', 'generaltext' ),
        'id' => 'general-text1',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ) );
}
add_action( 'widgets_init', 'general1_widgets_init' );

function general2_widgets_init() {
    register_sidebar( array(
        'name' => __( 'General product text 2', 'generaltext' ),
        'id' => 'general-text2',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ) );
}
add_action( 'widgets_init', 'general2_widgets_init' );

function product_socials_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Product socials', 'productsocials' ),
        'id' => 'product-soc',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ) );
}
add_action( 'widgets_init', 'product_socials_widgets_init' );
