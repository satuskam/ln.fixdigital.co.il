<?php
/**
 * Content: List
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $_pojo_parent_id;

$categories       = '';
$categories_terms = get_the_category();
if ( ! empty( $categories_terms ) && ! is_wp_error( $categories_terms ) ) :
	$categories = wp_list_pluck( $categories_terms, 'name' );
	$categories = $categories[0];
endif;

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( apply_filters( 'pojo_post_classes', array( 'media list-item' ), get_post_type() ) ); ?>>
	<?php if ( $image_url = Pojo_Thumbnails::get_post_thumbnail_url( array( 'width' => '460', 'height' => '295', 'crop' => true, 'placeholder' => true ) ) ) : ?>
		<div class="media-left">
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark" class="image-link">
				<img src="<?php echo $image_url; ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="media-object" />
				<?php if ( ! empty( $categories ) && po_archive_metadata_show( 'category', $_pojo_parent_id ) ) : ?>
					<div class="category-label"><div><span><?php echo $categories; ?></span></div></div>
				<?php endif; ?>
			</a>
		</div>
	<?php endif; ?>
	<div class="media-body">
		<h3 class="media-heading entry-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>
		<div class="entry-meta">
			<?php if ( po_archive_metadata_show( 'author', $_pojo_parent_id ) ) : ?>
				<span class="entry-user vcard author"><?php the_author_link(); ?></span>
			<?php endif; ?>
			<?php if ( po_archive_metadata_show( 'date', $_pojo_parent_id ) ) : ?>
				<span><time datetime="<?php the_time('o-m-d'); ?>" class="entry-date date published updated"><a href="<?php echo get_month_link( get_the_time('Y'), get_the_time('m') ); ?>"><?php echo get_the_date(); ?></a></time></span>
			<?php endif; ?>
			<?php if ( po_archive_metadata_show( 'time', $_pojo_parent_id ) ) : ?>
				<span class="entry-time"><?php echo get_the_time(); ?></span>
			<?php endif; ?>
			<?php if ( po_archive_metadata_show( 'comments', $_pojo_parent_id ) ) : ?>
				<span class="entry-comment"><?php comments_popup_link( __( 'No Comments', 'pojo' ), __( 'One Comment', 'pojo' ), __( '% Comments', 'pojo' ), 'comments' ); ?></span>
			<?php endif; ?>
		</div>
		<?php po_print_archive_excerpt( $_pojo_parent_id ); ?>

		<?php if ( po_archive_metadata_show( 'readmore', $_pojo_parent_id ) ) : ?>
			<div class="add-read-more">
				<?php po_print_archive_readmore( $_pojo_parent_id ); ?>
			</div>
		<?php endif; ?>
	</div>
</article>