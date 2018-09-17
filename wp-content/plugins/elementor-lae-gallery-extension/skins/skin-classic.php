<?php
namespace PostsWithAfcElementorPro\Skins;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Classic extends Skin_Base {

    public function get_id() {
//		return 'classic-acf';
		return 'classic';
	}

	public function get_title() {
		return __( 'Classic', 'elementor-pro' );
	}
}
