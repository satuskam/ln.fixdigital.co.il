<?php
/*
Plugin Name: Elementor. Add query string to links
Description: Add 'elementor' param to all links to provide walking between pages in edit mode
Version: 0.1.1
Author: satuskam
Author URI: atuskam@gmail.com
*/


add_action( 'elementor/editor/before_enqueue_scripts', function() {
	$pVersion = '0.1.1';

    wp_enqueue_script(
        'elementor_add_query_string_to_links',
        plugin_dir_url(__FILE__) . 'js/elementor_add_query_string_to_links.js'  ,
        [
           'elementor-dialog'// dependency
        ],
        $pVersion,
        true // in_footer
    );

} );