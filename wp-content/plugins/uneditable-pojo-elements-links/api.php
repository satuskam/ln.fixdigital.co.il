<?php
/*
Plugin Name:  Uneditable Pojo elements links
Description: Add links to uneditable Pojo elements(header, footer) to provide a convenient way to edit
Version: 0.2.2
Author: satuskam
Author URI: atuskam@gmail.com
*/

namespace UneditablePojoElementsLinks;

class Plugin {
    
    private $_version = '0.2.2';
    
    public function __construct()
    {
        add_action( 'elementor/editor/before_enqueue_scripts', [$this, 'addUneditablePojoElementsLinksScript'] );
        add_action( 'admin_enqueue_scripts', [$this, 'autoPassingIntoSubMenuScript'] );
    }
    

    private function addUneditablePojoElementsLinksScript()
    {
        wp_enqueue_script( 
            'add_uneditable_pojo_elements_links',
            plugin_dir_url(__FILE__) . 'js/add_uneditable_pojo_elements_links.js',
            ['jquery', 'elementor-dialog'],
            $this->_version,
            true 						
        );
    }


    public function autoPassingIntoSubMenuScript()
    {
        if (!isset($_GET['clickOn'])) return;

        wp_enqueue_script( 
            'auto_passing_into_submenu',
            plugin_dir_url(__FILE__) . 'js/auto_passing_into_submenu.js',
            ['jquery'],
            $this->_version,
            true 						
        );
    }
    
    
}

new Plugin;