<?php
/**
 * Created by PhpStorm.
 * User: shvv
 * Date: 29.11.17
 * Time: 11:29
 */
namespace BriarElementor\Widgets\Skins;

use ElementorPro\Modules\Posts\Skins\Skin_Base;

class SkinMGM extends Skin_Base {
	public function get_id() {
		return 'mgm';
	}

	public function get_title() {
		return __( 'MGM', 'briar-elementor' );
	}
}