<?php

class Mycustom_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'thisisanew_widget',
            __( 'My Custom Widget', 'thisisanewblock' ),
            array(
            'classname' => 'thisisanew_widget',
            'description' => __( 'Enter a custom description for your new widget', 'mycustomdomain' )
            )
        );

        load_plugin_textdomain( 'thisisanewblock', false, basename( dirname( __FILE__ ) ) . '/languages' );
    }
    
    
    
    public function form( $instance )
    {
        $title = esc_attr( $instance['title'] );
        $paragraph = esc_attr( $instance['paragraph'] );
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <br>
            <input type="text" value="<?php echo $title; ?>" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('paragraph'); ?>"><?php _e('Text'); ?></label>
            <br>
            <textarea rows="10" cols="16" name="<?php echo $this->get_field_name('paragraph'); ?>" id="<?php echo $this->get_field_id('paragraph'); ?>"><?php echo $paragraph; ?></textarea>
        </p>

        <?php
    }
    
    
    public function widget( $args, $instance )
    {
        extract( $args );

        $title = apply_filters( 'widget_title', $instance['title'] );
        $paragraph = $instance['paragraph'];

        echo $before_widget;
        if ( $title ) {echo $before_title . $title . $after_title;}
        if ( $paragraph ) {echo $before_paragraph . $paragraph . $after_paragraph;}
        echo $after_widget;
    }
    
    
    
    public function update( $new_instance, $old_instance ) {

$instance = $old_instance;

$instance['title'] = strip_tags( $new_instance['title'] );
$instance['paragraph'] = strip_tags( $new_instance['paragraph'] );

return $instance;

}



    
    
}
