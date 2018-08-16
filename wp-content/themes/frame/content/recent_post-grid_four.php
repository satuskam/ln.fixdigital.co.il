<?php
/**
 * Recent Post: Grid Four
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $_current_widget_instance;
?>
<div class="recent-post grid-item col-md-3 col-sm-6 col-xs-12">
	<a class="image-link" href="<?php the_permalink(); ?>">
		<?php if ( 'show' === $_current_widget_instance['thumbnail'] && $image_url = Pojo_Thumbnails::get_post_thumbnail_url( array( 'width' => '600', 'height' => '600', 'crop' => true, 'placeholder' => true ) ) ) : ?>
			<img src="<?php echo $image_url; ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="media-object" />
		<?php endif; ?>
		<div class="overlay-caption">
			<div class="caption">
				<div class="caption-inner">
					<div class="entry-meta">
						<?php if ( 'show' === $_current_widget_instance['metadata_date'] ) : ?>
							<span><time datetime="<?php the_time('o-m-d'); ?>" class="entry-date date published updated"><?php echo get_the_date(); ?></time></span>
						<?php endif; ?>
						<?php if ( 'show' === $_current_widget_instance['metadata_time'] ) : ?>
							<span class="entry-time"><?php echo get_the_time(); ?></span>
						<?php endif; ?>
						<?php if ( 'show' === $_current_widget_instance['metadata_comments'] ) : ?>
							<span class="entry-comment"><?php comments_number( __( 'No Comments', 'pojo' ), __( 'One Comment', 'pojo' ), __( '% Comments', 'pojo' )); ?></span>
						<?php endif; ?>
						<?php if ( 'show' === $_current_widget_instance['metadata_author'] ) : ?>
							<span class="entry-user vcard author"><?php echo get_the_author(); ?></span>
						<?php endif; ?>
					</div>
					<?php if ( 'show' === $_current_widget_instance['show_title'] ) : ?>
						<h4 class="grid-heading entry-title"><?php the_title(); ?></h4>
					<?php endif; ?>
					<?php if ( 'show' === $_current_widget_instance['except'] ) : ?>
						<div class="entry-excerpt">
							<p><?php echo pojo_get_words_limit( get_the_excerpt(), $_current_widget_instance['except_length_words'] ); ?></p>
						</div>
					<?php endif; ?>
					<?php if ( 'show' === $_current_widget_instance['metadata_readmore'] ) : ?>
						<span class="read-more"><?php echo  ! empty( $_current_widget_instance['text_readmore_mode'] ) ? $_current_widget_instance['text_readmore_mode'] : __( 'Read More &raquo;', 'pojo' ); ?></span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</a>
</div>