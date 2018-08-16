<?php
namespace FixSmartphoneElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;
use Elementor\Widget_Icon_Box;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class FixSmartphone extends Widget_Icon_Box {

	public function get_name() {
		return 'fix-smartphone';
	}

	public function get_title() {
		return __( 'FixDigital Smartphone', 'fix-smartphone-elementor' );
	}

	public function get_icon() {
		return 'fa fa-phone';
	}

	public function get_categories() {
		return [ 'other-elements' ];
	}

//	public function get_script_depends() {
//		return [ 'imagesloaded', 'jquery-slick'];
//	}

    
    	protected function _register_controls() {
		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'FixDigital Smartphone', 'elementor' ),
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'fix-smartphone-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => __( 'Default', 'elementor' ),
					'stacked' => __( 'Stacked', 'elementor' ),
					'framed' => __( 'Framed', 'elementor' ),
				],
				'default' => 'default',
				'prefix_class' => 'elementor-view-',
			]
		);
        
		$this->add_control(
			'icon',
			[
				'label' => __( 'Choose Icon', 'elementor' ),
				'type' => Controls_Manager::ICON,
                'include' => [
                    'fa fa-phone',
                    'fa fa-phone-square',
                    'fa fa-mobile-phone'
                ],
				'default' => 'fa fa-phone',
			]
		);

		$this->add_control(
			'shape',
			[
				'label' => __( 'Shape', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'circle' => __( 'Circle', 'elementor' ),
					'square' => __( 'Square', 'elementor' ),
				],
				'default' => 'circle',
				'condition' => [
					'view!' => 'default',
				],
				'prefix_class' => 'elementor-shape-',
			]
		);

		$this->add_control(
			'title_text',
			[
				'label' => __( 'Number & Description', 'fix-smartphone-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '123456789', 'elementor' ),
				'placeholder' => __( 'Phone number', 'fix-smartphone-elementor' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'description_text',
			[
				'label' => '',
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( '', 'elementor' ),
				'placeholder' => __( 'Your Description', 'elementor' ),
				'title' => __( 'Input icon text here', 'elementor' ),
				'rows' => 10,
				'separator' => 'none',
				'show_label' => false,
			]
		);
        
        $this->add_control(
            'show_description_before_number',
            [
                'label' => __( 'Show Description before Number', 'fix-smartphone-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => 'yes',
                'label_on' => __( 'Yes', 'elementor' ),
                'label_off' => __( 'No', 'elementor' ),
            ]
        );

		$this->add_control(
			'position',
			[
				'label' => __( 'Icon Position', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'top' => [
						'title' => __( 'Top', 'elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'prefix_class' => 'elementor-position-',
				'toggle' => false,
			]
		);

		$this->add_control(
			'title_size',
			[
				'label' => __( 'Number HTML Tag', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => __( 'H1', 'elementor' ),
					'h2' => __( 'H2', 'elementor' ),
					'h3' => __( 'H3', 'elementor' ),
					'h4' => __( 'H4', 'elementor' ),
					'h5' => __( 'H5', 'elementor' ),
					'h6' => __( 'H6', 'elementor' ),
					'div' => __( 'div', 'elementor' ),
					'span' => __( 'span', 'elementor' ),
					'p' => __( 'p', 'elementor' ),
				],
				'default' => 'h3',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => __( 'Icon', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'primary_color',
			[
				'label' => __( 'Primary Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'secondary_color',
			[
				'label' => __( 'Secondary Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'view!' => 'default',
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_space',
			[
				'label' => __( 'Spacing', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-position-right .elementor-icon-box-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.elementor-position-left .elementor-icon-box-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.elementor-position-top .elementor-icon-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}} .elementor-icon-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_padding',
			[
				'label' => __( 'Padding', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'condition' => [
					'view!' => 'default',
				],
			]
		);

		$this->add_control(
			'rotate',
			[
				'label' => __( 'Rotate', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
					'unit' => 'deg',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'border_width',
			[
				'label' => __( 'Border Width', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'view' => 'framed',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'view!' => 'default',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_hover',
			[
				'label' => __( 'Icon Hover', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'hover_primary_color',
			[
				'label' => __( 'Primary Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-framed .elementor-icon:hover, {{WRAPPER}}.elementor-view-default .elementor-icon:hover' => 'color: {{VALUE}}; border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_secondary_color',
			[
				'label' => __( 'Secondary Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'view!' => 'default',
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-view-framed .elementor-icon:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Animation', 'elementor' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => __( 'Content', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'text_align',
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
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-box-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_vertical_alignment',
			[
				'label' => __( 'Vertical Alignment', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => __( 'Top', 'elementor' ),
					'middle' => __( 'Middle', 'elementor' ),
					'bottom' => __( 'Bottom', 'elementor' ),
				],
				'default' => 'middle',
				'prefix_class' => 'elementor-vertical-align-',
			]
		);
        
        $this->add_control(
            'show_content_on_mobile',
            [
                'label' => __( 'Show Content on Mobile', 'fix-smartphone-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => 'yes',
                'label_on' => __( 'Show', 'elementor' ),
                'label_off' => __( 'Hide', 'elementor' ),
            ]
        );

		$this->add_control(
			'heading_title',
			[
				'label' => __( 'Number', 'elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_bottom_space',
			[
				'label' => __( 'Spacing', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-box-content .elementor-icon-box-title' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .elementor-icon-box-content .elementor-icon-box-title',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'heading_description',
			[
				'label' => __( 'Description', 'elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-box-content .elementor-icon-box-description' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .elementor-icon-box-content .elementor-icon-box-description',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();
	}

 
    protected function render() {
        $uniqId = uniqid('fixSmartphoneElement-');
        
		$settings = $this->get_settings();
        
        $icon_tag = 'span';
        
        $fixSmartphoneHrefClass = '';
        
        if ( ! empty( $settings['title_text'] ) ) {
			$this->add_render_attribute( 'link', 'href', 'tel:'.$settings['title_text'] );
			$icon_tag = 'a';

            $this->add_render_attribute( 'link', 'class', 'fix_smartphone_href' );
            
            $fixSmartphoneHrefClass = 'fix_smartphone_href';
		}

		$this->add_render_attribute( 'icon', 'class', [
            'elementor-icon',
            'elementor-animation-' . $settings['hover_animation'] ,
            $fixSmartphoneHrefClass
        ]);

		$this->add_render_attribute( 'i', 'class', $settings['icon'] );

		$icon_attributes = $this->get_render_attribute_string( 'icon' );
		$link_attributes = $this->get_render_attribute_string( 'link' );

		$this->add_render_attribute( 'description_text', 'class', 'elementor-icon-box-description' );
		$this->add_render_attribute( 'description_text', 'style', 'min-height:0' );

		$this->add_inline_editing_attributes( 'title_text', 'none' );

		$this->add_inline_editing_attributes( 'description_text' );
        
        // save markup of title(number) to $titleMarkup
        ob_start();
		?>

            <<?php echo $settings['title_size']; ?> class="elementor-icon-box-title">
                <<?php echo implode( ' ', [ $icon_tag, $link_attributes ] ); ?>
                    <?php echo $this->get_render_attribute_string( 'title_text' ); ?>>
                    <span class="fix_smartphone"><?php echo $settings['title_text']; ?></span>
                </<?php echo $icon_tag; ?>>
            </<?php echo $settings['title_size']; ?>>

        <?php
            $titleMarkup = ob_get_clean();
            
            // save markup of description to $descrMarkup
            ob_start();
        ?>
            
            <p <?php echo $this->get_render_attribute_string( 'description_text' ); ?>>
                <?= str_replace("\n", '<br />', $settings['description_text'] ); ?>
            </p>
                
        <?php
            $descrMarkup = ob_get_clean();
        ?>
                
        <div id="<?= $uniqId ?>" class="elementor-icon-box-wrapper fixSmartphoneElement">
            <div class="elementor-icon-box-icon">
                <<?php echo implode( ' ', [ $icon_tag, $icon_attributes, $link_attributes ] ); ?>>
                    <i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i>
                </<?php echo $icon_tag; ?>>
            </div>
            <div class="elementor-icon-box-content" >
                <?php if ($settings['show_description_before_number'] === 'yes') : ?>
                    <?= $descrMarkup ?>
                    <?= $titleMarkup ?>
                <?php else: ?>
                    <?= $titleMarkup ?>
                    <?= $descrMarkup ?>
                <?php endif; ?>
            </div>
        </div>
        
            <?php if ($settings['show_content_on_mobile'] !== 'yes') : ?>
                <style>
                    @media (max-width: 767px) {
                        #<?= $uniqId ?> .elementor-icon-box-content {
                            display: none;
                        }
                    }
                </style>
            <?php endif; ?>
                
            <style>
                #<?= $uniqId ?> .elementor-icon-box-title .fix_smartphone_href {
                    color: inherit;
                }
                
                .fixSmartphoneElement .elementor-icon-box-title {
                    padding: 0px;
                }
            </style>
		<?php
	}
    
    
    protected function _content_template() {
        $uniqId = uniqid('fixSmartphoneElement-');
        
		?>
        <# 
            var link = settings.title_text ? 'href="tel:' + settings.title_text + '"' : '';
			var iconTag = link ? 'a' : 'span';
            var displayVal = settings.show_content_on_mobile === 'yes' ? '' : 'none';
            console.log(settings.show_content_on_mobile);
        #>
        
        <?php
            ob_start();
        ?>
            <{{{ settings.title_size }}} class="elementor-icon-box-title"  style="padding:0">
                <{{{ iconTag + ' ' + link }}} class="elementor-inline-editing" data-elementor-setting-key="title_text" data-elementor-inline-editing-toolbar="none">{{{ settings.title_text }}}</{{{ iconTag }}}>
            </{{{ settings.title_size }}}>
        <?php
            $titleMarkup = ob_get_clean();
            
            ob_start();
        ?>
            <p class="elementor-icon-box-description elementor-inline-editing" data-elementor-setting-key="description_text" style="min-height: 0">
                {{{ settings.description_text.replace(/\n/g, '<br />') }}}
            </p>
        <?php
            $descrMarkup = ob_get_clean();
        ?>
            
            
        
        <div id="<?= $uniqId ?>" class="elementor-icon-box-wrapper fixSmartphoneElement">
            <div class="elementor-icon-box-icon">
                <{{{ iconTag + ' ' + link }}} class="elementor-icon elementor-animation-{{ settings.hover_animation }}">
                    <i class="{{ settings.icon }}"></i>
                </{{{ iconTag }}}>
            </div>
            <div class="elementor-icon-box-content" >
                <# if (settings.show_description_before_number === 'yes') { #>
                    <?= $descrMarkup ?>
                    <?= $titleMarkup ?>
                <# } else { #>
                    <?= $titleMarkup ?>
                    <?= $descrMarkup ?>
                <# } #>
            </div>
        </div>
        
        <# if (settings.show_content_on_mobile !== 'yes') { #>
        <style>
            @media (max-width: 767px) {
                #<?= $uniqId ?> .elementor-icon-box-content {
                    display: none;
                }
            }
        </style>
        
        <style>
            #<?= $uniqId ?> .elementor-icon-box-title .fix_smartphone_href {
                color: inherit;
            }
            
            .fixSmartphoneElement .elementor-icon-box-title {
                padding: 0px;
            }
        </style>
        <# } #>
		<?php
	}
    
    

}
