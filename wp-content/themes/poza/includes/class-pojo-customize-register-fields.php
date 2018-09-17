<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Pojo_Firma_Customize_Register_Fields {

	public function section_style( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'first_color',
			'title' => __( 'First Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#000000',
		);

		$fields[] = array(
			'id'    => 'second_color',
			'title' => __( 'Second Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#ffffff',
		);

		$fields[] = array(
			'id'    => 'border_color_first',
			'title' => __( 'Border - First Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#cdcdcd',
		);

		$fields[] = array(
			'id'    => 'border_color_second',
			'title' => __( 'Border - Second Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#000000',
		);

		$fields[] = array(
			'id'    => 'bg_body',
			'title' => __( 'Background', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_BACKGROUND,
			'std' => array(
				'color' => '#ffffff',
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
				'size'  => '31px',
				'family'  => 'Roboto',
				'weight' => 'bold',
				'color' => '#000000',
				'transform' => 'uppercase',
				'style'  => 'normal',
				'line_height' => '1',
				'letter_spacing' => '0px',
			),
			'selector' => 'div.logo-text a',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'image_logo',
			'title' => __( 'Logo', 'pojo' ),
			'type' => Pojo_Theme_Customize::FIELD_IMAGE,
			'std'   => get_template_directory_uri() . '/assets/images/logo.png',
		);

		$fields[] = array(
			'id'    => 'image_logo_padding_top',
			'title' => __( 'Logo Padding Top', 'pojo' ),
			'std'   => '40px',
			'selector' => '#header .logo',
			'change_type' => 'padding_top',
		);

		$fields[] = array(
			'id'    => 'image_logo_padding_bottom',
			'title' => __( 'Logo Padding Bottom', 'pojo' ),
			'std'   => '40px',
			'selector' => '#header .logo',
			'change_type' => 'padding_bottom',
		);

		$fields[] = array(
			'id'    => 'image_sticky_header_logo',
			'title' => __( 'Logo Sticky Header', 'pojo' ),
			'type' => Pojo_Theme_Customize::FIELD_IMAGE,
			'std'   => get_template_directory_uri() . '/assets/images/sticky-logo.png',
		);

		$fields[] = array(
			'id'    => 'image_mobile_header_logo',
			'title' => __( 'Logo Mobile Header', 'pojo' ),
			'type' => Pojo_Theme_Customize::FIELD_IMAGE,
			'std'   => get_template_directory_uri() . '/assets/images/mobile-logo.png',
		);

		$sections[] = array(
			'id' => 'logo',
			'title'      => __( 'Logo', 'pojo' ),
			'desc'       => '',
			'fields'     => $fields,
		);

		return $sections;
	}

	public function section_top_bar( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id' => 'bg_top_bar',
			'title' => __( 'Background', 'pojo' ),
			'type' => Pojo_Theme_Customize::FIELD_BACKGROUND,
			'std' => array(
				'color' => '#000000',
				'image'  => '',
				'position'  => 'top center',
				'repeat' => 'repeat-x',
				'size' => 'auto',
				'attachment' => 'scroll',
			),
			'skip_transport' => true,
			'selector' => '#top-bar',
			'change_type' => 'background',
		);

		$fields[] = array(
			'id'    => 'typo_top_bar',
			'title' => __( 'Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '13px',
				'family'  => 'Roboto',
				'weight' => 'normal',
				'color' => '#818181',
				'transform' => 'none',
				'style'  => 'normal',
				'line_height' => '35px',
				'letter_spacing' => '0px',
			),
			'selector' => '#top-bar, #top-bar .widget-title,#top-bar .form-search .field',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_link_top_bar',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#818181',
			'selector' => '#top-bar a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_link_hover_top_bar',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#ffffff',
			'selector' => '#top-bar a:hover',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$sections[] = array(
			'id' => 'top_bar',
			'title' => __( 'Top Bar', 'pojo' ),
			'desc' => '',
			'fields' => $fields,
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
				'color' => '#ffffff',
				'image'  => '',
				'position'  => 'top center',
				'repeat' => 'repeat-x',
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

		$fields[] = array(
			'id' => 'bg_sticky_header',
			'title' => __( 'Sticky Header - Background', 'pojo' ),
			'type' => Pojo_Theme_Customize::FIELD_BACKGROUND,
			'std' => array(
				'color' => '#000000',
				'image'  => '',
				'position'  => 'top center',
				'repeat' => 'repeat-x',
				'size' => 'auto',
				'attachment' => 'scroll',
			),
			'skip_transport' => true,
			'selector' => '.sticky-header',
			'change_type' => 'background',
		);

		$fields[] = array(
			'id'    => 'typo_menu_sticky',
			'title' => __( 'Sticky Header - Menu', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '14px',
				'family'  => 'Roboto',
				'weight' => 'normal',
				'color' => '#ffffff',
				'transform' => 'uppercase',
				'line_height' => '70px',
				'style' => 'normal',
				'letter_spacing' => '1px',
			),
			'selector' => '.sticky-header .sf-menu a,.sticky-header .mobile-menu a',
			'change_type' => 'typography',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_menu_sticky_hover',
			'title' => __( 'Sticky Header - Menu Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#999999',
			'selector' => '.sticky-header .sf-menu a:hover,.sticky-header .sf-menu li.active a,.sticky-header .sf-menu li.current-menu-item > a,.sticky-header .sf-menu li.current-menu-ancestor > a,.sticky-header .mobile-menu a:hover,.sticky-header .mobile-menu li.current-menu-item > a',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$sections[] = array(
			'id' => 'header',
			'title' => __( 'Header', 'pojo' ),
			'desc' => '',
			'fields' => $fields,
		);

		return $sections;
	}

	public function section_menus( $sections = array() ) {
		$fields = array();

		$fields = apply_filters( 'pojo_customizer_section_menus_before', $fields );

		$fields[] = array(
			'id'    => 'layout_menu',
			'title' => __( 'Layout Menu', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_SELECT,
			'choices' => array(
				'boxed' => __( 'Boxed', 'pojo' ),
				'wide' => __( 'Wide', 'pojo' ),
			),
			'std' => 'boxed',
		);

		$fields[] = array(
			'id'    => 'typo_menu_primary',
			'title' => __( 'Menu Primary', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '15px',
				'family'  => 'Roboto',
				'weight' => 'normal',
				'color' => '#000000',
				'transform' => 'uppercase',
				'line_height' => '60px',
				'style' => 'normal',
				'letter_spacing' => '1.7px',
			),
			'selector' => '.sf-menu a, .mobile-menu a',
			'change_type' => 'typography',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_menu_primary_hover',
			'title' => __( 'Menu Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#0a0a0a',
			'selector' => '.sf-menu a:hover,.sf-menu li.active a, .sf-menu li.current-menu-item > a,.sf-menu li.current-menu-ancestor > a,.mobile-menu a:hover,.mobile-menu li.current-menu-item > a',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_bg_sub_menu',
			'title' => __( 'Sub Menu - Background', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#ffffff',
			'selector' => '.nav-main .sf-menu .sub-menu li',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'color_bg_sub_menu_hover',
			'title' => __( 'Sub Menu - BG Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#f2f2f2',
			'selector' => '.sf-menu .sub-menu li:hover,.sf-menu .sub-menu li.current-menu-item',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'typo_sub_menu_link',
			'title' => __( 'Sub Menu', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '14px',
				'family'  => 'Roboto',
				'weight' => 'normal',
				'color' => '#232323',
				'transform' => 'none',
				'line_height' => '46px',
				'style' => 'normal',
				'letter_spacing' => '0px',
			),
			'selector' => '.nav-main .sf-menu .sub-menu li a',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_sub_menu_link_hover',
			'title' => __( 'Sub Menu - Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#0a0a0a',
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

	public function section_title_bar( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'title_bar_height',
			'title' => __( 'Height', 'pojo' ),
			'std'   => '100px',
			'selector' => '#title-bar',
			'change_type' => 'height',
		);

		$fields[] = array(
			'id' => 'title_bar_background',
			'title' => __( 'Background', 'pojo' ),
			'type' => Pojo_Theme_Customize::FIELD_BACKGROUND,
			'std' => array(
				'color' => '#ffffff',
				'image'  => '',
				'position'  => 'center center',
				'repeat' => 'repeat',
				'size' => 'cover',
				'attachment' => 'scroll',
				'opacity' => '0',
			),
			'selector' => '#title-bar.title-bar-style-custom_bg .title-bar-default',
			'change_type' => 'background',
		);

		$fields[] = array(
			'id'    => 'title_bar_typo_title',
			'title' => __( 'Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '24px',
				'family'  => 'Roboto',
				'weight' => '500',
				'transform' => 'uppercase',
				'color' => '#000000',
				'line_height' => false, // Skip for that's value !
				'style' => 'normal',
				'letter_spacing' => '1px',
			),
			'selector' => '#title-bar .title-primary',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'title_bar_typo_breadcrumbs',
			'title' => __( 'Breadcrumbs', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '12px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#939393',
				'style' => 'italic',
				'transform' => 'none',
				'line_height' => false, // Skip for that's value !
			),
			'selector' => '#title-bar div.breadcrumbs, #title-bar div.breadcrumbs a',
			'change_type' => 'typography',
		);

		$sections[] = array(
			'id' => 'title_bar',
			'title' => __( 'Title Bar', 'pojo' ),
			'desc' => '',
			'fields' => $fields,
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
				'family'  => 'Roboto',
				'weight' => 'normal',
				'color' => '#6d6d6d',
				'line_height' => '1.9',
			),
			'selector' => 'body',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#000000',
			'selector' => 'a',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#878787',
			'selector' => 'a:hover, a:focus',
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
			'std'   => '#000000',
			'selector' => 'selection',
			'change_type' => 'bg_selection',
		);

		$fields[] = array(
			'id'    => 'typo_h1',
			'title' => __( 'H1 - Page Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '29px',
				'family'  => 'Roboto',
				'weight' => 'bold',
				'color' => '#171717',
				'transform' => 'uppercase',
				'line_height' => '25px',
				'letter_spacing' => '2px',
				'style' => 'normal',
			),
			'selector' => 'h1',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_h2',
			'title' => __( 'H2', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '29px',
				'family'  => 'Roboto',
				'weight' => 'normal',
				'color' => '#000000',
				'transform' => 'uppercase',
				'line_height' => '1.3em',
				'letter_spacing' => '2px',
				'style' => 'normal',
			),
			'selector' => 'h2',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_h3',
			'title' => __( 'H3', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '25px',
				'family'  => 'Roboto',
				'weight' => 'normal',
				'color' => '#000000',
				'transform' => 'uppercase',
				'line_height' => '1.5em',
				'letter_spacing' => '4px',
				'style' => 'normal',
			),
			'selector' => 'h3',
			'change_type' => 'typography',
		);


		$fields[] = array(
			'id'    => 'typo_h4',
			'title' => __( 'H4', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '17px',
				'family'  => 'Roboto',
				'weight' => '500',
				'color' => '#000000',
				'style' => 'normal',
				'transform' => 'none',
				'line_height' => '1.6em',
				'letter_spacing' => '0px',
			),
			'selector' => 'h4',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_h5',
			'title' => __( 'H5', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '15px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#a8a8a8',
				'style' => 'italic',
				'transform' => 'none',
				'line_height' => '1.5em',
				'letter_spacing' => '0px',
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
				'family'  => 'Roboto',
				'weight' => 'normal',
				'color' => '#000000',
				'line_height' => '1.7em',
				'transform' => 'none',
				'letter_spacing' => '0px',
				'style' => 'normal',
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

	public function section_content( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'typo_overlay_title',
			'title' => __( 'Overlay Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '15px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#ffffff',
				'transform' => 'uppercase',
				'style' => 'italic',
				'line_height' => '1',
				'letter_spacing' => '0px',
			),
			'selector' => '.image-link .overlay-title,.image-link .overlay-title a,.image-link .overlay-title a:hover,.image-link .overlay-title a.button,.image-link .overlay-title a.button:hover',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'bg_overlay-date',
			'title' => __( 'Overlay Date - BG Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#000000',
			'selector' => '.image-link .entry-date,.woocommerce span.onsale',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'typo_overlay_day',
			'title' => __( 'Overlay Date - Day', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '30px',
				'family'  => 'Roboto',
				'weight' => 'normal',
				'color' => '#ffffff',
				'transform' => 'uppercase',
				'style' => 'normal',
				'line_height' => false, // Skip for that's value !
				'letter_spacing' => '-2px',
			),
			'selector' => '.image-link .entry-date .entry-date-day',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_overlay_month',
			'title' => __( 'Overlay Date - Month', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '13px',
				'family'  => 'Roboto',
				'weight' => 'normal',
				'color' => '#ffffff',
				'transform' => 'uppercase',
				'style' => 'normal',
				'line_height' => false, // Skip for that's value !
				'letter_spacing' => '0px',
			),
			'selector' => '.image-link .entry-date .entry-date-month,.woocommerce span.onsale',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_heading_blog',
			'title' => __( 'Heading - Blog', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '29px',
				'family'  => 'Roboto',
				'weight' => '500',
				'color' => '#000000',
				'transform' => 'uppercase',
				'style' => 'normal',
				'line_height' => '1.2',
				'letter_spacing' => '0px',
			),
			'selector' => '.blog-item h3.media-heading',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_meta_data_archive',
			'title' => __( 'Meta Data - Blog', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '12px',
				'family'  => 'Roboto',
				'weight' => 'normal',
				'color' => '#a7a7a7',
				'transform' => 'none',
				'style' => 'normal',
				'line_height' => '1',
				'letter_spacing' => '0px',
			),
			'selector' => '.entry-meta > span',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_heading_grid',
			'title' => __( 'Heading - Grid', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '18px',
				'family'  => 'Roboto',
				'weight' => '500',
				'color' => '#141414',
				'transform' => 'uppercase',
				'style' => 'normal',
				'line_height' => '26px',
				'letter_spacing' => '1px',
			),
			'selector' => 'h4.grid-heading',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_heading_galleries',
			'title' => __( 'Heading - Galleries', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '19px',
				'family'  => 'Roboto',
				'weight' => 'bold',
				'color' => '#ffffff',
				'transform' => 'uppercase',
				'style' => 'normal',
				'line_height' => '0.8em',
				'letter_spacing' => '2px',
			),
			'selector' => '.gallery-item h4.grid-heading',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_heading_small_galleries',
			'title' => __( 'Heading Small -  Category Galleries', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '15px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#bababa',
				'transform' => 'none',
				'style' => 'italic',
				'line_height' => '1',
				'letter_spacing' => '0px',
			),
			'selector' => '.gallery-item h4.grid-heading small',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_meta_data_single',
			'title' => __( 'Meta Data - Single', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '12px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#848484',
				'transform' => 'none',
				'style' => 'italic',
				'line_height' => '1em',
				'letter_spacing' => '1px',
			),
			'selector' => '.single .entry-meta > span',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_excerpt_archive',
			'title' => __( 'Excerpt Archive', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '15px',
				'family'  => 'Roboto',
				'weight' => '400',
				'color' => '#6c6c6c',
				'line_height' => '22px',
				'transform' => 'none',
				'letter_spacing' => '0px',
				'style' => 'normal',
			),
			'selector' => '.entry-excerpt',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_breadcrumbs',
			'title' => __( 'Breadcrumbs', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '13px',
				'family'  => 'Lato',
				'weight' => 'normal',
				'color' => '#6c6c6c',
				'style' => 'normal',
				'transform' => 'none',
				'line_height' => '1em',
				'letter_spacing' => '0px',
			),
			'selector' => '#primary #breadcrumbs,#primary #breadcrumbs a',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_pagination',
			'title' => __( 'Pagination', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '13px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#8e8e8e',
				'transform' => 'none',
				'style' => 'italic',
				'line_height' => '1.5em',
				'letter_spacing' => '0px',
			),
			'selector' => '.pagination > li > a,.pagination > li.active > a,.pagination > li > a:hover,.pagination > li.active > a:hover,nav.post-navigation a,.category-filters li a',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'bg_pagination',
			'title' => __( 'Pagination - BG Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#e8e8e8',
			'selector' => '.pagination > li.active > a,.pagination > li > a:hover,.pagination > li.active > a:hover,.pagination > li.active > a:focus,.category-filters li a:hover,.category-filters li a.active',
			'change_type' => 'bg_color',
		);

		$sections[] = array(
			'id' => 'content',
			'title'      => __( 'Content', 'pojo' ),
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
				'family'  => 'Roboto',
				'weight' => '300',
				'color' => '#666666',
				'transform' => 'none',
				'style' => 'normal',
				'line_height' => '1.9em',
				'letter_spacing' => '0px',
			),
			'selector' => '#sidebar',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_sidebar_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#000000',
			'selector' => '#sidebar a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_sidebar_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#878787',
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
				'family'  => 'Roboto',
				'weight' => '400',
				'color' => '#000000',
				'transform' => 'uppercase',
				'style' => 'normal',
				'line_height' => '50px',
				'letter_spacing' => '1px',
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
			'id'    => 'bg_footer',
			'title' => __( 'Background', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_BACKGROUND,
			'std' => array(
				'color' => '#0a0a0a',
				'image'  => '',
				'position'  => 'top center',
				'repeat' => 'repeat',
				'size' => 'auto',
				'attachment' => 'scroll',
			),
			'selector' => '#footer',
			'change_type' => 'background',
		);

		$fields[] = array(
			'id'    => 'typo_footer_text',
			'title' => __( 'Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '15px',
				'family'  => 'Roboto',
				'weight' => 'normal',
				'color' => '#878787',
				'transform' => 'none',
				'style' => 'normal',
				'line_height' => '1.9em',
				'letter_spacing' => '0px',
			),
			'selector' => '#footer',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_footer_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#878787',
			'selector' => '#footer a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_footer_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#ffffff',
			'selector' => '#footer a:hover',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'typo_footer_widget_title',
			'title' => __( 'Widget Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '17px',
				'family'  => 'Roboto',
				'weight' => '500',
				'color' => '#ffffff',
				'transform' => 'uppercase',
				'style' => 'normal',
				'line_height' => '2em',
				'letter_spacing' => '1px',
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
			'id'    => 'bg_copyright',
			'title' => __( 'Background', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_BACKGROUND,
			'std' => array(
				'color' => '#000000',
				'image'  => '',
				'position'  => 'top center',
				'repeat' => 'repeat',
				'size' => 'auto',
				'attachment' => 'scroll',
			),
			'selector' => '#copyright',
			'change_type' => 'background',
		);

		$fields[] = array(
			'id'    => 'typo_copyright_text',
			'title' => __( 'Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '11px',
				'family'  => 'Roboto',
				'weight' => '400',
				'color' => '#ffffff',
				'transform' => 'uppercase',
				'style' => 'normal',
				'line_height' => '80px',
				'letter_spacing' => '1px',
			),
			'selector' => '#copyright',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_copyright_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#bcbcbc',
			'selector' => '#copyright a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_copyright_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#ffffff',
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
		$first_color   = get_theme_mod( 'first_color' );
		$second_color = get_theme_mod( 'second_color' );

		if ( $first_color && $second_color ) {
			$string = <<<CSSCODE
			.image-link .overlay-image:before,.image-link .overlay-image:after {border-color: {$second_color};}
			article.sticky:before {background-color: {$first_color};}
			.author-info {background-color: {$first_color};color: {$second_color};}
			.author-info .author-link, .author-info h4 {color: {$second_color};}
			.widget_tag_cloud a, #sidebar-footer .widget_tag_cloud a {color: {$second_color};}
			.widget_tag_cloud a:hover, #sidebar-footer .widget_tag_cloud a:hover {background-color: {$first_color}; color: {$second_color};}
			ul.social-links li a .social-icon:before {background-color: {$first_color};}
			ul.social-links li a .social-icon:before {color: {$second_color}; }
			ul.social-links li a:hover .social-icon:before {background-color: {$second_color}; }
			ul.social-links li a:hover .social-icon:before {color: {$first_color}; }
			input[type="submit"],.button,.button.size-small,.button.size-large,.button.size-xl,.button.size-xxl {background-color: {$first_color}; border-color: {$first_color}; color: {$second_color};}
			input[type="submit"]:hover,.button:hover,.button.size-small:hover,.button.size-large:hover,.button.size-xl:hover, .button.size-xxl:hover {background: {$second_color}; border-color: {$first_color}; color: {$first_color};}
CSSCODE;

			$css_code->add_data( $string );

			$rga_color = pojo_hex2rgb( $first_color );
			if ( ! empty( $rga_color ) )
				$css_code->add_value( '.image-link .overlay-image', 'background', sprintf( 'rgba(%s, %s, %s, 0.5)', $rga_color[0], $rga_color[1], $rga_color[2] ) );

		} // End style colors

		$option = get_theme_mod( 'border_color_first' );
		if ( ! empty( $option ) ) {
			$css_code->add_value( '#header .nav-main,.sf-menu .sub-menu li,.sf-menu .sub-menu li:last-child,.media:hover .image-link,nav.post-navigation,#sidebar .widget-title', 'border-color', $option );
		}
		if ( ! empty( $option ) ) {
			$css_code->add_value( '.sf-menu > li > .sub-menu > li:first-child:before', 'border-bottom-color', $option );
		}

		$option = get_theme_mod( 'border_color_second' );
		if ( ! empty( $option ) ) {
			$css_code->add_value( '.sf-menu a:hover,.sf-menu li.active a, .sf-menu li.current-menu-item > a,.sf-menu li.current-menu-ancestor > a,.mobile-menu a:hover,.mobile-menu li.current-menu-item > a,#sidebar .widget-title:after', 'border-color', $option );
		}

		$option = get_theme_mod( 'typo_top_bar' );
		if ( ! empty( $option['line_height'] ) ) {
			$css_code->add_value( '#top-bar ul.social-links li a .social-icon:before', 'width', $option['line_height'] );
			$css_code->add_value( '#top-bar ul.social-links li a .social-icon:before', 'height', $option['line_height'] );
			$css_code->add_value( '#top-bar ul.social-links li a .social-icon:before', 'line-height', $option['line_height'] );
		}

		$option = get_theme_mod( 'typo_menu_primary' );
		if ( ! empty( $option['line_height'] ) ) {
			$css_code->add_value( '.sf-menu li:hover ul, .sf-menu li.sfHover ul', 'top', $option['line_height'] );
		}
		if ( ! empty( $option['color'] ) ) {
			$css_code->add_value( '.navbar-toggle .icon-bar,.navbar-toggle:hover .icon-bar, .navbar-toggle:focus .icon-bar', 'background-color', $option['color'] );
		}

		$option = get_theme_mod( 'color_bg_sub_menu' );
		if ( ! empty( $option ) ) {
			$css_code->add_value( '.sf-menu > li > .sub-menu > li:first-child:after', 'border-bottom-color', $option );
		}
		$option = get_theme_mod( 'color_bg_sub_menu_hover' );
		if ( ! empty( $option ) ) {
			$css_code->add_value( '.sf-menu > li > .sub-menu > li:first-child:hover:after,.sf-menu > li > .sub-menu > li.current-menu-item:first-child:after', 'border-bottom-color', $option );
		}

		$option = get_theme_mod( 'typo_menu_sticky' );
		if ( ! empty( $option['color'] ) ) {
			$css_code->add_value( '.sticky-header .logo', 'color', $option['color'] );
			$css_code->add_value( '.sticky-header .navbar-toggle .icon-bar,.sticky-header .navbar-toggle:hover .icon-bar,.sticky-header .navbar-toggle:focus .icon-bar', 'background-color', $option['color'] );
		}
	}

	public function __construct() {
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_style' ), 100 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_logo' ), 110 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_top_bar' ), 120 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_header' ), 130 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_menus' ), 140 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_title_bar' ), 150 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_typography' ), 160 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_content' ), 170 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_sidebar' ), 170 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_footer' ), 180 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_copyright' ), 190 );

		add_filter( 'pojo_wp_head_custom_css_code', array( &$this, 'pojo_wp_head_custom_css_code' ) );
	}

}
new Pojo_Firma_Customize_Register_Fields();