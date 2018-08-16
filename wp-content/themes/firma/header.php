<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$logo_img = get_theme_mod( 'image_logo' ); // Getting from option your choice.
$sticky_logo_img = get_theme_mod( 'image_sticky_header_logo' ); // Getting from option your choice.
if ( ! $sticky_logo_img )
	$sticky_logo_img = $logo_img;

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
	<?php if ( ! pojo_is_blank_page() ) : ?>

	<?php po_change_loop_to_parent( 'change' ); ?>

	<div class="container-wrapper">

		<header id="header" role="banner">
			<div class="<?php echo WRAP_CLASSES; ?>">
				<section class="top-bar">
					<div class="pull-left hidden-xs">
						<?php dynamic_sidebar( 'pojo-' . sanitize_title( 'Top Bar Left' ) ); ?>
					</div>
					<div class="logo">
						<?php if ( ! empty( $logo_img ) ) : ?>
							<div class="logo-img">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo esc_attr( $logo_img ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="logo-img-primary" /></a>
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
					</div><!--.logo -->
					<div class="pull-right hidden-xs">
						<?php dynamic_sidebar( 'pojo-' . sanitize_title( 'Top Bar Right' ) ); ?>
					</div>
				</section>
				<nav class="nav-main" role="navigation">
					<div class="navbar-collapse collapse">
						<div class="nav-main-inner">
							<?php if ( has_nav_menu( 'primary' ) ) : ?>
								<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'sf-menu hidden-xs', 'walker' => new Pojo_Navbar_Nav_Walker() ) );
								wp_nav_menu( array( 'theme_location' => has_nav_menu( 'primary_mobile' ) ? 'primary_mobile' : 'primary', 'container' => false, 'menu_class' => 'mobile-menu visible-xs', 'walker' => new Pojo_Navbar_Nav_Walker() ) ); ?>
							<?php elseif ( current_user_can( 'edit_theme_options' ) ) : ?>
								<mark class="menu-no-found"><?php printf( __( 'Please setup Menu <a href="%s">here</a>', 'pojo' ), admin_url( 'nav-menus.php?action=locations' ) ); ?></mark>
							<?php endif; ?>
						</div>
					</div>
				</nav><!--/#nav-menu -->
			</div><!-- /.container -->
		</header>

		<div class="sticky-header-running"></div>

		<?php if ( get_theme_mod( 'chk_enable_sticky_header' ) ) :?>
			<div class="sticky-header">
				<div class="<?php echo WRAP_CLASSES; ?>">
					<div class="sticky-header-inner">
						<div class="logo">
							<?php if ( ! empty( $sticky_logo_img ) ) : ?>
								<div class="logo-img">
									<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo esc_attr( $sticky_logo_img ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="logo-img-secondary" /></a>
								</div>
							<?php else : ?>
								<div class="logo-text">
									<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
								</div>
							<?php endif; ?>
							<?php if ( pojo_has_nav_menu( 'sticky_menu' ) ) : ?>
								<button type="button" class="navbar-toggle visible-xs" data-toggle="collapse" data-target=".navbar-collapse">
									<span class="sr-only"><?php _e( 'Toggle navigation', 'pojo' ); ?></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
							<?php endif; ?>
						</div><!--.logo -->
						<nav class="nav-main" role="navigation">
							<div class="navbar-collapse collapse">
								<div class="nav-main-inner">
									<?php if ( has_nav_menu( 'primary' ) ) : ?>
										<?php wp_nav_menu( array( 'theme_location' => 'sticky_menu', 'container' => false, 'menu_class' => 'sf-menu hidden-xs', 'walker' => new Pojo_Navbar_Nav_Walker() ) );
										wp_nav_menu( array( 'theme_location' => has_nav_menu( 'primary_mobile' ) ? 'primary_mobile' : 'primary', 'container' => false, 'menu_class' => 'mobile-menu visible-xs', 'walker' => new Pojo_Navbar_Nav_Walker() ) ); ?>
									<?php elseif ( current_user_can( 'edit_theme_options' ) ) : ?>
										<mark class="menu-no-found"><?php printf( __( 'Please setup Menu <a href="%s">here</a>', 'pojo' ), admin_url( 'nav-menus.php?action=locations' ) ); ?></mark>
									<?php endif; ?>
								</div>
							</div>
						</nav><!--.nav-menu -->
						<div class="clearfix"></div>
					</div><!--.sticky-header-inner-->
				</div><!-- /.container -->
			</div>
		<?php endif; // end sticky header ?>
		<?php endif; // end blank page ?>
		
		<?php po_change_loop_to_parent(); ?>
		<?php pojo_print_titlebar(); ?>

		<div id="primary"">
			<div class="<?php echo WRAP_CLASSES; ?>">
				<div id="content" class="<?php echo CONTAINER_CLASSES; ?>">