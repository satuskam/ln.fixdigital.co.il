<?php

function acp_missing_alt_submenu() {
	add_submenu_page(
		'accessible-poetry',
		'ALT Platform',
		'ALT Platform',
		'manage_options',
		'acp-alt',
		'acp_missing_alt_platform'
	);
}
add_action('admin_menu', 'acp_missing_alt_submenu');

function acp_missing_alt_platform() {

$query_args = array(
	'post_type' => 'attachment',
	'post_mime_type' => 'image',
	'post_status' => 'inherit',
	'posts_per_page' => -1,
);
$images_query = new WP_Query($query_args);

$images = array();

$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


foreach ($images_query->posts as $img) {
	$alt = get_post_meta($img->ID, '_wp_attachment_image_alt', true);
			
	if(strlen($alt) === 0) {
		$img_object = array(
			'id' => $img->ID,
			'name' => $img->post_title,
			'url' => wp_get_attachment_thumb_url($img->ID)
		);
		$images[] = $img_object;
	}
}

if( isset($_POST['acpAddAlt']) ){
	update_post_meta($_POST['thumb_id'], '_wp_attachment_image_alt', $_POST['thumb_alt']);
	echo '<meta http-equiv="refresh" content="0">';
}
?>
<div id="acp_missing_alts_platform" class="wrap">
	<h2><?php _e('Missing ALT\'s', 'acp'); ?></h2>
	<table class="widefat">
		<thead>
			<tr>
				<th><?php _e('ID', 'acp');?></th>
				<th><?php _e('Thumbnail', 'acp'); ?></th>
				<th><?php _e('Image Name', 'acp');?></th>
				<th><?php _e('Add your ALT', 'acp');?></th>
			</tr>
		</thead>
		<tbody>
		<?php if( $images != null ) : ?>
		<?php foreach($images as $key => $value) : ?>
			<tr class="alternate">
				<td><?php echo $value['id']; ?></td>
				<td>
					<img src="<?php echo $value['url']; ?>" class="acp-thumb" alt="<?php _e('Thumbnail of', 'acp');?> <?php echo $value['name']; ?>" />
				</td>
				<td><?php echo $value['name']; ?></td>
				<td>
					<form method="post" action="">
						<input type="hidden" name="thumb_id" value="<?php echo $value['id']; ?>" />
						<input type="text" name="thumb_alt" value="" />
						<input type="submit" name="acpAddAlt" class="button button-primary" value="Save ALT">
					</form>
				</td>
			</tr>
		<?php endforeach; ?>
		<?php else : ?>
			<tr class="alternate">
				<td colspan="4">
					<h3 style="font-weight:400;"><i class="dashicons-before dashicons-yes"></i><?php echo __('Congratulation! You don\'t have images without ALT text.', 'acp');?></h3>
				</td>
			</tr>
		<?php endif;?>
		</tbody>
	</table>
</div>
<?php
}

// Add Toolbar Menus
function acp_missing_alts_notes() {
	
	$query_args = array(
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'post_status' => 'inherit',
		'posts_per_page' => -1,
	);
	$images_query = new WP_Query($query_args);
	
	$images = 0;
	
	foreach ($images_query->posts as $img) {
		$alt = get_post_meta($img->ID, '_wp_attachment_image_alt', true);
				
		if(strlen($alt) === 0) {
			$img_object = array(
				'id' => $img->ID,
				'name' => $img->post_title,
				'url' => wp_get_attachment_thumb_url($img->ID)
			);
			$images++;
		}
	}
		
	
	global $wp_admin_bar;

	$args = array(
		'id'     => 'missing_alts',
		'title'  => $images . ' ' . __('Missing ALT\'s', 'acp'),
		'href'   => '#',
		'group'  => false,
	);
	$wp_admin_bar->add_menu( $args );

}
add_action( 'wp_before_admin_bar_render', 'acp_missing_alts_notes', 999 );