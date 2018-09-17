<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var string $page_header_inline_styles
 * @var string $height_header
 * @var string $sub_header_style
 * @var string $title
 * @var bool   $print_breadcrumbs
 */

if ( ! empty( $height_header ) ) {
	$page_header_inline_styles[] = sprintf( 'height: %1$s;', $height_header );
}
?>
<div id="title-bar" class="title-bar-style-<?php echo esc_attr( $sub_header_style ); ?>"<?php echo ! empty( $page_header_inline_styles ) ? ' style="' . esc_attr( implode( ';', $page_header_inline_styles ) ) . '"' : '';?>>
	<div class="<?php echo WRAP_CLASSES; ?>">
		<div class="title-bar-default">
			<?php if ( $title ) : ?>
				<div class="title-primary">
					<?php echo $title; ?>
				</div>
			<?php endif; ?>
			<?php if ( $print_breadcrumbs ) : ?>
				<div class="breadcrumbs hidden-xs">
					<?php pojo_breadcrumbs(); ?>
				</div>
			<?php endif; ?>
		</div><!--.title-bar-default-->
	</div><!-- /.container -->
</div><!-- /#title-bar -->