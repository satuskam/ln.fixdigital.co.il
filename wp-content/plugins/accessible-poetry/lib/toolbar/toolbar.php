<?php
class ACP_Toolbar {
	
	private $options;
	
	
	
	public function __construct() {
		
		$this->options = get_option( 'accessible_poetry' );
		
		if(isset($this->options['toolbar'])) {
			if(isset($this->options['hide_toolbar_mobile'])) {
				if( !wp_is_mobile() ) {
					add_action('wp_footer', array($this, 'toolbar_btn'));
					add_action('wp_footer', array($this, 'black_screen'));
					add_action('wp_footer', array($this, 'the_toolbar'));
				}
			} else {
				add_action('wp_footer', array($this, 'toolbar_btn'));
				add_action('wp_footer', array($this, 'black_screen'));
				add_action('wp_footer', array($this, 'the_toolbar'));
			}
			
		}
    }
    
    public function toolbar_btn() {
	    $icon_size			= (isset($this->options['icon_size'])) ? $this->options['icon_size'] : '';
	    $image_icon			= (isset($this->options['replacetoggle_icon'])) ? true : false;
	    $toolbar_position	= (isset($this->options['toolbar_position'])) ? $this->options['toolbar_position'] : 25;
	    
	    $output  = '<button type="button" id="acp-toggle-toolbar" class="' . $icon_size . ' toolbar-'.$this->options['toolbar_side'].'" style="top: ' . $toolbar_position . 'px;">';
	    $output .= '<img src="' . ACP_URL . '/assets/icons/access.svg" alt="' . __('Accessibility Icon', 'acp') . '">';
	    $output .= '</button>';
	    
	    echo $output;
    }
    
    public function black_screen() {
	    if( !isset($this->options['disable_blackscreen']) )
		    echo '<div id="acp-black-screen"></div>';
    }
    public function the_toolbar() {
	    $toolbar_position	= (isset($this->options['toolbar_position'])) ? $this->options['toolbar_position'] : 25;
	    $skin = (isset($this->options['toolbar_skin'])) ? $this->options['toolbar_skin'] : 1;
	    ?>
	    <style>#acp-toggle-toolbar{top:<?php echo $toolbar_position;?>px;}</style>
	    <div id="acp-toolbar" class="acp-toolbar acp-toolbar-skin-<?php echo $skin;?> toolbar-<?php echo $this->options['toolbar_side'];?>" aria-hidden="true">
	    	<button id="acp-close-toolbar">
	    		<span class="sr-only"><?php _e('Close the accessibility toolbar', 'acp');?></span>
	    		<span class="icon" aria-hidden="true">X</span>
	    	</button>
	    	<p class="toolbar-heading"><?php _e('Accessibility Toolbar', 'acp');?></p>
	    	<ul class="acp-main-nav">
		    	<?php
			    	echo $this->section_general();
			    	if(!isset($this->options['disable_zoom'])) {
				    	echo $this->section_resolution();
			    	}
			    	if(!isset($this->options['disable_fontzoom']) || !isset($this->options['hide_readable'])) {
				    	echo $this->section_font();
			    	}
			    	if(!isset($this->options['hide_contrast'])) {
				    	echo $this->section_contrast();
			    	}
			    	if(!isset($this->options['hide_underline']) || !isset($this->options['hide_linkmarks'])) {
				    	echo $this->section_links();
			    	}
				    echo $this->section_additional();
			    	echo $this->section_author();
			    ?>
	    	</ul>
	    </div>
		<?php
    }
    
    public function section_general() {
	    
	    $focus = ($this->options['focus'] === 'toolbar') ? true : false;
	    $disable_flashes = isset($this->options['hide_flashes']);
	    $disable_hemarks = isset($this->options['hide_headingmarks']);
	    
	    
	    $output = '';
	    
	    if( !$disable_flashes || $focus || !$disable_hemarks ) {
		    $output .= '<li>';
		    	$output .= '<p class="acp-label" id="acp-label-general">' . __('General', 'acp') . '</p>';
				$output .= '<ul class="ul-sub">';
				
				if( !$disable_flashes ) {
					$output .= '<li>';
						$output .= '<button type="button" id="acp_disable_animation" tabindex="-1" aria-labelledby="acp-label-general">';
							$output .= '<i class="material-icons" aria-hidden="true">visibility_off</i>';
							$output .= '<span>' . __('Disable flashes', 'acp') . '<span>';	
						$output .= '</button>';
					$output .= '</li>';
				}
					
				if( $focus ) {
					$output .= '<li>';
						$output .= '<button type="button" id="acp_keys_navigation" tabindex="-1" aria-labelledby="acp-label-general">';
							$output .= '<i class="material-icons" aria-hidden="true">keyboard</i>';
							$output .= '<span>' . __('Keyboard navigation', 'acp') . '</span>';
						$output .= '</button>';
					$output .= '</li>';
				}
				if( !$disable_hemarks ) {
					$output .= '<li>';
						$output .= '<button type="button" id="acp_headings_mark" tabindex="-1" aria-labelledby="acp-label-general">';
							$output .= '<i class="material-icons" aria-hidden="true">title</i>';
							$output .= '<span>' . __('Mark headings', 'acp') . '</span>';
						$output .= '</button>';
					$output .= '</li>';
				}
					
				$output .= '</ul>';
			$output .= '</li>';
	    }
		
		return $output;
    }
    
    public function section_resolution() {
	    $output  = '<li class="acp-li-zoom">';
	    	$output .= '<p class="acp-label" id="acp-label-resolution">' . __('Resolution', 'acp') . '</p>';
	    	$output .= '<ul class="ul-sub">';
	    		$output .= '<li>';
	    			$output .= '<button type="button" id="acp_screen_up" tabindex="-1" aria-labelledby="acp-label-resolution">';
						$output .= '<i class="material-icons" aria-hidden="true">zoom_in</i>';
						$output .= '<span>' . __('Zoom in', 'acp') . '</span>';	
					$output .= '</button>';
				$output .= '</li>';
				$output .= '<li>';
	    			$output .= '<button type="button" id="acp_screen_down" tabindex="-1" aria-labelledby="acp-label-resolution">';
						$output .= '<i class="material-icons" aria-hidden="true">zoom_out</i>';
						$output .= '<span>' . __('Zoom out', 'acp') . '</span>';
					$output .= '</button>';
				$output .= '</li>';
			$output .= '</ul>';
		$output .= '</li>';
		
		return $output;
    }
    
    public function section_font() {
	    $output  = '<li>';
	    	$output .= '<p class="acp-label" id="acp-label-fonts">' . __('Fonts', 'acp') . '</p>';
	    	$output .= '<ul class="ul-sub">';
	    	
	    	if(!isset($this->options['disable_fontzoom'])) {
		    	$output .= '<li>';
	    			$output .= '<button type="button" id="acp_fontsize_up" tabindex="-1" aria-labelledby="acp-label-fonts">';
		    			$output .= '<i class="material-icons" aria-hidden="true">add_circle_outline</i>';
						$output .= '<span>' . __('Increase font size', 'acp') . '</span>';
					$output .= '</button>';
				$output .= '</li>';
				$output .= '<li>';
	    			$output .= '<button type="button" id="acp_fontsize_down" tabindex="-1" aria-labelledby="acp-label-fonts">';
		    			$output .= '<i class="material-icons" aria-hidden="true">remove_circle_outline</i>';
		    			$output .= '<span>' . __('Decrease font size', 'acp') . '</span>';
					$output .= '</button>';
				$output .= '</li>';
	    	}
	    	if(!isset($this->options['hide_readable'])) {
		    	$output .= '<li>';
	    			$output .= '<button type="button" id="acp_readable_font" tabindex="-1" aria-labelledby="acp-label-fonts">';
		    			$output .= '<i class="material-icons" aria-hidden="true">spellcheck</i>';
		    			$output .= '<span>' . __('Readable font', 'acp') . '</span>';
					$output .= '</button>';
				$output .= '</li>';
	    	}
				
			$output .= '</ul>';
	    $output .= '</li>';
		
		return $output;
    }
    
    public function section_contrast() {
	     $output  = '<li class="acp-li-contrast">';
	    	$output .= '<p class="acp-label" id="acp-label-contrast">' . __('Color Contrast', 'acp') . '</p>';
	    	$output .= '<ul class="ul-sub">';
	    		$output .= '<li>';
	    			$output .= '<button type="button" id="acp_contrast_bright" tabindex="-1" aria-labelledby="acp-label-contrast">';
		    			$output .= '<i class="material-icons" aria-hidden="true">brightness_high</i>';
		    			$output .= '<span>' . __('Bright contrast', 'acp') . '</span>';
					$output .= '</button>';
				$output .= '</li>';
				$output .= '<li>';
	    			$output .= '<button type="button" id="acp_contrast_dark" tabindex="-1" aria-labelledby="acp-label-contrast">';
		    			$output .= '<i class="material-icons" aria-hidden="true">brightness_low</i>';
		    			$output .= '<span>' . __('Dark contrast', 'acp') . '</span>';
					$output .= '</button>';
				$output .= '</li>';
			$output .= '</ul>';
	    $output .= '</li>';
		
		return $output;
    }
    
    public function section_links() {
	    $ul_classes = ($this->options['link_underline'] != 'all') ? 'ul-2-items' : '';
	    
	    $output  = '<li>';
	    	$output .= '<p class="acp-label" id="acp-label-links">' . __('Links', 'acp') . '</p>';
	    	$output .= '<ul class="ul-sub ' . $ul_classes . '">';
    		if( $this->options['link_underline'] != 'all' && !isset($this->options['hide_underline']) ) {
	    		$output .= '<li>';
	    			$output .= '<button type="button" id="acp_links_underline" tabindex="-1" aria-labelledby="acp-label-links">';
		    			$output .= '<i class="material-icons" aria-hidden="true">format_underlined</i>';
		    			$output .= '<span>' . __('Underline links', 'acp') . '</span>';
					$output .= '</button>';
				$output .= '</li>';
    		}
    		if( !isset($this->options['hide_linkmarks']) ) {
	    		$output .= '<li>';
	    			$output .= '<button type="button" id="acp_links_mark" tabindex="-1" aria-labelledby="acp-label-links">';
		    			$output .= '<i class="material-icons" aria-hidden="true">font_download</i>';
						$output .= '<span>' . __('Mark links', 'acp') . '</span>';
					$output .= '</button>';
				$output .= '</li>';
    		}
			$output .= '</ul>';
	    $output .= '</li>';

		return $output;
    }
    
    public function section_additional() {
	    $statement = (isset($this->options['acp_statement'])) ? $this->options['acp_statement'] : '';
	    $feedback = (isset($this->options['acp_feedback'])) ? $this->options['acp_feedback'] : '';
	    
	    
	     $output  = '<li>';
	    	// $output .= '<p class="acp-label">' . __('Additional Options', 'acp') . '</p>';
	    	$output .= '<ul class="ul-sub ul-general">';
	    	
	    		$output .= '<li><button type="button" id="acp-reset" tabindex="-1" title="' . __('Reset all options', 'acp') . '">';
		    	$output .= '<i class="material-icons" aria-hidden="true">cached</i>';
		    	$output .= '<span class="sr-only">';
		    	$output .= __('Reset the accessibility toolbar options', 'acp');
		    	$output .= '</span>';
		    	$output .= '</button></li>';

		    	if( $feedback ) {
				    $output .= '<li><a href="'.get_page_link($feedback).'" id="acp_feedback" tabindex="-1" title="' . __('Leave feedback', 'acp') . '">';
			    		$output .= '<i class="material-icons" aria-hidden="true">flag</i>';
			    		$output .= '<span class="sr-only">';
			    		$output .= __('Leave feedback', 'acp');
			    		$output .= '</span>';
			    	$output .= '</a></li>';
			    }
			    
			    if( $statement ) {
				    $output .= '<li><a href="'.get_page_link($statement).'" id="acp_statement" tabindex="-1" title="' . __('Accessibility statement', 'acp') . '">';
			    		$output .= '<i class="material-icons" aria-hidden="true">accessibility</i>';
			    		$output .= '<span class="sr-only">';
			    		$output .= __('Accessibility statement', 'acp');
			    		$output .= '</span>';
			    	$output .= '</a></li>';
			    }
			$output .= '</ul>';
	    $output .= '</li>';
		
		return $output;
    }
    
    public function section_author() {
	    $title = (is_rtl()) ? 'EverAccess - נגישות אתרי וורדפרס' : 'EverAccess | WordPress Accessibility';
	    $title = $title . ' (' . __('the link will open in a new tab', 'acp') . ')';
	    $alt = (is_rtl()) ? 'EverAccess - נגישות אתרי וורדפרס' : 'EverAccess';
	    $access_alt = (is_rtl()) ? 'EverAccess - נגישות אתרי וורדפרס' : 'EverAccess';
	    $logo = ACP_URL . 'assets/img/logo-ea-black.png';
	    $output = '<li><a href="https://www.everaccess.co.il/" hreflang="he" tabindex="-1" title="'.$title.'" aria-label="'.$title.'" target="_blank"><img src="'.$logo.'" alt="'.$alt.'"></a></li>';
	    return $output;
    }
}
new ACP_Toolbar();



