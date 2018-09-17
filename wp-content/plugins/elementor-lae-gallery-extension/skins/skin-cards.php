<?php
namespace PostsWithAfcElementorPro\Skins;


use Elementor\Widget_Base;
use PostsWithAfcElementorPro\ACF;
use ElementorPro\Modules\Posts\Skins\Skin_Cards as SC;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Cards extends SC {

	protected function _register_controls_actions() {
		parent::_register_controls_actions();

		add_action( 'elementor/element/posts/cards_section_design_image/before_section_end', [ $this, 'register_additional_design_image_controls' ] );
	}


	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_columns_controls();
		$this->register_post_count_control();
		$this->register_thumbnail_controls();
		$this->register_title_controls();
		$this->register_excerpt_controls();
		$this->register_meta_data_controls();
		$this->register_read_more_controls();
		$this->register_badge_controls();
		$this->register_avatar_controls();
	}

	protected function register_meta_data_controls() {
		parent::register_meta_data_controls();
        
        $acfOptions = ACF::getAcfFieldsOptions();  // maxtep
       
        $options =  [
            'author' => __( 'Author', 'elementor-pro' ),
            'date' => __( 'Date', 'elementor-pro' ),
            'time' => __( 'Time', 'elementor-pro' ),
            'comments' => __( 'Comments', 'elementor-pro' ),
        ];
        
        $options = array_merge($options, $acfOptions);
        
        $this->update_control('meta_data', ['options' => $options]);
	}

	

	protected function render_post() {
		$this->render_post_header();
		$this->render_thumbnail();
		$this->render_text_header();
		$this->render_title();
		$this->render_excerpt();
		$this->render_read_more();
		$this->render_text_footer();
		$this->render_meta_data();
		$this->render_post_footer();
	}
    
    
    protected function render_meta_data() {
		/** @var array $settings. e.g. [ 'author', 'date', ... ] */
		$settings = $this->get_instance_value( 'meta_data' );
		if ( empty( $settings ) ) {
			return;
		}

		?>
		<div class="elementor-post__meta-data">
			<?php
			if ( in_array( 'author', $settings ) ) {
				$this->render_author();
			}

			if ( in_array( 'date', $settings ) ) {
				$this->render_date();
			}

			if ( in_array( 'time', $settings ) ) {
				$this->render_time();
			}

			if ( in_array( 'comments', $settings ) ) {
				$this->render_comments();
			}
            
            ACF::renderAcfFieds($settings);
			?>
		</div>
		<?php
	}
    
}

