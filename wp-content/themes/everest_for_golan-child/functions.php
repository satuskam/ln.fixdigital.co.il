<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// include( 'core/bootstrap.php' );
function pojo_add_builder_in_posts() {
    add_post_type_support( 'post', array( 'pojo-page-format' ) );
}
add_action( 'init', 'pojo_add_builder_in_posts' );


// Put your custom code here.


