<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$logo_img = get_theme_mod( 'image_logo' ); // Getting from option your choice.

$layout_site_default = 'normal';
$layout_site = get_theme_mod( 'layout_site', $layout_site_default );
if ( empty( $layout_site ) || ! in_array( $layout_site, array( 'normal', 'narrow', 'wide' ) ) )
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
		<nav class="nav-main" role="navigation">
			<div class="<?php echo WRAP_CLASSES; ?>">
				<?php if ( pojo_has_nav_menu( 'primary' ) ) : ?>
				<button type="button" class="navbar-toggle visible-xs" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only"><?php _e( 'Toggle navigation', 'pojo' ); ?></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<?php endif; ?>
				<div class="navbar-collapse collapse">
					<?php if ( has_nav_menu( 'primary' ) ) : ?>
						<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'sf-menu hidden-xs', 'walker' => new Pojo_Navbar_Nav_Walker() ) );
						wp_nav_menu( array( 'theme_location' => has_nav_menu( 'primary_mobile' ) ? 'primary_mobile' : 'primary', 'container' => false, 'menu_class' => 'mobile-menu visible-xs', 'walker' => new Pojo_Navbar_Nav_Walker() ) ); ?>
						<?php if ( get_theme_mod( 'chk_enable_menu_search' ) && pojo_has_nav_menu( 'primary' ) ) : ?>
							<div class="hidden-xs">
								<a href="javascript:void(0);" class="search-toggle" data-target="#search-section-primary">
									<i class="fa fa-search"></i>
								</a>
							</div>
						<?php endif; ?>
					<?php elseif ( current_user_can( 'edit_theme_options' ) ) : ?>
						<mark class="menu-no-found"><?php printf( __( 'Please setup Menu <a href="%s">here</a>', 'pojo' ), admin_url( 'nav-menus.php?action=locations' ) ); ?></mark>
					<?php endif; ?>
				</div>
			</div>
		</nav><!--/#nav-menu -->
		<?php if ( get_theme_mod( 'chk_enable_menu_search' ) ) : ?>
			<div class="hidden-xs">
				<div id="search-section-primary" class="search-section" style="display: none;">
					<div class="<?php echo WRAP_CLASSES; ?>">
						<?php get_search_form(); ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<header id="header" role="banner">
			<div class="sticky-header-running"></div>
			<div class="<?php echo WRAP_CLASSES; ?>">
				<div class="logo" role="banner">
					<?php if ( ! empty( $logo_img ) ) : ?>
						<div class="logo-img">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo esc_attr( $logo_img ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="logo-img-primary" /></a>
						</div>
					<?php else : ?>
						<div class="logo-text">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
						</div>
					<?php endif; ?>
				</div>
			</div><!-- /.container -->
		</header>
		<?php if ( get_theme_mod( 'chk_enable_sticky_header' ) ) :?>
			<div class="sticky-header">
				<nav class="nav-main" role="navigation">
					<div class="<?php echo WRAP_CLASSES; ?>">
						<div class="navbar-collapse collapse">
							<?php if ( has_nav_menu( 'primary' ) ) : ?>
								<?php wp_nav_menu( array( 'theme_location' => 'sticky_menu', 'container' => false, 'menu_class' => 'sf-menu hidden-xs', 'walker' => new Pojo_Navbar_Nav_Walker() ) );
								wp_nav_menu( array( 'theme_location' => has_nav_menu( 'primary_mobile' ) ? 'primary_mobile' : 'primary', 'container' => false, 'menu_class' => 'mobile-menu visible-xs', 'walker' => new Pojo_Navbar_Nav_Walker() ) ); ?>
								<?php if ( get_theme_mod( 'chk_enable_menu_search' ) && pojo_has_nav_menu( 'sticky_menu' ) ) : ?>
									<div class="hidden-xs">
										<a href="javascript:void(0);" class="search-toggle" data-target="#search-section-sticky">
											<i class="fa fa-search"></i>
										</a>
									</div>
								<?php endif; ?>
							<?php elseif ( current_user_can( 'edit_theme_options' ) ) : ?>
								<mark class="menu-no-found"><?php printf( __( 'Please setup Menu <a href="%s">here</a>', 'pojo' ), admin_url( 'nav-menus.php?action=locations' ) ); ?></mark>
							<?php endif; ?>
						</div>
					</div>
				</nav><!--/#nav-menu -->
				<?php if ( get_theme_mod( 'chk_enable_menu_search' ) ) : ?>
					<div class="hidden-xs">
						<div id="search-section-sticky" class="search-section" style="display: none;">
							<div class="<?php echo WRAP_CLASSES; ?>">
								<?php get_search_form(); ?>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	<?php endif; // end blank page ?>

	<?php po_change_loop_to_parent(); ?>

	<div id="primary">
		<div class="<?php echo WRAP_CLASSES; ?>">
			<div id="content" class="<?php echo CONTAINER_CLASSES; ?>">