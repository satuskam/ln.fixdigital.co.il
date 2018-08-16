<?php
/**
 * The template for displaying the footer.
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
			</div><!-- #content -->
		</div><!-- .container -->

<?php
// vaa change 2 beg
if (is_product_category())
{
	global $post;
	$prod_terms = get_the_terms( $post->ID, 'product_cat' );
	$product_cat_id = $prod_terms[0]->term_id;
	$product_parent_categories_all_hierachy = get_ancestors( $product_cat_id, 'product_cat' );
	// This cuts the array and extracts the last set in the array
	$last_parent_cat = array_slice($product_parent_categories_all_hierachy, -1, 1, true);
	$woo_up_term = get_term_by( 'id', $last_parent_cat[0], 'product_cat' );
	$product_top_cat_name=$woo_up_term->name;

	$top_cat_thumbnail_id = get_woocommerce_term_meta( $last_parent_cat[0], 'thumbnail_id', true );
	$image = wp_get_attachment_url( $top_cat_thumbnail_id );

	?>
	<img src="<?php echo $image;?>" style="display:none">
	<h2 class="topcatheader" style="background-image:linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ),url('<?php echo $image;?>')">
		<?php if ( is_active_sidebar( 'pojo-sidebar-51' ) ) : ?>
				<?php dynamic_sidebar( 'pojo-sidebar-51' ); ?>
		<?php endif; ?>
	</h2>
	<?php
};

// vaa change 2 end
 ?>

	</div><!-- #primary -->

	<?php
		if ( is_singular( 'post' ) ) :
			comments_template( '', true );
		endif;
	?>
	<?php po_change_loop_to_parent( 'change' ); ?>
	<?php if ( ! pojo_is_blank_page() ) : ?>
		<footer id="footer">
			<?php get_sidebar( 'footer' ); ?>
		</footer>
		<section id="copyright" role="contentinfo">
			<div class="<?php echo WRAP_CLASSES; ?>">
				<div class="pull-left-copyright">
					<?php echo nl2br( pojo_get_option( 'txt_copyright_left' ) ); ?>
				</div>
				<div class="pull-right-copyright">
					<?php echo nl2br( pojo_get_option( 'txt_copyright_right' ) ); ?>
				</div>
			</div><!-- .container -->
		</section>
	<?php endif; // end blank page ?>
	<?php po_change_loop_to_parent(); ?>

	</div><!-- .container-wrapper -->
</div><!-- #container -->
<?php wp_footer(); ?>
</body>
</html>
