<?php
/**
 * Content: Masonry
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $_pojo_parent_id;
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( apply_filters( 'pojo_post_classes', array( 'grid-item masonry-item col-md-4 col-sm-6 col-xs-12' ), get_post_type() ) ); ?>>
	<div class="inbox">
		<?php if ( $image_url = Pojo_Thumbnails::get_post_thumbnail_url( array( 'width' => '420', 'placeholder' => true ) ) ) : ?>
			<img src="<?php echo $image_url; ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="media-object" />
		<?php endif; ?>
	
		<div class="hover-object">
			<div class="caption">
				<h4 class="grid-heading entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>

				<?php po_print_archive_excerpt( $_pojo_parent_id ); ?>

				<?php if ( po_archive_metadata_show( 'readmore', $_pojo_parent_id ) ) : ?>
					<?php po_print_archive_readmore( $_pojo_parent_id ); ?>
				<?php endif; ?>

				<div class="entry-meta">
					<?php if ( po_archive_metadata_show( 'date', $_pojo_parent_id ) ) : ?>
						<span><time datetime="<?php the_time('o-m-d'); ?>" class="entry-date date published updated"><?php echo get_the_date(); ?></time></span>
					<?php endif; ?>
					<?php if ( po_archive_metadata_show( 'time', $_pojo_parent_id ) ) : ?>
						<span class="entry-time"><?php echo get_the_time(); ?></span>
					<?php endif; ?>
					<?php if ( po_archive_metadata_show( 'comments', $_pojo_parent_id ) ) : ?>
						<span class="entry-comment"><?php comments_popup_link( __( 'No Comments', 'pojo' ), __( 'One Comment', 'pojo' ), __( '% Comments', 'pojo' ), 'comments' ); ?></span>
					<?php endif; ?>
					<?php if ( po_archive_metadata_show( 'author', $_pojo_parent_id ) ) : ?>
						<span class="entry-user vcard author"><span class="fn"><?php echo get_the_author(); ?></span></span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>