<?php
/*
 * Create Panel Field
 * 
 * Generates fields for the options panel
 * 
 * @params $page, $section
 * @return html
*/
function acp_create_op_field($page, $section) {var_dump($page);
    global $wp_settings_fields;

    if ( ! isset( $wp_settings_fields[$page][$section] ) )
        return;

    foreach ( (array) $wp_settings_fields[$page][$section] as $field ) {
        $class = '';

        if ( ! empty( $field['args']['class'] ) ) {
            $class = ' class="' . esc_attr( $field['args']['class'] ) . '"';
        }

        echo "<div{$class}>";

        if ( ! empty( $field['args']['label_for'] ) ) {
            echo '<div scope="row"><label for="' . esc_attr( $field['args']['label_for'] ) . '">' . $field['title'] . '</label></div>';
        } else {
            echo '<div scope="row">' . $field['title'] . '</div>';
        }

        echo '<div>';
        call_user_func($field['callback'], $field['args']);
        echo '</div>';
        echo '</div>';
    }
}