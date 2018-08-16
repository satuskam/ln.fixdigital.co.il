<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
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
 * @version     3.3.0
 */
?>
<!-- mark loop start -->
<?php
	if (is_product_category())
	{
		global $post;
		$prod_terms = get_the_terms( $post->ID, 'product_cat' );
		$product_cat_id = $prod_terms[0]->term_id;
		$product_parent_categories_all_hierachy = get_ancestors( $product_cat_id, 'product_cat' );
		// This cuts the array and extracts the last set in the array
		$last_parent_cat = array_slice($product_parent_categories_all_hierachy, -1, 1, true);
		$woo_up_term = get_term_by( 'id', $last_parent_cat[0], 'product_cat' );

		$prod_cat_link=get_term_link($woo_up_term);
		$prod_cat_name=$prod_terms[0]->name;
?>
		<div class="prodheader">
			<h2 class="prodcattitul animated fadeIn"><?php echo $prod_cat_name;?></h2>
			<a class="prodtitbut animated fadeIn" href="<?php echo $prod_cat_link; ?>" role="link">
				<span class="">
					<span class="">
						<i class="fa fa-mail-forward" aria-hidden="true"></i>
					</span>
					<span class="">חזרה לקטלוג</span>
				</span>
			</a>
		</div>
<?php
	}
	// vaa change end
 ?>


<ul class="products columns-<?php echo esc_attr( wc_get_loop_prop( 'columns' ) ); ?>">
