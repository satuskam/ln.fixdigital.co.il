<?php
	


function acp_body_classes( $classes ) {
	$acp = get_option('accessible_poetry');
	// underline
	
	if( isset($acp['underline_important']) ) {
		$classes[] = 'acp-underline-important';
	}
	
	// mlinks
	
	if( isset($acp['mlinks_important']) ) {
		$classes[] = 'acp-mlinks-important';
	}
	
	
	if( $acp['link_underline'] === 'focus' ) {
		$classes[] = 'acp-underline-focus';
	} elseif( $acp['link_underline'] === 'hover' ) {
		$classes[] = 'acp-underline-hover';
	} elseif( $acp['link_underline'] === 'all' ) {
		$classes[] = 'acp-underline-all';
	}
	
	if( isset($acp['underline_important']) ) {
		$classes[] = 'acp-underline-important';
	}
	
	// contrast
	
	if( isset($acp['contrast_important']) ) {
		$classes[] = 'acp-contrast-important';
	}
	
	// heading marks
	
	if( isset($acp['mheading_important']) ) {
		$classes[] = 'acp-mheading-important';
	}
	
	// readable
	
	if( isset($acp['readable_important']) ) {
		$classes[] = 'acp-readable-important';
	}
	
	// outline
	
	if( $acp['focus'] === 'toolbar' ) {
		
		if( $acp['focus_type'] === 'custom' ) {
			$classes[] = 'acp-outline-custom';
		} elseif( $acp['focus_type'] === 'red' ) {
			$classes[] = 'acp-outline-red';
		} elseif( $acp['focus_type'] === 'blue' ) {
			$classes[] = 'acp-outline-blue';
		} elseif( $acp['focus_type'] === 'yellow' ) {
			$classes[] = 'acp-outline-yellow';
		}
		if( isset($acp['outline_important']) ) {
			$classes[] = 'acp-outline-important';
		}
	} elseif( $acp['focus'] === 'always' ) {
		$classes[] = 'acp-outline-always';
		
		if( $acp['focus_type'] === 'custom' ) {
			$classes[] = 'acp-outline-custom';
		} elseif( $acp['focus_type'] === 'red' ) {
			$classes[] = 'acp-outline-red';
		} elseif( $acp['focus_type'] === 'blue' ) {
			$classes[] = 'acp-outline-blue';
		}
		if( isset($acp['outline_important']) ) {
			$classes[] = 'acp-outline-important';
		}
	}
	
	
	// empty alt
    
    if( isset($acp['empty_alt']) ) {
		$classes[] = 'acp-alt';
	}
	
    return $classes;
}
add_filter( 'body_class', 'acp_body_classes' );


function acp_head() {
	$acp = get_option('accessible_poetry');
	$color = ( isset($acp['outline_color']) ) ? $acp['outline_color'] : '';
	$important = ( isset($acp['outline_important']) ) ? ' !important' : '';
	
	echo '<style>body.acp-outline-always.acp-outline-custom *:focus, body.acp-outline.acp-outline-custom *:focus {outline: 1px solid '.$color.$important.';}</style>';
}
$acp = get_option('accessible_poetry');
if( isset($acp) && isset($acp['focus']) && $acp['focus'] != 'none' && $acp['focus_type'] === 'custom' && $acp['outline_color'] != '' ) {
	add_filter( 'wp_head', 'acp_head' );
}
