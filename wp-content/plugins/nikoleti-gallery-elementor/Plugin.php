<?php
/*
	Plugin Name: Nikoleti Gallery Elementor
	Plugin URI: http://nikoleti.pro/
	Description: Additional lightbox gallery element for Elementor
	Version: 0.1.1
	Author: uco
	Author URI: http://nikoleti.pro/
	License:     GPL2
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain: nikoleti-gallery-elementor
	Domain Path: /languages/
 */




namespace NikoletiElementor;

use NikoletiElementor\Widgets\Gallery;

use Elementor;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Plugin {

	public $version = '0.1.1';

	protected $assetsUrl;

	protected $widgets = ['gallery'];

        
	public function __construct()
        {
            $this->assetsUrl = plugins_url( '/assets', __FILE__ );
            
            add_action( 'plugins_loaded', function(){
                load_plugin_textdomain( 'nikoleti-gallery-elementor', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
            } );

            add_action( 'elementor/init', [ $this, 'elementorInit' ] );

            add_action('elementor/widgets/widgets_registered' , [$this, 'registerWidgets']);

            add_action( 'wp_enqueue_scripts', [ $this, 'enqueueStyles' ] );

            add_action( 'wp_enqueue_scripts', [ $this, 'enqueueScripts' ] );
	}
        

	public function elementorInit()
        {
            // Add element category in panel
            \Elementor\Plugin::instance()->elements_manager->add_category(
                'other-elements',
                [
                    'title' => __( 'Other elements' , 'nikoleti-gallery-elementor' ),
                    'icon' => 'font',
                ],
                1
            );
	}

	/**
	 * @param $manager Elementor\Widgets_Manager
	 *
	 */

	public function registerWidgets( $manager )
        {
            include_once 'widgets/Gallery.php';

            $manager->register_widget_type( new Gallery() );
	}


	public function enqueueStyles()
        {
            wp_enqueue_style('nikoleti-elementor', $this->assetsUrl . '/main.css', null, $this->version );
            wp_enqueue_style('fancybox-3-css', $this->assetsUrl . '/jquery.fancybox.min.css', null, $this->version );
	}
        
        
        public function enqueueScripts()
        {
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
                ['jquery', 'magnific-popup-js'],
                $this->version,
                true
            );
        }

}

new Plugin();