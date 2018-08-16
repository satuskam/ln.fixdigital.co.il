<?php
namespace WebGroupApi\Api\ElementorTemplates;

define('ELEM_TMPL_PLUGIN_DIR',str_replace('\\','/',dirname(__FILE__)));

	
class ElementorTemplates {

    public function __construct()
    {
        add_action( 'admin_init', array( &$this, 'admin_init' ) );
    }
        
        
    public function admin_init()
    {
        add_meta_box('elem_type_post_meta', 'Template type', [$this, 'elem_type_meta_setup'], 'elementor_library', 'normal', 'high');

        add_action('save_post', [$this, 'elem_tmpl_meta_save']);
    }
    
    
    public function elem_type_meta_setup()
    {
        global $post;
    
        $blogId = get_current_blog_id();
        
        if (! _isBlogForBeginners($blogId)) return;

        // using an underscore, prevents the meta variable
        // from showing up in the custom fields section
        $elemTypeMeta = get_post_meta($post->ID,'_elementor_template_custom_type',TRUE);

        // instead of writing HTML here, lets do an include
        include(ELEM_TMPL_PLUGIN_DIR . '/elemTypeMeta.php');

        // create a custom nonce for submit verification later
        echo '<input type="hidden" name="elem_tmpl_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
    }


    public function elem_tmpl_meta_save($post_id) 
    {
        // make sure data came from our meta box
        if ( ! isset( $_POST['elem_tmpl_meta_noncename'] )
                || !wp_verify_nonce($_POST['elem_tmpl_meta_noncename'],__FILE__)) return $post_id;

        $new_footer_data = $_POST['_elementor_template_custom_type'];

        update_post_meta($post_id,'_elementor_template_custom_type',$new_footer_data);

        return $post_id;
    }

}


new ElementorTemplates();



