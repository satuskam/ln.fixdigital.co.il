<?php
/*
	Plugin Name: Stick Elementor Section on Top When Scrolling with Placeholder
	Description: Sticks the Elementor's section to top of window when scrolling. To do it add 'menuBarToBeSticked' class to section which should be sticked on top.
	Version: 0.2.0
	Author: uco
	License:     GPL2
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain: stick-elementor-section-on-top-when-scrolling
	Domain Path: /languages/
 */


namespace StickedElementorSection;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Plugin
{
	public $version = '0.2.0';

	protected $assetsUrl;

	public function __construct()
    {
        $this->assetsUrl = plugins_url( '/assets', __FILE__ );

        add_action( 'wp_enqueue_scripts', [ $this, 'enqueueStyles' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueueScripts' ] );
	}



    public function enqueueScripts()
    {
        wp_enqueue_script(
            'stick-elementor-section-on-top-when-scrolling-js',
            $this->assetsUrl . '/js/main.js',
            array('jquery'),
            $this->version
        );
    }



    public function enqueueStyles()
    {
        wp_enqueue_style(
            'stick-elementor-section-on-top-when-scrolling-css',
            $this->assetsUrl . '/css/main.css',
            null,
            $this->version
        );
	}

}

new Plugin();
