<?php

require_once 'DesktopMenuCustomizer.php';
require_once 'MobileMenuCustomizer.php';

/**
 * Description of MenuCustomiser
 *
 * @author satuskam
 */
class MenuCustomizer
{
    public $ctrlsData;
    
    private $_desktopMenuCustomizer;
    private $_mobileMenuCustomizer;
    
    public function init()
    {
        $this->_desktopMenuCustomizer = new DesktopMenuCustomizer();
        add_action('customize_register', [$this->_desktopMenuCustomizer, 'addSection']);
        add_action('wp_footer', [$this->_desktopMenuCustomizer, 'renderCss'], 1010);
        
        $this->_mobileMenuCustomizer = new MobileMenuCustomizer();
        add_action('customize_register', [$this->_mobileMenuCustomizer, 'addSection']);
        add_action('wp_footer', [$this->_mobileMenuCustomizer, 'renderCss'], 1010);
    }

    
    public function addControllToWpCustomizeSection($wpCustomize, $sectionId, $cData)
    {
        $simpleCtrlTypes = ['text', 'select', 'textarea', 'number'];
        
        $id = $cData['id'];
        $ctrlType = $cData['type'];

        if (in_array($ctrlType, $simpleCtrlTypes, true)) {
            $ctrlClass = 'WP_Customize_Control';
        } else {
            $ctrlClass = $cData['type'];
            $ctrlType = null;
        }

        $default = isset($cData['default']) ? $cData['default'] : '';

        $wpCustomize->add_setting( $id, array(
            'type' => 'theme_mod',
            'default' => $default
        ) );

        $ctrlParams = [
            'label'     => $cData['label'],
            'section'   => $sectionId,
            'settings'  => $id,
            'type'      => $ctrlType,
            'priority'  => isset($cData['priority']) ? $cData['priority'] : 100
        ];

        if (isset($cData['input_attrs'])) {
            $ctrlParams['input_attrs'] = $cData['input_attrs'];
        }

        if (isset($cData['choices'])) {
            $ctrlParams['choices'] = $cData['choices'];
        }

        $ctrl = new $ctrlClass(
            $wpCustomize, 
            $id, 
            $ctrlParams
        );

        $wpCustomize->add_control($ctrl);
    }
            
            
    public function getFontSizeCustomizeControlData($id, $label, $default=14)
    {
        return [
            'id'          => $id,
            'type'        => 'number',
            'label'       => __( $label ),
            'input_attrs' => [
                'min'  => 6,
                'max'  => 120,
                'step' => 1
            ],
            'default' => $default
        ];
    }
    
    
    public function getFontWeightCustomizeControlData($id, $label, $default='normal')
    {
        return [
            'id'    => $id,
            'type'  => 'select',
            'label' => __( $label ),
            'choices' => [
                'lighter' => 'lighter',
                'normal' => 'normal',
                'bold' => 'bold',
                'bolder' => 'bolder'
            ],
            'default' => $default
        ];
    }
    
    
    public function getBorderTypeCustomizeControlData($id, $label, $default='solid')
    {
        return [
            'id'    => $id,
            'type'  => 'select',
            'label' => __( $label ),
            'choices' => [
                'none'   => 'none',
                'solid'  => 'solid',
                'dashed' => 'dashed',
                'dotted' => 'dotted',
                'double' => 'double'
            ],
            'default' => $default
        ];
    }
    
    public function getPositiveNumberCustomizeControlData($id, $label, $default=1)
    {
        return [
            'id'          => $id,
            'type'        => 'number',
            'label'       => __( $label ),
            'default'     => $default,
            'input_attrs' => [
                'min' => 0,
                'step' => 1
            ]
        ];
    }
    
    
    /*
     *  Try to get value by get_theme_mod().
     *  If gotten value is empty then try to get default value from the cusomize control's structure
     */
    public function getThemeMod($option)
    {
        $val = get_theme_mod($option);

        if (!isset($val) || $val === false) {
            foreach ($this->ctrlsData as $sData) {
                if ($sData['id'] === $option) {
                    $val = $sData['default'];
                    break;
                }
            }
        }
            
        return $val;
    }
}
