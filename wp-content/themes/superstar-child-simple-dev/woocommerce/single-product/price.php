<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

?>
<table class="product-header text-red">
	<tr>
		<td><?= the_title();?></td>
		<td style="text-align: left"><?php echo $product->get_variation_sale_price() , ' ', get_woocommerce_currency_symbol(); ?></td>
	</tr>
</table>
<p class="text-red"><?php echo $product->get_variation_sale_price(), ' ', get_woocommerce_currency_symbol(); ?></p>
<p class="striked-price"><?php echo $product->get_variation_regular_price(), ' ', get_woocommerce_currency_symbol(); ?></p>