<?php
/**
 * Icon Box widget
 *
 * @package WPCasa Madrid
 */

/**
 * Register widget
 */
add_action( 'widgets_init', 'wpsight_madrid_register_widget_icon_box' );
 
function wpsight_madrid_register_widget_icon_box() {
	register_widget( 'WPSight_Madrid_Icon_Box' );
}

/**
 * Feature box widget class
 */
class WPSight_Madrid_Icon_Box extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_icon_box',
			'description' => _x( 'Display an icon box with text.', 'listing wigdet', 'wpcasa-madrid' )
		);

		parent::__construct( 'wpsight_madrid_icon_box', '&rsaquo; ' . _x( 'Icon Box', 'listing widget', 'wpcasa-madrid' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		
		$defaults = array(
			'title'			=> '',
			'icon_class'	=> 'fa fa-check',
			'text_1'		=> '',
			'text_2'		=> '',
		);
		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		
		$instance = wp_parse_args( (array) $instance, $defaults );

		// Echo before_widget
        echo $args['before_widget'];
			
		// Display icon box
		wpsight_madrid_icon_box( $instance, $args );
		
		// Echo after_widget
		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;
	    
	    $instance['title']			= strip_tags( $new_instance['title'] );
	    $instance['icon_class']		= strip_tags( $new_instance['icon_class'] );
	    $instance['text_1']			= strip_tags( $new_instance['text_1'], '<a><br><br /><em><i><strong><span>' );
	    $instance['text_2']			= strip_tags( $new_instance['text_2'], '<a><br><br /><em><i><strong><span>' );

        return $instance;

    }

	public function form( $instance ) {
		
		$defaults = array(
			'title'			=> '',
			'icon_class'	=> 'fa fa-check',
			'text_1'		=> '',
			'text_2'		=> '',
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpcasa-madrid' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'icon_class' ); ?>"><?php _e( 'Icon Class', 'wpcasa-madrid' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'icon_class' ); ?>" name="<?php echo $this->get_field_name( 'icon_class' ); ?>" type="text" value="<?php echo esc_attr( $instance['icon_class'] ); ?>" /></label><br />
			<span class="description"><?php _e( 'For example <a href="https://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a>', 'wpcasa-madrid' ); ?></span></p>
		
		<p><label for="<?php echo $this->get_field_id( 'text_1' ); ?>"><?php _e( 'Text Line', 'wpcasa-madrid' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'text_1' ); ?>" name="<?php echo $this->get_field_name( 'text_1' ); ?>" type="text" value="<?php echo esc_attr( $instance['text_1'] ); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'text_2' ); ?>"><?php _e( 'Text Line', 'wpcasa-madrid' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'text_2' ); ?>" name="<?php echo $this->get_field_name( 'text_2' ); ?>" type="text" value="<?php echo esc_attr( $instance['text_2'] ); ?>" /></label></p><?php

	}

}
