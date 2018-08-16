<?php
/*
	Plugin Name: FixDigitalSmartphone Elementor
	Description: Additional 'FixDigital Smartphone' element for Elementor.
	Version: 0.2.3
	Author: uco
	License:     GPL2
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain: fix-smartphone-elementor
	Domain Path: /languages/
 */


namespace FixSmartphoneElementor;

use FixSmartphoneElementor\Widgets\FixSmartphone;
use FixSmartphoneElementor\Controls\FixSmartphoneIcon;

use Elementor;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Plugin {

	public $version = '0.2.3';

	protected $assetsUrl;
        
	public function __construct()
    {
        $this->assetsUrl = plugins_url( '/assets', __FILE__ );

        add_action( 'plugins_loaded', function(){
            load_plugin_textdomain( 'fix-smartphone-elementor', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        } );

        add_action( 'elementor/init', [ $this, 'elementorInit' ] );

        add_action('elementor/widgets/widgets_registered' , [$this, 'registerWidgets']);
        
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueueStyles' ] );
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
                    'title' => __( 'Other elements' , 'fix-smartphone-elementor' ),
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

	public function registerWidgets( $manager )
    {
        include_once 'widgets/FixSmartphone.php';
        $manager->register_widget_type( new FixSmartphone() );
	}
    
    
    public function enqueueStyles()
    {
        wp_enqueue_style('fix-smartphone-elementor', $this->assetsUrl . '/css/main.css', null, $this->version );
	}

}

new Plugin();
