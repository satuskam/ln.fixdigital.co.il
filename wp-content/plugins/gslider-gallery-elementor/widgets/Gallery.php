<?php
namespace GSliderElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Gallery extends Widget_Base {

	public function get_name() {
		return 'gslider-gallery';
	}

	public function get_title() {
		return __( 'GSlider Gallery', 'gslider-elementor' );
	}

	public function get_icon() {
		return 'eicon-slideshow';
	}

	public function get_categories() {
		return [ 'other-elements' ];
	}

	public function get_script_depends() {
		return [ 'imagesloaded', 'jquery-slick'];
	}

	public static function get_button_sizes() {
		return [
			'xs' => __( 'Extra Small', 'gslider-elementor' ),
			'sm' => __( 'Small', 'gslider-elementor' ),
			'md' => __( 'Medium', 'gslider-elementor' ),
			'lg' => __( 'Large', 'gslider-elementor' ),
			'xl' => __( 'Extra Large', 'gslider-elementor'),
		];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_images',
			[
				'label' => __( 'Images', 'gslider-elementor' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'src',
			[
				'label' => __( 'Image', 'gslider-elementor' ),
				'type' => Controls_Manager::MEDIA
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Title', 'gslider-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Image Title', 'gslider-elementor' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'sub_title',
			[
				'label' => __( 'Subtitle', 'gslider-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Image SubTitle', 'gslider-elementor' ),
				'label_block' => true,
			]
		);


		$this->add_control(
			'images',
			[
				'label' => __( 'Images', 'gslider-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'show_label' => true,
				/*'default' => [
					[
						'title' => __( 'Image 1 Title', 'gslider-elementor' ),
						'sub_title' => __( 'Image 1 Subtitle', 'gslider-elementor' ),
						'src' => ''
					],
					[
						'title' => __( 'Image 2 Title', 'gslider-elementor' ),
						'sub_title' => __( 'Image 2 Subtitle', 'gslider-elementor' ),
						'src' => ''
					],
					[
						'title' => __( 'Image 3 Title', 'gslider-elementor' ),
						'sub_title' => __( 'Image 3 Subtitle', 'gslider-elementor' ),
						'src' => ''
					],
				],*/
				'fields' => array_values( $repeater->get_controls() ),
				'title_field' => '{{{ title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_gallery_options',
			[
				'label' => __( 'GSlider Options', 'gslider-elementor' ),
				'type' => Controls_Manager::SECTION,
			]
		);
                
                
                $this->add_responsive_control(
			'vertGap',
			[
				'label' => __( 'Vertical gap between items', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 5,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .lightbox-gallery__item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
                
                
                
                $this->add_responsive_control(
			'thumbWidth',
			[
				'label' => __( 'Thumbnail width', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 200,
				],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .lightbox-gallery__item' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
                
                $this->add_responsive_control(
			'thumbHeight',
			[
				'label' => __( 'Thumbnail height', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 200,
				],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .lightbox-gallery__image' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
                
                
                

		$this->end_controls_section();


	}

	protected function render() {
		$settings = $this->get_settings();

		if ( empty( $settings['images'] ) ) {
			return;
		}
                
                $galleryId = $this->get_id();
                
		$images = [];
		$image_count = 0;
		foreach ( $settings['images'] as $image ) {
                        $imageUrl = $image['src']['url'];
                        
                        $images[] = "<div><img src=\"{$imageUrl}\" alt=\"{$image['title']}\"/></div>";
                        
			$image_count++;
		}

		?>
		<div class="slider-for">
			<?php echo implode( '', $images ); ?>
		</div>
		<div class="slider-nav">
                    <?php echo implode( '', $images ); ?>
		</div>
		<?php
	}

	protected function _content_template() {

	}
}
