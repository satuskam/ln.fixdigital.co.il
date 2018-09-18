<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Pojo_River_Customize_Register_Fields {

	public function section_style( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'primary_color',
			'title' => __( 'Primary Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#52BAC3',
		);

		$fields[] = array(
			'id'    => 'secondary_color',
			'title' => __( 'Secondary Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#FFFFFF',
		);

		$fields[] = array(
			'id'    => 'layout_site',
			'title' => __( 'Layout Site', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_SELECT,
			'choices' => array(
				'boxed' => __( 'Boxed', 'pojo' ),
				'wide' => __( 'Wide', 'pojo' ),
			),
			'std' => 'boxed',
		);

		$fields[] = array(
			'id'    => 'bg_body',
			'title' => __( 'Background Site', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_BACKGROUND,
			'std' => array(
				'color' => '#eee',
				'image'  => get_template_directory_uri() . '/assets/images/pattern.png',
				'position'  => 'top center',
				'repeat' => 'repeat',
				'size' => 'auto',
				'attachment' => 'scroll',
			),
			'selector' => 'body',
			'change_type' => 'background',
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
				'family'  => 'Cabin',
				'weight' => 'normal',
				'color' => '#52BAC3',
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
			'std'   => '50px',
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

	public function section_header( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id' => 'bg_header',
			'title' => __( 'Background', 'pojo' ),
			'type' => Pojo_Theme_Customize::FIELD_BACKGROUND,
			'std' => array(
				'color' => '#303030',
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

	public function section_top_bar( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'color_bg_top_bar',
			'title' => __( 'Background Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#3C3C3C',
			'selector' => '#top-bar',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'typo_top_bar',
			'title' => __( 'Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '12px',
				'family'  => 'Cabin',
				'weight' => 'normal',
				'color' => '#888888',
				'line_height' => '40px',
			),
			'selector' => '#top-bar',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_link_top_bar',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#888888',
			'selector' => '#top-bar a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_link_hover_top_bar',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#52bac3',
			'selector' => '#top-bar a:hover',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$sections[] = array(
			'id' => 'top_bar',
			'title'      => __( 'Top Bar', 'pojo' ),
			'desc'       => '',
			'fields'     => $fields,
		);

		return $sections;
	}

	public function section_menus( $sections = array() ) {
		$fields = array();

		$fields = apply_filters( 'pojo_customizer_section_menus_before', $fields );

		$fields[] = array(
			'id' => 'height_menu',
			'title' => __( 'Height', 'pojo' ),
			'std' => '130px',
		);

		$fields[] = array(
			'id'    => 'typo_menu_primary',
			'title' => __( 'Menu Primary', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '14px',
				'family'  => 'Cabin',
				'weight' => 'normal',
				'color' => '#888888',
				'line_height' => false, // Skip for that's value !
			),
			'selector' => '.sf-menu a, .mobile-menu a,#sidebar .menu li a, #sidebar .sub-menu li a, #sidebar .sub-page-menu li a',
			'change_type' => 'typography',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_menu_primary_hover',
			'title' => __( 'Menu Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#52BAC3',
			'selector' => '.sf-menu a:hover,.sf-menu li.active a, .sf-menu li.current-menu-item > a,.sf-menu .sfHover > a,.sf-menu .sfHover > li.current-menu-item > a,.sf-menu li.current-menu-ancestor > a,.mobile-menu a:hover,.mobile-menu li.current-menu-item > a,#sidebar .menu li a:hover, #sidebar .sub-menu li a, #sidebar .sub-page-menu li a:hover, #sidebar .menu li.current-menu-item a, #sidebar .sub-menu li.current_page_item a,.pojo-menu-search:hover .menu-search-input:before',
			'change_type' => 'color',
			'skip_transport' => true,
		);


		$fields[] = array(
			'id'    => 'color_bg_sub_menu',
			'title' => __( 'Sub Menu - Background', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#3C3C3C',
			'selector' => '.nav-main .sf-menu .sub-menu',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'color_bg_sub_menu_hover',
			'title' => __( 'Sub Menu - BG Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#303030',
			'selector' => '.nav-main .sf-menu .sub-menu li:hover > a,.nav-main .sf-menu .sub-menu li.current-menu-item > a',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'typo_sub_menu_link',
			'title' => __( 'Sub Menu', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '15px',
				'family'  => 'Cabin',
				'weight' => 'normal',
				'color' => '#b5b5b5',
				'line_height' => '2.8em',
			),
			'selector' => '.nav-main .sf-menu .sub-menu li a',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_sub_menu_link_hover',
			'title' => __( 'Sub Menu - Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#52BAC3',
			'selector' => '.nav-main .sf-menu .sub-menu li:hover > a,.nav-main .sf-menu .sub-menu li.current-menu-item > a',
			'change_type' => 'color',
			'skip_transport' => true,
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

	public function section_typography( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'typo_body_text',
			'title' => __( 'Body Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '15px',
				'family'  => 'PT Sans',
				'weight' => 'normal',
				'color' => '#888888',
				'line_height' => '1.6em',
			),
			'selector' => 'body',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#474747',
			'selector' => 'a',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#52BAC3',
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
			'std'   => '#52BAC3',
			'selector' => 'selection',
			'change_type' => 'bg_selection',
		);

		$fields[] = array(
			'id'    => 'typo_h1',
			'title' => __( 'H1 - Page Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '33px',
				'family'  => 'Cabin',
				'weight' => 'bold',
				'color' => '#52BAC3',
				'transform' => 'uppercase',
				'line_height' => '1.4em',
			),
			'selector' => 'h1',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_h2',
			'title' => __( 'H2', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '27px',
				'family'  => 'Cabin',
				'weight' => 'normal',
				'color' => '#52BAC3',
				'transform' => 'uppercase',
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
				'size'  => '22px',
				'family'  => 'Cabin',
				'weight' => 'bold',
				'color' => '#52BAC3',
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
				'size'  => '18px',
				'family'  => 'Cabin',
				'weight' => 'normal',
				'color' => '#52BAC3',
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
				'size'  => '17px',
				'family'  => 'Cabin',
				'weight' => 'normal',
				'color' => '#52BAC3',
				'transform' => 'uppercase',
				'line_height' => '1.5em',
			),
			'selector' => 'h5',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_h6',
			'title' => __( 'H6', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '17px',
				'family'  => 'Cabin',
				'weight' => '400',
				'color' => '#7a7a7a',
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
			'id'    => 'ph_height',
			'title' => __( 'Height', 'pojo' ),
			'std'   => '70px',
			'selector' => '#page-header',
			'change_type' => 'height',
		);

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
				'color' => '#474747',
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
			'id'    => 'ph_typo_title',
			'title' => __( 'Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '25px',
				'family'  => 'Cabin',
				'weight' => 'normal',
				'color' => '#52BAC3',
				'transform' => 'uppercase',
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
				'size'  => '13px',
				'family'  => 'Cabin',
				'weight' => 'normal',
				'color' => '#888888',
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
				'size'  => '15px',
				'family'  => 'PT Sans',
				'weight' => 'normal',
				'color' => '#888888',
				'line_height' => '1.6em',
			),
			'selector' => '#sidebar',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_sidebar_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#474747',
			'selector' => '#sidebar a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_sidebar_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#52BAC3',
			'selector' => '#sidebar a:hover',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'typo_sidebar_widget_text',
			'title' => __( 'Widget Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '17px',
				'family'  => 'Cabin',
				'weight' => 'normal',
				'color' => '#52BAC3',
				'transform' => 'uppercase',
				'line_height' => '1.5em',
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
				'size'  => '14px',
				'family'  => 'Cabin',
				'weight' => 'normal',
				'color' => '#848484',
				'line_height' => '1.5em',
			),
			'selector' => '#footer',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_footer_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#888888',
			'selector' => '#footer a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_footer_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#52BAC3',
			'selector' => '#footer a:hover',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'typo_footer_widget_text',
			'title' => __( 'Widget Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '19px',
				'family'  => 'Cabin',
				'weight' => 'normal',
				'color' => '#888888',
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
			'std' => '#3C3C3C',
			'selector' => '#copyright',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'typo_copyright_text',
			'title' => __( 'Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '12px',
				'family'  => 'Cabin',
				'weight' => 'normal',
				'color' => '#888888',
				'line_height' => '40px',
			),
			'selector' => '#copyright',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_copyright_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#888888',
			'selector' => '#copyright a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_copyright_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#52BAC3',
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

	public function section_outer_slidebar( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id' => 'chk_enable_outer_slidebar',
			'title' => __( 'Outer Slidebar', 'pojo' ),
			'type' => Pojo_Theme_Customize::FIELD_CHECKBOX,
			'std' => true,
		);

		$fields[] = array(
			'id'    => 'color_bg_outer_slidebar',
			'title' => __( 'Background Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#52BAC3',
			'selector' => '#outer-slidebar #outer-slidebar-overlay',
			'change_type' => 'bg_color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'typo_outer_slidebar_text',
			'title' => __( 'Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '17px',
				'family'  => 'Cabin',
				'weight' => 'normal',
				'color' => '#ffffff',
				'line_height' => '1.5em',
			),
			'selector' => '#outer-slidebar',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_outer_slidebar_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#f2f2f2',
			'selector' => '#outer-slidebar a',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_outer_slidebar_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#ffffff',
			'selector' => '#outer-slidebar a:hover',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'typo_outer_slidebar_widget_text',
			'title' => __( 'Widget Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '21px',
				'family'  => 'Cabin',
				'weight' => 'bold',
				'color' => '#ffffff',
				'line_height' => '2.5em',
			),
			'selector' => '#outer-slidebar .widget-title',
			'change_type' => 'typography',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'slidebar_widget_columns',
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
			'id' => 'outer_slidebar',
			'title'      => __( 'Outer Slidebar', 'pojo' ),
			'desc'       => '',
			'fields'     => $fields,
		);

		return $sections;
	}

	public function pojo_wp_head_custom_css_code( Pojo_Create_CSS_Code $css_code ) {
		$primary_color   = get_theme_mod( 'primary_color' );
		$secondary_color = get_theme_mod( 'secondary_color' );

		if ( $primary_color && $secondary_color ) {
			$string = <<<CSSCODE
			#breadcrumbs .separator {color: {$primary_color};}
			#header .sf-menu > li.active > a > span,#header .sf-menu > li.current-menu-item > a > span,#header .sf-menu > li.current-menu-parent > a > span,#header .sf-menu > li.current-menu-ancestor > a > span {color:{$secondary_color}; background-color: {$primary_color};}
			.nav-main .pojo-menu-cart li.cart-checkout a {color: {$primary_color};border-color: {$primary_color};}
			.nav-main .pojo-menu-cart li.cart-checkout a:hover {color:{$secondary_color}; background-color: {$primary_color};}
			.pojo-loadmore-wrap .button,.pojo-loadmore-wrap .pojo-loading,.pojo-loading-wrap .button,.pojo-loading-wrap .pojo-loading {background-color: {$primary_color}; border-color: {$primary_color}; color: {$secondary_color};}

			.grid-item.masonry-item:hover .inbox .caption,.grid-item.gallery-item:hover .inbox .caption {background-color: {$primary_color}; color: {$secondary_color};}
			.grid-item.masonry-item:hover .inbox .caption h4,.grid-item.gallery-item:hover .inbox .caption h4,
			.grid-item.masonry-item:hover .inbox .caption a,.grid-item.gallery-item:hover .inbox .caption a,
			.grid-item.masonry-item:hover .inbox .caption .entry-meta span,
			.grid-item.gallery-item:hover .inbox .caption small {color: {$secondary_color};}

			.category-filters li a:hover, .category-filters li .active {color: {$primary_color}; }
			ul.social-links li a .social-icon:before {background-color: {$primary_color}; }
			ul.social-links li a .social-icon:before {color: {$secondary_color}; }
			ul.social-links li a:hover .social-icon:before {background-color: {$secondary_color}; }
			ul.social-links li a:hover .social-icon:before {color: {$primary_color}; }

			ul.products li.product .inbox:hover .caption {background-color: {$primary_color}; }
			ul.products li.product .inbox:hover .caption .product-title {color: {$secondary_color}; }
			ul.products li.product .inbox:hover .caption .price {color: {$secondary_color}; }

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

			 .woocommerce div.product .woocommerce-tabs ul.tabs li.active a,
			 .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active a,
			 .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active a,
			 .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active a,
			 .woocommerce div.product .woocommerce-tabs ul.tabs li a:hover,
			 .woocommerce-page div.product .woocommerce-tabs ul.tabs li a:hover,
			 .woocommerce #content div.product .woocommerce-tabs ul.tabs li a:hover,
			 .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li a:hover {color: {$primary_color};}

			 .woocommerce div.product .woocommerce-tabs ul.tabs li.active:before,
			 .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active:before,
			 .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active:before,
			 .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active:before,
			 .woocommerce div.product .woocommerce-tabs ul.tabs li.active:after,
			 .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active:after,
			 .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active:after,
			 .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active:after {border-color: {$primary_color};}

			 .woocommerce div.product .woocommerce-tabs ul.tabs li:hover:before,
			 .woocommerce-page div.product .woocommerce-tabs ul.tabs li:hover:before,
			 .woocommerce #content div.product .woocommerce-tabs ul.tabs li:hover:before,
			 .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li:hover:before,
			 .woocommerce div.product .woocommerce-tabs ul.tabs li:hover:after,
			 .woocommerce-page div.product .woocommerce-tabs ul.tabs li:hover:after,
			 .woocommerce #content div.product .woocommerce-tabs ul.tabs li:hover:after,
			 .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li:hover:after {border-color: {$primary_color};}

			input[type="submit"],.button,.button.size-small,.button.size-large,.button.size-xl,.button.size-xxl{background-color: {$primary_color}; border-color: {$primary_color}; color: {$secondary_color};}
			input[type="submit"]:hover,.button:hover,.button.size-small:hover,.button.size-large:hover,.button.size-xl:hover, .button.size-xxl:hover {background: {$secondary_color}; border-color: {$primary_color}; color: {$primary_color};}
CSSCODE;
			
			$css_code->add_data( $string );
			
			$rga_color = pojo_hex2rgb( $primary_color );
			if ( ! empty( $rga_color ) )
				$css_code->add_value( 'div.hover-object', 'background', sprintf( 'rgba(%s, %s, %s, 0.8)', $rga_color[0], $rga_color[1], $rga_color[2] ) );
			
		} // End style colors

		$option = get_theme_mod( 'height_menu', '130px');
		$css_code->add_value( '.sf-menu a, .menu-no-found,.sf-menu li.pojo-menu-search', 'line-height', $option );
		$css_code->add_value( '.sf-menu li:hover ul, .sf-menu li.sfHover ul', 'top', $option );

		$option = get_theme_mod( 'bg_header' );
		if ( ! empty( $option['color'] ) ) {
			$css_code->add_value( '.sf-menu .sub-menu', 'background-color', $option['color'] );
		}

		$option = get_theme_mod( 'typo_menu_primary' );
		if ( ! empty( $option['color'] ) ) {
			$css_code->add_value( '.navbar-toggle', 'border-color', $option['color'] );
			$css_code->add_value( '.icon-bar', 'background-color', $option['color'] );
		}

		$option = get_theme_mod( 'typo_body_text' );
		if ( ! empty( $option['color'] ) ) {
			$css_code->add_value( '.category-filters li a', 'color', $option['color'] );
		}

		$option = get_theme_mod( 'color_bg_outer_slidebar' );
		if ( ! empty( $option ) ) {
			$css_code->add_value( '#outer-slidebar #outer-slidebar-toggle a', 'border-right-color', $option );
			$css_code->add_value( '.rtl #outer-slidebar #outer-slidebar-toggle a', 'border-left-color', $option );
		}

		$option = get_theme_mod( 'typo_outer_slidebar_text' );
		if ( ! empty( $option ) ) {
			$css_code->add_value( '#outer-slidebar #outer-slidebar-toggle a', 'color',  $option['color'] );
		}
	}

	public function __construct() {
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_style' ), 100 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_logo' ), 110 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_header' ), 120 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_top_bar' ), 130 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_menus' ), 140 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_typography' ), 150 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_page_header' ), 160 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_sidebar' ), 180 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_footer' ), 190 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_copyright' ), 200 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_outer_slidebar' ), 210 );

		add_filter( 'pojo_wp_head_custom_css_code', array( &$this, 'pojo_wp_head_custom_css_code' ) );
	}

}
new Pojo_River_Customize_Register_Fields();