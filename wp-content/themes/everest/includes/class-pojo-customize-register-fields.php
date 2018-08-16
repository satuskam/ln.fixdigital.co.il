<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Pojo_Everest_Customize_Register_Fields {

	public function section_style( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'primary_color',
			'title' => __( 'Primary Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#ff7477',
		);

		$fields[] = array(
			'id'    => 'secondary_color',
			'title' => __( 'Secondary Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#FFFFFF',
		);

		$sections[] = array(
			'id' => 'style',
			'title'      => __( 'Style', 'pojo' ),
			'desc'       => '',
			'fields'     => $fields,
		);

		return $sections;
	}

	public function section_logo( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'typo_site_title',
			'title' => __( 'Site Name', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '30px',
				'family'  => 'Jura',
				'weight' => 'normal',
				'color' => '#FF7477',
				'line_height' => '1em',
			),
			'selector' => 'div.logo-text a',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'image_logo',
			'title' => __( 'Choose Logo', 'pojo' ),
			'type' => Pojo_Theme_Customize::FIELD_IMAGE,
			'std'   => get_template_directory_uri() . '/assets/images/logo.png',
		);

		$fields[] = array(
			'id'    => 'percent_logo_size',
			'title' => __( 'Percent Size', 'pojo' ),
			'std' => '100%',
			'change_type' => 'width',
			'selector' => '.logo-img a > img',
		);

		$fields[] = array(
			'id'    => 'image_logo_margin_top',
			'title' => __( 'Logo Margin Top', 'pojo' ),
			'std'   => '22px',
			'selector' => '.logo',
			'change_type' => 'margin_top',
		);

		$fields[] = array(
			'id'    => 'image_sticky_header_logo',
			'title' => __( 'Logo Sticky Header (and Mobile)', 'pojo' ),
			'type' => Pojo_Theme_Customize::FIELD_IMAGE,
			'std'   => get_template_directory_uri() . '/assets/images/logo-sticky.png',
		);

		$sections[] = array(
			'id' => 'logo',
			'title'      => __( 'Logo', 'pojo' ),
			'desc'       => '',
			'fields'     => $fields,
		);

		return $sections;
	}

	public function section_background( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'bg_body',
			'title' => __( 'Background', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_BACKGROUND,
			'std' => array(
				'color' => '#eef1f2',
				'image'  => '',
				'position'  => 'top center',
				'repeat' => 'repeat',
				'size' => 'auto',
				'attachment' => 'scroll',
			),
			'selector' => 'body',
			'change_type' => 'background',
		);

		$sections[] = array(
			'id' => 'background',
			'title'      => __( 'Background', 'pojo' ),
			'desc'       => '',
			'fields'     => $fields,
		);

		return $sections;
	}

	public function section_header( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id' => 'height_header',
			'title' => __( 'Height', 'pojo' ),
			'std' => '70px',
		);

		$fields[] = array(
			'id' => 'bg_header',
			'title' => __( 'Background', 'pojo' ),
			'type' => Pojo_Theme_Customize::FIELD_BACKGROUND,
			'std' => array(
				'color' => '#3a3f41',
				'image'  => '',
				'position'  => 'top center',
				'repeat' => 'repeat-x',
				'size' => 'auto',
				'attachment' => 'scroll',
			),
			'skip_transport' => true,
			'selector' => '#header, .sticky-header',
			'change_type' => 'background',
		);

		$fields[] = array(
			'id'    => 'header_layout',
			'title' => __( 'Header Layout', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_SELECT,
			'choices' => array(
				'logo_left' => __( 'Logo Left - Menu Right', 'pojo' ),
				'logo_right' => __( 'Menu Left - Logo Right', 'pojo' ),
			),
			'std' => 'logo_left',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id' => 'chk_enable_sticky_header',
			'title' => __( 'Sticky Header', 'pojo' ),
			'type' => Pojo_Theme_Customize::FIELD_CHECKBOX,
			'std' => true,
		);

		$sections[] = array(
			'id' => 'header',
			'title'      => __( 'Header', 'pojo' ),
			'desc'       => '',
			'fields'     => $fields,
		);

		return $sections;
	}

	public function section_menus( $sections = array() ) {
		$fields = array();

		$fields = apply_filters( 'pojo_customizer_section_menus_before', $fields );

		$fields[] = array(
			'id'    => 'typo_menu_primary',
			'title' => __( 'Menu Primary', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '13px',
				'family'  => 'Lato',
				'weight' => '400',
				'color' => '#ffffff',
				'line_height' => false, // Skip for that's value !
			),
			'selector' => '.sf-menu a, .mobile-menu a',
			'change_type' => 'typography',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_menu_primary_hover',
			'title' => __( 'Menu Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#ff7477',
			'selector' => '.sf-menu a:hover,.sf-menu li.active a, .sf-menu li.current-menu-item > a,.sf-menu .sfHover > a,.sf-menu .sfHover > li.current-menu-item > a,.sf-menu li.current-menu-ancestor > a,.mobile-menu a:hover,.mobile-menu li.current-menu-item > a',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_bg_sub_menu',
			'title' => __( 'Sub Menu - Background', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#3a3f41',
			'selector' => '.sf-menu .sub-menu li',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'color_bg_sub_menu_hover',
			'title' => __( 'Sub Menu - BG Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#e5e5e5',
			'selector' => '.sf-menu .sub-menu li:hover',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'typo_sub_menu_link',
			'title' => __( 'Sub Menu', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '13px',
				'family'  => 'Lato',
				'weight' => 'normal',
				'color' => '#a0a0a0',
				'line_height' => '3em',
			),
			'selector' => '.sf-menu .sub-menu li a',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_sub_menu_link_hover',
			'title' => __( 'Sub Menu - Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#ff7477',
			'selector' => '.sf-menu .sub-menu li:hover > a,.sf-menu .sub-menu li.current-menu-item > a',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$sections[] = array(
			'id' => 'menus',
			'title'      => __( 'Navigation', 'pojo' ),
			'desc'       => '',
			'fields'     => $fields,
		);

		return $sections;
	}

	public function section_typography( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'typo_body_text',
			'title' => __( 'Body Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '15px',
				'family'  => 'PT Sans',
				'weight' => '300',
				'color' => '#818181',
				'line_height' => '1.6em',
			),
			'selector' => 'body',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#FF7477',
			'selector' => 'a',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#FF7477',
			'selector' => 'a:hover',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_text_selection',
			'title' => __( 'Text Selection', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#ffffff',
			'selector' => 'selection',
			'change_type' => 'text_selection',
		);

		$fields[] = array(
			'id'    => 'color_text_bg_selection',
			'title' => __( 'Text Background Selection', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#FF7477',
			'selector' => 'selection',
			'change_type' => 'bg_selection',
		);

		$fields[] = array(
			'id'    => 'typo_h1',
			'title' => __( 'H1 - Page Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '38px',
				'family'  => 'Lato',
				'weight' => '400',
				'color' => '#797979',
				'transform' => 'uppercase',
				'line_height' => '60px',
			),
			'selector' => 'h1',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_h2',
			'title' => __( 'H2', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '35px',
				'family'  => 'Lato',
				'weight' => 'normal',
				'color' => '#FF7477',
				'line_height' => '1.5em',
			),
			'selector' => 'h2',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_h3',
			'title' => __( 'H3 - List Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '26px',
				'family'  => 'Lato',
				'weight' => 'bold',
				'color' => '#FF7477',
				'transform' => 'uppercase',
				'line_height' => '1.5em',
			),
			'selector' => 'h3',
			'change_type' => 'typography',
		);


		$fields[] = array(
			'id'    => 'typo_h4',
			'title' => __( 'H4 - Grid Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '19px',
				'family'  => 'Lato',
				'weight' => 'bold',
				'color' => '#FF7477',
				'transform' => 'uppercase',
				'line_height' => '1.5em',
			),
			'selector' => 'h4',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_h5',
			'title' => __( 'H5', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '18px',
				'family'  => 'Lato',
				'weight' => '400',
				'color' => '#FF7477',
				'transform' => 'uppercase',
				'line_height' => '2em',
			),
			'selector' => 'h5',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_h6',
			'title' => __( 'H6', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '15px',
				'family'  => 'Lato',
				'weight' => 'normal',
				'color' => '#FF7477',
				'line_height' => '1.5em',
			),
			'selector' => 'h6',
			'change_type' => 'typography',
		);

		$sections[] = array(
			'id' => 'typography',
			'title'      => __( 'Typography', 'pojo' ),
			'desc'       => '',
			'fields'     => $fields,
		);

		return $sections;
	}

	public function section_page_header( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'ph_style',
			'title' => __( 'Style', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_SELECT,
			'choices' => array(
				'custom_bg' => __( 'Custom Background', 'pojo' ),
				'transparent' => __( 'Transparent Background', 'pojo' ),
			),
			'std' => 'custom_bg',
		);

		$fields[] = array(
			'id' => 'ph_background',
			'title' => __( 'Background', 'pojo' ),
			'type' => Pojo_Theme_Customize::FIELD_BACKGROUND,
			'std' => array(
				'color' => '#3a3f41',
				'image'  => '',
				'position'  => 'center center',
				'repeat' => 'repeat',
				'size' => 'cover',
				'attachment' => 'scroll',
			),
			'selector' => '#page-header.page-header-style-custom_bg',
			'change_type' => 'background',
		);

		$fields[] = array(
			'id'    => 'ph_height',
			'title' => __( 'Height', 'pojo' ),
			'std'   => '220px',
			'selector' => '#page-header',
			'change_type' => 'line_height',
		);

		$fields[] = array(
			'id'    => 'ph_typo_title',
			'title' => __( 'Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '38px',
				'family'  => 'Lato',
				'weight' => '400',
				'color' => '#ffffff',
				'transform' => 'uppercase',
				'line_height' => false, // Skip for that's value !
			),
			'selector' => '#page-header',
			'change_type' => 'typography',
		);

		$sections[] = array(
			'id' => 'page_header',
			'title'      => __( 'Title Bar', 'pojo' ),
			'desc'       => '',
			'fields'     => $fields,
		);

		return $sections;
	}

	public function section_sidebar( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'typo_sidebar_text',
			'title' => __( 'Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '15px',
				'family'  => 'PT Sans',
				'weight' => '300',
				'color' => '#818181',
				'line_height' => '1.6em',
			),
			'selector' => '#sidebar',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_sidebar_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#FF7477',
			'selector' => '#sidebar a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_sidebar_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#FF7477',
			'selector' => '#sidebar a:hover',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'typo_sidebar_widget_text',
			'title' => __( 'Widget Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '18px',
				'family'  => 'Lato',
				'weight' => '400',
				'color' => '#FF7477',
				'transform' => 'uppercase',
				'line_height' => '2em',
			),
			'selector' => '#sidebar .widget-title',
			'change_type' => 'typography',
		);

		$sections[] = array(
			'id' => 'sidebar',
			'title'      => __( 'Sidebar', 'pojo' ),
			'desc'       => '',
			'fields'     => $fields,
		);

		return $sections;
	}

	public function section_footer( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'color_bg_footer',
			'title' => __( 'Background Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#3a3f41',
			'selector' => '#footer',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'typo_footer_text',
			'title' => __( 'Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '14px',
				'family'  => 'Lato',
				'weight' => 'normal',
				'color' => '#656a6a',
				'line_height' => '1.3em',
			),
			'selector' => '#footer',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_footer_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#ffffff',
			'selector' => '#footer a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_footer_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#FF7477',
			'selector' => '#footer a:hover',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'typo_footer_widget_text',
			'title' => __( 'Widget Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '16px',
				'family'  => 'Lato',
				'weight' => 'normal',
				'color' => '#ff7477',
				'line_height' => '2.5em',
			),
			'selector' => '#sidebar-footer .widget-title',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'sidebar_footer_columns',
			'title' => __( 'Widget Columns', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_SELECT,
			'choices' => array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
			),
			'std' => '4',
		);

		$sections[] = array(
			'id' => 'footer',
			'title'      => __( 'Footer', 'pojo' ),
			'desc'       => '',
			'fields'     => $fields,
		);

		return $sections;
	}

	public function section_copyright( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'color_bg_copyright',
			'title' => __( 'Background Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#3a3f41',
			'selector' => '#copyright',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'typo_copyright_text',
			'title' => __( 'Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '11px',
				'family'  => 'Lato',
				'weight' => 'normal',
				'color' => '#ffffff',
				'line_height' => '60px',
			),
			'selector' => '#copyright',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_copyright_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#ffffff',
			'selector' => '#copyright a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_copyright_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#ff7477',
			'selector' => '#copyright a:hover',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$sections[] = array(
			'id' => 'copyright',
			'title'      => __( 'Copyright', 'pojo' ),
			'desc'       => '',
			'fields'     => $fields,
		);

		return $sections;
	}

	public function pojo_wp_head_custom_css_code( Pojo_Create_CSS_Code $css_code ) {
		$primary_color = get_theme_mod( 'primary_color' );
		$secondary_color = get_theme_mod( 'secondary_color' );

		if ( $primary_color && $secondary_color ) {
			$string = <<<CSSCODE
			.navbar-toggle:hover .icon-bar, .navbar-toggle:focus .icon-bar {background-color: {$primary_color};}
			.nav-main .pojo-menu-cart li.cart-checkout a {color: {$primary_color};border-color: {$primary_color};}
			.nav-main .pojo-menu-cart li.cart-checkout a:hover {color:{$secondary_color}; background-color: {$primary_color};}
			#breadcrumbs .separator:before {color: {$primary_color}; }
			.title-comments, .title-respond,.pb-widget-title,.widget-title:after {border-color: {$primary_color};}
			.pojo-loadmore-wrap .button,.pojo-loadmore-wrap .pojo-loading,.pojo-loading-wrap .button,.pojo-loading-wrap .pojo-loading {color: {$primary_color}; border-color: {$primary_color}; }
			.align-pagination .pagination .active a {color: {$primary_color}; border-color: {$primary_color}; }
			#primary #content #main .entry-meta span:before {color: {$primary_color}; }
			.small-thumbnail .inbox:hover { border-color: {$primary_color}; }
			.grid-item .inbox:hover {border-color: {$primary_color}; }
			.image-link {background-color: {$primary_color}; }
			.image-link .overlay-image + .overlay-title figcaption { border-color: {$secondary_color}; color: {$secondary_color}; }
			.image-link .overlay-image + .overlay-title .fa {color: {$secondary_color} }
			.category-filters li a:hover, .category-filters li .active {border-color: {$primary_color}; color: {$primary_color}; }
			.square-item.grid-item .caption.overlay-title h4.grid-heading {color: {$secondary_color} }
			.square-item.grid-item .caption.overlay-title h4.grid-heading small {color: {$secondary_color}; }
			#sidebar ul li,#sidebar-footer ul li {color: {$primary_color}; }
			.woocommerce ul.products li.product .inbox:hover, .woocommerce-page ul.products li.product .inbox:hover {border-color: {$primary_color}; }
			.woocommerce ul.products .product .inbox .image-link, .woocommerce-page ul.products .product .inbox .image-link {background-color: {$primary_color}; }
			.woocommerce ul.products .product .inbox .image-link .overlay-image + .overlay-title .button, .woocommerce-page ul.products .product .inbox .image-link .overlay-image + .overlay-title .button {border-color: {$secondary_color}; color: {$secondary_color} }
			.woocommerce ul.products .product .inbox .image-link .overlay-image + .overlay-title .added_to_cart, .woocommerce-page ul.products .product .inbox .image-link .overlay-image + .overlay-title .added_to_cart {color: {$secondary_color} }
			.woocommerce span.onsale, .woocommerce-page span.onsale {background: {$primary_color}; }
			.woocommerce ul.products .product .inbox .image-link .onsale, .woocommerce-page ul.products .product .inbox .image-link .onsale {background: {$primary_color}; }
			.woocommerce ul.products .product .inbox .image-link:hover, .woocommerce-page ul.products .product .inbox .image-link:hover {border-color: {$primary_color}; }
			.woocommerce a.button,.woocommerce button.button,.woocommerce input.button,.woocommerce #respond input#submit,.woocommerce #content input.button,.woocommerce-page a.button,
			.woocommerce-page button.button,.woocommerce-page input.button,.woocommerce-page #respond input#submit,.woocommerce-page #content input.button {border-color: {$primary_color}; color: {$primary_color}; }
			.woocommerce a.button:hover,.woocommerce button.button:hover,.woocommerce input.button:hover,.woocommerce #respond input#submit:hover,.woocommerce #content input.button:hover,.woocommerce-page a.button:hover,
			.woocommerce-page button.button:hover,.woocommerce-page input.button:hover,.woocommerce-page #respond input#submit:hover,.woocommerce-page #content input.button:hover {background:{$primary_color}; border-color: {$primary_color}; color: {$secondary_color};}
			.woocommerce a.button.alt,.woocommerce button.button.alt,.woocommerce input.button.alt,.woocommerce #respond input#submit.alt,.woocommerce #content input.button.alt,.woocommerce-page a.button.alt,
			.woocommerce-page button.button.alt,.woocommerce-page input.button.alt,.woocommerce-page #respond input#submit.alt,.woocommerce-page #content input.button.alt {background: {$primary_color}; border-color: {$primary_color}; color: {$secondary_color}; }
			.woocommerce a.button.alt:hover,.woocommerce button.button.alt:hover,.woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover,.woocommerce #content input.button.alt:hover,.woocommerce-page a.button.alt:hover,
			.woocommerce-page button.button.alt:hover,.woocommerce-page input.button.alt:hover,	.woocommerce-page #respond input#submit.alt:hover,.woocommerce-page #content input.button.alt:hover {border-color: {$primary_color}; color: {$primary_color}; }
			.woocommerce .woocommerce-error,.woocommerce .woocommerce-info, .woocommerce .woocommerce-message, .woocommerce-page .woocommerce-error,.woocommerce-page .woocommerce-info, .woocommerce-page .woocommerce-message { border-color: {$primary_color}; }
			.woocommerce .woocommerce-error:before,.woocommerce .woocommerce-info:before, .woocommerce .woocommerce-message:before, .woocommerce-page .woocommerce-error:before,.woocommerce-page .woocommerce-info:before,
			 .woocommerce-page .woocommerce-message:before {background-color: {$primary_color}; color: {$secondary_color}; }
			.button,.button.size-small,.button.size-large,.button.size-xl,.button.size-xxl{ background-color: {$secondary_color}; border-color: {$primary_color}; color: {$primary_color}; }
			.button:hover,.button.size-small:hover,.button.size-large:hover,.button.size-xl:hover, .button.size-xxl:hover {background: {$primary_color}; border-color: {$primary_color}; color: {$secondary_color}; }
CSSCODE;
			
			$css_code->add_data( $string );
		} // End style colors
		
		$option = get_theme_mod( 'height_header', '70px' );
		$css_code->add_value( '#header', 'height', $option );
		$css_code->add_value( '.sf-menu a, .menu-no-found', 'line-height', $option );
		$css_code->add_value( '.sf-menu li:hover ul, .sf-menu li.sfHover ul, body.pojo-title-bar .sticky-header-running', 'top', $option );
		$css_code->add_value( '#page-header .page-header-title.container', 'padding-top', $option );

		$option = get_theme_mod( 'typo_menu_primary' );
		if ( ! empty( $option['color'] ) ) {
			$css_code->add_value( '.navbar-toggle', 'border-color', $option['color'] );
			$css_code->add_value( '.icon-bar', 'background-color', $option['color'] );
		}

		$option = get_theme_mod( 'typo_h1' );
		if ( ! empty( $option ) ) {
			$css_code->add_value( '#primary #content #main header.container #breadcrumbs, .woocommerce #breadcrumbs, .woocommerce-page #breadcrumbs', 'line-height', $option['line_height'] );
			$css_code->add_value( '#primary #content #main header.container #breadcrumbs, .woocommerce #breadcrumbs, .woocommerce-page #breadcrumbs', 'font-family', $option['family'] );
		}

		$option = get_theme_mod( 'color_menu_primary_hover' );
		if ( ! empty( $option ) ) {
			$css_code->add_value( '.sf-menu li.active, .sf-menu li:hover, .sf-menu li.current-menu-item, .sf-menu li.current-menu-parent, .sf-menu li.current-menu-ancestor, .sf-menu li.current_page_item, .sf-menu li.current_page_paren, .sf-menu li.current_page_ancestor', 'border-color', $option );
		}

		$option = get_theme_mod( 'color_link' );
		if ( ! empty( $option ) ) {
			$css_code->add_value( '#sidebar .menu li a:hover, #sidebar .sub-menu li a:hover, #sidebar .sub-page-menu li a:hover,
			 #sidebar .menu li.current_page_item > a, #sidebar .sub-menu li.current_page_item > a, #sidebar .sub-page-menu li.current_page_item > a, #sidebar .menu li.current-menu-item > a,
			 #sidebar .sub-menu li.current-menu-item > a, #sidebar .sub-page-menu li.current-menu-item > a', 'border-color', $option );
			$css_code->add_value( '.category-filters a', 'color', $option );
		}

		$option = get_theme_mod( 'color_link_hover' );
		if ( ! empty( $option ) ) {
			$css_code->add_value( '.category-filters a:hover,.category-filters a.active', 'color', $option);
			$css_code->add_value( '.category-filters a:hover,.category-filters a.active', 'border-top-color', $option );
		}
	}

	public function section_mobile( $sections = array() ) {
		$fields = array();

		$sections[] = array(
			'id' => 'mobile',
			'title' => __( 'Mobile', 'pojo' ),
			'desc' => '',
			'fields' => $fields,
		);

		return $sections;
	}

	public function __construct() {
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_style' ), 100 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_logo' ), 110 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_header' ), 130 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_menus' ), 140 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_background' ), 120 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_typography' ), 150 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_page_header' ), 160 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_sidebar' ), 170 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_footer' ), 180 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_copyright' ), 190 );

		add_filter( 'pojo_wp_head_custom_css_code', array( &$this, 'pojo_wp_head_custom_css_code' ) );
	}

}
new Pojo_Everest_Customize_Register_Fields();