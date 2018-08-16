<?php
namespace UcoBreadcrumbsElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;
use Elementor\Scheme_Color;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class UcoBreadcrumbs extends Widget_Base {

	public function get_name() {
		return 'uco-breadcrumbs';
        // return 'breadcrumbs';
	}
 
	public function get_title() {
		return __( 'Breadcrumbs', 'uco-breadcumbs-elementor' );
	}

	public function get_icon() {
		return 'fa fa-ellipsis-h';
	}

	public function get_categories() {
		return [ 'other-elements' ];
	}

    
    protected function _register_controls()
    {
		$this->start_controls_section(
			'uco_breadcrumbs',
			[
				'label' => __( 'UCO Breadcrumbs', 'elementor' ),
			]
		);

		$this->add_control(
			'markup',
			[
				'label' => __( 'Markup', 'uco-breadcumbs-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''                        => __( 'Default', 'elementor' ),
					'schema.org'              => __( 'schema.org', 'elementor' ),
					'rdf.data-vocabulary.org' => __( 'rdf.data-vocabulary.org', 'elementor' ),
				],
				'default' => ''
			]
		);
        
		$this->add_control(
			'separator',
			[
				'label' => __( 'Separator', 'uco-breadcumbs-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '>'
			]
		);
        
        $this->add_control(
            'on_front_page',
            [
                'label' => __( 'Show on frontpage', 'uco-breadcumbs-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '1',
                'return_value' => '1',
                'label_on' => __( 'Yes', 'elementor' ),
                'label_off' => __( 'No', 'elementor' ),
            ]
        );
        
        $this->add_control(
            'show_post_title',
            [
                'label' => __( 'Show current page title', 'uco-breadcumbs-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '1',
                'return_value' => '1',
                'label_on' => __( 'Yes', 'elementor' ),
                'label_off' => __( 'No', 'elementor' ),
            ]
        );
        
        $this->add_control(
            'show_nofollow',
            [
                'label' => __( "Add 'nofollow' to links", 'uco-breadcumbs-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => '1',
                'label_on' => __( 'Yes', 'elementor' ),
                'label_off' => __( 'No', 'elementor' ),
            ]
        );

		$this->add_control(
			'alignment',
			[
				'label' => __( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'fa fa-align-right',
					]
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
        
        
        $this->_registerControlsForStyleLinkSection();

		$this->_registerControlsForStyleSeparatorSection();
        
        $this->_registerControlsForStyleCurrentPageSection();

	}

    
    private function _registerControlsForStyleLinkSection()
    {
        
        
        $this->start_controls_section(
			'section_style_links',
			[
				'label' => __( 'Links', 'uco-breadcumbs-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
        
        $this->start_controls_tabs( 'tabs_links_style' );

		$this->start_controls_tab(
			'tab_links_normal',
			[
				'label' => __( 'Normal', 'elementor' ),
			]
		);

		$this->add_control(
			'links_color',
			[
				'label' => __( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'default' => '#5eb1d4',
				'selectors' => [
					'{{WRAPPER}} a' => 'color: {{VALUE}};'
                ],
			]
		);
        
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'links_typography',
				'selector' => '{{WRAPPER}} a',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
			'tab_links_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);
        
        $this->add_control(
			'hover_links_color',
			[
				'label' => __( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'default' => '#4ea1c4',
				'selectors' => [
					'{{WRAPPER}} a:hover' => 'color: {{VALUE}};'
                ],
			]
		);
        
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'hover_links_typography',
				'selector' => '{{WRAPPER}} a:hover',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);
        
        $this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->end_controls_section();
    }
    
    
    private function _registerControlsForStyleSeparatorSection()
    {
        $this->start_controls_section(
			'section_style_separator',
			[
				'label' => __( 'Separator', 'uco-breadcumbs-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
        
        $this->add_control(
			'separator_color',
			[
				'label' => __( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'default' => '#555555',
				'selectors' => [
					'{{WRAPPER}} .kb_sep' => 'color: {{VALUE}};'
                ],
			]
		);
        
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'separator_typography',
				'selector' => '{{WRAPPER}} .kb_sep',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);
        
        $this->add_responsive_control(
			'separator_space',
			[
				'label' => __( 'Spacing', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .kb_sep' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					
				],
			]
		);
        
        $this->end_controls_section();
    }
    
    
    private function _registerControlsForStyleCurrentPageSection()
    {
        $this->start_controls_section(
			'section_style_current_page',
			[
				'label' => __( 'Current page title', 'uco-breadcumbs-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
					'show_post_title' => '1',
				]
			]
		);

		$this->add_control(
			'current_page_color',
			[
				'label' => __( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .kb_title' => 'color: {{VALUE}};'
                ],
			]
		);
        
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'current_page_typography',
				'selector' => '{{WRAPPER}} .kb_title',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);
		
		$this->end_controls_section();
    }
    

    private function _getBreadcrumbsOptions()
    {
        $options = array(
            'on_front_page' =>  (bool) $this->get_settings('on_front_page'),
            'show_post_title' => (bool) $this->get_settings('show_post_title'),
            'nofollow' => (bool) $this->get_settings('show_nofollow'),
            'separator'   =>  $this->get_settings('separator'),
            'markup'   =>  $this->get_settings('markup')
        );
        
        return $options;
    }
    
    
    protected function render()
    {
        $opts = $this->_getBreadcrumbsOptions();
        $separator = $this->get_settings('separator');
        
        $bc = new \UcoBreadcrumbsElementor\Breadcrumbs();

        $crumbs = $bc->get_crumbs($separator, [], $opts);

        echo $crumbs;
    }
    
}
