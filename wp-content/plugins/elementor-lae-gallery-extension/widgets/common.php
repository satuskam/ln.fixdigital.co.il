<?php

namespace LaeGalleryExtension\Widgets;


use \LivemeshAddons\Gallery\LAE_Gallery_Video as LAE_Gallery_Video;

/**
 * Gallery class.
 *
 */
class Gallery_Common extends \LivemeshAddons\Gallery\LAE_Gallery_Common {

    /**
     * Holds the class object.
     */
    public static $instance;
    
    /**
     * Primary class constructor.
     * 
     */
    public function __construct() {
        add_filter('attachment_fields_to_edit', array($this, 'attachment_field_grid_width'), 10, 2);
        add_filter('attachment_fields_to_save', array($this, 'attachment_field_grid_width_save'), 10, 2);

        // Ajax calls
        add_action('wp_ajax_lae_load_gallery_items', array( $this, 'loadGalleryItemsByAjaxCallback'), 1, 1);
        add_action('wp_ajax_nopriv_lae_load_gallery_items', array( $this, 'loadGalleryItemsByAjaxCallback'), 1, 1);

    }
    
   
    function display_gallery($items, $settings, $paged = 1)
    {
        $gallery_video = LAE_Gallery_Video::get_instance();

        $items_per_page = intval($settings['items_per_page']); ?>

        <?php
        // If pagination option is chosen, filter the items for the current page
        if ($settings['pagination'] != 'none')
            $items = $this->get_items_to_display($items, $paged, $items_per_page);
        ?>

        <?php foreach ($items as $item): ?>

            <?php

            // No need to populate anything if no image is provided for video or for the image
            if (empty($item['item_image']))
                continue;

            $style = '';
            if (!empty($item['item_tags'])) {
                $terms = array_map('trim', explode(',', $item['item_tags']));

                foreach ($terms as $term) {
                    // Get rid of spaces before adding the term
                    $style .= ' term-' . preg_replace('/\s+/', '-', $term);
                }
            }
            ?>

            <?php

            $item_type = $item['item_type'];
            $item_class = 'lae-' . $item_type . '-type';

            $custom_class = get_post_meta($item['item_image']['id'], 'lae_grid_width', true);

            if ($custom_class !== '')
                $item_class .= ' ' . $custom_class;

            ?>

            <div class="lae-grid-item lae-gallery-item <?php echo $style; ?> <?php echo $item_class; ?>">

                <?php if ($gallery_video->is_inline_video($item, $settings)): ?>

                    <?php $gallery_video->display_inline_video($item, $settings); ?>

                <?php else: ?>

                    <div class="lae-project-image">

                        <?php if ($gallery_video->is_gallery_video($item, $settings)): ?>

                            <?php $image_html = ''; ?>

                            <?php if (isset($item['item_image']) && !empty($item['item_image']['id'])): ?>

                                <?php $image_html = lae_get_image_html($item['item_image'], 'thumbnail_size', $settings); ?>

                            <?php elseif ($item_type == 'youtube' || $item_type == 'vimeo') : ?>

                                <?php $thumbnail_url = $gallery_video->get_video_thumbnail_url($item['video_link'], $settings); ?>

                                <?php if (!empty($thumbnail_url)): ?>

                                    <?php $image_html = sprintf('<img src="%s" title="%s" alt="%s" class="lae-image"/>', esc_attr($thumbnail_url), esc_html($item['item_name']), esc_html($item['item_name'])); ?>

                                <?php endif; ?>

                            <?php endif; ?>

                            <?php echo $image_html; ?>

                        <?php else: ?>

                            <?php $image_html = lae_get_image_html($item['item_image'], 'thumbnail_size', $settings); ?>

                            <?php if ($item_type == 'image' && !empty($item['item_link']['url'])): ?>

                                <a href="<?php echo esc_url($item['item_link']['url']); ?>"
                                   title="<?php echo esc_html($item['item_name']); ?>"><?php echo $image_html; ?> </a>

                            <?php else: ?>

                                <?php echo $image_html; ?>

                            <?php endif; ?>

                        <?php endif; ?>

                        <?php  
                            if (empty($settings['item_info_placement']) || $settings['item_info_placement'] === 'onThumbOverlay') {
                                $this->displayInfoInsideImage($item, $gallery_video, $settings);
                            } else {
                                $this->displayInfoUnderImage($item, $gallery_video, $settings);
                            }
                            
                        ?>

                    </div>

                <?php endif; ?>

            </div>

            <?php

        endforeach;
    }
  
    
    function displayInfoInsideImage($item, $gallery_video, $settings)
    {
        $item_type = $item['item_type'];
        
        ?>
        <div class="lae-image-info">

            <div class="lae-entry-info">

                <?php if ($settings['display_item_title'] == 'yes'): ?>

                <<?php echo $settings['item_title_tag']; ?> class="lae-entry-title">

                <?php if ($item_type == 'image' && !empty($item['item_link']['url'])): ?>

                    <?php $target = $item['item_link']['is_external'] ? 'target="_blank"' : ''; ?>

                    <a href="<?php echo esc_url($item['item_link']['url']); ?>"
                       title="<?php echo esc_html($item['item_name']); ?>"
                        <?php echo $target; ?>><?php echo esc_html($item['item_name']); ?></a>

                <?php else: ?>

                    <?php echo esc_html($item['item_name']); ?>

                <?php endif; ?>

            </<?php echo $settings['item_title_tag']; ?>>

            <?php endif; ?>

            <?php if ($gallery_video->is_gallery_video($item, $settings)): ?>

                <?php $gallery_video->display_video_lightbox_link($item, $settings); ?>

            <?php endif; ?>

            <?php if ($settings['display_item_tags'] == 'yes'): ?>

                <span class="lae-terms"><?php echo esc_html($item['item_tags']); ?></span>

            <?php endif; ?>

        </div>

        <?php if ($item_type == 'image' && !empty($item['item_image']) && $settings['enable_lightbox']) : ?>

            <?php $this->display_image_lightbox_link($item, $settings); ?>

        <?php endif; ?>

        </div>
       <?php
    }
    
    
    function displayInfoUnderImage($item, $gallery_video, $settings)
    {
        $item_type = $item['item_type'];

        ?>
        <div class="lae-item-info">

            <div class="lae-entry-info">

                <?php if ($settings['display_item_title'] == 'yes'): ?>

                    <<?php echo $settings['item_title_tag']; ?> class="lae-entry-title">

                        <?php if ($item_type == 'image' && !empty($item['item_link']['url'])): ?>

                            <?php $target = $item['item_link']['is_external'] ? 'target="_blank"' : ''; ?>

                            <a href="<?php echo esc_url($item['item_link']['url']); ?>"
                               title="<?php echo esc_html($item['item_name']); ?>"
                                <?php echo $target; ?>>
                                    <?php echo esc_html($item['item_name']); ?></a>

                        <?php else: ?>

                            <?php echo esc_html($item['item_name']); ?>

                        <?php endif; ?>

                    </<?php echo $settings['item_title_tag']; ?>>

                <?php endif; ?>
                    
                   
                <?php if (!empty($item['item_subtitle']) && !empty($settings['item_subtitle_tag'])) : ?>                    
                    <<?php  echo $settings['item_subtitle_tag']; ?> class="lae-entry-subtitle">

                        <?php  echo esc_html($item['item_subtitle']); ?>

                    </<?php  echo $settings['item_subtitle_tag']; ?>>
                <?php endif; ?>                   
                    

            <?php if ($gallery_video->is_gallery_video($item, $settings)): ?>

                <?php $gallery_video->display_video_lightbox_link($item, $settings); ?>

            <?php endif; ?>

            <?php if ($settings['display_item_tags'] == 'yes'): ?>

                <span class="lae-terms"><?php echo esc_html($item['item_tags']); ?></span>

            <?php endif; ?>
                
                
            <?php
            
            if (!empty($item['item_description']) && !empty($settings['item_descr_tag'])) : ?>
                <<?php echo $settings['item_descr_tag']; ?> class="lae-entry-descr">

                    <?php echo esc_html($item['item_description']); ?>

                </<?php echo $settings['item_descr_tag']; ?>>
            <?php endif; ?>

        </div>

        <?php if ($item_type == 'image' && !empty($item['item_image']) && $settings['enable_lightbox']) : ?>

            <?php $this->display_image_lightbox_link($item, $settings); ?>

        <?php endif; ?>

        </div>
       <?php
    }
    

    /**
     * Returns the singleton instance of the class.
     * 
     */
    public static function get_instance()
    {
        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Gallery_Common ) ) {
            self::$instance = new Gallery_Common();
        }

        return self::$instance;
    }
    
    
    function loadGalleryItemsByAjaxCallback()
    {
        $items = $this->parse_items($_POST['items']);
        $settings = $this->parse_gallery_settings($_POST['settings']);
        $paged = intval($_POST['paged']);

        $this->display_gallery($items, $settings, $paged);

        wp_die();
    }

}

// Load the metabox class.
$lae_gallery_common = Gallery_Common::get_instance();


