<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Pojo_Theme_Template extends Pojo_Templates {

	protected function _print_start_layout_default() {
		?>
		<section id="main" class="<?php echo MAIN_CLASSES; ?> sidebar-right" role="main">
	<?php
	}

	protected function _print_end_layout_default() {
		?>
		</section><!-- section#main -->
		<?php get_sidebar(); ?>
	<?php
	}

	protected function _print_start_layout_sidebar_left() {
		?>
		<section id="main" class="<?php echo MAIN_CLASSES; ?> sidebar-left" role="main">
	<?php
	}

	protected function _print_end_layout_sidebar_left() {
		?>
		</section><!-- section#main -->
		<?php get_sidebar(); ?>
	<?php
	}

	protected function _print_start_layout_full() {
		?>
		<section id="main" class="<?php echo FULLWIDTH_CLASSES; ?> full-width" role="main">
	<?php
	}

	protected function _print_end_layout_full() {
		?>
		</section><!-- section#main -->
	<?php
	}

	protected function _print_start_layout_section() {
		?>
		<section id="main" role="main">
	<?php
	}

	protected function _print_end_layout_section() {
		?>
		</section><!-- section#main -->
	<?php
	}

	public function pojo_before_content_loop( $display_type ) {
		echo '<div id="list-items">';
	}

	public function pojo_after_content_loop( $display_type ) {
		echo '</div>';
	}

	public function po_display_types( $display_types = array(), $cpt ) {
		if ( 'post' === $cpt ) {
			$display_types['blog'] = __( 'Blog', 'pojo' );
		}

		return $display_types;
	}

	public function pojo_recent_posts_layouts( $styles = array() ) {
		$styles['blog'] = __( 'Blog', 'pojo' );
		$styles['list'] = __( 'List', 'pojo' );

		return $styles;
	}

	public function default_layout( $default ) {
		return Pojo_Layouts::LAYOUT_FULL;
	}

	public function default_layout_post( $default ) {
		return Pojo_Layouts::LAYOUT_FULL;
	}

	public function __construct() {
		add_filter( 'pojo_default_layout', array( &$this, 'default_layout' ) );
		add_filter( 'pojo_default_layout_post', array( &$this, 'default_layout_post' ) );
		
		parent::__construct();
	}

}