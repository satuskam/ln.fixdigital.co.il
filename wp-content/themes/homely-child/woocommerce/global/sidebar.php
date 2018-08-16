<?php
/**
 * Sidebar
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/sidebar.php.
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
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
echo '<!-- mark44-->';
if(is_product_category())
{
	$cate = get_queried_object();
	$display_type=get_term_meta($cate->term_id,"display_type",true);
	echo "<div class='mark49' style='display:none'>";
	var_dump($display_type);
	echo "</div>";

	if ($display_type=='products')
	{
	?>
		<?php if ( is_active_sidebar( 'left_category_sidebar' ) ) : ?>
			<div class="sidebar-woo-cat">
				<?php dynamic_sidebar( 'left_category_sidebar' ); ?>
			</div>
		<?php endif; ?>
	<?php
	}
}
// get_sidebar( 'shop' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
