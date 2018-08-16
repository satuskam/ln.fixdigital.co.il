<?php
/**
 * The main WC template file.
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$view = 'archive';
if ( is_singular() )
	$view = 'single';
elseif ( is_search() )
	$view = 'search';

do_action( 'pojo_setup_body_classes', $view, get_post_type(), '' );

get_header();
echo "<!-- vaamark42-->";
 if ( po_breadcrumbs_need_to_show() ) {
 ?>
 <div class="breadprod">
 <?php
	pojo_breadcrumbs();
	if(is_product())
	{
		global $product;
		$prod_cat=$product->get_categories( ', ', ' ' . _n( ' ', '  ', $cat_count, 'woocommerce' ) . ' ', ' ' );
		echo "<span class='bread-titul'>".$prod_cat."</span>";
	}
}
?>
	<div class='clearfix'></div>
</div>
<?php
do_action( 'pojo_get_start_layout', $view, get_post_type(), '' );



woocommerce_content();

do_action( 'pojo_get_end_layout', $view, get_post_type(), '' );

get_footer();
