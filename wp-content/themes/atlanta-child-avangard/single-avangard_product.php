<?php
/**
 * The template for displaying all single products
 */
get_header(); ?>


			<div class="product-page-cont">
				<div class="row">
					<div class="product-cont col-md-7">
						<?php $gallery_images = get_post_meta( get_the_ID(), 'product_gallery_images', true ); ?>
						<?php if ( ! empty( $gallery_images ) ) : ?>
							<div class="slick-slider">
								<?php foreach ( $gallery_images as $gallery_image ) : ?>
									<?php 
										$image_attributes_sm = wp_get_attachment_image_src( $gallery_image, 'madium' );
										$image_attributes_lg = wp_get_attachment_image_src( $gallery_image, 'large' );
									?>
									<a data-fancybox="gallery" href="<?php echo $image_attributes_lg[0];?>"><img  src="<?php echo $image_attributes_sm[0]; ?>" data-zoom-image="<?php echo $image_attributes_lg[0];?>"/></a>
								<?php endforeach; ?>
							</div>
							<div id="zoom-product"><a class="zoom-in"><i class="fa fa-search-plus"></i></a><a class="zoom-out" style="display: none;"><i class="fa fa-search-minus"></i></a></div> 
						<?php endif; ?>
					</div>
					<div class="col-md-5 product-desc">
						<h2 class="title-product"><?php the_title(); ?></h2> 
						<p><?php the_content(); ?></p>
						<div class="line-product"></div>
						<div class="product-form-title"><b>לפרטים נוספים על מוצר זה מלא פרטיך</b></div>
						<?php echo do_shortcode('[contact-form-7 id="3672" title="Product form"]'); ?>
					</div>
				</div> 
			</div>





<?php get_footer();