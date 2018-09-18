<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Pojo_Titanium_Customize_Register_Fields {

	public function section_style( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'layout_site',
			'title' => __( 'Layout Site', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_SELECT,
			'choices' => array(
				'normal' => __( 'Boxed', 'pojo' ),
				'wide' => __( 'Wide', 'pojo' ),
			),
			'std' => 'normal',
		);

		$sections[] = array(
			'id' => 'style',
			'title' => __( 'Style', 'pojo' ),
			'desc' => '',
			'fields' => $fields,
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
				'family'  => 'Open Sans',
				'weight' => 'normal',
				'color' => '#303030',
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
			'std' => 'auto',
			'change_type' => 'width',
			'selector' => '.logo-img a > img',
		);

		$fields[] = array(
			'id'    => 'image_logo_margin_top',
			'title' => __( 'Logo Margin Top', 'pojo' ),
			'std'   => '38px',
			'selector' => '#logo',
			'change_type' => 'margin_top',
		);

		$fields[] = array(
			'id'    => 'image_header_logo_mobile',
			'title' => __( 'Mobile Logo', 'pojo' ),
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
				'color' => '#f9f9f9',
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

	public function section_typography( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'typo_body_text',
			'title' => __( 'Body Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '14px',
				'family'  => 'PT Sans',
				'weight' => 'normal',
				'color' => '#5e5e5e',
				'line_height' => '1.6em',
			),
			'selector' => 'body',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#919191',
			'selector' => 'a',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#FAA11F',
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
			'std'   => '#818181',
			'selector' => 'selection',
			'change_type' => 'bg_selection',
		);

		$fields[] = array(
			'id'    => 'typo_h1',
			'title' => __( 'H1 - Page Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '30px',
				'family'  => 'Droid Serif',
				'weight' => 'bold',
				'color' => '#818181',
				'line_height' => '1.5em',
			),
			'selector' => 'h1',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_h2',
			'title' => __( 'H2', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '25px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#818181',
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
				'size'  => '19px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#faa11f',
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
				'size'  => '18px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#faa11f',
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
				'size'  => '16px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#faa11f',
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
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#818181',
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

	public function section_header( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'header_layout',
			'title' => __( 'Header Layout', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_SELECT,
			'choices' => array(
				'logo_left' => __( 'Logo Left - Widgets Right', 'pojo' ),
				'logo_right' => __( 'Widgets Left - Logo Right', 'pojo' ),
			),
			'std' => 'logo_left',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id' => 'height_header',
			'title' => __( 'Height', 'pojo' ),
			'std' => '120px',
		);

		$fields[] = array(
			'id'    => 'widget_header_margin_top',
			'title' => __( 'Widget Header Margin Top', 'pojo' ),
			'std'   => '44px',
			'selector' => '#widget-header',
			'change_type' => 'margin_top',
		);

		$fields[] = array(
			'id'    => 'border_color_header',
			'title' => __( 'Border Color Header', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#FAA11F',
			'selector' => '#header',
			'change_type' => 'border_color',
		);

		$fields[] = array(
			'id' => 'bg_header',
			'title' => __( 'Background', 'pojo' ),
			'type' => Pojo_Theme_Customize::FIELD_BACKGROUND,
			'std' => array(
				'color' => '#FFFFFF',
				'image'  => '',
				'position'  => 'top center',
				'repeat' => 'repeat',
				'size' => 'auto',
				'attachment' => 'scroll',
			),
			'skip_transport' => true,
			'selector' => '#header',
			'change_type' => 'background',
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
			'id'    => 'color_bg_menu_hover',
			'title' => __( 'Menu Background', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#2A2A2A',
			'selector' => '.nav-main',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'color_divider_1_menu_primary',
			'title' => __( 'Menu Divider - Color 1', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#171717',
			'selector' => '.sf-menu > li:before',
			'change_type' => 'border_right_color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_divider_2_menu_primary',
			'title' => __( 'Menu Divider - Color 2', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#353535',
			'selector' => '.sf-menu > li:before',
			'change_type' => 'border_left_color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'typo_menu_primary',
			'title' => __( 'Menu Primary', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '14px',
				'family'  => 'Droid Serif',
				'weight' => 'bold',
				'color' => '#D1D1D1',
				'line_height' => '44px',
			),
			'selector' => '.sf-menu a, .mobile-menu a',
			'change_type' => 'typography',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_menu_primary_hover',
			'title' => __( 'Menu Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#FAA11F',
			'selector' => '.sf-menu a:hover,.sf-menu li.active a, .sf-menu li.current-menu-item > a,.sf-menu .sfHover > a,.sf-menu .sfHover > li.current-menu-item > a,.sf-menu li.current-menu-ancestor > a,.mobile-menu a:hover,.mobile-menu li.current-menu-item > a',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_bg_sub_menu',
			'title' => __( 'Sub Menu - Background', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#434343',
			'selector' => '.nav-main .sf-menu .sub-menu',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'color_bg_sub_menu_hover',
			'title' => __( 'Sub Menu - BG Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#2A2A2A',
			'selector' => '.nav-main .sf-menu .sub-menu li:hover',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'typo_sub_menu_link',
			'title' => __( 'Sub Menu', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '13px',
				'family'  => 'Droid Sans',
				'weight' => 'normal',
				'color' => '#FFFFFF',
				'line_height' => '3em',
			),
			'selector' => '.nav-main .sf-menu .sub-menu li a',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_sub_menu_link_hover',
			'title' => __( 'Sub Menu - Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#FAA11F',
			'selector' => '.nav-main .sf-menu .sub-menu li:hover > a,.nav-main .sf-menu .sub-menu li.current-menu-item > a',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'chk_enable_search_button',
			'title' => __( 'Add Search Button', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_CHECKBOX,
			'std'   => true,
		);

		$fields = apply_filters( 'pojo_customizer_section_menus_after', $fields );

		$sections[] = array(
			'id' => 'menus',
			'title'      => __( 'Navigation', 'pojo' ),
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
				'color' => '#ffffff',
				'image'  => get_template_directory_uri() . '/assets/images/header-bg-line.png',
				'position'  => 'top left',
				'repeat' => 'repeat-x',
				'size' => 'auto',
				'attachment' => 'scroll',
			),
			'selector' => '#page-header.page-header-style-custom_bg',
			'change_type' => 'background',
		);

		$fields[] = array(
			'id'    => 'ph_height',
			'title' => __( 'Height', 'pojo' ),
			'std'   => '80px',
			'selector' => '#page-header',
			'change_type' => 'height',
		);

		$fields[] = array(
			'id'    => 'ph_typo_title',
			'title' => __( 'Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '28px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#909090',
				'line_height' => false, // Skip for that's value !
			),
			'selector' => '#page-header',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'ph_typo_breadcrumbs',
			'title' => __( 'Breadcrumbs', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '11px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#909090',
				'line_height' => false, // Skip for that's value !
			),
			'selector' => '#page-header div.breadcrumbs, #page-header div.breadcrumbs a',
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
				'size'  => '14px',
				'family'  => 'PT Sans',
				'weight' => 'normal',
				'color' => '#5e5e5e',
				'line_height' => '1.6em',
			),
			'selector' => '#sidebar',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_sidebar_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#919191',
			'selector' => '#sidebar a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_sidebar_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#FAA11F',
			'selector' => '#sidebar a:hover',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'typo_sidebar_widget_text',
			'title' => __( 'Widget Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '16px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#faa11f',
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
			'std' => '#303030',
			'selector' => '#footer',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'typo_footer_text',
			'title' => __( 'Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '13px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#5A5A5A',
				'line_height' => '1.5em',
			),
			'selector' => '#footer',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_footer_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#666666',
			'selector' => '#footer a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_footer_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#FFFFFF',
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
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#faa11f',
				'line_height' => '1.5em',
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
			'std' => '#1E1E1E',
			'selector' => '#copyright',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'typo_copyright_text',
			'title' => __( 'Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '11px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#5A5A5A',
				'line_height' => '44px',
			),
			'selector' => '#copyright',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_copyright_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#5A5A5A',
			'selector' => '#copyright a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_copyright_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#FFFFFF',
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
		$option = get_theme_mod( 'height_header', '120px' );
		$css_code->add_value( '#header', 'height', $option );

		$option = get_theme_mod( 'typo_menu_primary' );
		if ( ! empty( $option['color'] ) ) {
			$css_code->add_value( '.navbar-toggle', 'border-color', $option['color'] );
			$css_code->add_value( '.icon-bar', 'background-color', $option['color'] );
			$css_code->add_value( '.nav-main .menu-search', 'color', $option['color'] );
		}
		$option = get_theme_mod( 'typo_menu_primary' );
		if ( ! empty( $option['line_height'] ) ) {
			$css_code->add_value( '.nav-main .menu-search', 'line-height', $option['line_height'] );
			$css_code->add_value( '.sf-menu li:hover ul, .sf-menu li.sfHover ul', 'top', $option['line_height'] );
		}

		$option = get_theme_mod( 'color_link' );
		if ( ! empty( $option ) ) {
			$css_code->add_value( '#sidebar .menu li a:hover, #sidebar .sub-menu li a:hover, #sidebar .sub-page-menu li a:hover, #sidebar .menu li.current_page_item > a, #sidebar .sub-menu li.current_page_item > a, #sidebar .sub-page-menu li.current_page_item > a, #sidebar .menu li.current-menu-item > a, #sidebar .sub-menu li.current-menu-item > a, #sidebar .sub-page-menu li.current-menu-item > a', 'border-color', $option );
			$css_code->add_value( '.category-filters a', 'color', $option );
		}

		$option = get_theme_mod( 'color_link_hover' );
		if ( ! empty( $option ) ) {
			$css_code->add_value( '.category-filters a:hover,.category-filters a.active', 'color', $option);
			$css_code->add_value( '.category-filters a:hover,.category-filters a.active', 'border-color', $option );

		}
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
new Pojo_Titanium_Customize_Register_Fields();