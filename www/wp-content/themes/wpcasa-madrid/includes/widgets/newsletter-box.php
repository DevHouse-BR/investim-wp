<?php
/**
 * Newsletter Box widget
 *
 * @package WPCasa Madrid
 */

/**
 * Register widget
 */
add_action( 'widgets_init', 'wpsight_madrid_register_widget_newsletter_box' );
 
function wpsight_madrid_register_widget_newsletter_box() {
	register_widget( 'WPSight_Madrid_Newsletter_Box' );
}

/**
 * Newsletter box widget class
 */
class WPSight_Madrid_Newsletter_Box extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_newsletter_box',
			'description' => _x( 'Display a newsletter box.', 'listing wigdet', 'wpcasa-madrid' )
		);

		parent::__construct( 'wpsight_madrid_newsletter_box', '&rsaquo; ' . _x( 'Newsletter Box', 'listing widget', 'wpcasa-madrid' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		
		$defaults = array(
			'title'			=> __( 'Signup for Our Newsletter', 'wpcasa-madrid' ),
			'description'	=> __( 'Stay updated and get our latest news right into your inbox. No spam.', 'wpcasa-madrid' ),
			'form'			=> '',
			'icon_class'	=> 'fa fa-envelope-o',
		);
		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		
		$instance = wp_parse_args( (array) $instance, $defaults );

		// Echo before_widget
        echo $args['before_widget'];
			
		// Display feature box
		wpsight_madrid_newsletter_box( $instance, $args );
		
		// Echo after_widget
		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;
	    
	    $instance['title']			= strip_tags( $new_instance['title'] );
	    $instance['description']	= wp_kses_post( $new_instance['description'] );
	    $instance['form']			= $new_instance['form'];
	    $instance['icon_class']		= strip_tags( $new_instance['icon_class'] );

        return $instance;

    }

	public function form( $instance ) {
		
		$defaults = array(
			'title'			=> __( 'Signup for Our Newsletter', 'wpcasa-madrid' ),
			'description'	=> __( 'Stay updated and get our latest news right into your inbox. No spam.', 'wpcasa-madrid' ),
			'form'			=> '',
			'icon_class'	=> 'fa fa-envelope-o',
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpcasa-madrid' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description' ); ?>:
		<textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>"><?php echo esc_textarea( $instance['description'] ); ?></textarea></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'form' ); ?>"><?php _e( 'Form' ); ?>:
		<textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id( 'form' ); ?>" name="<?php echo $this->get_field_name( 'form' ); ?>"><?php echo esc_textarea( $instance['form'] ); ?></textarea></label><br />
			<span class="description"><?php _e( 'Place the newsletter form code or shortcode here. We recommend to use <a href="https://wordpress.org/plugins/mailchimp-for-wp/" target="_blank">Mailchimp for WordPress</a>', 'wpcasa-madrid' ); ?></span></p>
		
		<p><label for="<?php echo $this->get_field_id( 'icon_class' ); ?>"><?php _e( 'Icon Class', 'wpcasa-madrid' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'icon_class' ); ?>" name="<?php echo $this->get_field_name( 'icon_class' ); ?>" type="text" value="<?php echo esc_attr( $instance['icon_class'] ); ?>" /></label><br />
			<span class="description"><?php _e( 'For example <a href="https://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a>', 'wpcasa-madrid' ); ?></span></p><?php

	}

}
