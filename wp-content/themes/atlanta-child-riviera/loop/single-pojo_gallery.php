<?php
/**
 * Single Gallery
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( have_posts() ) :

	while ( have_posts() ) : the_post(); ?>
<!-- mark42 -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="entry-post row">
				<div class="gallery-thumbnail <?php echo esc_attr( pojo_gallery_get_single_layout_class( 'thumbnail' ) ); ?>">
					<?php do_action( 'pojo_gallery_print_front', get_the_ID() ); ?>
				</div>
				<div class="gallery-content <?php echo esc_attr( pojo_gallery_get_single_layout_class( 'content' ) ); ?>">
					<header class="entry-header">
						<?php if ( po_breadcrumbs_need_to_show() ) : ?>
							<?php pojo_breadcrumbs(); ?>
						<?php endif; ?>
						<?php if ( pojo_is_show_page_title() ) : ?>
							<div class="page-title">
								<h1 class="entry-title"><?php the_title(); ?></h1>
							</div>
						<?php endif; ?>
					</header>
					<div class="entry-content">
						<?php if ( ! Pojo_Core::instance()->builder->display_builder() ): ?>
							<?php the_content(); ?>
						<?php endif; ?>
					</div>
					<?php

						// vaa changes comments

						// Previous/next post navigation.
						// echo pojo_get_post_navigation(
						// 	array(
						// 		'prev_text' => __( '&laquo; Previous', 'pojo' ),
						// 		'next_text' => __( 'Next &raquo;', 'pojo' ),
						// 	)
						// );
					?>
					<footer>
						<?php pojo_button_post_edit(); ?>
					</footer>
				</div>
			</div>
		</article>
			<!-- vaa changes for galtitul -->
		<?php if ( is_active_sidebar( 'pojo-sidebar-37' ) ) : ?>
				<?php dynamic_sidebar( 'pojo-sidebar-37' ); ?>
		<?php endif; ?>
		<!-- vaa changes for galtitul end-->
		<!-- vaa changes for alt everywhere  beg-->
		<script>
				var alt_title=jQuery(".entry-content>h1").text();
				alt_title="testtext";
				console.log('test41');
				jQuery('.pojo-gallery-wrapper img').each(function(index, el) {
					var alt='';
					var target_src=jQuery(el).attr('src');
					console.log('test42');
					alt=jQuery(el).attr('alt');
					var title='';
					title=jQuery(el).attr('title');
					if(!alt)
						jQuery(el).attr('alt',alt_title);
					if(!title)
						jQuery(el).attr('title',alt_title);
				});
		</script>
		<!-- vaa changes for alt everywhere  end-->
	<?php endwhile;
else :
	pojo_get_content_template_part( 'content', 'none' );
endif;
