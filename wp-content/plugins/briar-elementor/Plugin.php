<?php
/*
	Plugin Name: Briar Elementor
	Plugin URI: http://briar.pro/
	Description: Additional element for Elementor
	Version: 0.1.2
	Author: briar
	Author URI: http://briar.pro/
	License:     GPL2
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain: briar-elementor
	Domain Path: /languages
 */




namespace BriarElementor;

use BriarElementor\Widgets\GalleryMasonry;
use BriarElementor\Widgets\NavigationMenu;
use BriarElementor\Widgets\PostsMGM;
use Elementor;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Plugin {

	public $version = '0.1.2';

	protected $assetsUrl;

	protected $widgets = ['gallery'];

	public function __construct() {

		$this->assetsUrl = plugins_url( '/assets', __FILE__ );

		add_action( 'elementor/init', [ $this, 'elementorInit' ] );

		add_action('elementor/widgets/widgets_registered' , [$this, 'registerWidgets']);

		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueueStyles' ] );



	}

	public function elementorInit() {

		// Add element category in panel
		\Elementor\Plugin::instance()->elements_manager->add_category(
			'briar-elements',
			[
				'title' => __( 'Briar Elements', 'briar-elementor' ),
				'icon' => 'font',
			],
			1

		);
	}

	/**
	 * @param $manager Elementor\Widgets_Manager
	 *
	 */

	public function registerWidgets( $manager ) {

	//	include_once 'widgets/skins/SkinMGM.php';

	//	include_once 'widgets/Gallery.php';
		include_once 'widgets/GalleryMasonry.php';
	//	include_once 'widgets/NavigationMenu.php';
	//	include_once 'widgets/PostsMGM.php';

	//	$manager->register_widget_type( new Gallery() );
		$manager->register_widget_type( new GalleryMasonry() );
	//	$manager->register_widget_type( new NavigationMenu() );
	//	$manager->register_widget_type( new PostsMGM() );

	}


	public function enqueueStyles() {
		wp_enqueue_style('briar-elementor', $this->assetsUrl . '/css/main.css', null, $this->version );

		wp_enqueue_script('briar-elementor', $this->assetsUrl . '/js/main.js', ['jquery'], $this->version, true);

	}

}

new Plugin();