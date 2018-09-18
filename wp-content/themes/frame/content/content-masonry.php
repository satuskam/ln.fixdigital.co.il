<?php
/**
 * Content: Masonry
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $_pojo_parent_id;
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( apply_filters( 'pojo_post_classes', array( 'grid-item masonry-item col-md-4 col-sm-6 col-xs-12' ), get_post_type() ) ); ?>>
	<a class="image-link" href="<?php the_permalink(); ?>">
		<?php if ( $image_url = Pojo_Thumbnails::get_post_thumbnail_url( array( 'width' => '600', 'placeholder' => true ) ) ) : ?>
			<img src="<?php echo $image_url; ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="media-object" />
		<?php endif; ?>
		<div class="overlay-caption">
			<div class="caption">
				<div class="caption-inner">
					<div class="entry-meta">
						<?php if ( po_archive_metadata_show( 'date', $_pojo_parent_id ) ) : ?>
							<span><time datetime="<?php the_time('o-m-d'); ?>" class="entry-date date published updated"><?php echo get_the_date(); ?></time></span>
						<?php endif; ?>
						<?php if ( po_archive_metadata_show( 'time', $_pojo_parent_id ) ) : ?>
							<span class="entry-time"><?php echo get_the_time(); ?></span>
						<?php endif; ?>
						<?php if ( po_archive_metadata_show( 'comments', $_pojo_parent_id ) ) : ?>
							<span class="entry-comment"><?php comments_number( __( 'No Comments', 'pojo' ), __( 'One Comment', 'pojo' ), __( '% Comments', 'pojo' )); ?></span>
						<?php endif; ?>
						<?php if ( po_archive_metadata_show( 'author', $_pojo_parent_id ) ) : ?>
							<span class="entry-user vcard author"><?php echo get_the_author(); ?></span>
						<?php endif; ?>
					</div>

					<h4 class="grid-heading entry-title"><?php the_title(); ?></h4>

					<?php po_print_archive_excerpt( $_pojo_parent_id ); ?>

					<?php if ( po_archive_metadata_show( 'readmore', $_pojo_parent_id ) ) : ?>
						<span class="read-more"><?php echo po_get_archive_readmore_text( $_pojo_parent_id ); ?></span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</a>
</div>