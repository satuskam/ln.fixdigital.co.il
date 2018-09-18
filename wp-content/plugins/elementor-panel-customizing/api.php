<?php
/*
Plugin Name: Elementor Panel Customizing
Description: Customizing of Elementor Panel to meet the requirements of UCO WebGroup
Version: 0.2.3
Author: satuskam
Author URI: atuskam@gmail.com
*/

add_action( 'elementor/editor/before_enqueue_scripts', function() {
	$version = '0.2.3';

    wp_enqueue_script(
        'elementor_panel_customizing',
        plugin_dir_url(__FILE__) . 'js/elementor_panel_customizing.js',
        [
            'elementor-dialog' // dependency
        ],
        $version,
        true // in_footer
    );
    
    $logoUrl = plugin_dir_url(__FILE__) . 'images/fix_digital_logo.png';
    
    echo "   
        <style>
            .elementorPanelHeaderLogo {
                background-image: url($logoUrl) !important;
                background-position: center center;
                background-size: contain;
                background-repeat: no-repeat;
                width: 100px;
                height: 40px;
                color: rgba(0, 0, 0, 0);
                display: inline-block;
            }
            
            .elementor-panel #elementor-panel-header-wrapper #elementor-panel-header {
                background-color: white;
                color: #9b0a46;
                
            }
            
            #elementor-panel-content-wrapper {
                border-top: 3px solid #9b0a46;
            }
        </style>
     ";
} );