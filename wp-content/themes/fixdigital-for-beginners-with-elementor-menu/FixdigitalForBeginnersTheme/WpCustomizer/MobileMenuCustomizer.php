<?php

/**
 * Description of MobileMenuCustomizer
 *
 * @author satuskam
 */
class MobileMenuCustomizer extends MenuCustomizer
{
    public $ctrlsData;
    
    
    public function __construct()
    {
        $this->_initCtrlData();
    }
    
    
    private function _initCtrlData()
    {
        $this->ctrlsData = [
            $this->getPositiveNumberCustomizeControlData(
                'mobile_menu_max_width', 
                'Mobile menu maximal width',
                914
            ),
            
            [
                'id'      => 'mobile_menu_bg',
                'type'    => 'WP_Customize_Color_Control',
                'label'   => __( 'Background color' ),
                'default' => '#000'
            ],
//            [
//                'id'          => 'mobile_menu_bg_opacity',
//                'type'        => 'number',
//                'label'       => __( 'Background opacity' ),
//                'default'     =>  0.95,
//                'input_attrs' => [
//                    'min' => 0,
//                    'max' => 1,
//                    'step' => 0.05
//                ]
//            ],
            [
                'id'      => 'toggle_bg_color',
                'type'    => 'WP_Customize_Color_Control',
                'label'   => __( 'Toggle background color' ),
                'default' => '#fff'
            ],
            [
                'id'      => 'toggle_icon_color',
                'type'    => 'WP_Customize_Color_Control',
                'label'   => __( 'Toggle icon color' ),
                'default' => '#000'
            ],
            
            $this->getBorderTypeCustomizeControlData('mobile_bar_delimeter_type', 'Menu bar delimeter type'),
            
            [
                'id'      => 'mobile_bar_delimeter_color',
                'type'    => 'WP_Customize_Color_Control',
                'label'   => __( 'Menu bar delimeter color' ),
                'default' => '#808080'
            ],
            
            $this->getPositiveNumberCustomizeControlData(
                'mobile_bar_delimeter_width', 
                'Menu bar delimeter width'
            ),
            
            [
                'id'    => 'mobile_menu_font_family',
                'type'  => 'text',
                'label' => __( 'Item font family' ),
                'default' => 'Arial, "Helvetica CY", "Nimbus Sans L", sans-serif'
            ],

            $this->getFontSizeCustomizeControlData('mobile_menu_font_size', 'Item font size (px)'),
            
            $this->getFontWeightCustomizeControlData('mobile_menu_font_weight', 'Item font weight', 'bold'),
            
            [
                'id'      => 'mobile_menu_font_color',
                'type'    => 'WP_Customize_Color_Control',
                'label'   => __( 'Item color' ),
                'default' => '#fff'
            ],
            [
                'id'    => 'mobile_menu_active_font_color',
                'type'  => 'WP_Customize_Color_Control',
                'label' => __( 'Active item color' ),
                'default' => '#FF7F50'
            ],
            [
                'id'    => 'mobile_menu_hover_font_color',
                'type'  => 'WP_Customize_Color_Control',
                'label' => __( 'Hover item color' ),
                'default' => '#f00'
            ],
            $this->getBorderTypeCustomizeControlData('mobile_menu_item_delimeter_type', 'Item delimeter type'),
            
            [
                'id'      => 'mobile_menu_item_delimeter_color',
                'type'    => 'WP_Customize_Color_Control',
                'label'   => __( 'Item delimeter color' ),
                'default' => '#f00'
            ],
            
            $this->getPositiveNumberCustomizeControlData(
                'mobile_menu_item_delimeter_width', 
                'Item delimeter width'
            ),
            
            [
                'id'    => 'mobile_menu_item_padding_top',
                'type'  => 'number',
                'label' => __( 'Bar item top padding(px)' ),
                'default' => 12
            ],
            [
                'id'    => 'mobile_menu_item_padding_right',
                'type'  => 'number',
                'label' => __( 'Bar item right padding(px)' ),
                'default' => 0
            ],
            [
                'id'    => 'mobile_menu_item_padding_bottom',
                'type'  => 'number',
                'label' => __( 'Bar item bottom padding(px)' ),
                'default' => 12
            ],
            [
                'id'    => 'mobile_menu_item_padding_left',
                'type'  => 'number',
                'label' => __( 'Bar item left padding(px)' ),
                'default' => 0
            ],
        ];
    }
    
    
    public function addSection($wpCustomize)
    {
        $sectionId = 'mobile_menu_styles_section';

        $wpCustomize->add_section( $sectionId , array(
            'title'    => __( 'Mobile menu styles' ),
            'priority' => 1010
        ));
            
        foreach ($this->ctrlsData as $cData) {
            $this->addControllToWpCustomizeSection($wpCustomize, $sectionId, $cData);
        }
    }
    
    
    public function renderCss()
    {
        ?>
        <style>
            @media all and (max-width: <?= $this->getThemeMod('mobile_menu_max_width') ?>px) {
                .desktopInfoSection,
                .topMenuSection .rightMenuContainer {
                    display: none;
                }

                .topMenuSection .menuToggle,
                .topMenuSection .menuPhone,
                .topMenuSection .menuLogo {
                    border: none;
                    padding: 0px;
                    margin-bottom: 0px;
                }

                .headerInfoContainer {
                    padding-top: 55px;
                }

                .topMenuSection {
                    display: block;
                    position: relative;
                    z-index: 100;
                }

                .topMenuSection .elementor-row {
                    display: block;
                    /*position: absolute;*/
                }

                .showMobileMenu.topMenuSection .rightMenu,
                .showMobileMenu.topMenuSection .rightMenuContainer {
                    display: block;
                    margin-bottom: 0;
                }

                .topMenuSection .rightMenu {
                    width: 100% !important;
                    margin-bottom: 0;
                    top: 0px;
                }

                .topMenuSection .menu {
                   margin: 0 0 0 10px;
                }

                .rtl .topMenuSection .menu {
                   margin: 10px 10px 0 0;
                }

                .topMenuSection .menu li {
                    display: block;
                    text-align: left;
                    border: none;
                }

                .topMenuSection .menu li a,
                .topMenuSection .sub-menu li a {
                    display: inline-block;
                }

                .rtl .topMenuSection .menu li {
                    text-align: right;
                    border: none;
                }

                .topMenuSection .sub-menu li {
                    padding: auto 12px;
                }

                .showMobileMenu.topMenuSection .menuToggle {
                    margin-bottom: 20px;
                    padding-bottom: 5px;
                }

                .topMenuSection .elementor-row {
                    /*padding-bottom: 20px;*/
                }
            }
        </style>
        <?php
        
        $this->renderParametredCss();
    }
    
    
    public function renderParametredCss()
    {
        ?>
        <style>
            @media all and (max-width: <?= $this->getThemeMod('mobile_menu_max_width') ?>px) {
                
                .topMenuSection .sub-menu {
                    padding: 0px !important;
                }
                
                .topMenuSection ul > li > a {
                    color: <?= $this->getThemeMod('mobile_menu_font_color') ?>;
                    padding: <?= $this->getThemeMod('mobile_menu_item_padding_top') ?>px <?= $this->getThemeMod('mobile_menu_item_padding_right') ?>px <?= $this->getThemeMod('mobile_menu_item_padding_bottom') ?>px <?= $this->getThemeMod('mobile_menu_item_padding_left') ?>px;
                }
                
                .topMenuSection ul > li.current-menu-item > a {
                    color: <?= $this->getThemeMod('mobile_menu_active_font_color') ?>;
                }
                
                .topMenuSection ul > li > a:hover {
                    color: <?= $this->getThemeMod('mobile_menu_hover_font_color') ?>;
                }
                
                .topMenuSection .menu li a,
                .topMenuSection .sub-menu li a {
                    color: <?= $this->getThemeMod('mobile_menu_font_color') ?>;
                    font-family: <?= $this->getThemeMod('mobile_menu_font_family') ?>;
                    font-size: <?= $this->getThemeMod('mobile_menu_font_size') ?>px;
                    font-weight: <?= $this->getThemeMod('mobile_menu_font_weight') ?>;
                    border-bottom: <?= $this->getThemeMod('mobile_menu_item_delimeter_width') ?>px <?= $this->getThemeMod('mobile_menu_item_delimeter_type') ?> <?= $this->getThemeMod('mobile_menu_item_delimeter_color') ?>;
                }
                
                .topMenuSection .elementor-row .rightMenu > .elementor-column-wrap {
                    background-color: <?= $this->getThemeMod('mobile_menu_bg') ?>
                }
                
                .topMenuSection .rightMenu .menuToggle a.elementor-button {
                    background-color: <?= $this->getThemeMod('toggle_bg_color') ?>;
                    color: <?= $this->getThemeMod('toggle_icon_color') ?>;
                }
                
                .showMobileMenu.topMenuSection .menuToggle {
                    border-bottom: <?= $this->getThemeMod('mobile_bar_delimeter_width') ?>px <?= $this->getThemeMod('mobile_bar_delimeter_type') ?> <?= $this->getThemeMod('mobile_bar_delimeter_color') ?>;
                }
            }
        </style>
        <?php
    }
   
}
