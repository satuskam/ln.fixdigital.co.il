<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Pojo_Aleph_Customize_Register_Fields {

	public function section_style( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'primary_color',
			'title' => __( 'Primary Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#61bd6d',
		);

		$fields[] = array(
			'id'    => 'secondary_color',
			'title' => __( 'Secondary Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#FFFFFF',
		);

		$fields[] = array(
			'id'    => 'another_color',
			'title' => __( 'Another Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#565656',
		);

		$fields[] = array(
			'id'    => 'layout_site',
			'title' => __( 'Layout Site', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_SELECT,
			'choices' => array(
				'narrow' => __( 'Narrow', 'pojo' ),
				'normal' => __( 'Normal', 'pojo' ),
				'wide' => __( 'Wide', 'pojo' ),
			),
			'std' => 'normal',
		);

		$fields[] = array(
			'id'    => 'bg_body',
			'title' => __( 'Background Site', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_BACKGROUND,
			'std' => array(
				'color' => '#eeeeee',
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
				'size'  => '38px',
				'family'  => 'Roboto Slab',
				'weight' => 'bold',
				'color' => '#61bd6d',
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
			'id'    => 'image_logo_margin_top',
			'title' => __( 'Logo Margin Top', 'pojo' ),
			'std'   => '45px',
			'selector' => '.logo',
			'change_type' => 'margin_top',
		);

		$fields[] = array(
			'id'    => 'image_logo_margin_bottom',
			'title' => __( 'Logo Margin Bottom', 'pojo' ),
			'std'   => '45px',
			'selector' => '.logo',
			'change_type' => 'margin_bottom',
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
			'id' => 'nav_background',
			'title' => __( 'Background', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#ffffff',
			'selector' => '.nav-main',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'typo_menu_primary',
			'title' => __( 'Menu Primary', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '13px',
				'family'  => 'Roboto Slab',
				'weight' => 'bold',
				'color' => '#878787',
				'transform' => 'uppercase',
				'line_height' => '25px',
			),
			'selector' => '.sf-menu a, .mobile-menu a,#sidebar .menu li a, #sidebar .sub-menu li a, #sidebar .sub-page-menu li a',
			'change_type' => 'typography',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'separator_color',
			'title' => __( 'Separator Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#f2f2f2',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_menu_primary_hover',
			'title' => __( 'Menu Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#61bd6d',
			'selector' => '.sf-menu a:hover,.sf-menu li.active a, .sf-menu li.current-menu-item > a,.sf-menu .sfHover > a,.sf-menu .sfHover > li.current-menu-item > a,.sf-menu li.current-menu-ancestor > a,.mobile-menu a:hover,.mobile-menu li.current-menu-item > a,#sidebar .menu li a:hover, #sidebar .sub-menu li a, #sidebar .sub-page-menu li a:hover, #sidebar .menu li.current-menu-item a, #sidebar .sub-menu li.current_page_item a, a.search-toggle:hover, a.search-toggle .fa-times',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_bg_sub_menu',
			'title' => __( 'Sub Menu - Background', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#61bd6d',
			'selector' => '.nav-main .sf-menu .sub-menu',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'color_bg_sub_menu_hover',
			'title' => __( 'Sub Menu - BG Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#41a85f',
			'selector' => '.nav-main .sf-menu .sub-menu li:hover > a,.nav-main .sf-menu .sub-menu li.current-menu-item > a',
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
				'color' => '#ffffff',
				'transform' => 'uppercase',
				'line_height' => '3em',
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

		$fields[] = array(
			'id' => 'chk_enable_sticky_header',
			'title' => __( 'Sticky Menu', 'pojo' ),
			'type' => Pojo_Theme_Customize::FIELD_CHECKBOX,
			'std' => true,
		);

		$fields = apply_filters( 'pojo_customizer_section_menus_after', $fields );

		$sections[] = array(
			'id' => 'menus',
			'title'      => __( 'Navigation Bar', 'pojo' ),
			'desc'       => '',
			'fields'     => $fields,
		);

		return $sections;
	}

	public function section_search_bar( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'chk_enable_menu_search',
			'title' => __( 'Add Search Button', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_CHECKBOX,
			'std'   => true,
		);

		$fields[] = array(
			'id'    => 'bg_bar_search',
			'title' => __( 'Background Search Bar', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#61bd6d',
			'selector' => '.search-section',
			'change_type' => 'bg_color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'bg_search_field',
			'title' => __( 'Background Field', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#ffffff',
			'selector' => '.search-section .form-search .field',
			'change_type' => 'bg_color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_search_field',
			'title' => __( 'Color Field', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#61bd6d',
			'selector' => '.search-section .form-search .field',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_search_button',
			'title' => __( 'Color Button', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#ffffff',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_search_button_hover',
			'title' => __( 'Color Button Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#61bd6d',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$sections[] = array(
			'id' => 'search_bar',
			'title' => __( 'Search Bar', 'pojo' ),
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
				'family'  => 'Noto Sans',
				'weight' => 'normal',
				'color' => '#878787',
				'line_height' => '1.6em',
			),
			'selector' => 'body',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#61bd6d',
			'selector' => 'a',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#41a85f',
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
			'std'   => '#61bd6d',
			'selector' => 'selection',
			'change_type' => 'bg_selection',
		);

		$fields[] = array(
			'id'    => 'typo_h1',
			'title' => __( 'H1 - Page Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '37px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#61bd6d',
				'transform' => 'uppercase',
				'line_height' => '1.3em',
			),
			'selector' => 'h1',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_h2',
			'title' => __( 'H2', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '33px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#61bd6d',
				'transform' => 'uppercase',
				'line_height' => '1.3em',
			),
			'selector' => 'h2',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_h3',
			'title' => __( 'H3 - List Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '23px',
				'family'  => 'Roboto Slab',
				'weight' => 'bold',
				'color' => '#61bd6d',
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
				'family'  => 'Roboto Slab',
				'weight' => 'bold',
				'color' => '#61bd6d',
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
				'size'  => '19px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#5b5b5b',
				'transform' => 'uppercase',
				'line_height' => '1.6em',
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
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#6d6d6d',
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

	public function section_sidebar( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'typo_sidebar_text',
			'title' => __( 'Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '15px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#878787',
				'line_height' => '1.6em',
			),
			'selector' => '#sidebar',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_sidebar_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#61bd6d',
			'selector' => '#sidebar a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_sidebar_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#41a85f',
			'selector' => '#sidebar a:hover',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'typo_sidebar_widget_text',
			'title' => __( 'Widget Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '19px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#5b5b5b',
				'transform' => 'uppercase',
				'line_height' => '1.6em',
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
			'std' => '#3d3d3d',
			'selector' => '#footer',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'typo_footer_text',
			'title' => __( 'Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '13px',
				'family'  => 'Roboto Slab',
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
			'std'   => '#61bd6d',
			'selector' => '#footer a:hover',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'typo_footer_widget_text',
			'title' => __( 'Widget Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '17px',
				'family'  => 'Roboto Slab',
				'weight' => 'normal',
				'color' => '#61bd6d',
				'transform' => 'uppercase',
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
			'std' => '#3d3d3d',
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
				'color' => '#61bd6d',
				'line_height' => '1em',
			),
			'selector' => '#copyright',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_copyright_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#61bd6d',
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
		$primary_color   = get_theme_mod( 'primary_color' );
		$secondary_color = get_theme_mod( 'secondary_color' );
		$another_color = get_theme_mod( 'another_color' );

		if ( $primary_color && $secondary_color && $another_color ) {
			$string = <<<CSSCODE
			#breadcrumbs .separator {color: {$primary_color};}
			.nav-main .pojo-menu-cart li.cart-checkout a {color: {$primary_color} !important;border-color: {$primary_color} !important;}
			.nav-main .pojo-menu-cart li.cart-checkout a:hover {color:{$secondary_color} !important; background-color: {$primary_color} !important;}
			.pagination > li > a, .pagination > li > span {background-color: {$another_color}; color: {$secondary_color};}
			.pagination > li > a:hover, .pagination > li > span:hover {background-color: {$primary_color}; color: {$secondary_color};}
			.pagination > .active > a, .pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus {background-color: {$primary_color}; color: {$secondary_color};}
			.pojo-loadmore-wrap .button,.pojo-loadmore-wrap .pojo-loading,.pojo-loading-wrap .button,.pojo-loading-wrap .pojo-loading {background-color: {$primary_color}; border-color: {$primary_color}; color: {$secondary_color};}
			div.logo-text a {background-color: {$primary_color}; color: {$secondary_color};}
			div.logo-text a:hover {background-color: {$another_color}; color: {$secondary_color};}
			article.sticky:before {background-color: {$primary_color}; color: {$secondary_color};}
			.archive-header h1 span {color: {$another_color};}
			#list-items .hentry.format-status {background-color: {$primary_color}; color: {$secondary_color};}
			#list-items .hentry.format-status a {background-color: {$primary_color}; color: {$secondary_color};}
			.widget_tag_cloud a, #sidebar-footer .widget_tag_cloud a {background-color: {$another_color}; color: {$secondary_color};}
			.widget_tag_cloud a:hover, #sidebar-footer .widget_tag_cloud a:hover {background-color: {$primary_color}; color: {$secondary_color};}
			ul.social-links li a .social-icon:before {background-color: {$primary_color}; }
			ul.social-links li a .social-icon:before {color: {$secondary_color}; }
			ul.social-links li a:hover .social-icon:before {background-color: {$secondary_color}; }
			ul.social-links li a:hover .social-icon:before {color: {$primary_color}; }
			.button,.button.size-small,.button.size-large,.button.size-xl,.button.size-xxl,.read-more,.more-link span {border-color: {$primary_color};color: {$primary_color};}
			.button:hover,.button.size-small:hover,.button.size-large:hover,.button.size-xl:hover, .button.size-xxl:hover,.read-more:hover,.more-link span:hover {background: {$primary_color};color: {$secondary_color};}
CSSCODE;
			
			$css_code->add_data( $string );

		} // End style colors

		$option = get_theme_mod( 'typo_menu_primary' );
		if ( ! empty( $option['line_height'] ) ) {
			$css_code->add_value( '.sf-menu a, .menu-no-found,.sf-menu li.pojo-menu-search', 'line-height', $option['line_height'] );
			$css_code->add_value( 'a.search-toggle', 'color', $option['color'] );
		}

		$option = get_theme_mod( 'bg_header' );
		if ( ! empty( $option['color'] ) ) {
			$css_code->add_value( '.sf-menu .sub-menu', 'background-color', $option['color'] );
		}

		$option = get_theme_mod( 'typo_menu_primary' );
		if ( ! empty( $option['color'] ) ) {
			$css_code->add_value( '.navbar-toggle', 'border-color', $option['color'] );
			$css_code->add_value( '.icon-bar', 'background-color', $option['color'] );
		}
		
		$css_code->add_value( '.sf-menu > li > a, body.rtl .sf-menu > li > a', 'border-color', get_theme_mod( 'separator_color' ) );

		$css_code->add_value( '.search-section .form-search .button', 'color', get_theme_mod( 'color_search_button' ) );
		$css_code->add_value( '.search-section .form-search .button', 'border-color', get_theme_mod( 'color_search_button' ) );
		$css_code->add_value( '.search-section .form-search .button:hover', 'color', get_theme_mod( 'color_search_button_hover' ) );
		$css_code->add_value( '.search-section .form-search .button:hover', 'background-color', get_theme_mod( 'color_search_button' ) );
		$css_code->add_value( '.search-section .form-search .button:hover', 'border-color', get_theme_mod( 'color_search_button' ) );
	}

	public function __construct() {
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_style' ), 100 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_logo' ), 110 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_menus' ), 120 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_search_bar' ), 130 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_typography' ), 140 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_sidebar' ), 160 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_footer' ), 170 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_copyright' ), 180 );

		add_filter( 'pojo_wp_head_custom_css_code', array( &$this, 'pojo_wp_head_custom_css_code' ) );
	}

}
new Pojo_Aleph_Customize_Register_Fields();