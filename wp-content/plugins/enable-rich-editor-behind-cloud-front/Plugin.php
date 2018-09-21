<?php
/*
    Plugin Name:  Enable Rich Editor Behind CloudFront
    Description: Provide the working of rich editor for blogs which are distributed by CloudFront
    Version: 0.1.0
    Author: UCO
    Author URI: uco.co.il
*/

namespace EnableRichEditorBehindCloudFront;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main class plugin
 */
class Plugin
{
    static public function init()
    {
        add_action( 'init', ['EnableRichEditorBehindCloudFront\Plugin', 'enableRichEditorBehindCloudFront'] , 9 );
    }
    
    
    public function enableRichEditorBehindCloudFront()
    {
        add_filter('user_can_richedit','__return_true');
    }
}

Plugin::init();