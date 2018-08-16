<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Add theme support for Featured Images
add_theme_support('post-thumbnails', array(
'post',
'page'
));

if( !is_admin() )
{
    add_action( 'wp_enqueue_scripts', 'addCustomStyles1' );
}
function   addCustomStyles1() {
    wp_enqueue_style(
        'atlanta-fix-style4',
        get_stylesheet_directory_uri() . '/assets/css/nofshim.css'
    );
}


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);