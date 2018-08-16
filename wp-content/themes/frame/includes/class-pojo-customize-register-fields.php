<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Pojo_Frame_Customize_Register_Fields {

	public function section_style( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'first_color',
			'title' => __( 'First Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#f5c433',
		);

		$fields[] = array(
			'id'    => 'second_color',
			'title' => __( 'Second Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#FFFFFF',
		);

		$fields[] = array(
			'id'    => 'third_color',
			'title' => __( 'Third Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#010101',
		);

		$fields[] = array(
			'id'    => 'border_color_first',
			'title' => __( 'Border Color First', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#010101',
		);

		$fields[] = array(
			'id'    => 'border_color_second',
			'title' => __( 'Border Color Second', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#ededed',
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

	public function section_logo( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'typo_site_title',
			'title' => __( 'Site Name', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '30px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#00ADEF',
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
			'id'    => 'logo_margin_top',
			'title' => __( 'Logo Margin Top', 'pojo' ),
			'std'   => '35px',
			'selector' => '.logo',
			'change_type' => 'margin_top',
		);

		$fields[] = array(
			'id'    => 'logo_margin_bottom',
			'title' => __( 'Logo Margin Bottom', 'pojo' ),
			'std'   => '35px',
			'selector' => '.logo',
			'change_type' => 'margin_bottom',
		);

		$fields[] = array(
			'id'    => 'image_sticky_header_logo',
			'title' => __( 'Logo Sticky Header', 'pojo' ),
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

	public function section_menus( $sections = array() ) {
		$fields = array();

		$fields = apply_filters( 'pojo_customizer_section_menus_before', $fields );

		$fields[] = array(
			'id'    => 'menu_margin_top',
			'title' => __( 'Menu Margin Top', 'pojo' ),
			'std'   => '35px',
			'selector' => '#header .nav-main .sf-menu',
			'change_type' => 'margin_top',
		);

		$fields[] = array(
			'id'    => 'menu_margin_bottom',
			'title' => __( 'Menu Margin Bottom', 'pojo' ),
			'std'   => '35px',
			'selector' => '#header .nav-main .sf-menu',
			'change_type' => 'margin_bottom',
		);

		$fields[] = array(
			'id'    => 'typo_menu_primary',
			'title' => __( 'Menu Primary', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '14px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#0a0a0a',
				'transform' => 'uppercase',
				'line_height' => '50px',
				'letter-spacing' => '1.7px',
			),
			'selector' => '.sf-menu a, .mobile-menu a',
			'change_type' => 'typography',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_menu_primary_hover',
			'title' => __( 'Menu Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#000000',
			'selector' => '.sf-menu a:hover,.sf-menu li.active a, .sf-menu li.current-menu-item > a,.sf-menu .sfHover > a,.sf-menu .sfHover > li.current-menu-item > a,.sf-menu li.current-menu-ancestor > a,.mobile-menu a:hover,.mobile-menu li.current-menu-item > a',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_bg_sub_menu',
			'title' => __( 'Sub Menu - Background', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#ffffff',
			'selector' => '.nav-main .sf-menu .sub-menu',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'typo_sub_menu_link',
			'title' => __( 'Sub Menu', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '12px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#0a0a0a',
				'transform' => 'uppercase',
				'line_height' => '3.2em',
				'letter-spacing' => '1px',
			),
			'selector' => '.nav-main .sf-menu .sub-menu li a',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_sub_menu_link_hover',
			'title' => __( 'Sub Menu - Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#8c8c8c',
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
			'selector' => '#title-bar.title-bar-style-custom_bg',
			'change_type' => 'background',
		);

		$fields[] = array(
			'id'    => 'title_bar_padding_top',
			'title' => __( 'Title Bar Padding Top', 'pojo' ),
			'std'   => '90px',
			'selector' => '#title-bar.title-bar-style-custom_bg > .container',
			'change_type' => 'padding_top',
		);

		$fields[] = array(
			'id'    => 'title_bar_padding_bottom',
			'title' => __( 'Title Bar Padding Bottom', 'pojo' ),
			'std'   => '90px',
			'selector' => '#title-bar.title-bar-style-custom_bg > .container',
			'change_type' => 'padding_bottom',
		);

		$fields[] = array(
			'id'    => 'title_bar_title',
			'title' => __( 'Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '28px',
				'family'  => 'Roboto Slab',
				'weight' => 'bold',
				'color' => '#000000',
				'transform' => 'uppercase',
				'line_height' => '1em',
			),
			'selector' => '#title-bar',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'title_bar_breadcrumbs',
			'title' => __( 'Breadcrumbs', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '10px',
				'family'  => 'Roboto Slab',
				'weight' => '300',
				'color' => '#0c0c0c',
				'line_height' => '40px',
				'letter-spacing' => '2px',
			),
			'selector' => '#title-bar div.breadcrumbs, #title-bar div.breadcrumbs a',
			'change_type' => 'typography',
		);

		$sections[] = array(
			'id' => 'title_bar',
			'title'      => __( 'Title Bar', 'pojo' ),
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
				'family'  => 'Roboto Slab',
				'weight' => '300',
				'color' => '#0a0a0a',
				'line_height' => '24px',
			),
			'selector' => 'body',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#000000',
			'selector' => 'a,#sidebar .menu li a:hover, #sidebar .sub-menu li a:hover, #sidebar .sub-page-menu li a:hover,#sidebar .menu li.current_page_item > a, #sidebar .sub-menu li.current_page_item > a, #sidebar .sub-page-menu li.current_page_item > a, #sidebar .menu li.current-menu-item > a, #sidebar .sub-menu li.current-menu-item > a, #sidebar .sub-page-menu li.current-menu-item > a',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#494949',
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
			'std'   => '#f4c536',
			'selector' => 'selection',
			'change_type' => 'bg_selection',
		);

		$fields[] = array(
			'id'    => 'typo_h1',
			'title' => __( 'H1 - Page Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '25px',
				'family'  => 'Roboto Slab',
				'weight' => 'bold',
				'color' => '#000000',
				'transform' => 'uppercase',
				'line_height' => '30px',
				'letter-spacing' => '0.2px',
			),
			'selector' => 'h1',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_h2',
			'title' => __( 'H2', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '32px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#000000',
				'line_height' => '1.5em',
			),
			'selector' => 'h2',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_h3',
			'title' => __( 'H3', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '30px',
				'family'  => 'Roboto Slab',
				'weight' => 'bold',
				'color' => '#000000',
				'transform' => 'uppercase',
				'line_height' => '2em',
			),
			'selector' => 'h3',
			'change_type' => 'typography',
		);


		$fields[] = array(
			'id'    => 'typo_h4',
			'title' => __( 'H4', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '21px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#000000',
				'transform' => 'uppercase',
				'line_height' => '25px',
			),
			'selector' => 'h4',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_h5',
			'title' => __( 'H5', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '20px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#000000',
				'transform' => 'uppercase',
				'line_height' => '1.8em',
			),
			'selector' => 'h5',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_h6',
			'title' => __( 'H6', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '18px',
				'family'  => 'Roboto Slab',
				'weight' => 'bold',
				'color' => '#000000',
				'line_height' => '1em',
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
				'size'  => '28px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#000000',
				'transform' => 'uppercase',
				'line_height' => '35px',
			),
			'selector' => 'h3.media-heading',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_heading_grid',
			'title' => __( 'Heading - Grid', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '25px',
				'family'  => 'Roboto Slab',
				'weight' => 'bold',
				'color' => '#000000',
				'transform' => 'uppercase',
				'line_height' => '1.2em',
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
				'family'  => 'Roboto Slab',
				'weight' => 'bold',
				'color' => '#000000',
				'transform' => 'uppercase',
				'line_height' => '1em',
				'letter-spacing' => '1.5px',
			),
			'selector' => '.gallery-item h4.grid-heading',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_meta_data_list',
			'title' => __( 'Meta Data - List Layout', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '12px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#000000',
				'transform' => 'capitalize',
				'line_height' => '1.2em',
				'letter-spacing' => '0px',
			),
			'selector' => '.media .entry-meta > span',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_meta_data_grid',
			'title' => __( 'Meta Data - Grid Layout', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '11px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#000000',
				'transform' => 'capitalize',
				'line_height' => '2.6em',
				'letter-spacing' => '0px',
			),
			'selector' => '.grid-item .entry-meta > span',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_meta_data_single',
			'title' => __( 'Meta Data - Single', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '12px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#000000',
				'transform' => 'capitalize',
				'line_height' => '20px',
				'letter-spacing' => '0px',
			),
			'selector' => '.entry-post .entry-meta > span',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_excerpt_list',
			'title' => __( 'Excerpt - List Layout', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '14px',
				'family'  => 'Roboto Slab',
				'weight' => '300',
				'color' => '#000000',
				'line_height' => '21px',
			),
			'selector' => '.list-item .entry-excerpt',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_excerpt_grid',
			'title' => __( 'Excerpt - Grid Layout', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '14px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#000000',
				'line_height' => '20px',
			),
			'selector' => '.grid-item .entry-excerpt',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_breadcrumbs',
			'title' => __( 'Breadcrumbs', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '11px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#000000',
				'transform' => 'uppercase',
				'line_height' => '4em',
				'letter-spacing' => '1px',
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
				'family'  => 'Roboto Slab',
				'weight' => '300',
				'color' => '#000000',
				'transform' => 'uppercase',
				'line_height' => '4em',
				'letter-spacing' => '0px',
			),
			'selector' => 'nav.post-navigation a',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_filter_cats',
			'title' => __( 'Category Filter', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '13px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#000000',
				'transform' => 'uppercase',
				'line_height' => '1em',
				'letter-spacing' => '0px',
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
			'id'    => 'bg_sidebar',
			'title' => __( 'Background', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_BACKGROUND,
			'std' => array(
				'color' => '#F1F1F1',
				'image'  => '',
				'position'  => 'top center',
				'repeat' => 'repeat',
				'size' => 'auto',
				'attachment' => 'scroll',
			),
			'selector' => '#sidebar',
			'change_type' => 'background',
		);

		$fields[] = array(
			'id'    => 'typo_sidebar_text',
			'title' => __( 'Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '14px',
				'family'  => 'Roboto Slab',
				'weight' => '300',
				'color' => '#000000',
				'line_height' => '1.6em',
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
			'std'   => '#4f4f4f',
			'selector' => '#sidebar a:hover',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'typo_sidebar_widget_text',
			'title' => __( 'Widget Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '14px',
				'family'  => 'Roboto Slab',
				'weight' => 'bold',
				'color' => '#000000',
				'transform' => 'uppercase',
				'line_height' => '2em',
				'letter-spacing' => '2px',
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
			'std' => '#ffffff',
			'selector' => '#footer',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'typo_footer_text',
			'title' => __( 'Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '14px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#0a0a0a',
				'line_height' => '1.5em',
			),
			'selector' => '#footer',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_footer_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#0a0a0a',
			'selector' => '#footer a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_footer_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#f5c433',
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
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#0a0a0a',
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
			'std' => '3',
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
			'std' => '#ffffff',
			'selector' => '#copyright',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'typo_copyright_text',
			'title' => __( 'Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '12px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#0a0a0a',
				'line_height' => '70px',
			),
			'selector' => '#copyright',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_copyright_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#0a0a0a',
			'selector' => '#copyright a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_copyright_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#f5c433',
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
		$third_color = get_theme_mod( 'third_color' );

		if ( $first_color && $second_color ) {
			$string = <<<CSSCODE
			.sf-menu > li > a span:after {background-color: {$first_color};}
			.nav-main .pojo-menu-cart li.cart-checkout a {color: {$first_color};border-color: {$first_color};}
			.nav-main .pojo-menu-cart li.cart-checkout a:hover {color:{$second_color}; background-color: {$first_color};}
			.align-pagination .pagination > li > a {color: {$third_color}; }
			.align-pagination .pagination .active a,.align-pagination .pagination > li > a:hover {color: {$third_color}; background-color: {$first_color}; }
			.pojo-loadmore-wrap .button,.pojo-loadmore-wrap .pojo-loading,.pojo-loading-wrap .button,.pojo-loading-wrap .pojo-loading {background-color: {$third_color}; border-color: {$third_color}; color: {$second_color};}
			.pojo-loadmore-wrap .button:hover,.pojo-loading-wrap .button:hover, {background-color: {$second_color}; border-color: {$third_color}; color: {$third_color};}
			.media .read-more,.entry-tags a,nav.post-navigation a {background-color: {$third_color};color: {$second_color};border-color {$third_color}}
			.media .read-more:hover,.entry-tags a:hover,nav.post-navigation a:hover {background-color: {$second_color};color: {$third_color};border-color {$third_color}}
			.image-link .overlay-caption .caption {color: {$third_color}; }
			.image-link .overlay-caption .caption .grid-heading:after {background-color: {$third_color};}
			.category-filters li a:hover, .category-filters li .active {background-color: {$first_color}; }
			.author-info .author-link,.commentlist .comment-author .comment-reply-link {border-color: {$first_color};}
			#sidebar .widget-title {background-color: {$first_color};}
			.woocommerce span.onsale, .woocommerce-page span.onsale {background: {$first_color}; }
			.woocommerce a.button,.woocommerce button.button,.woocommerce input.button,.woocommerce #respond input#submit,.woocommerce #content input.button,.woocommerce-page a.button,
			.woocommerce-page button.button,.woocommerce-page input.button,.woocommerce-page #respond input#submit,.woocommerce-page #content input.button {border-color: {$first_color}; color: {$first_color}; }
			.woocommerce a.button:hover,.woocommerce button.button:hover,.woocommerce input.button:hover,.woocommerce #respond input#submit:hover,.woocommerce #content input.button:hover,.woocommerce-page a.button:hover,
			.woocommerce-page button.button:hover,.woocommerce-page input.button:hover,.woocommerce-page #respond input#submit:hover,.woocommerce-page #content input.button:hover {background:{$first_color}; border-color: {$first_color}; color: {$second_color};}
			.woocommerce a.button.alt,.woocommerce button.button.alt,.woocommerce input.button.alt,.woocommerce #respond input#submit.alt,.woocommerce #content input.button.alt,.woocommerce-page a.button.alt,
			.woocommerce-page button.button.alt,.woocommerce-page input.button.alt,.woocommerce-page #respond input#submit.alt,.woocommerce-page #content input.button.alt {background: {$first_color}; border-color: {$first_color}; color: {$second_color}; }
			.woocommerce a.button.alt:hover,.woocommerce button.button.alt:hover,.woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover,.woocommerce #content input.button.alt:hover,.woocommerce-page a.button.alt:hover,
			.woocommerce-page button.button.alt:hover,.woocommerce-page input.button.alt:hover,	.woocommerce-page #respond input#submit.alt:hover,.woocommerce-page #content input.button.alt:hover {border-color: {$first_color}; color: {$first_color}; }
			.woocommerce .woocommerce-error,.woocommerce .woocommerce-info, .woocommerce .woocommerce-message, .woocommerce-page .woocommerce-error,.woocommerce-page .woocommerce-info, .woocommerce-page .woocommerce-message { border-color: {$first_color}; }
			.woocommerce .woocommerce-error:before,.woocommerce .woocommerce-info:before, .woocommerce .woocommerce-message:before, .woocommerce-page .woocommerce-error:before,.woocommerce-page .woocommerce-info:before,
			 .woocommerce-page .woocommerce-message:before {background-color: {$first_color}; color: {$second_color}; }
			 .woocommerce div.product .woocommerce-tabs ul.tabs li.active a,
			 .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active a,
			 .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active a,
			 .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active a,
			 .woocommerce div.product .woocommerce-tabs ul.tabs li a:hover,
			 .woocommerce-page div.product .woocommerce-tabs ul.tabs li a:hover,
			 .woocommerce #content div.product .woocommerce-tabs ul.tabs li a:hover,
			 .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li a:hover {color: {$first_color};}
			 .woocommerce div.product .woocommerce-tabs ul.tabs li.active:before,
			 .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active:before,
			 .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active:before,
			 .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active:before,
			 .woocommerce div.product .woocommerce-tabs ul.tabs li.active:after,
			 .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active:after,
			 .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active:after,
			 .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active:after {border-color: {$first_color};}
			 .woocommerce div.product .woocommerce-tabs ul.tabs li:hover:before,
			 .woocommerce-page div.product .woocommerce-tabs ul.tabs li:hover:before,
			 .woocommerce #content div.product .woocommerce-tabs ul.tabs li:hover:before,
			 .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li:hover:before,
			 .woocommerce div.product .woocommerce-tabs ul.tabs li:hover:after,
			 .woocommerce-page div.product .woocommerce-tabs ul.tabs li:hover:after,
			 .woocommerce #content div.product .woocommerce-tabs ul.tabs li:hover:after,
			 .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li:hover:after {border-color: {$first_color};}
			input[type="submit"],.button,.button.size-small,.button.size-large,.button.size-xl,.button.size-xxl{background-color: {$third_color}; border-color: {$third_color}; color: {$second_color};}
			input[type="submit"]:hover,.button:hover,.button.size-small:hover,.button.size-large:hover,.button.size-xl:hover, .button.size-xxl:hover {background: {$second_color}; border-color: {$third_color}; color: {$third_color};}
CSSCODE;
			
			$css_code->add_data( $string );
			
			$rga_color = pojo_hex2rgb( $first_color );
			if ( ! empty( $rga_color ) )
				$css_code->add_value( '.image-link .overlay-caption', 'background', sprintf( 'rgba(%s, %s, %s, 0.8)', $rga_color[0], $rga_color[1], $rga_color[2] ) );
			
		} // End style colors

		$option = get_theme_mod( 'border_color_first' );
		if ( ! empty( $option ) ) {
			$css_code->add_value( '.sf-menu .sub-menu,#title-bar > .container,#sidebar-footer > .container, #copyright > .container, .category-filters, #sidebar .menu, #sidebar .sub-page-menu, #sidebar .menu li a, #sidebar .sub-menu li a, #sidebar .sub-page-menu li a, .woocommerce div.product .woocommerce-tabs ul.tabs:before,	.woocommerce-page div.product .woocommerce-tabs ul.tabs:before, .woocommerce #content div.product .woocommerce-tabs ul.tabs:before, .woocommerce-page #content div.product .woocommerce-tabs ul.tabs:before', 'border-color', $option );
			$css_code->add_value( '#list-items .media-heading:after,.list-items .media-heading:after,.single .page-title h1:after', 'background-color', $option );
		}
		$option = get_theme_mod( 'border_color_second' );
		if ( ! empty( $option ) ) {
			$css_code->add_value( '.author-info,nav.post-navigation,#comments,#respond,.commentlist li,.form .field', 'border-color', $option );
		}

		$option = get_theme_mod( 'typo_menu_primary' );
		if ( ! empty( $option['line_height'] ) ) {
			$css_code->add_value( '.sf-menu a, .menu-no-found,.sf-menu li.pojo-menu-search,.search-header', 'line-height', $option['line_height'] );
			$css_code->add_value( '.sf-menu li:hover ul, .sf-menu li.sfHover ul', 'top', $option['line_height'] );
		}

		$option = get_theme_mod( 'bg_header' );
		if ( ! empty( $option['color'] ) ) {
			$css_code->add_value( '.sf-menu > li > a:before', 'background-color', $option['color'] );
		}

		$option = get_theme_mod( 'typo_menu_primary' );
		if ( ! empty( $option['color'] ) ) {
			$css_code->add_value( '.navbar-toggle', 'border-color', $option['color'] );
			$css_code->add_value( '.icon-bar', 'background-color', $option['color'] );
			$css_code->add_value( '.sf-menu > li.active > a:before, .sf-menu > li:hover > a:before, .sf-menu > li.current-menu-item > a:before, .sf-menu > li.current-menu-parent > a:before, .sf-menu > li.current-menu-ancestor > a:before, .sf-menu > li.current_page_item > a:before, .sf-menu > li.current_page_paren > a:before, .sf-menu > li.current_page_ancestor > a:before', 'color', $option['color'] );
		}

	}

	public function __construct() {
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_style' ), 100 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_header' ), 110 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_logo' ), 120 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_menus' ), 140 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_title_bar' ), 150 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_typography' ), 160 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_content' ), 170 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_sidebar' ), 180 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_footer' ), 190 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_copyright' ), 200 );

		add_filter( 'pojo_wp_head_custom_css_code', array( &$this, 'pojo_wp_head_custom_css_code' ) );
	}

}
new Pojo_Frame_Customize_Register_Fields();