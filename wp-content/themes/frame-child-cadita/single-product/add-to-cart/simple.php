<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
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
	exit;
}

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product );

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart" method="post" enctype='multipart/form-data'>
		<?php
			/**
			 * @since 2.1.0.
			 */
			do_action( 'woocommerce_before_add_to_cart_button' );

			/**
			 * @since 3.0.0.
			 */
			do_action( 'woocommerce_before_add_to_cart_quantity' );
                        
                        
                        $productInCartQuantity = 0;
                        foreach( WC()->cart->get_cart() as $cart_item ){
                            if ($product->get_id() === $cart_item['product_id']) {
                                $productInCartQuantity = $cart_item['quantity'];
                                break;
                            }
                        }

			woocommerce_quantity_input( array(
                            'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
                            'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
                            'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : $product->get_min_purchase_quantity(),
			) );
                        

			/**
			 * @since 3.0.0.
			 */
			do_action( 'woocommerce_after_add_to_cart_quantity' );
		?>

		<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

                
                <?php if ($productInCartQuantity) : ?>
                    <div class="wishlistInfo">
                        <?php 
                    	$posts = get_posts(array(
            				'name' => 'wishlist',
            				'posts_per_page' => 1,
    					));
                            //$cartUrl = get_permalink( $posts[0]->post_ID );//WC_Cart::get_cart_url();
                        	
                    		echo '<a href="'.get_permalink( get_page_by_path( 'wishlist' )->ID ).'">המוצר נוסף לרשימת משאלות שלך</a>';
                    
                            /*echo sprintf (
                                __('Your %s wishlist %s already has %s%d%s items of this product'),
                                "<a href='$cartUrl->post_name'>",
                                '</a>',
                                '<strong>',
                                $productInCartQuantity,
                                '</strong>'
                            );*/
                        ?>
                    </div>
                <?php endif; ?>
                
                
		<?php
			/**
			 * @since 2.1.0.
			 */
			do_action( 'woocommerce_after_add_to_cart_button' );
		?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
