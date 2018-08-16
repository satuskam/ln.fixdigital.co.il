<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
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
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */

	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		/**
		 * woocommerce_before_single_product_summary hook.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
	?>

	<div class="summary entry-summary test42">
	<!-- vaamark 1 -->

		<?php
			/**
			 * woocommerce_single_product_summary hook.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 * @hooked WC_Structured_Data::generate_product_data() - 60
			 */
			do_action( 'woocommerce_single_product_summary' );
		?>
		<!-- vaamark 2 -->
	</div><!-- .summary -->

	<?php
		/**
		 * woocommerce_after_single_product_summary hook.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		//do_action( 'woocommerce_after_single_product_summary' );
	?>

</div><!-- #product-<?php the_ID(); ?> -->
<script>
	console.log("test44");
	var variblock=jQuery(".variations .label").first();
	variblock.detach()
	jQuery(".variations tbody").prepend('<tr class="first-vari-row"></tr>');
	jQuery(".variations .first-vari-row").prepend(variblock);
	jQuery( ".variations_form" ).on( "woocommerce_variation_select_change", function () {
    	console.log("vari change");

	} );
	jQuery('.chleft').click(function(event) {
		if(jQuery(".tawcvs-swatches .swatch-color.selected").next('.swatch-color').length>0)
		{
			jQuery(".tawcvs-swatches .swatch-color.selected").next('.swatch-color').trigger('click');
		}
		else
		{
			jQuery(".tawcvs-swatches .swatch-color:first-of-type").trigger('click');
		}
	});
	jQuery('.chright').click(function(event) {
		if(jQuery(".tawcvs-swatches .swatch-color.selected").prev('.swatch-color').length>0)
		{
			jQuery(".tawcvs-swatches .swatch-color.selected").prev('.swatch-color').trigger('click');
		}
		else
		{
			jQuery(".tawcvs-swatches .swatch-color:last-of-type").trigger('click');
		}
	});
	jQuery("#id-text-url").val(jQuery(location).attr('href'));

</script>
	<div class="clearfix"></div>
<?php //do_action( 'woocommerce_after_single_product' ); ?>
