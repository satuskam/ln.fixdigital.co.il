<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Pojo_Firma_Customize_Register_Fields {

	public function section_style( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'first_color',
			'title' => __( 'First Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#0e4964',
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
			'std'   => '#0e4964',
		);

		$fields[] = array(
			'id'    => 'border_color_second',
			'title' => __( 'Border - Second Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#e4e4e4',
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
			'title' => __( 'Background Body (Boxed Mode)', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_BACKGROUND,
			'std' => array(
				'color' => '#ededed',
				'image'  => '',
				'position'  => 'top center',
				'repeat' => 'repeat',
				'size' => 'auto',
				'attachment' => 'scroll',
			),
			'selector' => 'body',
			'change_type' => 'background',
		);

		$fields[] = array(
			'id'    => 'bg_content',
			'title' => __( 'Background Content', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_BACKGROUND,
			'std' => array(
				'color' => '#FFFFFF',
				'image'  => '',
				'position'  => 'top center',
				'repeat' => 'repeat',
				'size' => 'auto',
				'attachment' => 'scroll',
			),
			'selector' => '#container.boxed',
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
				'family'  => 'Droid Serif',
				'weight' => 'bold',
				'color' => '#e14938',
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
			'id'    => 'image_logo_width',
			'title' => __( 'Logo Width (required)', 'pojo' ),
			'std'   => '140px',
			'selector' => '#header .top-bar .logo',
			'change_type' => 'width',
		);

		$fields[] = array(
			'id'    => 'image_logo_height',
			'title' => __( 'Logo Height (required)', 'pojo' ),
			'std'   => '160px',
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

	public function section_top_bar( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id' => 'bg_top_bar',
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
			'selector' => '.top-bar',
			'change_type' => 'background',
		);

		$fields[] = array(
			'id'    => 'typo_top_bar',
			'title' => __( 'Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '13px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#a8a8a8',
				'transform' => 'none',
				'style'  => 'italic',
				'line_height' => '60px',
				'letter_spacing' => '0px',
			),
			'selector' => '.top-bar, .top-bar .widget-title',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_link_top_bar',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#a8a8a8',
			'selector' => '.top-bar a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_link_hover_top_bar',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#0e4964',
			'selector' => '.top-bar a:hover',
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
			'selector' => '#header, .sticky-header',
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
			'id'    => 'typo_menu_primary',
			'title' => __( 'Menu Primary', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '15px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#0e4964',
				'transform' => 'uppercase',
				'line_height' => '80px',
			),
			'selector' => '.sf-menu a, .mobile-menu a,.sf-menu a:hover,.sf-menu li.active a, .sf-menu li.current-menu-item > a,.sf-menu .sfHover > a,.sf-menu .sfHover > li.current-menu-item > a,.sf-menu li.current-menu-ancestor > a,.mobile-menu a:hover,.mobile-menu li.current-menu-item > a',
			'change_type' => 'typography',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_menu_primary_hover',
			'title' => __( 'Menu Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#5a7789',
			'selector' => '.sf-menu a:hover,.sf-menu li.active a, .sf-menu li.current-menu-item > a,.sf-menu li.current-menu-ancestor > a,.mobile-menu a:hover,.mobile-menu li.current-menu-item > a',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_bg_sub_menu',
			'title' => __( 'Sub Menu - Background', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#5a7789',
			'selector' => '.nav-main .sf-menu .sub-menu',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'color_bg_sub_menu_hover',
			'title' => __( 'Sub Menu - BG Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#0e4964',
			'selector' => '.nav-main .sf-menu .sub-menu li:hover > a,.nav-main .sf-menu .sub-menu li.current-menu-item > a',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'typo_sub_menu_link',
			'title' => __( 'Sub Menu', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '14px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#ffffff',
				'transform' => 'none',
				'line_height' => '3.5em',
			),
			'selector' => '.nav-main .sf-menu .sub-menu li a',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_sub_menu_link_hover',
			'title' => __( 'Sub Menu - Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#ffffff',
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
			),
			'selector' => '#title-bar.title-bar-style-custom_bg .title-bar-default',
			'change_type' => 'background',
		);

		$fields[] = array(
			'id'    => 'title_bar_typo_title',
			'title' => __( 'Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '17px',
				'family'  => 'Droid Serif',
				'weight' => 'bold',
				'transform' => 'uppercase',
				'color' => '#5a7789',
				'line_height' => false, // Skip for that's value !
			),
			'selector' => '#title-bar .title-primary',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'title_bar_typo_breadcrumbs',
			'title' => __( 'Breadcrumbs', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '13px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#a8a8a8',
				'style' => 'italic',
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
				'size'  => '14px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#6c6c6c',
				'line_height' => '2em',
			),
			'selector' => 'body',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#0e4964',
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
			'std'   => '#0e4964',
			'selector' => 'selection',
			'change_type' => 'bg_selection',
		);

		$fields[] = array(
			'id'    => 'typo_h1',
			'title' => __( 'H1 - Page Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '29px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#a8a8a8',
				'transform' => 'uppercase',
				'line_height' => '1.3em',
				'letter_spacing' => '5px',
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
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#a8a8a8',
				'transform' => 'uppercase',
				'line_height' => '1.3em',
				'letter_spacing' => '5px',
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
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#5a7789',
				'transform' => 'uppercase',
				'line_height' => '1.5em',
				'letter_spacing' => '4px',
			),
			'selector' => 'h3',
			'change_type' => 'typography',
		);


		$fields[] = array(
			'id'    => 'typo_h4',
			'title' => __( 'H4', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '16px',
				'family'  => 'Droid Serif',
				'weight' => 'bold',
				'color' => '#0e4964',
				'style' => 'italic',
				'transform' => 'none',
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
				'size'  => '15px',
				'family'  => 'Droid Serif',
				'weight' => 'bold',
				'color' => '#5a7789',
				'style' => 'italic',
				'transform' => 'none',
				'line_height' => '1.7em',
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
				'color' => '#6d6d6d',
				'line_height' => '1.7em',
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
			'id'    => 'typo_heading_list',
			'title' => __( 'Heading - List', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '21px',
				'family'  => 'Droid Serif',
				'weight' => 'bold',
				'color' => '#0e4964',
				'transform' => 'uppercase',
				'style' => 'normal',
				'line_height' => '31px',
			),
			'selector' => '.list-item h3.media-heading',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_heading_blog',
			'title' => __( 'Heading - Blog', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '29px',
				'family'  => 'Droid Serif',
				'weight' => 'bold',
				'color' => '#0e4964',
				'transform' => 'uppercase',
				'style' => 'normal',
				'line_height' => '35px',
			),
			'selector' => '.blog-item h3.media-heading',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_heading_grid',
			'title' => __( 'Heading - Grid', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '16px',
				'family'  => 'Droid Serif',
				'weight' => 'bold',
				'color' => '#0e4964',
				'transform' => 'uppercase',
				'style' => 'normal',
				'line_height' => '1.4em',
			),
			'selector' => 'h4.grid-heading',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_heading_galleries',
			'title' => __( 'Heading - Galleries', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '18px',
				'family'  => 'Droid Serif',
				'weight' => 'bold',
				'color' => '#0e4964',
				'transform' => 'uppercase',
				'style' => 'normal',
				'line_height' => '1em',
			),
			'selector' => '.gallery-item h4.grid-heading',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_heading_small_galleries',
			'title' => __( 'Heading Small -  Category Galleries', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '13px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#6c6c6c',
				'transform' => 'none',
				'style' => 'italic',
				'line_height' => '0.5em',
				'letter_spacing' => '0px',
			),
			'selector' => '.gallery-item h4.grid-heading small',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_meta_data_archive',
			'title' => __( 'Meta Data - Archive', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '12px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#6c6c6c',
				'transform' => 'capitalize',
				'style' => 'italic',
				'line_height' => '2.4em',
				'letter_spacing' => '0px',
			),
			'selector' => '.entry-meta > span',
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
				'color' => '#0e4964',
				'transform' => 'capitalize',
				'style' => 'italic',
				'line_height' => '1em',
				'letter_spacing' => '0px',
			),
			'selector' => '.single .entry-meta > span',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_excerpt_archive',
			'title' => __( 'Excerpt Archive', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '14px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#6c6c6c',
				'line_height' => '23px',
			),
			'selector' => '.entry-excerpt',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_read_more',
			'title' => __( 'Read More', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '12px',
				'family'  => 'Droid Serif',
				'weight' => 'bold',
				'style' => 'italic',
				'color' => '#0e4964',
				'line_height' => '1',
			),
			'selector' => '.read-more',
			'change_type' => 'typography',
		);


		$fields[] = array(
			'id'    => 'typo_breadcrumbs',
			'title' => __( 'Breadcrumbs', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '12px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#6c6c6c',
				'style' => 'italic',
				'transform' => 'none',
				'line_height' => '1em',
				'letter_spacing' => '0px',
			),
			'selector' => '#primary #breadcrumbs,#primary #breadcrumbs a',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_post_nav',
			'title' => __( 'Post Navigation', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '13px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#a8a8a8',
				'transform' => 'none',
				'style' => 'italic',
				'line_height' => '4em',
				'letter_spacing' => '0px',
			),
			'selector' => 'nav.post-navigation a',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_filter_cats',
			'title' => __( 'Category Filter', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '14px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#a8a8a8',
				'transform' => 'none',
				'style' => 'italic',
				'line_height' => '2em',
				'letter_spacing' => '0.5px',
			),
			'selector' => '.category-filters li a',
			'change_type' => 'typography',
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
				'size'  => '14px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#6c6c6c',
				'transform' => 'none',
				'style' => 'italic',
				'line_height' => '2em',
				'letter_spacing' => '0px',
			),
			'selector' => '#sidebar',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_sidebar_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#6c6c6c',
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
				'size'  => '15px',
				'family'  => 'Droid Serif',
				'weight' => 'bold',
				'color' => '#0e4964',
				'transform' => 'none',
				'style' => 'italic',
				'line_height' => '2em',
				'letter_spacing' => '0px',
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
				'color' => '#5a7789',
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
				'size'  => '14px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#9ba9b3',
				'transform' => 'none',
				'style' => 'italic',
				'line_height' => '1.5em',
				'letter_spacing' => '0px',
			),
			'selector' => '#footer',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_footer_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#9ba9b3',
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
				'size'  => '15px',
				'family'  => 'Droid Serif',
				'weight' => 'bold',
				'color' => '#ffffff',
				'transform' => 'none',
				'style' => 'italic',
				'line_height' => '2em',
				'letter_spacing' => '0px',
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
				'color' => '#5a7789',
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
				'size'  => '13px',
				'family'  => 'Droid Serif',
				'weight' => 'normal',
				'color' => '#ffffff',
				'transform' => 'none',
				'style' => 'italic',
				'line_height' => '80px',
				'letter_spacing' => '0px',
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
			'std'   => '#0e4964',
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
			article.sticky:before {background-color: {$first_color};}
			.align-pagination .pagination > li > a:hover,.align-pagination .pagination > li > span:hover,.align-pagination .pagination > .active > a,.align-pagination .pagination > .active > span,.align-pagination .pagination > .active > a:hover,.align-pagination .pagination > .active > span:hover,.align-pagination .pagination > .active > a:focus,.align-pagination .pagination > .active > span:focus {border-color: {$first_color}; color: {$first_color};}
			.woocommerce ul.products li.product .caption .price, .woocommerce-page ul.products li.product .caption .price {color: {$first_color};}
			.category-filters li a:hover,.category-filters li a.active {color: {$first_color};}
			.widget_tag_cloud a, #sidebar-footer .widget_tag_cloud a {color: {$second_color};}
			.widget_tag_cloud a:hover, #sidebar-footer .widget_tag_cloud a:hover {background-color: {$first_color}; color: {$second_color};}
			ul.social-links li a .social-icon:before {background-color: {$first_color};}
			ul.social-links li a .social-icon:before {color: {$second_color}; }
			ul.social-links li a:hover .social-icon:before {background-color: {$second_color}; }
			ul.social-links li a:hover .social-icon:before {color: {$first_color}; }
			.navbar-toggle:hover .icon-bar, .navbar-toggle:focus .icon-bar {background-color: {$first_color};}
			.button,.button.size-small,.button.size-large,.button.size-xl,.button.size-xxl {border-color: {$first_color};color: {$first_color};background: {$second_color};}
			.button:hover,.button:focus,.button.size-small:hover,.button.size-large:hover,.button.size-xl:hover, .pojo-loadmore-wrap .pojo-loading, .pojo-loading-wrap .pojo-loading  {border-color: {$first_color};background: {$first_color};color: {$second_color};}
CSSCODE;

			$css_code->add_data( $string );

			$rga_color = pojo_hex2rgb( $first_color );
			if ( ! empty( $rga_color ) )
				$css_code->add_value( '.image-link .overlay-image', 'background', sprintf( 'rgba(%s, %s, %s, 0.5)', $rga_color[0], $rga_color[1], $rga_color[2] ) );

		} // End style colors

		$option = get_theme_mod( 'border_color_first' );
		if ( ! empty( $option ) ) {
			$css_code->add_value( '.nav-main,.sticky-header .sticky-header-inner,.media:hover .image-link,.grid-item:hover .inbox .caption,li.product:hover .image-link', 'border-color', $option );
		}

		$option = get_theme_mod( 'border_color_second' );
		if ( ! empty( $option ) ) {
			$css_code->add_value( '.media .image-link,.grid-item .inbox .caption,li.product .image-link,.align-pagination .pagination,nav.post-navigation,.author-info,#sidebar .widget-title', 'border-color', $option );
		}

		$option = get_theme_mod( 'typo_menu_primary' );
		if ( ! empty( $option['line_height'] ) ) {
			$css_code->add_value( '.sf-menu li:hover ul, .sf-menu li.sfHover ul', 'top', $option['line_height'] );
		}
		if ( ! empty( $option['color'] ) ) {
			$css_code->add_value( '.icon-bar', 'background-color', $option['color'] );
		}

		$option = get_theme_mod( 'image_logo_height' );
		if ( ! empty( $option ) ) {
			$css_code->add_value( '#header .top-bar', 'height', $option );
		}

		$option = get_theme_mod( 'color_bg_sub_menu_hover' );
			$css_code->add_value( '.sf-menu .sub-menu li > a', 'border-color', $option );
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