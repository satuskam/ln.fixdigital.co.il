<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include( 'core/bootstrap.php' );

add_filter( 'jpeg_quality', 'custom_image_quality' );
add_filter( 'wp_editor_set_quality', 'custom_image_quality' );
function custom_image_quality( $quality ) {

    return 85;

}

// EOF