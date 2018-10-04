<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function addMainStyles(){
    wp_enqueue_style('event-planning-child-gasparo', get_stylesheet_directory_uri() . '/style.css');
}

add_action('wp_enqueue_scripts', 'addMainStyles');

add_action( 'woocommerce_product_options_pricing', 'add_service_to_products' );

$add_custom_price_fields_arr = [

    $advance_payment_arr = array(
        $advance_payment = array(
            'id' => 'advance_payment',
            'class' => 'short wc_input_price',
            'label' => __( 'Advance payment', 'woocommerce' ),
            'description' => 'Advance payment',
            'placeholder' => 'Value'
        ),
        $advance_payment_2 = array(
            'id' => 'advance_payment_2',
            'class' => 'short wc_input_price',
            'description' => 'Advance payment 2 row',
            'placeholder' => 'Value'
        ),
        $advance_payment_3 = array(
            'id' => 'advance_payment_3',
            'class' => 'short wc_input_price',
            'description' => 'Advance payment 3 row',
            'placeholder' => 'Value'
        ),
        $advance_payment_4 = array(
            'id' => 'advance_payment_4',
            'class' => 'short wc_input_price',
            'description' => 'Advance payment 4 row',
            'placeholder' => 'Value'
        )
    ),
    $refund_period_arr = array(
        $refund_period = array(
            'id' => 'refund_period',
            'class' => 'short wc_input_price',
            'label' => __( 'Refund period', 'woocommerce' ),
            'description' => 'Refund period',
            'placeholder' => 'Value'
        ),
        $refund_period_2 = array(
            'id' => 'refund_period_2',
            'class' => 'short wc_input_price',
            'description' => 'Refund period 2 row',
            'placeholder' => 'Value'
        ),
        $refund_period_3 = array(
            'id' => 'refund_period_3',
            'class' => 'short wc_input_price',
            'description' => 'Refund period 3 row',
            'placeholder' => 'Value'
        ),
        $refund_period_4 = array(
            'id' => 'refund_period_4',
            'class' => 'short wc_input_price',
            'description' => 'Refund period 4 row',
            'placeholder' => 'Value'
        )
    ),
    $monthly_payment_arr = array(
        $monthly_payment = array(
            'id' => 'monthly_payment',
            'class' => 'short wc_input_price',
            'label' => __( 'Monthly payment', 'woocommerce' ),
            'description' => 'Monthly payment',
            'placeholder' => 'Value'
        ),
        $monthly_payment_2 = array(
            'id' => 'monthly_payment_2',
            'class' => 'short wc_input_price',
            'description' => 'Monthly payment 2 row',
            'placeholder' => 'Value'
        ),
        $monthly_payment_3 = array(
            'id' => 'monthly_payment_3',
            'class' => 'short wc_input_price',
            'description' => 'Monthly payment 3 row',
            'placeholder' => 'Value'
        ),
        $monthly_payment_4 = array(
            'id' => 'monthly_payment_4',
            'class' => 'short wc_input_price',
            'description' => 'Monthly payment 4 row',
            'placeholder' => 'Value'
        )
    ),
    $payment_at_end_of_period_arr = array(
        $payment_at_end_of_period = array(
            'id' => 'payment_at_end_of_period',
            'class' => 'short wc_input_price',
            'label' => __( 'Payment at end of period', 'woocommerce' ),
            'description' => 'Payment at end of period',
            'placeholder' => 'Value'
        ),
        $payment_at_end_of_period_2 = array(
            'id' => 'payment_at_end_of_period_2',
            'class' => 'short wc_input_price',
            'description' => 'Payment at end of period 2 row',
            'placeholder' => 'Value'
        ),
        $payment_at_end_of_period_3 = array(
            'id' => 'payment_at_end_of_period_3',
            'class' => 'short wc_input_price',
            'description' => 'Payment at end of period 3 row',
            'placeholder' => 'Value'
        ),
        $payment_at_end_of_period_4 = array(
            'id' => 'payment_at_end_of_period_4',
            'class' => 'short wc_input_price',
            'description' => 'Payment at end of period 4 row',
            'placeholder' => 'Value'
        )
    )
];

function add_service_to_products() {
    global $add_custom_price_fields_arr;

    foreach ($add_custom_price_fields_arr as $i){
        foreach ($i as $a){
            woocommerce_wp_text_input($a);
        }
    }
}
// Saving input field
add_action( 'save_post', 'add_custom_price' );
function add_custom_price( $product_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;
    $add_custom_price_fields_id_arr = ['advance_payment', 'advance_payment_2', 'advance_payment_3', 'advance_payment_4', 'refund_period', 'refund_period_2', 'refund_period_3', 'refund_period_4', 'monthly_payment', 'monthly_payment_2', 'monthly_payment_3', 'monthly_payment_4', 'payment_at_end_of_period', 'payment_at_end_of_period_2', 'payment_at_end_of_period_3', 'payment_at_end_of_period_4'];
    foreach ($add_custom_price_fields_id_arr as $i){
        if ( isset ( $_POST[$i] ) ) {
            update_post_meta($product_id, $i, $_POST[$i]);
        } else delete_post_meta($product_id, $i, $_POST[$i]);
    }

}
// Visible to front end Product page
add_action( 'woocommerce_single_product_summary', 'add_frontend_custom_price', 9 );
function add_frontend_custom_price() {
    global $product;
    global $add_custom_price_fields_arr;

    echo '<h2>פרטי העסקה</h2><div class="product_price-table"><div class="product_price-table-header">';

    echo '<div>מקדמה</div><div> תקופת החזר </div><div> תשלום חודשי </div><div> תשלום בסוף התקופה </div>';

    echo '</div>';
        foreach ( $add_custom_price_fields_arr as $i ){
            echo '<div class="product_price-table-col">';
            foreach ($i as $a) {
                $b = $a['id'] . '_value';
                $c = get_woocommerce_currency_symbol();
                        if ($product->product_type <> 'variable' && $b = get_post_meta($product->id, $a['id'], true)) {
                            if ($a['id'] == 'refund_period' || $a['id'] == 'refund_period_2' || $a['id'] == 'refund_period_3' || $a['id'] == 'refund_period_4') {
                                echo '<div>';
                                echo "{$b}";
                                echo '</div>';
                            } else {
                                echo '<div>';
                                echo "{$b} {$c}";
                                echo '</div>';
                            }
                        } else {
                            echo '<div>';
                            echo "—";
                            echo '</div>';
                        }
            }
            echo '</div>';
        }
    echo '</div>';
}
/* Product page */

add_action( 'wp', 'remove_sidebar_product_pages' );

function remove_sidebar_product_pages() {
    if ( is_product() ) {
        remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
    }
}

    remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);
    add_action( 'woocommerce_before_single_product_summary', 'woocommerce_template_single_title', 5 ); 
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );

    add_action( 'woocommerce_after_shop_loop_item', 'remove_add_to_cart_buttons', 1 );
        function remove_add_to_cart_buttons() {
            remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
            add_action( 'woocommerce_after_shop_loop_item', 'mycode_more_info_button' );
        }

    function arphabet_widgets_init() {
    register_sidebar( array(
        'name'          => 'Product form widget',
        'id'            => 'product_form',
        'before_widget' => '<div class="product-form">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="rounded">',
        'after_title'   => '</h2>',
    ) );
    }
    add_action( 'widgets_init', 'arphabet_widgets_init' );

    add_action( 'woocommerce_after_single_product_summary', 'add_form_widget', 1 );
    function add_form_widget() {
         dynamic_sidebar( 'product_form' ); 
    }    

    // Change the description tab heading
    add_filter( 'woocommerce_product_description_heading', 'wc_change_product_description_tab_heading', 10, 1 );
    function wc_change_product_description_tab_heading( $title ) {
        global $post;
        return 'תנאים כללים';
    }
    // Change the additional information tab heading 
    add_filter( 'woocommerce_product_additional_information_heading', 'wc_change_product_additional_information_tab_heading', 10, 1 );
    function wc_change_product_additional_information_tab_heading( $title ) {
        global $post;
        return 'מפרט טכני';
    }
    // Change the Related Products heading
    function related_product_heading() {
        echo '<h2 class="related-product-header">רכבים נוספים שעשויים לעניין אותך</h2>';
    }
    add_action( 'woocommerce_after_single_product_summary', 'related_product_heading', 19 );

