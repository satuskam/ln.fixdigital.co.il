<!DOCTYPE html>
<html >
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<?php wp_head(); ?>
</head>
<body >

<div id="container" class="wide">

	<div class="container-wrapper">

        <header id="header">
            <?php 
        		if (is_active_sidebar('elementor_header_with_banner')) {
            		dynamic_sidebar('elementor_header_with_banner');
        		}
      		?>
        
        <?php	wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?>

        </header>


        <div id="primary">
            <div class="container">
                <div id="content" class="row">       
                
                
                