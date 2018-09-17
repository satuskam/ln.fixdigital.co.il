<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

// remove 'last' and 'first' classes from list of products
$postClass = get_post_class();
$exclude = ['last', 'first'];
$postClass = array_diff($postClass, $exclude);
$postClass = "class='" . join(' ', $postClass) . "'";

?>
<li <?= $postClass ?> >
	<div class="inbox">

		<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

		<div class="image-link">
			<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
                        remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
			do_action( 'woocommerce_before_shop_loop_item_title' );
			?>
			<a href="<?php the_permalink(); ?>"><?php woocommerce_template_loop_product_thumbnail(); ?></a>
			<a  href="<?php the_permalink(); ?>" class="overlay-image"></a>
                        
			<div class="overlay-title">
                            <?php 
                                global $product;

                                $attachment_ids = $product->get_gallery_attachment_ids();
                                foreach( $attachment_ids as $attachment_id ) {
                                  $imagesLinks[] = wp_get_attachment_url( $attachment_id );
                                }
                            ?>

                            <a href="#" class="productGalleryLink button" data-product_gallery_images="<?= join(' , ', $imagesLinks );  ?>">
                                <svg class="icon-search-plus" viewBox="0 0 512 512">
                                    <path d="m311 229l0 18c0 2-1 4-3 6-2 2-4 3-6 3l-64 0 0 64c0 2-1 5-3 6-2 2-4 3-6 3l-19 0c-2 0-4-1-6-3-2-1-3-4-3-6l0-64-64 0c-2 0-4-1-6-3-2-2-3-4-3-6l0-18c0-3 1-5 3-7 2-2 4-3 6-3l64 0 0-64c0-2 1-4 3-6 2-2 4-3 6-3l19 0c2 0 4 1 6 3 2 2 3 4 3 6l0 64 64 0c2 0 4 1 6 3 2 2 3 4 3 7z m36 9c0-36-12-66-37-91-25-25-55-37-91-37-35 0-65 12-90 37-25 25-38 55-38 91 0 35 13 65 38 90 25 25 55 38 90 38 36 0 66-13 91-38 25-25 37-55 37-90z m147 237c0 11-4 19-11 26-7 7-16 11-26 11-10 0-19-4-26-11l-98-98c-34 24-72 36-114 36-27 0-53-5-78-16-25-11-46-25-64-43-18-18-32-39-43-64-10-25-16-51-16-78 0-28 6-54 16-78 11-25 25-47 43-65 18-18 39-32 64-43 25-10 51-15 78-15 28 0 54 5 79 15 24 11 46 25 64 43 18 18 32 40 43 65 10 24 16 50 16 78 0 42-12 80-36 114l98 98c7 7 11 15 11 25z"></path>
                                </svg>
                            </a>
                            
                            <?php //woocommerce_template_loop_add_to_cart(); ?>
            	<a href="<?= get_permalink(); ?>" class="button product_type_simple ajax_add_to_cart">קרא עוד</a>
                            
			</div>

		</div>

		<a class="caption" href="<?php the_permalink(); ?>">
			<?php
			/**
			 * woocommerce_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_product_title - 10
			 */
			do_action( 'woocommerce_shop_loop_item_title' );
			
			/**
			 * woocommerce_after_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
                        
                        remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
			do_action( 'woocommerce_after_shop_loop_item_title' );
			?>

			<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>

		</a>
            
                

	</div>
    
        <div class="shortDescr"><?php the_excerpt(); ?></div>
</li>
