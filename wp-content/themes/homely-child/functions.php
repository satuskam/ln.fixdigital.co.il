<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_theme_support( 'post-thumbnails', array( 'post', 'page', 'movie', 'product' ) );

function mytheme_add_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );

add_filter( 'woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98 );
    function wcs_woo_remove_reviews_tab($tabs) {
    unset($tabs['reviews']);
    return $tabs;
}
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb',20 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display',15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products',20 );
// remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs',10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta',40 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs',10 );

function   addCustomStyles1() {
    wp_enqueue_style(
        'atlanta-fix-style2',
        get_stylesheet_directory_uri() . '/assets/css/style_child.css'
    );
}
if( !is_admin() )
{
    add_action( 'wp_enqueue_scripts', 'addCustomStyles1' );
}

add_action( 'woocommerce_before_main_content', 'get_cat_top',20 );
add_action( 'woocommerce_before_single_product', 'get_cat_top1',5);

function get_cat_top()
{
    if(is_product())
    {
        global $post;
        $terms = get_the_terms( $post->ID, 'product_cat' );
        foreach ( $terms as $term ) {
            $cate=$term;
            // $product_cat_id = $term->term_id;
            break;
        }
    }
    if(is_product_category())
    {
        $cate = get_queried_object();

    }


    $image = get_field('prod_cat_back_image', $cate);
    ?>
    <div class="top-bar" style="background-image: url(<?php echo  $image; ?>)">
        <a href="<?php echo get_term_link($cate);  ?>"> <h2><?php echo $cate->name; ?></h2></a>
    </div>


    <?php

}

function get_cat_top1()
{
    if(is_product())
    {
        global $post;
        $terms = get_the_terms( $post->ID, 'product_cat' );
        foreach ( $terms as $term ) {
            $cate=$term;

            break;
        }
    }
    if(is_product_category())
    {
        $cate = get_queried_object();

    }

    ?>
    <div class="top-button-cont">
        <a href="<?php echo get_term_link($cate);  ?>">
            <span class="elementor-button-content-wrapper">
                <span class="elementor-button-icon elementor-align-icon-left">
                    <i class="fa fa-share" aria-hidden="true"></i>
                </span>
                <span class="elementor-button-text">חזרה לקטלוג</span>
            </span>
        </a>
            <div class="clearfix"></div>
    </div>

    <?php

}




add_action( 'widgets_init', function (){
            register_sidebar( array(
                'name'          => 'product form',
                'id'            => "product_form",
                'description'   => 'single product page form',
                'class'         => '',
                'before_widget' => '',
                'after_widget'  => "",
                'before_title'  => '<h3>',
                'after_title'   => "</h3>\n",
            ) );
        } );
add_action( 'widgets_init', function (){
            register_sidebar( array(
                'name'          => 'left category sidebar',
                'id'            => "left_category_sidebar",
                'description'   => 'sidebar for product categories',
                'class'         => '',
                'before_widget' => '',
                'after_widget'  => "",
                'before_title'  => '<h3>',
                'after_title'   => "</h3>\n",
            ) );
        } );


add_filter('loop_shop_columns', 'loop_columns');
// echo '<!--mark47-->';
if (!function_exists('loop_columns')) {
    function loop_columns() {
             if(is_product_category())
             {
                 $cate = get_queried_object();
                 $display_type=get_term_meta($cate->term_id,"display_type",true);
                 if ($display_type=='products')
                 {
                     return 3; // 3 products per row
                 }
             }
             return 4; // 4 products per row

    }
}


// code for custom cat widget

add_action( 'widgets_init', function(){
    register_widget( 'Sibling_prod_cat_widget' );
});

class Sibling_prod_cat_widget extends WP_Widget {

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        $widget_ops = array(
            'classname' => 'Sibling_prod_cat_widget',
            'description' => 'Sibling product categories for this categorY',
        );
        parent::__construct( 'Sibling_prod_cat_widget', 'Sibling Product Categories', $widget_ops );
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        // outputs the content of the widget
        echo "<!--mark42 cat-->";
        return;
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) {
        // outputs the options form on admin
        return;
    }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     *
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        // processes widget options to be saved
        return;
    }
}


// Put your custom code here.
