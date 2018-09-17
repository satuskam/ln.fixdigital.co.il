<?php
/*
	Plugin Name: UCO Breadcrumbs Elementor
	Description: Breadcrumbs from UCO. Additional element for Elementor.
	Version:     0.1.4
	Author:      uco
	License:     GPL2
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain: uco-breadcumbs-elementor
	Domain Path: /languages/
 */


namespace UcoBreadcrumbsElementor;

use UcoBreadcrumbsElementor\Widgets\UcoBreadcrumbs;

use Elementor;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Plugin {

	public $version = '0.1.4';

	protected $assetsUrl;
        
	public function __construct()
    {
        require_once 'Breadcrumbs.php';
        
        $this->assetsUrl = plugins_url( '/assets', __FILE__ );

        add_action( 'plugins_loaded', function(){
            load_plugin_textdomain( 'uco-breadcumbs-elementor', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        } );

        add_action( 'elementor/init', [ $this, 'elementorInit' ] );

        add_action('elementor/widgets/widgets_registered' , [$this, 'registerWidgets']);

        add_action('elementor/widgets/widgets_registered', [$this, 'removeYoastBreadcrumbsWidget'], 1000 );
        
//        add_action( 'wp_enqueue_scripts', [ $this, 'enqueueStyles' ] );
	}
        

	public function elementorInit()
    {
        if (!$this->_checkRequirements()) return;
        
        $elManager = \Elementor\Plugin::instance()->elements_manager;
        $cats = $elManager->get_categories();

        if (!isset($cats['other-elements'])) {
            // Add element category in panel
            $elManager->add_category(
                'other-elements',
                [
                    'title' => __( 'Other elements' , 'uco-breadcumbs-elementor' ),
                    'icon' => 'font',
                ],
                1
            );
        }
	}
    
    
    private function _checkRequirements()
    {
        // check for reqired classes
        $requiredClasses = [
            'Elementor\Scheme_Color',
            'Elementor\Widget_Base',
            'Elementor\Controls_Manager',
            'Elementor\Group_Control_Typography',
            'Elementor\Scheme_Typography'
        ];
        
        foreach ($requiredClasses as $class) {
            if (!class_exists($class)) {
                return false;
            }
        }
        
        return true;
    }
    

	/**
	 * @param $manager Elementor\Widgets_Manager
	 *
	 */

	public function registerWidgets( $manager )
    {
        include_once 'widgets/UcoBreadcrumbs.php';
        $manager->register_widget_type( new UcoBreadcrumbs() );
	}
    

    public function removeYoastBreadcrumbsWidget($manager)
    {
	    $manager->unregister_widget_type('breadcrumbs'); 
    }

    
//    public function enqueueStyles()
//    {
//        wp_enqueue_style('uco-breadcumbs-elementor', $this->assetsUrl . '/css/main.css', null, $this->version );
//	}

}

new Plugin();
