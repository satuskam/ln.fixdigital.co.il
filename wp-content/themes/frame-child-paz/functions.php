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


// change "add to czrt" button text
/*add_filter( 'add_to_cart_text', 'woo_custom_product_add_to_cart_text' );            // < 2.1
add_filter( 'woocommerce_product_add_to_cart_text', 'woo_custom_product_add_to_cart_text' );  // 2.1 +

function woo_custom_product_add_to_cart_text() {

    return __( 'הוסף לרשימת משאלות', 'woocommerce' );

}*/

add_filter( 'add_to_cart_text', 'woo_custom_single_add_to_cart_text' );                // < 2.1
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_single_add_to_cart_text' );  // 2.1 +

function woo_custom_single_add_to_cart_text() {

    return __( 'הוסף לרשימת משאלות', 'woocommerce' );

}
// vaa changes 2018.07.012
function remove_pojo_theme_setup() {
	remove_theme_support( 'pojo-wc-menu-cart' );
	remove_theme_support( 'pojo-menu-search' );
}
add_action( 'after_setup_theme', 'remove_pojo_theme_setup', 30 );

function fun_subcats_in_cat()
{
	$catid=get_queried_object_id();
	woocommerce_subcats_from_parentcat_by_ID(get_queried_object_id($catid));
	echo "<h2 class='woo-cat-title'>";
	single_cat_title();
	echo"</h2>";

}

function add_subcats_in_cat() {
	add_action( 'woocommerce_before_shop_loop', 'fun_subcats_in_cat', 10 );
}
add_action( 'after_setup_theme', 'add_subcats_in_cat');

function woocommerce_subcats_from_parentcat_by_ID($parent_cat_ID) {

   $args = array(

       'hierarchical' => 1,

       'show_option_none' => '',

       'hide_empty' => 0,

       'parent' => $parent_cat_ID,

     'taxonomy' => 'product_cat'

   );

$subcats = get_categories($args);


echo '<div class="wooc_sclist">';

foreach ($subcats as $sc) {

       $link = get_term_link( $sc->slug, $sc->taxonomy );

echo '<div class="sclistel"><a href="'. $link .'">'.$sc->name.'</a></div>';

     }

echo '</div>';

}

 // exclude subcategory products from category listing page

// function exclude_product_cat_children($wp_query) {
// if ( isset ( $wp_query->query_vars['product_cat'] ) && $wp_query->is_main_query()) {
//     $wp_query->set('tax_query', array(
//                                     array (
//                                         'taxonomy' => 'product_cat',
//                                         'field' => 'slug',
//                                         'terms' => $wp_query->query_vars['product_cat'],
//                                         'include_children' => false
//                                     )
//                                  )
//     );
//   }
// }
// add_filter('pre_get_posts', 'exclude_product_cat_children');
