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
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $ingoThemeApp, $product;
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
        <div class="sidebar col-md-3 col-sm-12 com-xs-12 column clearfix">
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
                    //do_action( 'woocommerce_single_product' );
                ?>

                <div class="feedback hidden-sm hidden-xs">
                    <div class="accordion-wrapper">
                    <div class="accordion-title">
                        <h2>קטלוג מוצרים</h2>
                    </div>
                    <?php
                        echo $ingoThemeApp->getCategoriesAccordionMarkup();
                        //echo $ingoThemeApp->addForm(937, 'hidden-sm hidden-xs');
                    ?>
                    <!--<div class="wishListWrapper">
                        <?php //echo  do_shortcode('[yith_wcwl_add_to_wishlist]')



//                            add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 9 );

//                            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
                            // woocommerce_template_single_add_to_cart();
                        ?>



                    </div> -->

                    </div>
                    <?php
                         if (is_active_sidebar('single_product_description_bottom_widget_zone')) {
                             dynamic_sidebar('single_product_description_bottom_widget_zone');
                         }
                    ?>
                </div>

            </div><!-- .summary -->

    </div>

        <!--<div class="col-md-1 column hidden-sm hidden-xs"></div>-->

        <div class="singleProductDescription col-md-9 col-sm-12 com-xs-12 column clearfix">
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
                ?>

                <div class="wc-product-summary">
<table class="product-header text-red">
    <tr>
        <td><?= the_title();?></td>
        <td style="text-align: left">
        <div class="price">
            <div class="woocommerce-Price-amount amount">
                <?php echo $product->get_price_html(); ?></td>
            </div>
        </div>
        <script>

        </script>
    </tr>
</table>
<!--vaa temp mark1 -->
<div class="new_price"></div>
<div class="old_price">
    <?php
        $old_price_vaa=get_post_meta($product->get_id(),'_old_price',true);
        if ($old_price_vaa)
        {
            echo '<span class="bef-crossed"> במקום </span>'.'<span class="crossed">'.get_post_meta($product->get_id(),'_old_price',true).' ₪ '.'</span>';
        }

    ?>
</div>

<div class="woocommerce-product-details__short-description"><?php echo $product->get_short_description(); ?></div>
<!--vaa temp mark2 -->
<!-- for general text1 -->
<div class="gen-text1">
    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('general-text1') ) :

    endif; ?>
</div>

<!-- vaa change color beg -->
<?php
    $hook_name = 'woocommerce_before_single_product_summary';
    global $wp_filter;
    $hook_name = 'woocommerce_after_single_product_summary';
    $target_obj=reset($wp_filter[$hook_name][1])['function'][0];
    $target_func=reset($wp_filter[$hook_name][1])['function'];
    remove_action($hook_name, $target_func, 1 );
?>

<div class="wrap-colors" >
    <?php $target_obj->woocommerce_after_single_product_summary(); ?>

<!-- vaa change end -->

<?php //echo do_action( 'woocommerce_single_product_summary' );
      //the_ecxerpt();
      woocommerce_template_single_add_to_cart(); ?>
                </div>
<!--vaa temp mark3 -->


                <?php

                    remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

                    do_action( 'woocommerce_before_single_product_summary' );
                ?>
<!--vaa temp mark4 -->
                <div class="product-extra-left">
                    <p><strong>מפרט כללי</strong></p>
                    <div class="gen-text2">
                        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('general-text2') ) :
                        endif; ?>
                    </div>

                    <!-- for socials -->
                    <div class="prod-soc">
                        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('product-soc') ) :
                        endif; ?>
                    </div>

                </div>

                <div class="product-extra-right">
                    <p><strong>סידור פנימי</strong></p>
                    <!-- vaa test48 beg -->
                    <div class="img-right-cont">
                      <img src="<?= get_field( 'configuration1' ); ?>" alt="product configuration config1"/>
                    </div>
                    <!-- vaa test48 end -->
                </div>
<!--vaa temp mark5 -->
                <?php

                    do_action( 'woocommerce_after_single_product_summary' );

                ?>
<!--vaa temp mark6 -->
            </div>

        <div class="clear"></div>


            <div class="hidden-sm hidden-xs">
                <?php echo $ingoThemeApp->addForm(3112, '') ?>
            </div>
            <div class="feedback hidden-md hidden-lg clearfix">
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

            remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
            remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );

            //do_action( 'woocommerce_after_single_product_summary' );
    ?>

</div><!-- #product-<?php the_ID(); ?> -->

<?php
//  do_action( 'woocommerce_after_single_product' ); ?>

<!-- script for variation price -->
<script>
            jQuery('.wrap-colors .selected-radio-image').trigger('click');
    jQuery( '.variations_form' ).each( function() {

        jQuery(this).on( 'change', function(ev)
        {
            setTimeout(function(){
              jQuery('.wrap-colors .selected-radio-image').trigger('click');
            },300);
            var new_price=jQuery('.woocommerce-variation-price span.woocommerce-Price-amount.amount').first().text()
            if((!new_price)||(!jQuery('#material').val())||(!jQuery('#shelf_count').val()))
            {
              new_price=jQuery('.product-header .price>.woocommerce-Price-amount.amount').first().html()
              jQuery('.new_price').html(new_price);
            }
            else
            {
                jQuery('.new_price').text(new_price);
            }
        });
    });
    jQuery('.wrap-colors').on( 'change', function(ev)
        {

            var new_price=jQuery('.woocommerce-variation-price del span.woocommerce-Price-amount.amount').first().text()
            if((!new_price)||(!jQuery('#material').val())||(!jQuery('#shelf_count').val()))
            {
              new_price=jQuery('.product-header .price>.woocommerce-Price-amount.amount').first().html()
              jQuery('.new_price').html(new_price);
            }
            else
            {
                jQuery('.new_price').text(new_price);
            }

        });

</script>

