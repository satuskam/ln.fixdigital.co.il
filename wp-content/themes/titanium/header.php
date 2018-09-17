<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$logo_img = get_theme_mod( 'image_logo' ); // Getting from option your choice.
$mobile_logo_img = get_theme_mod( 'image_header_logo_mobile' );
	
if ( empty( $mobile_logo_img ) )
	$mobile_logo_img = $logo_img;

$layout_site_default = 'boxed';
$layout_site = get_theme_mod( 'layout_site', $layout_site_default );
if ( empty( $layout_site ) || ! in_array( $layout_site, array( 'boxed', 'wide' ) ) )
	$layout_site = $layout_site_default;

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
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<!--[if lt IE 7]><p class="chromeframe">Your browser is <em>ancient!</em>
	<a href="http://browsehappy.com/">Upgrade to a different browser</a> or
	<a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.
</p><![endif]-->

<div id="container" class="<?php echo esc_attr( str_replace( '_', '-', $layout_site ) ); ?>">
	<?php po_change_loop_to_parent( 'change' ); ?>
	<?php if ( ! pojo_is_blank_page() ) : ?>
		<header id="header" class="logo-<?php echo ( 'logo_right' === get_theme_mod( 'header_layout' ) ) ? 'right' : 'left'; ?>" role="banner">
			<div class="<?php echo WRAP_CLASSES; ?>">
				<div id="logo">
					<?php if ( ! empty( $logo_img ) ) : ?>
					<div class="logo-img">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<img src="<?php echo $logo_img; ?>" alt="<?php bloginfo( 'name' ); ?>" class="pojo-hidden-phone" />
							<img src="<?php echo esc_attr( $mobile_logo_img ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="pojo-visible-phone" />
						</a>
					</div>
					<?php else : ?>
					<div class="logo-text">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
					</div>
					<?php endif; ?>

					<?php if ( pojo_has_nav_menu( 'primary' ) ) : ?>
					<button type="button" class="navbar-toggle visible-xs" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only"><?php _e( 'Toggle navigation', 'pojo' ); ?></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<?php endif; ?>
				</div>
				<div id="widget-header" class="hidden-xs">
					<?php dynamic_sidebar( 'pojo-' . sanitize_title( 'Header' ) ); ?>
				</div>
			</div><!-- /.container -->
		</header>

		<nav class="nav-main" role="navigation">
			<div class="<?php echo WRAP_CLASSES; ?>">
				<div class="navbar-collapse collapse">
					<?php if ( has_nav_menu( 'primary' ) ) : ?>
						<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'sf-menu hidden-xs', 'walker' => new Pojo_Navbar_Nav_Walker() ) );
						wp_nav_menu( array( 'theme_location' => has_nav_menu( 'primary_mobile' ) ? 'primary_mobile' : 'primary', 'container' => false, 'menu_class' => 'mobile-menu visible-xs', 'walker' => new Pojo_Navbar_Nav_Walker() ) ); ?>
					<?php elseif ( current_user_can( 'edit_theme_options' ) ) : ?>
						<mark class="menu-no-found"><?php printf( __( 'Please setup Menu <a href="%s">here</a>', 'pojo' ), admin_url( 'nav-menus.php?action=locations' ) ); ?></mark>
					<?php endif; ?>
				</div>
				<?php if ( get_theme_mod( 'chk_enable_search_button', true ) && pojo_has_nav_menu( 'primary' ) ) : ?>
				<div class="menu-search hidden-xs">
					<div class="click-search"><i class="fa fa-search"></i></div>
					<?php get_search_form(); ?>
				</div>
				<?php endif; ?>
			</div><!-- /.container -->
		</nav><!-- /.nav-menu -->

		<?php if ( get_theme_mod( 'chk_enable_sticky_header' ) ) : ?>
			<div class="sticky-header">
				<nav class="nav-main" role="navigation">
					<div class="<?php echo WRAP_CLASSES; ?>">
						<div class="navbar-collapse collapse">
							<?php if ( has_nav_menu( 'primary' ) ) : ?>
								<?php wp_nav_menu( array( 'theme_location' => 'sticky_menu', 'container' => false, 'menu_class' => 'sf-menu hidden-xs', 'walker' => new Pojo_Navbar_Nav_Walker() ) );
								wp_nav_menu( array( 'theme_location' => has_nav_menu( 'primary_mobile' ) ? 'primary_mobile' : 'primary', 'container' => false, 'menu_class' => 'mobile-menu visible-xs', 'walker' => new Pojo_Navbar_Nav_Walker() ) ); ?>
							<?php elseif ( current_user_can( 'edit_theme_options' ) ) : ?>
								<mark class="menu-no-found"><?php printf( __( 'Please setup Menu <a href="%s">here</a>', 'pojo' ), admin_url( 'nav-menus.php?action=locations' ) ); ?></mark>
							<?php endif; ?>
						</div>
						<?php if ( get_theme_mod( 'chk_enable_search_button', true ) && pojo_has_nav_menu( 'sticky_menu' ) ) : ?>
							<div class="menu-search hidden-xs">
								<div class="click-search"><i class="fa fa-search"></i></div>
								<?php get_search_form(); ?>
							</div>
						<?php endif; ?>
					</div><!-- /.container -->
				</nav><!-- /.nav-menu -->
			</div>
		<?php endif; ?>
		<div class="sticky-header-running"></div>
	<?php endif; // end blank page ?>

	<?php po_change_loop_to_parent(); ?>
	<?php pojo_print_titlebar(); ?>

		<div id="primary">
			<div class="<?php echo WRAP_CLASSES; ?>">
				<div id="content" class="<?php echo CONTAINER_CLASSES; ?>">
					