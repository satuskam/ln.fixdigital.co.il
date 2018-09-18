<?php
$acp = get_option('accessible_poetry');

function acp_register_main_skiplinks_menu() {
  register_nav_menu( 'skiplinks', __( 'Skiplinks', 'acp' ) );
}

function acp_skiplinks($acp) {
	$side = (isset($acp['skiplinks_side'])) ? $acp['skiplinks_side'] : 'left';
	
	wp_nav_menu(array(
		'theme_location'=> 'skiplinks',
		'menu_id'		=> 'skiplinks-ul',
		'menu_class'	=> 'skiplinks',
		'container'		=> 'nav',
		'container_id'	=> 'acp-skiplinks',
		'container_class' => $side,
	));
}

if(isset($acp['skiplinks'])) {
	add_action( 'after_setup_theme', 'acp_register_main_skiplinks_menu' );
	add_action('wp_footer', 'acp_skiplinks');
}