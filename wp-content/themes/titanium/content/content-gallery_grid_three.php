<?php
/**
 * Content: Gallery Grid Three
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $_pojo_parent_id;
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( apply_filters( 'pojo_post_classes', array( 'grid-item gallery-item col-md-4 col-sm-6 col-xs-6' ), get_post_type() ) ); ?>>
	<?php if ( $image_url = Pojo_Thumbnails::get_post_thumbnail_url( array( 'width' => '360', 'height' => '240', 'crop' => true, 'placeholder' => true ) ) ) : ?>
		<a class="image-link" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
			<img src="<?php echo $image_url; ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="media-object" />
			<div class="overlay-plus">
				<span class="glyphicon glyphicon-plus"></span>
			</div>
		</a>
	<?php endif; ?>

	<div class="caption">
		<h4 class="grid-heading entry-title">
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h4>
	</div>
</div>