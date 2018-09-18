<?php
namespace BriarElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Gallery extends Widget_Base {

	public function get_name() {
		return 'lightbox-gallery';
	}

	public function get_title() {
		return __( 'Lightbox Gallery', 'briar-elementor' );
	}

	public function get_icon() {
		return 'eicon-slideshow';
	}

	public function get_categories() {
		return [ 'briar-elements' ];
	}

	public function get_script_depends() {
		return [ 'imagesloaded', 'jquery-slick'];
	}

	public static function get_button_sizes() {
		return [
			'xs' => __( 'Extra Small', 'briar-elementor' ),
			'sm' => __( 'Small', 'briar-elementor' ),
			'md' => __( 'Medium', 'briar-elementor' ),
			'lg' => __( 'Large', 'briar-elementor' ),
			'xl' => __( 'Extra Large', 'briar-elementor'),
		];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_images',
			[
				'label' => __( 'Images', 'briar-elementor' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'src',
			[
				'label' => __( 'Image', 'briar-elementor' ),
				'type' => Controls_Manager::MEDIA
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Title', 'briar-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Image Title', 'briar-elementor' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'sub_title',
			[
				'label' => __( 'Subtitle', 'briar-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Image SubTitle', 'briar-elementor' ),
				'label_block' => true,
			]
		);


		$this->add_control(
			'images',
			[
				'label' => __( 'Images', 'briar-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'show_label' => true,
				'default' => [
					[
						'title' => __( 'Image 1 Title', 'briar-elementor' ),
						'sub_title' => __( 'Image 1 Subtitle', 'briar-elementor' ),
						'src' => ''
					],
					[
						'title' => __( 'Image 2 Title', 'briar-elementor' ),
						'sub_title' => __( 'Image 2 Subtitle', 'briar-elementor' ),
						'src' => ''
					],
					[
						'title' => __( 'Image 3 Title', 'briar-elementor' ),
						'sub_title' => __( 'Image 3 Subtitle', 'briar-elementor' ),
						'src' => ''
					],
				],
				'fields' => array_values( $repeater->get_controls() ),
				'title_field' => '{{{ title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_gallery_options',
			[
				'label' => __( 'Gallery Options', 'briar-elementor' ),
				'type' => Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'columns',
			[
				'label'   => __( 'Columns', 'briar-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
				'min'     => 1,
				'max'     => 5,
				'step'    => 1
			]
		);

		$this->end_controls_section();


	}

	protected function render() {
		$settings = $this->get_settings();

		if ( empty( $settings['images'] ) ) {
			return;
		}

		$images = [];
		$image_count = 0;
		foreach ( $settings['images'] as $image ) {
			$images[] = "<div class='lightbox-gallery__item'>
<img src='{$image['src']['url']}' class='lightbox-gallery__image'>
<div class='lightbox-gallery__title'>{$image['title']}</div>
<div class='lightbox-gallery__subtitle'>{$image['sub_title']}</div>
</div>";
			$image_count++;
		}

		?>
		<div class="briar-elementor-lightbox-gallery-wrapper lightbox-gallery columns-<?php echo $settings['columns']; ?>">
				<?php echo implode( '', $images ); ?>
		</div>
		<?php
	}

	protected function _content_template() {

	}
}
