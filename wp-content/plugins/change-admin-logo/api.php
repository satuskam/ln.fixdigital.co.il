<?php
/*
Plugin Name: Change Logo for admin
Description: Change Logo for admin
Version: 0.1
Author: satuskam
Author URI: atuskam@gmail.com
*/


function changeAdminBarLogo($wp_admin_bar) {
    $id = 'wp-logo';
    $logoLink = 'http://www.fixdigital.co.il';
    
    // change url for logo node
    $wpLogo = $wp_admin_bar->get_node($id);
    $wpLogo->href = $logoLink;
    $wp_admin_bar->add_node($wpLogo );
    
    // remove submenus
    $wp_admin_bar->remove_node('about');
    $wp_admin_bar->remove_menu('wp-logo-external');
    
    renderCustomStylesFoLogo();
}
add_action( 'admin_bar_menu', 'changeAdminBarLogo', 999 );


function renderCustomStylesFoLogo() {
    $logoUrl = plugin_dir_url(__FILE__) . 'images/logo_white.png';
    
    echo '
        <style type="text/css">
            #wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon {
                width: 105px;
                height: 100%;
                padding: 0 !important;
                margin: 0 !important;
            }

            #wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before {
                background-image: url(' . $logoUrl . ') !important;
                background-position: center center;
                background-size: contain;
                background-repeat: no-repeat;
                width: 105px;
                height: 100%;
                top: 0px;
                color:rgba(0, 0, 0, 0);
                display: inline-block;
            }
            
            #wpadminbar #wp-admin-bar-wp-logo.hover > .ab-item .ab-icon {
                background-position: 0 0;
            }
        </style>
    ';
}
