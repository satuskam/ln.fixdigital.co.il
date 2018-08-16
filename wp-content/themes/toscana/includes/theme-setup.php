<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function pojo_main_widgets_init() {
	register_sidebar(
		array(
			'id'            => 'pojo-' . sanitize_title( 'Main Sidebar' ),
			'name'          => __( 'Main Sidebar', 'pojo' ),
			'description'   => __( 'These are widgets for the Main Sidebar', 'pojo' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></section>',
			'before_title'  => '<h5 class="widget-title"><span>',
			'after_title'   => '</span></h5>',
		)
	);

	register_sidebar(
		array(
			'id'            => 'pojo-' . sanitize_title( 'Header' ),
			'name'          => __( 'Header', 'pojo' ),
			'description'   => __( 'These widgets appear underneath the menu in the Header', 'pojo' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></section>',
			'before_title'  => '<h5 class="widget-title"><span>',
			'after_title'   => '</span></h5>',
		)
	);

	$footer_class = pojo_get_sidebar_columns_class(
		array(
			'1' => 'col-sm-12',
			'2' => 'col-sm-6',
			'3' => 'col-sm-4',
			'4' => 'col-md-3 col-sm-6',
		),
		'4',
		'sidebar_footer_columns'
	);

	register_sidebar(
		array(
			'id'            => 'pojo-' . sanitize_title( 'Footer' ),
			'name'          => __( 'Footer', 'pojo' ),
			'description'   => __( 'These are widgets for the Footer', 'pojo' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s ' . $footer_class . '"><div class="widget-inner">',
			'after_widget'  => '</div></section>',
			'before_title'  => '<h5 class="widget-title"><span>',
			'after_title'   => '</span></h5>',
		)
	);

}
add_action( 'widgets_init', 'pojo_main_widgets_init' );

function pojo_theme_setup() {
	add_theme_support( 'pojo-blank-page' );
	add_theme_support( 'pojo-page-header' );
	add_theme_support( 'pojo-infinite-scroll' );
	add_theme_support( 'pojo-background-options' );
	add_theme_support( 'pojo-about-author' );
	add_theme_support( 'pojo-recent-post-metadata' );
	add_theme_support( 'pojo-post-formats' );
	add_theme_support( 'post-formats', array( 'image', 'video', 'gallery', 'audio' ) );
}
add_action( 'after_setup_theme', 'pojo_theme_setup', 20 );

include( 'class-pojo-wc-templates.php' );