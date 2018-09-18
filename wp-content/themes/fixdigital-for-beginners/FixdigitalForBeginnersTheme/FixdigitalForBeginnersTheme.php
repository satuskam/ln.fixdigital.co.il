<?php

require_once 'WpCustomizer/MenuCustomizer.php';

class FixdigitalForBeginnersTheme
{
    private $_menuCustomizer;
    
    public function init()
    {
        add_theme_support('menus');
        $this->_menuCustomizer = new MenuCustomizer();
        $this->_menuCustomizer->init();
        
        $this->_includeStyles();
        $this->_includeScripts();
        $this->_initWidgets();
        $this->_extendWpCustomizer();
//        $this->_createCustomMenuWidget();
//        $this->_addCustomImageSizes();  // not need yet
    }
    
    
    public function isContentPage()
    {
        return get_page_template_slug() === 'content_page.php';
    }
    
    
    private function _includeStyles()
    {
        function nikoletiStyles() {
            // Theme stylesheet.
            wp_enqueue_style( 'nikoleti-style', get_stylesheet_uri() );
        
            wp_enqueue_style(
                'custom-style',
                get_template_directory_uri() . '/css/style.css', 
                ['nikoleti-style'],
                '1.0'
            );
        }
        
        add_action( 'wp_enqueue_scripts', 'nikoletiStyles' );
    }
    
    
    private function _includeScripts()
    {
        function nikoletiScripts() {
            wp_enqueue_script(
                'modernizr',
                get_template_directory_uri() . '/js/modernizr-2.6.2.min.js', 
                [],
                '1.0',
                true
            );
            
            wp_enqueue_script(
                'jquery',
                'https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js', 
                ['modernizr'],
                '1.0',
                true
            );
            
            wp_enqueue_script(
                'bootstrap',
                get_template_directory_uri() . '/js/bootstrap.min.js', 
                ['jquery'],
                '1.0',
                true
            );
            
            wp_enqueue_script(
                'admin_customize',
                get_template_directory_uri() . '/js/admin.js', 
                ['jquery', 'bootstrap'],
                '3.1',
                true
            );

        }
        
        add_action( 'wp_enqueue_scripts', 'nikoletiScripts' );
    }
    
    
    private function _initWidgets()
    {
        add_action( 'widgets_init', function(){
            register_sidebar( [
		'name'          => __( 'Gallery page header', 'nikoleti' ),
		'id'            => 'gallery_page_header',
		'description'   => __( 'Add widgets here to appear in your gallery page header.', 'nikoleti' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
            ] );
            
            register_sidebar( [
		'name'          => __( 'Content page header', 'nikoleti' ),
		'id'            => 'content_page_header',
		'description'   => __( 'Add widgets here to appear in your gallery page header.', 'nikoleti' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
            ] );
            
            register_sidebar( [
                'name'          => __( 'Footer', 'nikoleti' ),
                'id'            => 'footer',
                'description'   => __( 'Add widgets here to appear in your footer.', 'nikoleti' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<span style="display: none;">',
                'after_title'   => '</span>',
            ] );
        });
    }
    

/*    
    private function _createCustomMenuWidget()
    {
        require('ResponsiveMultiMenusWidget.php');
        
        add_action( 'widgets_init', function(){
            register_widget( 'Mycustom_Widget' );
        });
    }
    
    
    private function _addCustomImageSizes()
    {
        if ( function_exists( 'add_image_size' ) ) {
            $imgSizes = [150, 250, 350, 500, 750, 1000, 1500, 2000, 3000];
            $thumbSizes = [100, 200, 300, 500];

            foreach($thumbSizes as $size) {
                add_image_size("thumb_{$size}x{$size}", $size, $size, true); //cropped
            }

            foreach($imgSizes as $size) {
                add_image_size("{$size}x{$size}", $size, $size, false); // scaled
            }
        }
    }
    */
    
    
    private function _extendWpCustomizer()
    {
        function addCommonBgImageSection($wp_customize) 
        {
            $wp_customize->add_section( 'common_background_image_section' , array(
                'title'    => __( 'Common background image' ),
                'priority' => 30
            ));   

            $wp_customize->add_setting( 'common_background_image' , array(
                'default'   => null,
                'type' => 'theme_mod',
                'transport' => 'refresh',
            ) );

            $bgControl = new WP_Customize_Upload_Control(
                $wp_customize,
                'common_background_image',
                [
                    'label'    => __( 'Common background image' ),
                    'section'  => 'common_background_image_section',
                    'settings' => 'common_background_image',
                ]
            );

            $wp_customize->add_control($bgControl);
        }
        add_action('customize_register', 'addCommonBgImageSection');
        
        
        function addJavaScriptSection($wp_customize) 
        {
            $wp_customize->add_section( 'js_section' , array(
                'title'    => __( 'JavaScript' ),
                'priority' => 1000
            ));
            
            $wp_customize->add_setting( 'custom_js', array(
                'type' => 'theme_mod',
                'default' => ''
            ) );

              $wp_customize->add_control( 'custom_js', array(
                'type' => 'textarea',
                'section' => 'js_section', // // Add a default or your own section
                'label' => __( 'JavaScript' ),
//                    'description' => __( 'Descr' ),
                'input_attrs' => [
                    'class' => 'code', // Ensures contents displayed as LTR instead of RTL.
                ]
              ) );

        }
        add_action('customize_register', 'addJavaScriptSection');
        
        
        function addFaviconSection($wp_customize) 
        {
            $wp_customize->add_section( 'favicon_section' , array(
                'title'    => __( 'Favicon' ),
                'priority' => 10
            ));
            
            $wp_customize->add_setting( 'site_icon', array(
                'type'       => 'option',
                'capability' => 'manage_options',
                'transport'  => 'postMessage', // Previewed with JS in the Customizer controls window.
            ) );

            $wp_customize->add_control( new WP_Customize_Site_Icon_Control( $wp_customize, 'site_icon', array(
                'label'       => __( 'Site Icon' ),
                'description' => sprintf(
                    /* translators: %s: site icon size in pixels */
                    __( 'The Site Icon is used as a browser and app icon for your site. Icons must be square, and at least %s pixels wide and tall.' ),
                    '<strong>512</strong>'
                ),
                'section'     => 'favicon_section',
                'priority'    => 10,
                'height'      => 512,
                'width'       => 512,
            ) ) );

        }
        add_action('customize_register', 'addFaviconSection');

    }
    
    
}




/*
 *  Desktop menu
 * ********************
 *  background color
 *  Border type, width, color
 *  Font: family, size, weight, color, active color, hover color
 *  Delimeter: content, color, size, weight
 *  Submenu border: type, width, color
 *  Submenu indicator: symbol
 *  Submenu item: background color, hover bg color, delim color
 * 
 *  Desktop menu min-width
 * 
 *  Mobile menu:
 * ********************
 * toggle color, toggle bg color, border,  Bar background, bar border, menu bg color, item delimeter color
 * Font: family, size, weight, color, active color, hover color
 *  
 * 
 */

