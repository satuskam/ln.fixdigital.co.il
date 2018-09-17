<?php
/*
	Plugin Name: GSlider Gallery Elementor
	Plugin URI: http://uco.co.il/
	Description: Additional lightbox gallery element for Elementor
	Version: 0.1.1
	Author: gslider
	Author URI: http://kenwheeler.github.io/slick/
	License:     GPL2
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain: gslider-gallery-elementor
	Domain Path: /languages
 */




namespace GSliderElementor;

use GSliderElementor\Widgets\Gallery;

use Elementor;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Plugin {

	public $version = '0.1.1';

	protected $assetsUrl;

	protected $widgets = ['gallery'];

	public function __construct() {

		$this->assetsUrl = plugins_url( '/assets', __FILE__ );
    
    	add_action( 'plugins_loaded', function(){
                    load_plugin_textdomain( 'gslider-gallery-elementor', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
                } );

		add_action( 'elementor/init', [ $this, 'elementorInit' ] );

		add_action('elementor/widgets/widgets_registered' , [$this, 'registerWidgets']);

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueueStyles' ] );

                add_action( 'wp_enqueue_scripts', [ $this, 'enqueueScripts' ] );

	}

	public function elementorInit()
    {

		$elManager = \Elementor\Plugin::instance()->elements_manager;
           $cats = $elManager->get_categories();
           
           if (!isset($cats['other-elements'])) {
                // Add element category in panel
                $elManager->add_category(
                    'other-elements',
                    [
                        'title' => __( 'Other elements', 'gslider-gallery-elementor' ),
                        'icon' => 'font',
                    ],
                    1
                );
            }
	}

	/**
	 * @param $manager Elementor\Widgets_Manager
	 *
	 */

	public function registerWidgets( $manager ) {

		include_once 'widgets/Gallery.php';

		$manager->register_widget_type( new Gallery() );

	}


	public function enqueueStyles() {

		wp_enqueue_style('glsider-elementor', '//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css', null, $this->version );
		wp_enqueue_style('slick-theme', $this->assetsUrl . '/slick-theme.css', null, $this->version );
		wp_enqueue_style('gslider-main', $this->assetsUrl . '/main.css', null, $this->version );

	}
        
        
        public function enqueueScripts() {
            wp_enqueue_script(
                'slick-js','//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js', 
                ['jquery'],
                $this->version,
                true
            );
            
            wp_enqueue_script(
                'gslider-gallery-js',
                $this->assetsUrl . '/main.js', 
                ['jquery', 'slick-js'],
                $this->version,
                true
            );
        }

}

new Plugin();