<?php

function dtbaker_wp_cloudfront(){
    add_filter('user_can_richedit','__return_true');
}
add_action( 'init', 'dtbaker_wp_cloudfront' , 9 );
