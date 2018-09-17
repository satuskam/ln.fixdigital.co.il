<?php
/*
Plugin Name: 'Additional CSS' for blog's admins.
Description: Show 'Additional CSS' item in the theme customizer panel for blog's admins.
Version: 0.1
Author: satuskam
Author URI: atuskam@gmail.com
*/


add_filter( 'map_meta_cap', 'changeCustomCssCapability', 10, 3 );

function changeCustomCssCapability( $caps, $cap, $user_id ) {
    if ( 'edit_css' === $cap ) {
        $caps = ['manage_options'];
    }

    return $caps;
}
