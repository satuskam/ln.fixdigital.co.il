<?php
/**
 * Template Name: Product
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();

if ( have_posts() ) :
	while ( have_posts() ) : the_post(); ?>
		<div class="row" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="col-sm-6">
				<?php if ( pojo_is_show_page_title() ) : ?>
				
				<h4 class="entry-title"><?php the_title(); ?></h4>
				<h5 class="elementor-heading-title elementor-size-default">
					<?php the_field('price1'); ?> ₪ ליחיד, 
					<?php the_field('price2'); ?> ₪ לזוג
				</h5>
				<?php endif; ?>
				<div class="entry-content">
					<?php if ( ! Pojo_Core::instance()->builder->display_builder() ) : ?>
						<?php the_content(); ?>
						<?php pojo_link_pages(); ?>
					<?php endif; ?>
				</div>
				<div>
					<strong>
						<?php the_field('price1'); ?> ₪ ליחיד, 
						<?php the_field('price2'); ?> ₪ לזוג
					</strong>
				</div>
			</div>

			<div class="col-sm-6">
				<?php if ( has_post_thumbnail() ) {
						the_post_thumbnail('full');
				} ?>
				<h3><?php the_field('discount'); ?>% הנחה בהזמנה Online </h3>
				<a href="/buy?product_id=<?=the_ID()?>&price_id=1">להזמנה Online לזוג</a>
				<a href="/buy?product_id=<?=the_ID()?>&price_id=2">להזמנה Online ליחיד</a>
			</div>
			
		</div>
		
	<?php endwhile;
else :
	pojo_get_content_template_part( 'content', 'none' );
endif;


get_footer();