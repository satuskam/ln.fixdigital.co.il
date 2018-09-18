<?php
/**
 * The main template file.
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $ingoThemeApp;

$view = 'archive';
if ( is_singular() )
	$view = 'single';
elseif ( is_search() )
	$view = 'search';

do_action( 'pojo_setup_body_classes', $view, get_post_type(), '' );

get_header();

do_action( 'pojo_get_start_layout', $view, get_post_type(), '' );

?>

<!-- Start woocommerce content -->
    
<?php

if ( is_singular( 'product' ) ) {
    while ( have_posts() ) {
        the_post();
        wc_get_template_part( 'content', 'single-product' );
    }

} else {
    $ingoThemeApp->renderProductCategoryPageContent();
}

?>

<!-- End woocommerce content -->'

<?php

do_action( 'pojo_get_end_layout', $view, get_post_type(), '' );

get_footer();