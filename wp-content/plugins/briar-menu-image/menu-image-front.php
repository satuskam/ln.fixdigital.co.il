<?php

use Timber\Menu;

class Menu_Image_Front {
	protected $styles = [];

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

		add_filter( 'wp_nav_menu', array( $this, 'output' ), 10, 2 );
	}


	/**
	 * Loading additional stylesheet.
	 *
	 * Loading custom stylesheet to fix images positioning in match themes
	 */
	public function enqueue_assets() {
		wp_register_style( 'menu-image', plugins_url( '', __FILE__ ) . '/menu-image.css', array(), '1.1', 'all' );
		wp_enqueue_style( 'menu-image' );
	}

	/**
	 * Filters the HTML content for navigation menus.
	 *
	 * @since 3.0.0
	 *
	 * @see wp_nav_menu()
	 *
	 * @param string $nav_menu The HTML content for the navigation menu.
	 * @param stdClass $args An object containing wp_nav_menu() arguments.
	 *
	 * @return string Html content
	 */

	public function output( $nav_menu, $args ) {

		$menu = new Menu($args->menu->slug);

		//error_log('MenuTimber:');
		//error_log(print_r($menu->get_items(), true));

		$nav_menu       = '';
		$show_container = false;

		if ( $args->container ) {
			/**
			 * Filters the list of HTML tags that are valid for use as menu containers.
			 *
			 * @since 3.0.0
			 *
			 * @param array $tags The acceptable HTML tags for use as menu containers.
			 *                    Default is array containing 'div' and 'nav'.
			 */
			$allowed_tags = apply_filters( 'wp_nav_menu_container_allowedtags', array( 'div', 'nav' ) );
			if ( is_string( $args->container ) && in_array( $args->container, $allowed_tags ) ) {
				$show_container = true;
				$class          = $args->container_class ? ' class="' . esc_attr( $args->container_class ) . '"' : ' class="menu-' . $menu->slug . '-container"';
				$id             = $args->container_id ? ' id="' . esc_attr( $args->container_id ) . '"' : '';
				$nav_menu       .= '<' . $args->container . $id . $class . '>';
			}
		}

		$wrap_id = $args->menu_id ? $args->menu_id : 'menu-' . $args->menu->slug;

		$wrap_class = $args->menu_class ? $args->menu_class : '';

		$items = '';

		foreach ( $menu->get_items() as $item ) {
			$items .= $this->get_item( $item );
		}

		$nav_menu .= sprintf( $args->items_wrap, esc_attr( $wrap_id ), esc_attr( $wrap_class ), $items );


		if ( $show_container ) {
			$nav_menu .= '</' . $args->container . '>';
		}

		$nav_menu .= '<style>';
		foreach ($this->styles as $id => $image) {
			$nav_menu .= sprintf('.c-briar-image__%s:after { background-image:url(%s); width:%spx; height:%spx}', $id, $image['url'], $image['width'], $image['height']);
		}
		$nav_menu .= '</style>';


		return $nav_menu;

	}


	/**
	 * @param $item \Timber\MenuItem
	 *
	 * @return string
	 *
	 */
	protected function get_item( $item ) {

		$output = "<li class=\"{$item->class}\"><a href=\"{$item->link()}\">{$item->title()}</a>";

		if ( !empty($item->children()) ) {
			$class = $thumb_url = '';
			if ($item->thumbnail_id) {
				$class = "c-briar-image c-briar-image__{$item->id}";
				$thumb_url = wp_get_attachment_image_url( $item->thumbnail_id, apply_filters('briar_menu_image_image_size', $item->image_size ? $item->image_size : 'full') );
				$this->styles[$item->id] = [
					'url' => $thumb_url,
					'width' => $item->image_width,
					'height' => $item->image_height
					];
				$padding = $item->image_width + 40;
			}
			$output .= "<ul class=\"sub-menu {$class}\" style=\"padding-right: {$padding}px; \">";

			foreach ( $item->children() as $child ) {
				$output .= $this->get_item( $child );
			}

			$output .= '</ul>';
		}

		$output .= '</li>';

		return $output;
	}

}

new Menu_Image_Front();
