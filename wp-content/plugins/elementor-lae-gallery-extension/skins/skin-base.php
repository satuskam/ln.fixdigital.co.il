<?php
namespace PostsWithAfcElementorPro\Skins;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use PostsWithAfcElementorPro\ACF;
use ElementorPro\Modules\Posts\Skins\Skin_Base as SB;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class Skin_Base extends SB
{

	protected function _register_controls_actions()
    {
		add_action( 'elementor/element/posts/section_layout/before_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/posts/section_layout/after_section_end', [ $this, 'register_style_sections' ] );
	}

	public function register_style_sections( Widget_Base $widget )
    {
		$this->parent = $widget;

		$this->register_design_controls();
	}


	public function register_controls( Widget_Base $widget )
    {
		$this->parent = $widget;

//		$this->register_columns_controls();
//		$this->register_post_count_control();
//		$this->register_thumbnail_controls();
//		$this->register_title_controls();
//		$this->register_excerpt_controls();
		
        $this->register_categories_controls();
        
		$this->register_meta_data_controls();
//		$this->register_read_more_controls();
	}

	public function register_design_controls()
    {
		$this->register_design_layout_controls();
		$this->register_design_image_controls();
		$this->register_design_content_controls();
		$this->registerCategoriesDesignControls();
	}
    
    
	public function register_categories_controls()
    {
		$this->add_control(
			'show_categories',
			[
				'label' => __( 'Categories', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-pro' ),
				'label_off' => __( 'Hide', 'elementor-pro' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
			]
		);
        
        $this->add_control(
			'categories_separator',
			[
				'label' => __( 'Separator Between', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => '|',
				'selectors' => [
					'{{WRAPPER}} .elementor-post__categories a + span:before' => 'content: "{{VALUE}}"',
				],
				'condition' => [
					$this->get_control_id( 'show_categories' ) => 'yes',
				],
			]
		);

	}


	protected function register_meta_data_controls()
    {
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

    

	protected function render_meta_data()
    {
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
    
    
    protected function renderCatogories()
    {
        $showCats = $this->get_instance_value( 'show_categories' );
        if ($showCats !== 'yes') return;
        
        $delimeter = $this->get_instance_value( 'categories_separator' );
        
        $currPostId =  get_the_ID();
        
        $catsData = [];
        $cats = wp_get_post_categories($currPostId);
        foreach ($cats as $catId) {
            $cat = get_category($catId);
            $catsData[] = [
                'id' => $cat->term_id,
                'name' => $cat->name,
                'link' => get_category_link($cat)
            ];
        }

        $html = '<div class="elementor-post__categories">';
        
        $links = [];
        foreach ($catsData as $c) {
            $links[] = "<a href='{$c["link"]}' >{$c['name']}</a>";
        }
        
        $html .= join(" <span></span> ", $links);
        
        $html .= '</div>';
        
        echo $html;
    }
 
    
    
    protected function registerCategoriesDesignControls()
    {
		$this->start_controls_section(
			'section_design_content',
			[
				'label' => __( 'Categories', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
        $this->add_control(
			'heading_categories_style',
			[
				'label' => __( 'Categories', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
                'separator' => 'before',
				'condition' => [
					$this->get_control_id( 'show_categories' ) => 'yes',
				],
			]
		);
        
        $this->add_control(
			'categories_color',
			[
				'label'     => __( 'Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post__categories a' => 'color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'show_categories' ) => 'yes'
				],
			]
		);

		$this->add_control(
			'categories_separator_color',
			[
				'label' => __( 'Separator Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post__categories span:before' => 'color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'show_categories' ) => 'yes'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'categories_typography',
				'label'    => __( 'Typography', 'elementor-pro' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .elementor-post__categories',
				'condition' => [
					$this->get_control_id( 'show_categories' ) => 'yes'
				],
			]
		);

		$this->add_control(
			'categories_spacing',
			[
				'label'     => __( 'Spacing', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__categories' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'show_categories' ) => 'yes'
				],
			]
		);
        
		$this->end_controls_section();
	}


	protected function render_post()
    {
		$this->render_post_header();
		$this->render_thumbnail();
        
		$this->render_text_header();
        $this->renderCatogories();
		$this->render_title();
		$this->render_meta_data();
		$this->render_excerpt();
		$this->render_read_more();
		$this->render_text_footer();
		$this->render_post_footer();
	}
}
