<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $ingoThemeApp;
?>

<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	 remove_action( 'woocommerce_before_single_product', 'wc_print_notices', 10 );
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="row clearfix">
        <div class="sidebar col-md-3 col-sm-12 com-xs-12 column clearfix prodcont prodcont-closed">
            <div class="summary entry-summary">

                <?php
                    /**
                     * woocommerce_single_product_summary hook.
                     *
                     * @hooked woocommerce_template_single_title - 5
                     * @hooked woocommerce_template_single_rating - 10
                     * @hooked woocommerce_template_single_price - 10
                     * @hooked woocommerce_template_single_excerpt - 20
                     * @hooked woocommerce_template_single_add_to_cart - 30
                     * @hooked woocommerce_template_single_sharing - 50
                     * @hooked WC_Structured_Data::generate_product_data() - 60
                     */

                    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
                    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
                    //do_action( 'woocommerce_single_product_summary' );
                ?>
                <!-- vaa changes beg1 -->
                <h3 class="col-title top-level">
                   <?=
                        // $tempvalue=get_the_terms($post->ID, 'product_cat')[0];
                        wp_kses(get_the_terms($post->ID, 'product_cat')[0]->name);
                   ?>
                </h3>
                <!-- vaa changes end1 -->
            <h3 class="col-title bot-level"><?= the_title(); ?></h3>
            <div class="woocommerce-product-details__short-description">
    			<?= the_excerpt(); ?>
			</div>

                <div class="feedback hidden-sm hidden-xs">
                    <?= $ingoThemeApp->addForm(3112, '') ?>

                    <div class="wishListWrapper">
                        <?php // echo  do_shortcode('[yith_wcwl_add_to_wishlist]')



//                            add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 9 );

//                            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
                            woocommerce_template_single_add_to_cart();
                        ?>



                    </div>

                    <?php
                        if (is_active_sidebar('single_product_description_bottom_widget_zone')) {
                            dynamic_sidebar('single_product_description_bottom_widget_zone');
                        }
                    ?>
                </div>
            </div><!-- .summary -->

        <?php echo "<h3 class='col-title'><span class='plus sign'>+</span><span class='minus sign'>–</span>" . __('קטלוג מוצרים', 'ingo') . "</h3>";
                    echo $ingoThemeApp->getCategoriesAccordionMarkup() ?>

	</div>
    <script>
        jQuery(".prodcont>.col-title>.sign.minus").click(function(){
            jQuery(".prodcont").removeClass("prodcont-opened").addClass("prodcont-closed");
        });
        jQuery(".prodcont>.col-title>.sign.plus").click(function(){
            jQuery(".prodcont").removeClass("prodcont-closed").addClass("prodcont-opened");
        });
        jQuery(".subcatsAccordion .accordion-title>.sign.minus").click(function()
        {
            var target_content_class="#"+jQuery(this).parent(".accordion-title").attr("aria-controls");
            console.log(target_content_class);
            console.log(jQuery(target_content_class));
            jQuery(this).parent(".accordion-title").attr({
                "aria-selected": "false",
                "aria-expanded": "false"
            });
            jQuery(target_content_class).attr({
                "aria-hidden": "true"
            });
            jQuery(target_content_class).css("display", "none");
            jQuery(this).parent(".accordion-title").removeClass("open").removeClass("read");
            return false;
        });
    </script>

        <div class="col-md-2 column hidden-sm hidden-xs"></div>

        <div class="col-md-7 col-sm-12 com-xs-12 column clearfix">
            <div class="hidden-sm hidden-xs">
                 <?php woocommerce_breadcrumb(); ?>
            </div>

            <div class="row-fluid clearfix">
                <?php
                    /**
                     * woocommerce_before_single_product_summary hook.
                     *
                     * @hooked woocommerce_show_product_sale_flash - 10
                     * @hooked woocommerce_show_product_images - 20
                     */
                    remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
                    do_action( 'woocommerce_before_single_product_summary' );
                ?>
            </div>

            <?php //  echo $ingoThemeApp->addForm(2548, 'hidden-md hidden-lg') ?>
            <div class="feedback hidden-md hidden-lg clearfix">
                         <!-- vaa change -->
                <?= $ingoThemeApp->addForm(3112, '') ?>

                <div class="wishListWrapper">
                    <?php // echo  do_shortcode('[yith_wcwl_add_to_wishlist]')

                        woocommerce_template_single_add_to_cart();
                    ?>
                </div>

                <?php
                    if (is_active_sidebar('single_product_description_bottom_widget_zone')) {
                        dynamic_sidebar('single_product_description_bottom_widget_zone');
                    }
                ?>
            </div>
            <!-- vaa recent beg -->
            <?php
            /**
             * woocommerce_after_single_product_summary hook.
             *
             * @hooked woocommerce_output_product_data_tabs - 10
             * @hooked woocommerce_upsell_display - 15
             * @hooked woocommerce_output_related_products - 20
             */

                remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
                remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
                do_action( 'woocommerce_after_single_product_summary' );
            ?>
            <!-- vaa recent end -->
        </div>
    </div>


    <div class="row-fluid clearfix">
        <?php // do_action( 'woocommerce_before_single_product_summary' ); ?>
    </div>
	<?php
            /**
             * woocommerce_after_single_product_summary hook.
             *
             * @hooked woocommerce_output_product_data_tabs - 10
             * @hooked woocommerce_upsell_display - 15
             * @hooked woocommerce_output_related_products - 20
             */

            // remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
            // remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
            // do_action( 'woocommerce_after_single_product_summary' );
	?>

</div><!-- #product-<?php the_ID(); ?> -->

<?php //  do_action( 'woocommerce_after_single_product' ); ?>
