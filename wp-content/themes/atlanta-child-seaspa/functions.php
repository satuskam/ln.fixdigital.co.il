<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Code for removing not nneded formating in Contact Form 7 vaa beg
add_filter('wpcf7_form_elements', function($content) {
    $content = preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content);

    return $content;
});
remove_filter('the_content', 'wpautop');
// Code for removing not nneded formating in Contact Form 7 vaa end
// code for not activating email disable plugin vaa beg
// add_filter('site_option_active_sitewide_plugins', 'modify_sitewide_plugins');

// function modify_sitewide_plugins($value) {
//     global $current_blog;

//     if( $current_blog->blog_id == 584 ) {
//         unset($value['disable-emails/disable-emails.php']);
//     }

//     return $value;
// }
// code for not activating email disable plugin vaa end

function pojo_add_builder_in_posts() {
    add_post_type_support( 'post', array( 'pojo-page-format' ) );
}
add_action( 'init', 'pojo_add_builder_in_posts' );


// Put your custom code here.
