<?php

/**
 * Description of DesktopMenuCustomizer
 *
 * @author satuskam
 */
class DesktopMenuCustomizer extends MenuCustomizer
{
    public $ctrlsData;
    
    public function __construct()
    {
        $this->_initCtrlData();
    }
    
    
    public function addSection($wpCustomize)
    {
        $sectionId = 'desktop_menu_styles_section';

        $wpCustomize->add_section( $sectionId , array(
            'title'    => __( 'Desktop menu styles' ),
            'priority' => 1010
        ));
            
        foreach ($this->ctrlsData as $cData) {
            $this->addControllToWpCustomizeSection($wpCustomize, $sectionId, $cData);
        }
    }
    
    
    private function _initCtrlData()
    {
        $this->ctrlsData = [
            [
                'id'      => 'desktop_menu_bar_bg',
                'type'    => 'WP_Customize_Color_Control',
                'label'   => __( 'Bar background' ),
                'default' => '#000'
            ],
            [
                'id'    => 'desktop_menu_bar_font_family',
                'type'  => 'text',
                'label' => __( 'Bar font family' ),
                'default' => 'Arial, "Helvetica CY", "Nimbus Sans L", sans-serif'
            ],
            [
                'id'    => 'desktop_menu_bar_line-height',
                'type'  => 'text',
                'label' => __( 'Bar line height' ),
                'default' => '1.69'
            ],

            $this->getFontSizeCustomizeControlData('desktop_menu_bar_font_size', 'Bar item font size (px)'),
            
            $this->getFontWeightCustomizeControlData('desktop_menu_bar_font_weight', 'Bar item font weight', 'bold'),
            
            [
                'id'      => 'desktop_menu_bar_font_color',
                'type'    => 'WP_Customize_Color_Control',
                'label'   => __( 'Bar item color' ),
                'default' => '#fff'
            ],
            [
                'id'    => 'desktop_menu_bar_active_font_color',
                'type'  => 'WP_Customize_Color_Control',
                'label' => __( 'Bar active item color' ),
                'default' => '#FF7F50'
            ],
            [
                'id'    => 'desktop_menu_bar_hover_font_color',
                'type'  => 'WP_Customize_Color_Control',
                'label' => __( 'Bar hover item color' ),
                'default' => '#f00'
            ],
            [
                'id'    => 'desktop_menu_bar_item_padding_top',
                'type'  => 'number',
                'label' => __( 'Bar item top padding(px)' ),
                'default' => 0
            ],
            [
                'id'    => 'desktop_menu_bar_item_padding_right',
                'type'  => 'number',
                'label' => __( 'Bar item right padding(px)' ),
                'default' => 4
            ],
            [
                'id'    => 'desktop_menu_bar_item_padding_bottom',
                'type'  => 'number',
                'label' => __( 'Bar item bottom padding(px)' ),
                'default' => 0
            ],
            [
                'id'    => 'desktop_menu_bar_item_padding_left',
                'type'  => 'number',
                'label' => __( 'Bar item left padding(px)' ),
                'default' => 5
            ],
            [
                'id'     => 'desktop_menu_delimeter_color',
                'type' => 'WP_Customize_Color_Control',
                'label'      => __( 'Delimeter color' ),
                'default' => '#f00'
            ],
            
            $this->getPositiveNumberCustomizeControlData(
                'desktop_menu_delimeter_width', 
                'Delimeter width'
            ),
            
            $this->getBorderTypeCustomizeControlData('desktop_menu_delimeter_type', 'Delimeter type'),
            
            [
                'id'      => 'desktop_submenu_indicator',
                'type'    => 'text',
                'label'   => __( 'Submenu inticator symbol' ),
                'default' => '\1f783'
            ],
            
            $this->getFontSizeCustomizeControlData('desktop_submenu_item_font_size', 'Submenu item font size(px)'),
            
            $this->getFontWeightCustomizeControlData('desktop_submenu_item_font_weight', 'Submenu item font weight', 'bold'),
            
            [
                'id'    => 'desktop_submenu_item_padding_top',
                'type'  => 'number',
                'label' => __( 'Submenu item top padding(px)' ),
                'default' => 8
            ],
            [
                'id'    => 'desktop_submenu_item_padding_right',
                'type'  => 'number',
                'label' => __( 'Submenu item right padding(px)' ),
                'default' => 12
            ],
            [
                'id'    => 'desktop_submenu_item_padding_bottom',
                'type'  => 'number',
                'label' => __( 'Submenu item bottom padding(px)' ),
                'default' => 8
            ],
            [
                'id'    => 'desktop_submenu_item_padding_left',
                'type'  => 'number',
                'label' => __( 'Submenu item left padding(px)' ),
                'default' => 12
            ],
            [
                'id'      => 'desktop_submenu_item_background',
                'type'    => 'WP_Customize_Color_Control',
                'label'   => __( 'Submenu item background' ),
                'default' => '#000'
            ],
            [
                'id'     => 'desktop_submenu_hover_item_background',
                'type' => 'WP_Customize_Color_Control',
                'label'      => __( 'Submenu item hover background' ),
                'default' => '#000'
            ],
            [
                'id'     => 'desktop_submenu_hover_item_color',
                'type' => 'WP_Customize_Color_Control',
                'label'      => __( 'Submenu item hover color' ),
                'default' => '#f00'
            ],
            [
                'id'      => 'desktop_submenu_delimeter_color',
                'type'    => 'WP_Customize_Color_Control',
                'label'   => __( 'Submenu delimeter color' ),
                'default' => '#f00'
            ],
            
            $this->getBorderTypeCustomizeControlData('desktop_submenu_delimeter_type', 'Submenu delimeter type'),
            
            $this->getPositiveNumberCustomizeControlData(
                'desktop_submenu_delimeter_width', 
                'Submenu delimeter width'
            ),
            
            $this->getBorderTypeCustomizeControlData('desktop_submenu_border_type', 'Submenu border type'),
            
            [
                'id'      => 'desktop_submenu_border_color',
                'type'    => 'WP_Customize_Color_Control',
                'label'   => __( 'Submenu border color' ),
                'default' => '#f00'
            ],
            
            $this->getPositiveNumberCustomizeControlData(
                'desktop_submenu_border_width', 
                'Submenu border width'
            )
        ];
    }
    
    
    public function renderCss()
    {
        $this->renderUnparametrizedMenuCss();
        $this->renderDesktopMenuBarCss();
        $this->renderDesktopSubMenuCss();
    }
    
    
    public function renderUnparametrizedMenuCss()
    {
        ?>
        <style>
            .topMenuSection #menu-main {
                padding: 0;
            }
            
            .topMenuSection ul li {
                display: inline-block;
            }

            .topMenuSection .rightMenuContainer {
                margin-bottom: 0px;
            }
            
            .rtl .topMenuSection ul li {
                border: none;
            }
            
            .topMenuSection .rightMenuContainer ul li:last-child {
                border: none;
            }
            
            .mobileSection .elementor-column {
                display: inline-block !important;
            }
            
            .menuLogo {
                width: 50% !important;
                display: inline-block;
            }
            
            .menuToggle, .menuPhone {
                width: 23% !important;
                display: inline-block;
            }
            
            @media all and (min-width: <?= $this->getThemeMod('mobile_menu_max_width') ?>px) {
                .topMenuSection .menuToggle, .topMenuSection .menuPhone, .topMenuSection .menuLogo {
                    display: none;
                }

                .topMenuSection .rightMenuContainer .menu {
                    text-align: left;
                }

                .rtl .topMenuSection .rightMenuContainer .menu {
                    text-align: right;
                }
                
                .topMenuSection .sub-menu {
                    display: none;
                    background-color: black;
                    padding: 0px;
                }

               .topMenuSection  {
                    z-index: 2;
                }
                
                /* Need to show underlying columns tabs in elementor edit mode */
                .elementor_library-template .topMenuSection  {
                    z-index: 0;
                    border: 1px solid blue;
                }
                
                .topMenuSection .menu-item-has-children:after {
                    font-size: 10px;
                    vertical-align: middle;
                }
                
                .topMenuSection .sub-menu .menu-item-has-children:after {
                    position: absolute;
                    transform: rotate(-90deg);
                    right: 10px;
                }
                .rtl .topMenuSection .sub-menu .menu-item-has-children:after {
                    left: 10px;
                    transform: rotate(90deg);
                    right: auto;
                }
                
                .topMenuSection .sub-menu .menu-item-has-children > a {
                    padding-right: 15px;
                }
                .rtl .topMenuSection .sub-menu .menu-item-has-children > a {
                    padding-left: 15px;
                    padding-right: 0px;
                }
                
                .topMenuSection .menu-item-has-children > .sub-menu {
                    position: absolute;
                    left: 0px;
                    z-index: 1000;
                }
                .rtl .topMenuSection .menu-item-has-children > .sub-menu {
                    right: 0px;
                    left: auto;
                }
                
                .topMenuSection .sub-menu .menu-item-has-children > .sub-menu {
                    left: 100%;
                    top: 30%;
                    z-index: 1000;
                }
                .rtl .topMenuSection .sub-menu .menu-item-has-children > .sub-menu {
                    right: 100%;
                    left: auto;
                }
                
                .topMenuSection .menu-item-has-children:hover > .sub-menu {
                    display: block;
                }

                .topMenuSection .menu-item-has-children {
                    position: relative;
                    z-index: 10000;
                }
                
                .topMenuSection ul .sub-menu li {
                    display: block;
                    border: none;
                    padding: 8px 12px;
                    white-space: nowrap;
                    text-align: left;
                }
                .rtl .topMenuSection ul .sub-menu li {
                    text-align: right;
                }
                
                .topMenuSection ul .sub-menu li a {
                   display: inline-block;
                   width: 100%;
                }

                .topMenuSection ul .sub-menu li:last-child {
                    border: none;
                }
            }
        </style>
        <?php
    }
    
    
    public function renderDesktopMenuBarCss()
    {
        ?>
        <style>
            .topMenuSection .elementor-row .rightMenu > .elementor-column-wrap {
                background-color: <?= $this->getThemeMod('desktop_menu_bar_bg') ?>
            }
            
            .topMenuSection #menu-main {
                line-height: <?= $this->getThemeMod('desktop_menu_bar_line-height') ?>;
                font-size: <?= $this->getThemeMod('desktop_menu_bar_font_size') ?>px;
                font-weight: <?= $this->getThemeMod('desktop_menu_bar_font_weight') ?>;
                font-family: <?= $this->getThemeMod('desktop_menu_bar_font_family') ?>;
            }

            .topMenuSection ul a {
                color: <?= $this->getThemeMod('desktop_menu_bar_font_color') ?>;
            }

            .topMenuSection ul li {
                padding: <?= $this->getThemeMod('desktop_menu_bar_item_padding_top') ?>px <?= $this->getThemeMod('desktop_menu_bar_item_padding_right') ?>px <?= $this->getThemeMod('desktop_menu_bar_item_padding_bottom') ?>px <?= $this->getThemeMod('desktop_menu_bar_item_padding_left') ?>px;
                border-right: <?= $this->getThemeMod('desktop_menu_delimeter_width') ?>px <?= $this->getThemeMod('desktop_menu_delimeter_type') ?> <?= $this->getThemeMod('desktop_menu_delimeter_color') ?>;
                line-height: <?= $this->getThemeMod('desktop_menu_bar_font_size') ?>px;
            }
            
            .rtl .topMenuSection ul li {
                border-left: <?= $this->getThemeMod('desktop_menu_delimeter_width') ?>px <?= $this->getThemeMod('desktop_menu_delimeter_type') ?> <?= $this->getThemeMod('desktop_menu_delimeter_color') ?>;
            }

            .topMenuSection .current-menu-item > a {
                color: <?= $this->getThemeMod('desktop_menu_bar_active_font_color') ?>;
            }
            
                
        </style>
        <?php
    }
    
    
    public function renderDesktopSubMenuCss()
    {
        ?>
        <style>
            @media all and (min-width: <?= $this->getThemeMod('mobile_menu_max_width') ?>px) {

                .topMenuSection ul > li:hover > a {
                    color: <?= $this->getThemeMod('desktop_menu_bar_hover_font_color') ?>;
                }

                .topMenuSection .menu-item-has-children:after {
                    content: '<?= $this->getThemeMod('desktop_submenu_indicator'); ?>';
                    color: <?= $this->getThemeMod('desktop_menu_bar_font_color') ?>;
                }

                .topMenuSection .menu-item-has-children:hover:after {
                    color: <?= $this->getThemeMod('desktop_menu_bar_hover_font_color') ?>;
                }

                .topMenuSection .menu-item-has-children:hover .sub-menu {
                    border: <?= $this->getThemeMod('desktop_submenu_border_width') ?>px <?= $this->getThemeMod('desktop_submenu_border_type') ?> <?= $this->getThemeMod('desktop_submenu_border_color') ?>;
                }
                
                .topMenuSection ul .sub-menu li {
                    font-size: <?= $this->getThemeMod('desktop_submenu_item_font_size') ?>px;
                    font-weight: <?= $this->getThemeMod('desktop_submenu_item_font_weight') ?>;
                    padding: <?= $this->getThemeMod('desktop_submenu_item_padding_top') ?>px <?= $this->getThemeMod('desktop_submenu_item_padding_right') ?>px <?= $this->getThemeMod('desktop_submenu_item_padding_bottom') ?>px <?= $this->getThemeMod('desktop_submenu_item_padding_left') ?>px;
                    border-bottom: <?= $this->getThemeMod('desktop_submenu_delimeter_width') ?>px <?= $this->getThemeMod('desktop_submenu_delimeter_type') ?> <?= $this->getThemeMod('desktop_submenu_delimeter_color') ?>;
                }
                
                .topMenuSection ul .sub-menu li:hover {
                    background-color: <?= $this->getThemeMod('desktop_submenu_hover_item_background') ?>;
                }
            }
            
        </style>
        <?php
    }
}
