<?php

require_once get_template_directory() . '/FixdigitalForBeginnersTheme/FixdigitalForBeginnersTheme.php';
$pnTheme = new FixdigitalForBeginnersTheme();
$pnTheme->init();

function register_my_menu() {
  register_nav_menu('header-menu',__( 'Header Menu' ));
}
add_action( 'init', 'register_my_menu' );

add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );