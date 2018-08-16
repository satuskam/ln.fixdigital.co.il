<?php
/**
 * Product attributes
 *
 * Used by list_attributes() in the products class.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-attributes.php.
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
 * @version     3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="shop_attributes">

	<?php foreach ( $attributes as $attribute ) : ?>
		<?php if($attribute->get_name()!='pa_color'): ?>

			<div class="attr_cont">
				<div class="attr_titul"><?php echo wc_attribute_label( $attribute->get_name() ); ?></div>
				<div class="attr_list">
					<?php
						$values = array();

						if ( $attribute->is_taxonomy() ) {
							$attribute_taxonomy = $attribute->get_taxonomy_object();
							$attribute_values = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );

							foreach ( $attribute_values as $attribute_value ) {
								$value_name = esc_html( $attribute_value->name );

								if ( $attribute_taxonomy->attribute_public ) {
									$values[] = '<a href="' . esc_url( get_term_link( $attribute_value->term_id, $attribute->get_name() ) ) . '" rel="tag">' . $value_name . '</a>';
								} else {
									$values[] = $value_name;
								}
							}
						} else {
							$values = $attribute->get_options();

							foreach ( $values as &$value ) {
								$value = make_clickable( esc_html( $value ) );
							}
						}
						$dilimeter='</span><span class="list_item">';
						// $dilimeter=', ';
						// echo '<span class="list_item">'.wptexturize( implode( $dilimeter, $values ) ).'</span>';
						echo '<span class="list_item">'.apply_filters( 'woocommerce_attribute',wptexturize( implode( $dilimeter, $values ) ), $attribute, $values ).'</span>';
						// echo '<span class="list_item">'.apply_filters( 'woocommerce_attribute', implode( $dilimeter, $values ), $attribute, $values ).'</span>';
												// echo '<span class="list_item">'.apply_filters( 'woocommerce_attribute',  implode( $dilimeter, $values ) , $attribute, $values ).'</span>';
// echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );
					?>
				</div>
			</div>

		<?php endif; ?>
	<?php endforeach; ?>
</div>
