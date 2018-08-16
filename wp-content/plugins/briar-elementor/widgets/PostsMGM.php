<?php
/**
 * Created by PhpStorm.
 * User: shvv
 * Date: 29.11.17
 * Time: 11:12
 */

namespace BriarElementor\Widgets;

use ElementorPro\Modules\Posts\Widgets\Posts;


class PostsMGM extends Posts {

	public function get_name() {
		return 'mgm-posts';
	}

	public function get_title() {
		return __( 'MGM posts', 'briar-elementor' );
	}

	public function get_categories() {
		return [ 'briar-elements' ];
	}

	protected function _register_skins() {
		$this->add_skin( new Skins\SkinMGM( $this ) );
		//$this->add_skin( new ElementorSkins\Skin_Classic( $this ) );
	}

}