<?php

class ACP_OptionsPanel {

    private $options;

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'register_panel_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
    }

    public function register_panel_page() {
	    add_menu_page( 'Accessible Poetry', __('Accessibility', 'acp'), 'manage_options', 'accessible-poetry', array( $this, 'create_panel_page' ), 'dashicons-yes', 59 );
    }

    public function create_panel_page() {
        $this->options = get_option( 'accessible_poetry' );
        ?>
        <div id="accessible-poetry-panel">
	        <?php acp_op_header();?>
	        <div id="acp-admin-row">
		        <div id="acp-panel-content">
			        <form method="post" action="options.php">
				        <?php settings_fields( 'acp_group' );?>
				        
					    <?php acp_op_menu();?>
					    <div class="tabs-container tab-content" class="tab-content"><?php acp_create_op_sections( 'accessible-poetry' );?>
				        <div class="clearfix"></div>
				        <?php submit_button();?>
			        </form>
		        </div>
	        </div>
	        
        </div>
        <?php	
    }

    public function page_init() {
        register_setting('acp_group', 'accessible_poetry', array($this, 'sanitize'));
		
        add_settings_section('acp_general', __('General', 'acp'), array($this, 'empty_section_info'), 'accessible-poetry');
        add_settings_section('acp_toolbar', __('Toolbar', 'acp'), array($this, 'empty_section_info'), 'accessible-poetry');
        add_settings_section('acp_skiplinks', __('Skiplinks', 'acp'), array($this, 'empty_section_info'), 'accessible-poetry');
        add_settings_section('acp_customcode', __('Custom Code', 'acp'), array($this, 'empty_section_info'), 'accessible-poetry');

        add_settings_field('focus', '', array( $this, 'focus_callback' ), 'accessible-poetry', 'acp_general');
        add_settings_field('link_underline', '', array( $this, 'link_underline_callback' ), 'accessible-poetry', 'acp_general');
        add_settings_field('empty_alt', '', array( $this, 'empty_alt_callback' ), 'accessible-poetry', 'acp_general');
        
        add_settings_field('toolbar', '', array($this, 'toolbar_callback'), 'accessible-poetry', 'acp_toolbar');
        add_settings_field('toolbar_skin', '',array( $this, 'toolbar_customization_callback' ),'accessible-poetry','acp_toolbar');
        add_settings_field('toolbar_fontsizer','',array($this,'toolbar_fontsizer_callback'), 'accessible-poetry', 'acp_toolbar');
        add_settings_field('toolbar_additional_buttons','',array($this,'toolbar_additional_buttons_callback'), 'accessible-poetry', 'acp_toolbar');
        add_settings_field('toolbar_visibility', '', array($this, 'toolbar_visibility_callback'), 'accessible-poetry', 'acp_toolbar');
        add_settings_field('toolbar_important', '', array($this, 'toolbar_important_callback'), 'accessible-poetry', 'acp_toolbar');
        
        add_settings_field('skiplinks', '',array( $this, 'skiplinks_callback' ),'accessible-poetry','acp_skiplinks');
        add_settings_field('skiplinks_side', '',array( $this, 'skiplinks_side_callback' ),'accessible-poetry','acp_skiplinks');
        
        add_settings_field('custom_css', '', array($this, 'customcode_callback'), 'accessible-poetry', 'acp_customcode');
    }

    public function sanitize($input) {

        $new_input = array();

        foreach ($input as $key => $value) {
            $new_input[$key] = $value;
        }

        return $new_input;
    }

    /////////////// SECTIONS CALLBACKS

    public function empty_section_info() {}

    /////////////// FIELDS CALLBACKS

    /*
     * Add alt attribute to images without alt
     *
     * @type    checkbox
     * @id      empty_alt
     * @since   2.0.1
     */
    public function empty_alt_callback() {

        $field = '<div class="acp-op-group">';
        
        	$field .= '<h3>' . __('Images', 'acp') . '</h3>';
			$field .= '<label class="checkbox"><input type="checkbox" id="empty_alt" name="accessible_poetry[empty_alt]" value="1" ' . checked( '1', isset( $this->options['empty_alt'] ), false ) . '> ' . __('Add empty value for the Alternative text attribute (ALT) when the attribute is missing.', 'acp') . '</label>';
			
        $field  .= '</div>';

        printf($field,isset( $this->options['image_alt'] ) ? esc_attr( $this->options['image_alt']) : '');
    
    }

    /*
     * Add effect to all focusable items
     *
     * @type    select
     * @id      focus
     * @since   2.0.1
     */
    public function focus_callback() {
	    
	    $focus = $this->options['focus'];
	    
	    $toolbar = ($focus == 'toolbar') ? ' selected' : '';
	    $always = ($focus == 'always') ? ' selected' : '';
		
		$field = '<h3>' . __('Focus', 'acp') . '</h3>';
        $field .= '<div class="row">';
        	$field .= '<div class="col-sm-4">';
				$field .= '<div class="acp-op-group">';
					$field .= '<label for="focus">'. __('Add effect for items in focus mode:','acp').'</label>';
					$field .= '<select name="accessible_poetry[focus]" id="focus">';
						$field .= '<option value="none">' . __('Don\'t do nothing', 'acp') . '</option>';
						$field .= ' <option value="toolbar"' . $toolbar . '>' . __('Only using the toolbar', 'acp') . '</option>';
						$field .= ' <option value="always"' . $always . '>' . __('Use always', 'acp') . '</option>';
					$field .= '</select>';
				$field .= '</div>';
			$field .= '</div>';
			printf($field,isset($focus) ? esc_attr($focus) : '');
        
        $focus_type = isset($this->options['focus_type']);
        
        $red = ($focus_type == 'red') ? ' selected' : '';
	    $blue = ($focus_type == 'blue') ? ' selected' : '';
	    $yellow = ($focus_type == 'yellow') ? ' selected' : '';
	    $custom = ($focus_type == 'custom') ? ' selected' : '';

        	$field = '<div class="col-sm-3">';
				$field .= '<div class="acp-op-group">';
					$field .= '<label for="focus_type">'. __('Choose the type of effect for focus mode:','acp').'</label>';
					$field .= '<select name="accessible_poetry[focus_type]" id="focus_type">';
						$field .= '<option value="none">' . __('-- None --', 'acp') . '</option>';
						$field .= ' <option value="red"' . $red . '>' . __('Red outline', 'acp') . '</option>';
						$field .= ' <option value="blue"' . $blue . '>' . __('Blue outline', 'acp') . '</option>';
						$field .= ' <option value="yellow"' . $yellow . '>' . __('Yellow background', 'acp') . '</option>';
						$field .= ' <option value="custom"' . $custom . '>' . __('Custom Outline', 'acp') . '</option>';
					$field .= '</select>';
				$field .= '</div>';
			$field .= '</div>';
		$field .= '</div>';
        printf($field,isset( $this->options['focus_type'] ) ? esc_attr( $this->options['focus_type']) : '');
        
        
        
        $customColor = $this->options['outline_color'];
        $cls = ( isset($this->options['focus_type']) && $this->options['focus_type'] === 'custom' ) ? ' open' : '';
        
        $field = '<div class="acp-op-group outline_color-group'.$cls.'">';
        	$field .= '<label for="outline_color">Choose custom outline color for focus mode:</label><input type="color" id="outline_color" name="accessible_poetry[outline_color]" value="' . $customColor . '">';
        $field .= '</div>';
		
        printf($field,isset( $this->options['outline_color'] ) ? esc_attr( $this->options['outline_color']) : '');
        
        $field = '<div class="acp-op-group">';
        	$field .= '<label><input type="checkbox" id="outline_important" name="accessible_poetry[outline_important]" value="1" ' . checked( '1', isset( $this->options['outline_important'] ), false ) . '> ' .  __('Use <strong>!important</strong> for the outline CSS (check this only if the outline does not work).', 'acp') . '</label>';
        $field .= '</div>';
		$field .= '<hr>';
		
        printf($field,isset( $this->options['outline_important'] ) ? esc_attr( $this->options['outline_important']) : '');
    }

    public function link_underline_callback() {
        
        $focus_only = ($this->options['link_underline'] == 'focus') ? ' selected' : '';
        $focus_hover = ($this->options['link_underline'] == 'hover') ? ' selected' : '';
	    $on_all = ($this->options['link_underline'] == 'all') ? ' selected' : '';

        $field = '<div class="acp-op-group">';
        	$field .= '<h3>' . __('Links', 'acp') . '</h3>';
			$field .= '<label for="link_underline">'. __('Links Underline','acp').'</label>';
			$field .= '<select name="accessible_poetry[link_underline]" id="outline">';
				$field .= '<option value="none">' . __('-- None --', 'acp') . '</option>';
				$field .= ' <option value="focus"' . $focus_only . '>' . __('On focus mode only', 'acp') . '</option>';
				$field .= ' <option value="hover"' . $focus_hover . '>' . __('On focus and hover mode', 'acp') . '</option>';
				$field .= ' <option value="all"' . $on_all . '>' . __('Always', 'acp') . '</option>';
			$field .= '</select>';
        $field .= '</div>';
        printf($field,isset( $this->options['link_underline'] ) ? esc_attr( $this->options['link_underline']) : '');
        
        $field = '<div class="acp-op-group">';
        	$field .= '<label><input type="checkbox" id="underline_important" name="accessible_poetry[underline_important]" value="1" ' . checked( '1', isset( $this->options['underline_important'] ), false ) . '> ' .  __('Use <strong>!important</strong> for the underline CSS (check this only if the underline does not work).', 'acp') . '</label>';
        $field .= '</div>';
		$field .= '<hr>';
		
        printf($field,isset( $this->options['underline_important'] ) ? esc_attr( $this->options['underline_important']) : '');
    }
    
    public function toolbar_callback() {
        $field = '<div class="acp-op-group">
        			<label class="checkbox">
        				<input type="checkbox" id="toolbar" name="accessible_poetry[toolbar]" value="1" ' . checked('1', isset($this->options['toolbar']), false) . '> ' .  __('Activate the toolbar', 'acp') . '</label>
        		  </div>
        		  <hr />';
        printf($field,isset( $this->options['toolbar'] ) ? esc_attr( $this->options['toolbar']) : '');
    }
    
    public function toolbar_visibility_callback() {
	    
	    $fields = array(
        	'disable_blackscreen' => __("Disable the black screen", 'acp'),
        	'hide_toolbar_mobile' => __("Hide the toolbar for mobile users", 'acp'),
        	
        	'hide_flashes' => __("Hide the button to disable flashes", 'acp'),
        	'hide_headingmarks' => __("Hide the button to to mark heading", 'acp'),
        	
        	'disable_zoom' => __("Hide the screen zoom buttons", 'acp'),
        	'disable_fontzoom' => __("Hide the font resize buttons", 'acp'),
        	'hide_readable' => __("Hide the button that change to readable font", 'acp'),
        	
        	'hide_contrast' => __("Hide the contrast buttons", 'acp'),
        	
        	'hide_underline' => __("Hide the underline button", 'acp'),
        	'hide_linkmarks' => __("Hide the button to mark links", 'acp'),
    	);
    	
    	$i = 0;
    	
    	
    	foreach($fields as $key => $value) {
        	$i++;
        	
        	$output = '';
        	
        	if($i == 1) {
	        	$output .= '<h3>' . __('Visibility', 'acp') . '</h3>';
	        	
	        	$output .= '<div class="row acp-op-group"><div class="col-sm-4">';
        	}
        	
        	$output .= '<label class="checkbox"><input type="checkbox" id="'.$key.'" name="accessible_poetry['.$key.']" value="1" ' . checked( '1', isset( $this->options[$key] ), false ) . '> ' .  $value . '</label>';
        	
        	if($i == 2) {
	        	$output .= '</div><div class="col-sm-8">';
        	} elseif($i == 10) {
	        	$output .= '</div></div><hr />';
        	}
        	
        	printf($output,isset( $this->options[$key] ) ? esc_attr( $this->options[$key]) : '');
        	
        }
    }
    
    
    public function toolbar_important_callback() {
        	
    	$fields = array(
        	'mheading_important' => __("Mark headings mode", 'acp'),
        	'readable_important' => __("Readable font mode", 'acp'),
        	'contrast_important' => __("Contrast modes", 'acp'),
        	'underline_important' => __("Underline mode", 'acp'),
        	'mlinks_important' => __("Mark links mode", 'acp'),
    	);
        	
    	$i = 0;
    	foreach($fields as $key => $value) {
        	$i++;
        	$field_output = '';
	        	
	        	if($i == 1) {
		        	$field_output .= '<h3>' . __('CSS Important (Advanced)', 'acp') . '</h3>';
					$field_output .= '<p>' . __('You can hardened the effect of some toolbar button with the use of CSS important. this is not the best way, the best way is to implement a better CSS by yourself with Accessible Poetry set of classes.', 'acp') . '</p>';
					$field_output .= '<div class="acp-op-group">';
	        	}
	        	$field_output .= '<label class="checkbox">';
	        	$field_output .= '<input type="checkbox" id="'.$key.'" name="accessible_poetry['.$key.']" value="1" '.checked( '1',isset($this->options[$key]), false ).'> ';
				$field_output .= $value;
				$field_output .= '</label>';
				
				if($i == count($fields)) {
					$field_output .= '</div>';
				}
				printf($field_output,isset( $this->options[$key] ) ? esc_attr( $this->options[$key]) : '');
        	}
    }

    public function toolbar_customization_callback() {
	    
	    /// toolbar skin
        
        $skin = $this->options['toolbar_skin'];
		
		$skin_classic	= ($skin == 1) ? ' selected' : '';
		$skin_flat		= ($skin == 2) ? ' selected' : '';
		$skin_rounded	= ($skin == 3) ? ' selected' : '';
		$skin_minimal	= ($skin == 4) ? ' selected' : '';
		$skin_smooth	= ($skin == 5) ? ' selected' : '';
		$skin_space		= ($skin == 6) ? ' selected' : '';

        $field  = '<h3>' . __('Customization', 'acp') . '</h3>';
        $field .= '<div class="row acp-op-group">';
        
			$field .= '<div class="col-sm-2">';
	        	$field .= '<div class="select">';
					$field .= '<label for="toolbar_skin">' . __('Toolbar Skin', 'acp') . '</label>';
					$field .= '<select name="accessible_poetry[toolbar_skin]" id="toolbar_skin">';
						$field .= '<option value="1"' . $skin_classic . '>' . __('Classic', 'acp') . '</option>';
						$field .= '<option value="2"' . $skin_flat . '>' . __('Flat', 'acp') . '</option>';
						$field .= '<option value="3"' . $skin_rounded . '>' . __('Rounded', 'acp') . '</option>';
						$field .= '<option value="4"' . $skin_minimal . '>' . __('Minimal', 'acp') . '</option>';
						$field .= '<option value="5"' . $skin_smooth . '>' . __('Smooth', 'acp') . '</option>';
						$field .= '<option value="6"' . $skin_space . '>' . __('Space', 'acp') . '</option>';
					$field .= '</select>';
				$field .= '</div>';
			$field .= '</div>';

			printf($field,isset( $this->options['toolbar_skin'] ) ? esc_attr( $this->options['toolbar_skin']) : '');
        
        /// toolbar side
        
		$right = ($this->options['toolbar_side'] == 'right') ? ' selected' : '';
        $left = ($this->options['toolbar_side'] == 'left') ? ' selected' : '';
        
        	$field = '<div class="col-sm-2">';
        		$field .= '<div class="select">';
		        	$field .= '<label for="toolbar_side">' . __('Toolbar Side', 'acp') . '</label>';
					$field .= '<select name="accessible_poetry[toolbar_side]" id="toolbar_side">';
						$field .= '<option value="right"' . $right . '>' . __('Right side', 'acp') . '</option>';
						$field .= '<option value="left"' . $left . '>' . __('Left side', 'acp') . '</option>';
					$field .= '</select>';
		        $field .= '</div>';
        	$field .= '</div>';
        
			printf($field,isset( $this->options['toolbar_side'] ) ? esc_attr( $this->options['toolbar_side']) : '');
        	
        
        
        /// icon size
        
        $icon_size = $this->options['icon_size'];
        
        $small = ($icon_size == 'small') ? ' selected' : '';
        $normal = ($icon_size == 'normal') ? ' selected' : '';
        $big = ($icon_size == 'big') ? ' selected' : '';
        
        	$field  = '<div class="col-sm-2">';
        		$field .= '<div class="select">';
		        	$field .= '<label for="icon_size">' . __('Icon Size', 'acp') . '</label>';
					$field .= '<select name="accessible_poetry[icon_size]" id="icon_size">';
						$field .= '<option value="normal"' . $normal . '>' . __('Normal size', 'acp') . '</option>';
						$field .= '<option value="big"' . $big . '>' . __('Big size', 'acp') . '</option>';
						$field .= '<option value="small"' . $small . '>' . __('Small size', 'acp') . '</option>';
					$field .= '</select>';
			    $field .= '</div>';
			$field .= '</div>';
		
        
		printf($field, isset( $this->options['icon_size'] ) ? esc_attr( $this->options['icon_size']) : '');
		
		/// toolbar position
        
        	$field = '<div class="col-sm-4">';
				$field .= '<label for="toolbar_position">' . __('Positioning of the icon and the toolbar from top', 'acp') . '</label>';
				$field .= '<input type="number" min="5" max="240" value="%s" name="accessible_poetry[toolbar_position]" id="toolbar_position"><span class="px">px</span>';
			$field .= '</div>';
		$field .= '</div>';
        $field .= '<hr>';
		
		printf($field, isset( $this->options['toolbar_position'] ) ? esc_attr( $this->options['toolbar_position']) : '');
        
    }

    
    /*
     * {...}
     *
     * @id      toolbar_additional_buttons_callback
     * @since   3.0
     */
    public function toolbar_additional_buttons_callback() {
	    
	    $acp_statement = ( isset($this->options['acp_statement']) ) ? $this->options['acp_statement'] : '';
        
        $field = '<h3>' . __('Additional Buttons', 'acp') . '</h3>';
		$field .= '<div class="row acp-op-group">';
		$field .= '<div class="col-sm-4">';
			$field .= '<label for="acp_statement">' . __('To display a link to your Accessibility Statement page, select your Accessibility Statement page', 'acp') . '</label>';
			$field .= '<select name="accessible_poetry[acp_statement]" id="acp_statement">';
				$field .= '<option value="">' . esc_attr( __( 'Select page', 'acp' ) ) . '</option>';
				$pages = get_pages(); 
				foreach ( $pages as $page ) {
					$selected = ( $acp_statement == $page->ID ) ? 'selected' : '';
					
					$option = '<option value="' . $page->ID . '" ' . $selected . '>';
					$option .= $page->post_title;
					$option .= '</option>';
					$field .= $option;
				}
			$field .= '</select>';
		$field .= '</div>';

        printf($field,isset( $this->options['toolbar_statement'] ) ? esc_attr( $this->options['toolbar_statement']) : '');
        
        $acp_feedback = ( isset($this->options['acp_feedback']) ) ? $this->options['acp_feedback'] : '';
        
		$field = '<div class="col-sm-3">';
			$field .= '<label for="toolbar_feedback">' . __('To display a link to your Feedback page, select your Feedback page', 'acp') . '</label>';
			$field .= '<select name="accessible_poetry[acp_feedback]" id="acp_feedback">';
				$field .= '<option value="">' . esc_attr( __( 'Select page', 'acp' ) ) . '</option>';
				$pages = get_pages(); 
				foreach ( $pages as $page ) {
					$selected = ( $acp_feedback == $page->ID ) ? 'selected' : '';
					
					$option = '<option value="' . $page->ID . '" ' . $selected . '>';
					$option .= $page->post_title;
					$option .= '</option>';
					$field .= $option;
				}
			$field .= '</select>';
		$field .= '</div>';
		$field .= '</div>';
		$field .= '<hr />';
		printf($field,isset( $this->options['acp_feedback'] ) ? esc_attr( $this->options['acp_feedback']) : '');
    }
    
    /*
     * {...}
     *
     * @id      toolbar_fontsizer_callback
     * @since   3.0
     */
    public function toolbar_fontsizer_callback() {
	    
	    $fontsizer_inc = (isset($this->options['fontsizer_inc'])) ? $this->options['fontsizer_inc'] : '';
		
        $field = '<div class="acp-op-group acp-op-font-sizer">';
        
        	$field .= '<h3>' . __('Font Size Modifier', 'acp') . '</h3>';
			$field .= '<label for="fontsizer_inc">' . __('Include additional elements that should be affected with the font size modifier (default is: body, p, h1, h2, h3, h4, h5, h6, label, input, a, button, textarea)', 'acp') . '</label>';
			$field .= '<textarea name="accessible_poetry[fontsizer_inc]" id-"fontsizer_inc">'.$fontsizer_inc.'</textarea>';
			
        $field  .= '</div>';

        printf($field,isset( $this->options['fontsizer_inc'] ) ? esc_attr( $this->options['fontsizer_inc']) : '');
        
        $fontsizer_exc = (isset($this->options['fontsizer_exc'])) ? $this->options['fontsizer_exc'] : '';
		
        $field = '<div class="acp-op-group acp-op-font-sizer">';
        
			$field .= '<label for="fontsizer_exc">' . __('Exclude additional elements from the effect of the font size modifier', 'acp') . '</label>';
			$field .= '<textarea name="accessible_poetry[fontsizer_exc]" id-"fontsizer_exc">' . $fontsizer_exc . '</textarea>';
			
        $field  .= '</div>';

        printf($field,isset( $this->options['fontsizer_exc'] ) ? esc_attr( $this->options['fontsizer_exc']) : '');
    }
    
    /*
     * Activate Skiplinks
     *
     * @type    checkbox
     * @id      skiplinks
     * @since   2.0.1
     */
    public function skiplinks_callback() {
        $field = '<h3>' . __('Skiplinks', 'acp') . '</h3>';
        $field .= '<div class="acp-op-group">';
        	$field .= '<label><input type="checkbox" id="skiplinks" name="accessible_poetry[skiplinks]" value="1" ' .checked('1',isset($this->options['skiplinks']),false). '>';
        $field .= __('Activate skiplinks menus', 'acp') . '</label>';
        $field .= '</div>';

        printf($field,isset( $this->options['skiplinks'] ) ? esc_attr( $this->options['skiplinks']) : '');
    }
    
    /*
     * Skiplinks side
     *
     * @type    checkbox
     * @id      skiplinks_side
     * @since   2.1.1
     *
     * PRO ONLY
     */
    public function skiplinks_side_callback() {

        $left = ($this->options['skiplinks_side'] == 'left') ? ' selected' : '';
        $right = ($this->options['skiplinks_side'] == 'right') ? ' selected' : '';

        $field = '<div class="acp-op-group select">';
        $field .= '<label for="skiplinks_side">' . __('Skiplinks Side', 'acp') . '</label>';
        $field .= ' <select name="accessible_poetry[skiplinks_side]" id="skiplinks_side">';
        $field .= '     <option value="left"' . $left . '>' . __('Left', 'acp') . '</option>';
        $field .= '     <option value="right"' . $right . '>' . __('Right', 'acp') . '</option>';
        $field .= ' </select>';
        $field .= '</div>';

        printf($field,isset( $this->options['skiplinks_side'] ) ? esc_attr( $this->options['skiplinks_side']) : '');
    }
    
    
    public function customcode_callback() {
		$customcss = (isset($this->options['custom_css'])) ? $this->options['custom_css'] : '';
		
        $field = '<div class="acp-op-group">';
        
        	$field .= '<h3>' . __('Custom Code', 'acp') . '</h3>';
			$field .= '<label for="custom_css">' . __('Add additional CSS code', 'acp') . '</label>';
			$field .= '<textarea name="accessible_poetry[custom_css]" id-"custom_css" class="acp-code">' . $customcss . '</textarea>';
			
        $field  .= '</div>';

        printf($field,isset( $this->options['custom_css'] ) ? esc_attr( $this->options['custom_css']) : '');
        
        $customjs = (isset($this->options['custom_js'])) ? $this->options['custom_js'] : '';
		
        $field = '<div class="acp-op-group">';
        
			$field .= '<label for="custom_css">' . __('Add additional JS code', 'acp') . '</label>';
			$field .= '<p>To use jQuery $ functionality wrap your code with this:</p>';
			$field .= '<pre><code>jQuery(document).ready(function($){
});</code></pre>';
			$field .= '<textarea name="accessible_poetry[custom_js]" id-"custom_js" class="acp-code">' . $customjs . '</textarea>';
			
        $field  .= '</div>';

        printf($field,isset( $this->options['custom_js'] ) ? esc_attr( $this->options['custom_js']) : '');
    
    }
    
    /*
     * Register administration scripts & styles
     */
	function admin_assets($hook) {
		wp_register_style( 'acp-admin-style', plugins_url('accessible-poetry/assets/css/acp-admin-style.css') );
		wp_enqueue_style( 'acp-admin-style' );
		wp_enqueue_script( 'bootstrap', plugins_url('accessible-poetry/assets/js/bootstrap.min.js') );
		wp_enqueue_script( 'acp-admin', plugins_url('accessible-poetry/assets/js/acp-admin.js') );
	}
}

new ACP_OptionsPanel();
