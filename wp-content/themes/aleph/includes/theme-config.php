<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! defined( 'POJO_THEME_NAME' ) )
	define( 'POJO_THEME_NAME', 'Aleph' );

if ( ! defined( 'POJO_VER_BOOTSTRAP' ) )
	define( 'POJO_VER_BOOTSTRAP', '3.2.0' );

if ( ! defined( 'POJO_VER_FONT_AWESOME' ) )
	define( 'POJO_VER_FONT_AWESOME', '4.7.0' );

if ( ! defined( 'POST_EXCERPT_LENGTH' ) )
	define( 'POST_EXCERPT_LENGTH', 40 );

if ( ! defined( 'POJO_DEVELOPER_MODE' ) )
	define( 'POJO_DEVELOPER_MODE', apply_filters( 'pojo_developer_mode', false ) );

if ( ! defined( 'WRAP_CLASSES' ) )
	define( 'WRAP_CLASSES', 'container' );

if ( ! defined( 'CONTAINER_CLASSES' ) )
	define( 'CONTAINER_CLASSES', 'row' );

if ( ! defined( 'MAIN_CLASSES' ) )
	define( 'MAIN_CLASSES', 'col-sm-8 col-md-8' );

if ( ! defined( 'SIDEBAR_CLASSES' ) )
	define( 'SIDEBAR_CLASSES', 'col-sm-4 col-md-4' );

if ( ! defined( 'FULLWIDTH_CLASSES' ) )
	define( 'FULLWIDTH_CLASSES', 'col-sm-12 col-md-12' );

// Content Width
if ( ! defined( 'POJO_GLOBAL_CONTENT_WIDTH' ) )
	define( 'POJO_GLOBAL_CONTENT_WIDTH', 1170 );