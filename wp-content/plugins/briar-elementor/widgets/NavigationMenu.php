<?php
namespace BriarElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class NavigationMenu extends Widget_Base {

	protected $nav_menu_index = 1;

	public function get_name() {
		return 'briar-navigation-menu';
	}

	public function get_title() {
		return __( 'Briar Menu', 'briar-elementor' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'briar-elements' ];
	}

	public function get_script_depends() {
		return [ 'jquery'];
	}

	protected function get_nav_menu_index() {
		return $this->nav_menu_index++;
	}

	private function get_available_menus() {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout', 'elementor-pro' ),
			]
		);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu',
				[
					'label'   => __( 'Menu', 'elementor-pro' ),
					'type'    => Controls_Manager::SELECT,
					'options' => $menus,
					'default' => array_keys( $menus )[0],
					'separator' => 'after',
					'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'elementor-pro' ), admin_url( 'nav-menus.php' ) ),
				]
			);
		} else {
			$this->add_control(
				'menu',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf( __( '<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'elementor-pro' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator' => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		$this->end_controls_section();


	}

	protected function render() {
		$available_menus = $this->get_available_menus();

		if ( ! $available_menus ) {
			return;
		}

		$settings = $this->get_active_settings(); ?>

        <nav id="main-navigation" class="primary-navigation navigation clearfix" role="navigation">
			<?php
			// Display Main Navigation.
			wp_nav_menu( array(
					'theme_location' => 'primary',
					'container' => false,
					'menu_class' => 'main-navigation-menu',
					'menu' => $settings['menu'],
					'menu_id' => 'menu-' . $this->get_nav_menu_index() . '-' . $this->get_id(),
					'echo' => true,
					'fallback_cb' => '__return_empty_string',
				)
			);
			?>
        </nav>

		<?php

	}

	protected function _content_template() {

	}
}
