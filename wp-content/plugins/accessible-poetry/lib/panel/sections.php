<?php
/*
 * Create Section
 * 
 * Generates sections for the options panel
 *
 * @params $page
*/
function acp_create_op_sections( $page ) {
    global $wp_settings_sections, $wp_settings_fields;

    if ( ! isset( $wp_settings_sections[$page] ) )
        return;

    foreach ( (array) $wp_settings_sections[$page] as $section ) {

        if ( $section['callback'] )
            call_user_func( $section['callback'], $section );

        if ( ! isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$page] ) || !isset( $wp_settings_fields[$page][$section['id']] ) )
            continue;
		
		$class = ($section['id'] === 'acp_general') ? 'active' : '';
        echo '<div id="' . $section['id'] . '" class="tab-pane '.$class.'" role="tabpanel">';
        echo '<header class="page-header"><h2>' . $section['title'] . '</h2></header>';
        do_settings_fields( $page, $section['id'] );
        echo '</div>';
    }
}