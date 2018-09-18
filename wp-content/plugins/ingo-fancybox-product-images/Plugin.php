<?php
/*
	Plugin Name: Ingo Fancybox Product Images
	Plugin URI: http://nikoleti.pro/
	Description: Additional lightbox gallery for product images. Note: plugin requires for specific HTML markup
	Version: 0.1.0
	Author: nikoleti
	Author URI: http://nikoleti.pro/
	License:     GPL2
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain: ingo-fancybox-product-images
	Domain Path: /languages
 */




namespace Ingo;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Plugin {

	public $version = '0.1.0';

	protected $assetsUrl;

	protected $widgets = ['gallery'];

	public function __construct() {

		$this->assetsUrl = plugins_url( '/assets', __FILE__ );

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueueStyles' ] );

                add_action( 'wp_enqueue_scripts', [ $this, 'enqueueScripts' ] );

	}



	public function enqueueStyles() {

		wp_enqueue_style('nikoleti-elementor', $this->assetsUrl . '/main.css', null, $this->version );
		wp_enqueue_style('fancybox-3-css', $this->assetsUrl . '/jquery.fancybox.min.css', null, $this->version );

	}
        
        
        public function enqueueScripts() {
            wp_enqueue_script(
                'fancybox-3-js',
                $this->assetsUrl . '/jquery.fancybox.min.js', 
                ['jquery'],
                $this->version,
                true
            );
            
            wp_enqueue_script(
                'nikoleti-gallery-js',
                $this->assetsUrl . '/main.js', 
                ['jquery'],
                $this->version,
                true
            );
        }

}

new Plugin();