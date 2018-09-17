<?php
/**
 * Content: Three Columns
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $_pojo_parent_id;
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( apply_filters( 'pojo_post_classes', array( 'grid-item col-md-4 col-sm-6 col-xs-12' ), get_post_type() ) ); ?>>
	<div class="inbox">
		<?php if ( has_post_format( 'gallery' ) ) :
			$gallery_items = explode( ',', atmb_get_field( 'format_gallery' ) );
			$slides = array();
			if ( ! empty( $gallery_items ) ) :
				foreach ( $gallery_items as $item_id ) :
					$attachment     = get_post( $item_id );
					$attachment_url = Pojo_Thumbnails::get_attachment_image_src( $item_id, array( 'width' => '460', 'height' => '300' ) );
					if ( ! empty( $attachment_url ) )
						$slides[] = sprintf(
							'<li><a href="%1$s"><img src="%2$s" title="%3$s" alt="%3$s" /></a></li>',
							esc_attr( get_permalink() ),
							esc_attr( $attachment_url ),
							esc_attr( $attachment->post_excerpt )
						);
				endforeach;
				if ( ! empty( $slides ) ) :
					echo '<ul class="pojo-simple-gallery">' . implode( '', $slides ) . '</ul>';
				endif;
			endif;
		elseif ( has_post_format( 'video' ) ) : ?>
			<?php if ( $video_link = atmb_get_field( 'format_video_link' ) ) : ?>
				<div class="custom-embed" data-save_ratio="<?php echo atmb_get_field( 'format_aspect_ratio' ); ?>"><?php echo wp_oembed_get( $video_link, wp_embed_defaults() ); ?></div>
			<?php endif; ?>
		<?php else : ?>
		<?php $image_url = Pojo_Thumbnails::get_post_thumbnail_url( array( 'width' => '460', 'height' => '300', 'crop' => true, 'placeholder' => true ) ); ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark" class="image-link">
				<img src="<?php echo $image_url; ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="media-object image-radius" />
				<div class="overlay-image"></div>
				<div class="overlay-title">
					<i class="fa fa-link"></i>
				</div>
			</a>
		<?php endif; ?>
		<?php if ( has_post_format( 'audio' ) ) : ?>
			<?php echo wp_audio_shortcode( array( 'mp3' => atmb_get_field( 'format_mp3_url' ), 'ogg' => atmb_get_field( 'format_oga_url' ) ) ); ?>
			<div class="custom-embed"><?php echo wp_oembed_get( atmb_get_field( 'format_embed_url' ), wp_embed_defaults() ); ?></div>
		<?php endif; ?>
		<div class="caption">
			<div class="entry-meta">
				<?php if ( po_archive_metadata_show( 'date', $_pojo_parent_id ) ) : ?>
					<span><time datetime="<?php the_time('o-m-d'); ?>" class="entry-date date published updated"><a href="<?php echo get_month_link( get_the_time('Y'), get_the_time('m') ); ?>"><?php echo get_the_date(); ?></a></time></span>
				<?php endif; ?>
				<?php if ( po_archive_metadata_show( 'time', $_pojo_parent_id ) ) : ?>
					<span class="entry-time"><?php echo get_the_time(); ?></span>
				<?php endif; ?>
				<?php if ( po_archive_metadata_show( 'comments', $_pojo_parent_id ) ) : ?>
					<span class="entry-comment"><?php comments_popup_link( __( 'No Comments', 'pojo' ), __( 'One Comment', 'pojo' ), __( '% Comments', 'pojo' ), 'comments' ); ?></span>
				<?php endif; ?>
				<?php if ( po_archive_metadata_show( 'author', $_pojo_parent_id ) ) : ?>
					<span class="entry-user vcard author"><a class="fn" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author"><?php echo get_the_author(); ?></a></span>
				<?php endif; ?>
			</div>
			<h4 class="grid-heading entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
			<?php po_print_archive_excerpt( $_pojo_parent_id ); ?>
			<?php if ( po_archive_metadata_show( 'readmore', $_pojo_parent_id ) ) : ?>
				<?php po_print_archive_readmore( $_pojo_parent_id ); ?>
			<?php endif; ?>
		</div>
	</div>
</div>