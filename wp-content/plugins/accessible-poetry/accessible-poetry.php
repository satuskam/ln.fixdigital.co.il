<?php
/**
 * Plugin Name: Accessible Poetry - WordPress Accessibility Plugin
 * Plugin URI: http://www.accessible-poetry.com/
 * Description: The Accessibility plugin that makes  WordPress site accessible for people with disabilities, the plugin provides set of advanced accessibility tools.
 * Version: 3.0.4
 * Author: Amit Moreno
 * Author URI: http://www.amitmoreno.com/
 * Text Domain: acp
 * Domain Path: /lang
 * License: GPL2
 */

define('ACP_URL', plugin_dir_url( __FILE__ ));

// Panel
require_once('lib/panel/fields.php');
require_once('lib/panel/sections.php');
require_once('lib/panel/menu.php');
require_once('lib/panel/header.php');
require_once('lib/panel/panel.php');

// toolbar
require_once('lib/toolbar/toolbar.php');
require_once('lib/toolbar/body-classes.php');

require_once('missing-alts.php');
require_once('skiplinks.php');


/*
 * Load the plugin localization files
 */
function acp_localization() {
   load_plugin_textdomain( 'acp', false, plugin_basename( dirname( __FILE__ ) ) . '/lang/' );
}
add_action( 'plugins_loaded', 'acp_localization' );

function acp_front_assets($hook) {
     
    wp_enqueue_script( 'acp-js', plugins_url( 'assets/js/accessible-poetry.js', __FILE__ ), array('jquery'), false );
    wp_register_style( 'acp-css',    plugins_url( 'assets/css/accessible-poetry.css',    __FILE__ ), false,   false );
    wp_enqueue_style ( 'acp-css' );
 
}
add_action('wp_enqueue_scripts', 'acp_front_assets');

function acp_customcss() {
	$acp = get_option('accessible_poetry');
	
	if(isset($acp['custom_css'])) {
		?>
		<style><?php echo $acp['custom_css'];?></style>
		<?php
	}
}
add_action('wp_head', 'acp_customcss');

function acp_customjs() {
	$acp = get_option('accessible_poetry');
	
	if(isset($acp['custom_js'])) {
		?>
		<script><?php echo $acp['custom_js'];?></script>
		<?php
	}
}
add_action('wp_footer', 'acp_customjs');

function acp_fontsizer_inc() {
	$acp = get_option('accessible_poetry');
	
	if(isset($acp['fontsizer_inc'])) {
		?>
		<div id="acp_fontsizer_inc" data-acp-value="<?php echo $acp['fontsizer_inc'];?>"></div>
		<?php
	}
}
add_action('wp_footer', 'acp_fontsizer_inc');

function acp_fontsizer_exc() {
	$acp = get_option('accessible_poetry');
	
	if(isset($acp['fontsizer_exc'])) {
		?>
		<div id="acp_fontsizer_exc" data-acp-value="<?php echo $acp['fontsizer_exc'];?>"></div>
		<?php
	}
}
add_action('wp_footer', 'acp_fontsizer_exc');



