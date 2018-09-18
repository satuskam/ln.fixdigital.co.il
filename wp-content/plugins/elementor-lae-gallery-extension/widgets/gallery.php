<?php
namespace LaeGalleryExtension\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Typography;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Class Posts
 */
class Gallery extends \LivemeshAddons\Widgets\LAE_Gallery_Widget
{

    public function get_name()
    {
        return 'lae-gallery';
    }

    public function get_title()
    {
        return __('Livemesh Gallery (Extended by UCO)');
    }
    
    
    public function __construct( $data = [], $args = null )
    {
        add_action( 'elementor/element/lae-gallery/section_settings/before_section_end', function( $element, $args ) {
            /** @var \Elementor\Element_Base $element */
            
            // Because we re-register widget 'lae-gallery' the next condition is need to avoid a second attempt to add control with same name
            if ($this->get_control_index('item_info_placement') !== false) {
                return;
            }
            
            $element->add_control(
                'item_info_placement',
                [
                    'type' => Controls_Manager::SELECT,
                    'label' => __('Item info placement', 'livemesh-el-addons'),
                    'options' => array(
                        'onThumbOverlay' => __('On thumb overlay', 'livemesh-el-addons'),
                        'underThumb' => __('Under thumb', 'livemesh-el-addons'),
                    ),
                    'default' => 'onThumbOverlay',
                    'condition' => [
                        'layout_mode' => ['fitRows', 'masonry']
                    ],
                ]
            );
        }, 10, 2 );
        
        add_action(
            'elementor/element/lae-gallery/section_item_title_styling/after_section_end',
            [$this, 'addAdditionalSections'],
            10,
            2
        );
        
        add_action(
            'elementor/element/lae-gallery/section_item_title_styling/before_section_end',
            [$this, 'changeItemTitleStylingSection'],
            10,
            2
        );
        
        parent::__construct( $data, $args );
    }
    
    
    public function addAdditionalSections($element, $args)
    {
        // Because we re-register widget 'lae-gallery' the next condition is need to avoid a second attempt to add control with same name
        if ($this->get_control_index('item_subtitle_tag') !== false) {
            return;
        }
        
        $this->start_controls_section(
            'section_item_subtitle_styling',
            [
                'label' => __('Gallery Item Subtitle', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $controls = [
            'item_subtitle_tag', 'item_subtitle_color', 'section_item_descr_styling', 'item_descr_tag',
            'item_descr_color'
        ];
        foreach ($controls as $ctrlId) {
            $this->remove_control($ctrlId);
        }

        $this->add_control(
            'item_subtitle_tag',
            [
                'label' => __( 'Subitle HTML Tag', 'livemesh-el-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'div' => __( 'div', 'livemesh-el-addons' ),
                    'span' => __( 'span', 'livemesh-el-addons' ),
                    'p' => __( 'p', 'livemesh-el-addons' ),
                    'h1' => __( 'H1', 'livemesh-el-addons' ),
                    'h2' => __( 'H2', 'livemesh-el-addons' ),
                    'h3' => __( 'H3', 'livemesh-el-addons' ),
                    'h4' => __( 'H4', 'livemesh-el-addons' ),
                    'h5' => __( 'H5', 'livemesh-el-addons' ),
                    'h6' => __( 'H6', 'livemesh-el-addons' ),

                ],
                'default' => 'div',
            ]
        );

        $this->add_control(
            'item_subtitle_color',
            [
                'label' => __( 'Subtitle Color', 'livemesh-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery-wrap .lae-gallery .lae-gallery-item .lae-entry-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'item_subtitle_typography',
                'selector' => '{{WRAPPER}} .lae-gallery-wrap .lae-gallery .lae-gallery-item .lae-project-image .lae-entry-subtitle',
            ]
        );

        $this->end_controls_section();
        

        $this->start_controls_section(
            'section_item_descr_styling',
            [
                'label' => __('Gallery Item Description', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_descr_tag',
            [
                'label' => __( 'Description HTML Tag', 'livemesh-el-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'div' => __( 'div', 'livemesh-el-addons' ),
                    'span' => __( 'span', 'livemesh-el-addons' ),
                    'p' => __( 'p', 'livemesh-el-addons' ),
                ],
                'default' => 'div',
            ]
        );

        $this->add_control(
            'item_descr_color',
            [
                'label' => __( 'Description Color', 'livemesh-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                     '{{WRAPPER}} .lae-gallery-wrap .lae-gallery .lae-gallery-item .lae-entry-descr' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'item_descr_typography',
                'selector' => '{{WRAPPER}} .lae-gallery-wrap .lae-gallery .lae-gallery-item .lae-project-image .lae-entry-descr',
            ]
        );

        $this->end_controls_section();
    }
    
    
    /*
     *  replace selectors for item_title_typography group control
     */
    public function changeItemTitleStylingSection($element, $args)
    {
        $controls = $element->get_section_controls('section_item_title_styling');
        
        foreach ($controls as $ctrlId => $ctrlData) {
            if (strpos($ctrlId, 'item_title_typography_') === false) continue;
            
            if (!empty($ctrlData['selectors']) && is_array($ctrlData['selectors'])) {

                foreach ($ctrlData['selectors'] as $selector => $value) {
                    $newSelector = str_replace('.lae-image-info ', '', $selector);
                    unset($ctrlData['selectors'][$selector]);
                    $ctrlData['selectors'][$newSelector] = $value;
                }
                
                $element->update_control($ctrlId, ['selectors' => $ctrlData['selectors'] ] );
            }
        }
    }
    

    protected function _register_controls() {
        
        parent::_register_controls();
        
        $args = ['fields' => [
            [
                "type" => Controls_Manager::SELECT,
                "name" => "item_type",
                "label" => __("Item Type", "livemesh-el-addons"),
                'options' => array(
                    'image' => __('Image', 'livemesh-el-addons'),
                    'youtube' => __('YouTube Video', 'livemesh-el-addons'),
                    'vimeo' => __('Vimeo Video', 'livemesh-el-addons'),
                    'html5video' => __('HTML5 Video', 'livemesh-el-addons'),
                ),
                'default' => 'image',
            ],
            [
                'name' => 'item_name',
                'label' => __('Item Label', 'livemesh-el-addons'),
                'type' => Controls_Manager::TEXT,
                'description' => __('The label or name for the gallery item.', 'livemesh-el-addons'),
            ],
            [
                'name' => 'item_subtitle',
                'label' => __('Item Subtitle', 'livemesh-el-addons'),
                'type' => Controls_Manager::TEXT,
                'description' => __('The subtitle for the gallery item.', 'livemesh-el-addons'),
                'condition' => [
                    'item_type' => ['image'],
            //        'item_info_placement' => ['underThumb'],
                ],
            ],
            [
                'name' => 'item_image',
                'label' => __('Gallery Image', 'livemesh-el-addons'),
                'description' => __('The image for the gallery item. If item type chosen is YouTube or Vimeo video, the image will be used as a placeholder image for video.', 'livemesh-el-addons'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'label_block' => true,
            ],
            [
                'name' => 'item_tags',
                'label' => __('Item Tag(s)', 'livemesh-el-addons'),
                'type' => Controls_Manager::TEXT,
                'description' => __('One or more comma separated tags for the gallery item. Will be used as filters for the items.', 'livemesh-el-addons'),
            ],
            [
                'name' => 'item_link',
                'label' => __('Item Link', 'livemesh-el-addons'),
                'description' => __('The URL of the page to which the image gallery item points to (optional).', 'livemesh-el-addons'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '',
                    'is_external' => 'false',
                ],
                'placeholder' => __('http://your-link.com', 'livemesh-el-addons'),
                'condition' => [
                    'item_type' => ['image'],
                ],
            ],
            [
                'name' => 'video_link',
                'label' => __('Video URL', 'livemesh-el-addons'),
                'description' => __('The URL of the YouTube or Vimeo video.', 'livemesh-el-addons'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'item_type' => ['youtube', 'vimeo'],
                ],
            ],
            [
                'name' => 'mp4_video_link',
                'label' => __('MP4 Video URL', 'livemesh-el-addons'),
                'description' => __('The URL of the MP4 video.', 'livemesh-el-addons'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'item_type' => ['html5video'],
                ],
                'default' 		=> '',
            ],
            [
                'name' => 'webm_video_link',
                'label' => __('WebM Video URL', 'livemesh-el-addons'),
                'description' => __('The URL of the WebM video.', 'livemesh-el-addons'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'item_type' => ['html5video'],
                ],
                'default' 		=> '',
            ],
            [
                'name' => 'display_video_inline',
                'type' => Controls_Manager::SWITCHER,
                'label' => __('Display video inline?', 'livemesh-el-addons'),
                'label_off' => __('No', 'livemesh-el-addons'),
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'condition' => [
                    'item_type' => ['youtube', 'vimeo', 'html5video'],
                ],
                'return_value' => 'yes',
                'default' => 'no',
            ],
            [
                'name' => 'item_description',
                'label' => __('Item description', 'livemesh-el-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'description' => __('Short description for the gallery item displayed in the lightbox gallery.(optional)', 'livemesh-el-addons'),
                'label_block' => true,
            ],
        ],];
        
        $this->update_control('gallery_items', $args);
        
        // update selectors for controll
        $selData = [
            'item_title_color' => [
                '{{WRAPPER}} .lae-gallery-wrap .lae-gallery .lae-gallery-item .lae-project-image .lae-entry-title , {{WRAPPER}} .lae-gallery-wrap .lae-gallery .lae-gallery-item .lae-project-image .lae-entry-title a' => 'color: {{VALUE}};',
            ],
            'title_hover_border_color' => [
                '{{WRAPPER}} .lae-gallery-wrap .lae-gallery .lae-gallery-item .lae-project-image .lae-entry-title a:hover' => 'border-color: {{VALUE}};',
            ]
            
        ];
        foreach ($selData as $id => $selectors) {
            $this->update_control($id, ['selectors' => $selectors]);
        }
        
        return;
    }

    
    protected function get_settings_data_atts($settings)
    {
        $data = parent::get_settings_data_atts($settings);
        
        $data['item_descr_tag'] = $settings['item_descr_tag'];
        $data['item_subtitle_tag'] = $settings['item_subtitle_tag'];
        $data['item_info_placement'] = $settings['item_info_placement'];
        
        return $data;
    }

    
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $common = \LaeGalleryExtension\Widgets\Gallery_Common::get_instance();

        self::$gallery_counter++;

        $settings['gallery_class'] = !empty($settings['gallery_class']) ? sanitize_title($settings['gallery_class']) : 'gallery-' . self::$gallery_counter;

        $settings['gallery_id'] = $this->get_id();

        if ($settings['bulk_upload'] == 'yes') {

            $items = array();

            $images = $this->get_settings('gallery_images');

            foreach ($images as $image) {

                $item_image = array('id' => $image['id'], 'url' => $image['url']);

                $attachment = get_post($image['id']);

                $image_title = $attachment->post_title;

                $image_description = $attachment->post_excerpt;

                $item = array('item_type' => 'image', 'item_image' => $item_image, 'item_name' => $image_title, 'item_tags' => '', 'item_link' => '','item_description' => $image_description);

                $items[] = $item;
            }

            unset($settings['gallery_images']); // exclude items from settings
            
        } else {
            $items = $settings['gallery_items'];

            unset($settings['gallery_items']); // exclude items from settings
        }


        if (!empty($items)) :

            $terms = $common->get_gallery_terms($items);

            $max_num_pages = 1;

            if ($settings['pagination'] !== 'none')
                $max_num_pages = ceil(count($items) / $settings['items_per_page']);

            ?>

            <div class="lae-gallery-wrap lae-gapless-grid"
                 data-settings='<?php echo wp_json_encode($this->get_settings_data_atts($settings)); ?>'
                 data-items='<?php echo ($settings['pagination'] !== 'none') ? json_encode($items, JSON_HEX_APOS) : ''; ?>'
                 data-maxpages='<?php echo $max_num_pages; ?>'
                 data-total='<?php echo count($items); ?>'
                 data-current='1'>

                <?php if (!empty($settings['heading']) || $settings['filterable'] == 'yes'): ?>

                    <?php $header_class = (trim($settings['heading']) === '') ? ' lae-no-heading' : ''; ?>

                    <div class="lae-gallery-header <?php echo $header_class; ?>">

                        <?php if (!empty($settings['heading'])) : ?>

                            <<?php echo $settings['heading_tag']; ?> class="lae-heading"><?php echo wp_kses_post($settings['heading']); ?></<?php echo $settings['heading_tag']; ?>>

                        <?php endif; ?>

                        <?php

                        if ($settings['bulk_upload'] !== 'yes' && $settings['filterable'] == 'yes')
                            echo $common->get_gallery_terms_filter($terms);

                        ?>

                    </div>

                <?php endif; ?>

                <div id="<?php echo uniqid('lae-gallery'); ?>"
                     class="lae-gallery js-isotope lae-<?php echo esc_attr($settings['layout_mode']); ?> lae-grid-container <?php echo lae_get_grid_classes($settings); ?> <?php echo $settings['gallery_class']; ?>"
                     data-isotope-options='{ "itemSelector": ".lae-gallery-item", "layoutMode": "<?php echo esc_attr($settings['layout_mode']); ?>", "masonry": { "columnWidth": ".lae-grid-sizer" } }'>

                    <?php if ($settings['layout_mode'] == 'masonry'): ?>

                        <div class="lae-grid-sizer"></div>

                    <?php endif; ?>

                    <?php $common->display_gallery($items, $settings, 1); ?>

                </div><!-- Isotope items -->

                <?php echo $common->paginate_gallery($items, $settings); ?>

            </div><!-- .lae-gallery-wrap -->

            <?php

        endif;
    }
    
}
