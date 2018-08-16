<?php
/**
 * Template Name: Buy Form
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : 0;

$price_id = isset($_GET['price_id']) ? $_GET['price_id'] : 1;

$price =  get_field('price' . $price_id, $product_id);

$discount = get_field('discount', $product_id);
$price_r=$price*(100-$discount)/100;

get_header();

if ( have_posts() ) :
	while ( have_posts() ) : the_post(); ?>
		<div class="row buy-page" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="col-sm-12">

			<!-- <h4 class="entry-title"><?=the_title()?></h4> -->
				<h1 class="formcenter">לפרטים נוספים ושאלות: 04-6223030</h1>
				<div class="form1-cont">
						<div class="titul1 titul">מלאו פרטיכם וניצור איתכם קשר:</div>
						<?php echo do_shortcode( '[pojo-form id="3346"]' ); ?>
				</div>

				<?php if ($product_id != 0 && $price > 0 ) { ?>
				<div class="form-picture">
					<?=get_the_post_thumbnail($product_id, 'thumbnail')?>
				</div>
				<div class="titul2 titul">פרטי המוצר:</div>
				<div class="smart-cont">
					<div class="wfix wfix1">
						<span>שם המוצר:</span> <?=get_the_title($product_id)?>
					</div>
					<div class="wfix">
						<span>מחיר:</span><?=$price?> ₪
					</div>
					<div class="wfix">
						<strong><span >סה"כ לתשלום:</span><?=$price*(100-$discount)/100?> ₪</strong>
					</div>
				</div>


				<script type="text/javascript">
					document.addEventListener("DOMContentLoaded", function(){
						document.getElementById('form-field-1-1').value = '<?=get_the_title($product_id);?>';
						document.getElementById('id_product_name').value = '<?=get_the_title($product_id);?>';
						document.getElementById('form-field-1-3').value = '<?=$price?> ₪';
						document.getElementById('form-field-1-2').value = '<?=$discount?>';
						document.getElementById('form-fielda-2-28').value = '<?=$price_r?>';
						document.getElementById('id_page_url').value ='<?=get_the_permalink($product_id)?>';
						document.getElementById('id-order-number').value =Math.floor(Math.random() * 1000000000000000000);;


					});
				</script>
				<?php } ?>
				<div class="titul3 titul">ברצוני:</div>
				<!-- new form vaa 2018.01.15 -->
				<?php echo do_shortcode( '[contact-form-7 id="4272" title="buy3"]' ); ?>
				<script>
					jQuery("#wpcf7-f4272-p3347-o1").addClass("form2-cont");
					jQuery("#wpcf7-f4272-p3347-o1>form").addClass("pojo-form form-align-left");
					// jQuery("#wpcf7-f4272-p3347-o1 input.wpcf7-form-control").unwrap();
				</script>
			</div>
		</div>

	<?php endwhile;
else :
	pojo_get_content_template_part( 'content', 'none' );
endif;


get_footer();




