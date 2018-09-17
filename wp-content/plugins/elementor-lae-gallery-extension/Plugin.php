<?php
/*
	Plugin Name: LAE Gallery Extension 
	Description: Extends the LAE gellery(addons-for-elementor-pro): add subtitle, add option to display item's info below the item
	Version: 0.1.1
	Author: uco
	License:     GPL2
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain: lae-gallery-extension
	Domain Path: /languages/
 */


namespace LaeGalleryExtension;


use LaeGalleryExtension\Widgets\Gallery as Gallery;
use Elementor;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Plugin {

	public $version = '0.1.1';

        
    public function __construct()
    {
        // It's necessary for loading gallery items by ajax
        add_action( 'elementor/init', function() {
            if ($this->_checkRequirements(true)) {
                require_once 'widgets/common.php';
            }
        } );
        
        // It's necessary for usual initialisation of our our gallery extention
        add_action( 'elementor/widgets/widgets_registered', [$this, 'elementorProInit'], 1000, 1 );
    }
    
        
    public function elementorProInit($widgetsManager)
    {
        if (!$this->_checkRequirements()) return;
        
        require_once 'widgets/common.php';
        require_once 'widgets/gallery.php';

        $widgetsManager->register_widget_type(new Gallery());
    }
    
    
    private function _checkRequirements($forAjax=false)
    {
        // check for reqired classes
        $requiredClasses = ['LivemeshAddons\Gallery\LAE_Gallery_Common'];
        
        if (!$forAjax) {
            $requiredClasses = array_merge($requiredClasses, [
                'Elementor\Plugin',
                'ElementorPro\Plugin',
                'LivemeshAddons\Widgets\LAE_Gallery_Widget'
            ]);
        }
        
        foreach ($requiredClasses as $class) {
            if (!class_exists($class)) {
                return false;
            }
        }
        
        return true;
    }


}


new Plugin();

