<?php
namespace BriarElementor\Widgets;

use Elementor\Widget_Image_Gallery;
use Elementor\Control_Animation;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class GalleryMasonry extends Widget_Image_Gallery {
    
        public function __construct( $data = [], $args = null ) {
            add_action( 'elementor/element/briar-masonry-gallery/section_gallery_images/after_section_start', function( $element, $args ) {
                $element->add_control(
                    'image_spacing',
                    [
                        'label' => __( 'Spacing', 'elementor' ),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                            '' => __( 'Default', 'elementor' ),
                            'custom' => __( 'Custom', 'elementor' ),
                            'custom_and_separate' => __( 'Custom & separate', 'elementor' )
                        ],
                        'prefix_class' => 'gallery-spacing-',
                        'default' => '',
                    ]
		);
                
                $columnsMarginHor = is_rtl() ? "margin-right: 0; margin-left: -{{SIZE}}{{UNIT}}" : "margin-left: 0; margin-right: -{{SIZE}}{{UNIT}}";
                $columnsPaddingHor = is_rtl() ? "padding-right: 0; padding-left: {{SIZE}}{{UNIT}}" : "padding-left: 0; padding-right: {{SIZE}}{{UNIT}}";
                
		$element->add_control(
                    'image_hor_spacing_custom',
                    [
                        'label' => __( 'Image Horizontal Spacing', 'elementor' ),
                        'type' => Controls_Manager::SLIDER,
                        'range' => [
                            'px' => [
                                'max' => 100,
                            ],
                        ],
                        'default' => [
                            'size' => 15,
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .gallery-item' => $columnsPaddingHor,
                            '{{WRAPPER}} .gallery' => $columnsMarginHor,
                        ],
                        'condition' => [
                            'image_spacing' => 'custom_and_separate',
                        ]
                    ]
		);
                
                $columnsMarginVert = "margin-top: 0; margin-bottom: -{{SIZE}}{{UNIT}}";
                $columnsPaddingVert = "padding-top: 0; padding-bottom: {{SIZE}}{{UNIT}}";
                
                $element->add_control(
                    'image_vert_spacing_custom',
                    [
                        'label' => __( 'Image Vertical Spacing', 'elementor' ),
                        'type' => Controls_Manager::SLIDER,
                        'range' => [
                            'px' => [
                                'max' => 100,
                            ],
                        ],
                        'default' => [
                            'size' => 15,
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .gallery-item' => $columnsPaddingVert,
                            '{{WRAPPER}} .gallery' => $columnsMarginVert,
                        ],
                        'condition' => [
                            'image_spacing' => 'custom_and_separate',
                        ]
                    ]
		);
            }, 10, 2 );
        
            parent::__construct( $data, $args );
        }

	public function get_name() {
		return 'briar-masonry-gallery';
	}

	public function get_title() {
		return __( 'Masonry Gallery', 'briar-elementor' );
	}

	public function get_categories() {
		return [ 'briar-elements' ];
	}


	/**
	 * Render image gallery widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();

		if ( ! $settings['wp_gallery'] ) {
			return;
		}

		$ids = wp_list_pluck( $settings['wp_gallery'], 'id' );

		$this->add_render_attribute( 'shortcode', 'ids', implode( ',', $ids ) );
		$this->add_render_attribute( 'shortcode', 'size', $settings['thumbnail_size'] );

		if ( $settings['gallery_columns'] ) {
			$this->add_render_attribute( 'shortcode', 'columns', $settings['gallery_columns'] );
		}

		if ( $settings['gallery_link'] ) {
			$this->add_render_attribute( 'shortcode', 'link', $settings['gallery_link'] );
		}

		if ( ! empty( $settings['gallery_rand'] ) ) {
			$this->add_render_attribute( 'shortcode', 'orderby', $settings['gallery_rand'] );
		}
		?>
		<div class="elementor-image-gallery">
			<?php
			$this->add_render_attribute( 'link', [
//				'class' => 'elementor-clickable animated elementor-invisible',
				'class' => 'elementor-clickable animated',
				'data-elementor-open-lightbox' => $settings['open_lightbox'],
				'data-elementor-lightbox-slideshow' => $this->get_id()
			] );

			add_filter( 'wp_get_attachment_link', [ $this, 'add_lightbox_data_to_image_link' ] );

			echo do_shortcode( '[gallery ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

			remove_filter( 'wp_get_attachment_link', [ $this, 'add_lightbox_data_to_image_link' ] );
			?>
		</div>
		<?php
	}

	protected function getAnimation() {
		$animations = array_keys(Control_Animation::get_animations()['Fading']);
		return $animations[rand(0, count($animations) - 1)];
	}

	/**
	 * Add lightbox data to image link.
	 *
	 * Used to add lightbox data attributes to image link HTML.
	 *
	 * @since 1.6.0
	 * @access public
	 *
	 * @param string $link_html Image link HTML.
	 *
	 * @return string Image link HTML with lightbox data attributes.
	 */
	public function add_lightbox_data_to_image_link( $link_html ) {
		$this->set_render_attribute( 'link', [
			'data-animation'=> $this->getAnimation()
		] );
		return preg_replace( '/^<a/', '<a ' . $this->get_render_attribute_string( 'link' ), $link_html );
	}
        
        
        protected function _content_template() {

	}

}
