<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function pojo_add_builder_in_posts() {
    add_post_type_support( 'post', array( 'pojo-page-format' ) );
}
add_action( 'init', 'pojo_add_builder_in_posts' );


// Put your custom code here.
function perfectline_enqueue_scripts() {
    wp_enqueue_script( 'perfectline-main', get_stylesheet_directory_uri() . '/assets/js/main.js', array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'perfectline_enqueue_scripts' );

function my_child_wc_support() {
  add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'my_child_wc_support' );

remove_action( 'woocommerce_after_single_product_summary','woocommerce_output_product_data_tabs',10 );
remove_action( 'woocommerce_after_single_product_summary','woocommerce_upsell_display',15 );
remove_action( 'woocommerce_after_single_product_summary','woocommerce_output_related_products',20 );

remove_action( 'woocommerce_single_product_summary','woocommerce_template_single_price',10 );

remove_action( 'woocommerce_single_product_summary','woocommerce_template_single_excerpt',20 );
add_action( 'woocommerce_single_product_summary','woocommerce_template_single_excerpt',70 );

add_action( 'woocommerce_single_product_summary','vaa_sec_descr',60 );


remove_action( 'woocommerce_single_product_summary','woocommerce_template_single_meta',40 );
add_action( 'woocommerce_single_product_summary','woocommerce_template_single_meta',10 );



remove_action( 'woocommerce_single_variation','woocommerce_single_variation_add_to_cart_button',20 );


// Disable the gallery zoom on single products
// =============================================================================
add_action( 'after_setup_theme', 'vaa_custom_things', 100 );

function vaa_sec_descr() {
	echo "<div class='sec_attr_titul'>מפרט טכני</div>";
	echo "<div class='sec_attr'>";
	$big_string=get_field('second_description');

	$delimiter="</div><div class='sec_attr_item'>";
	$delimiter1="</span><span>";
	$exploded=explode(',',$big_string);

	for ($i=0;$i<count($exploded);$i++)
	{
		$exploded[$i]='<span class="sec_attr_item_titul">'.implode($delimiter1,explode(':',$exploded[$i]))."</span>";
	}
	$end_string="<div class='sec_attr_item'>".implode($delimiter,$exploded)."</div>";
	echo $end_string;
	echo "</div>";
}


function vaa_custom_things() {
	remove_theme_support( 'wc-product-gallery-zoom' );
	add_action( 'woocommerce_single_product_summary','vaa_prod_attr',15 );
}
function vaa_prod_attr()
{
	global $product;
	wc_display_product_attributes($product);
}
// =============================================================================

add_action( 'widgets_init', function (){
			register_sidebar( array(
				'name'          => 'single product contact area',
				'id'            => "single_product_contact",
				'description'   => 'widget area for woocommerce single product contact form',
				'class'         => '',
				'before_widget' => '',
				'after_widget'  => "",
				'before_title'  => '<h3>',
				'after_title'   => "</h3>\n",
			) );
		} );

