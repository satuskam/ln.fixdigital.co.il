<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// $logo_img = get_theme_mod( 'image_logo' ); // Getting from option your choice.
// $sticky_logo_img = get_theme_mod( 'image_sticky_header_logo' ); // Getting from option your choice.
// if ( ! $sticky_logo_img )
// 	$sticky_logo_img = $logo_img;

// $layout_site_default = 'normal';
// $layout_site = get_theme_mod( 'layout_site', $layout_site_default );
// if ( empty( $layout_site ) || ! in_array( $layout_site, array( 'normal', 'narrow', 'wide' ) ) )
// 	$layout_site = $layout_site_default;

?><!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php wp_title( '|', true, 'right' ); ?></title>

	<?php
	remove_theme_support( 'wc-product-gallery-zoom' );
    // remove_theme_support( 'wc-product-gallery-lightbox' );
    // remove_theme_support( 'wc-product-gallery-slider' );
    ?>

	<?php wp_head(); ?>
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Heebo:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic|Roboto+Slab:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic|Neuton:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic|Karla:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&subset=hebrew">

			<style type="text/css" media="print">#wpadminbar { display:none; }</style>

	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Poppins:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&subset=hebrew"><meta name="generator" content="Powered by Slider Revolution 5.3.1.5 - responsive, Mobile-Friendly Slider Plugin for WordPress with comfortable drag and drop interface." />
</head>
<body <?php body_class('layout-section'); ?>>
<!--[if lt IE 7]><p class="chromeframe">Your browser is <em>ancient!</em>
	<a href="http://browsehappy.com/">Upgrade to a different browser</a> or
	<a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.
</p><![endif]-->
<div id="container" class="wide">
	<!-- vaa change beg -->
	  <header id="header">
	      <?php
	  		if (is_active_sidebar('elementor_header')) {
	      		dynamic_sidebar('elementor_header');
	  		}
			?>
	  </header>
	<!-- vaa change end -->
		<div id="primary">
			<div class="container">
				<div id="content" class="row">


