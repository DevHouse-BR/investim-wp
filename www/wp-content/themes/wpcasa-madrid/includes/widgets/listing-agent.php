<?php
/**
 * Listing Agent widget
 *
 * @package WPCasa Madrid
 */

/**
 * Register widget
 */
add_action( 'widgets_init', 'wpsight_madrid_register_widget_listing_agent' );
 
function wpsight_madrid_register_widget_listing_agent() {
	register_widget( 'WPSight_Madrid_Listing_Agent' );
}

/**
 * Listing agent widget class
 */
class WPSight_Madrid_Listing_Agent extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listing_agent',
			'description' => _x( 'Display listing agent on single listing pages.', 'listing wigdet', 'wpcasa-madrid' )
		);

		parent::__construct( 'wpsight_madrid_listing_agent', '&rsaquo; ' . _x( 'Single Listing Agent', 'listing widget', 'wpcasa-madrid' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		global $listing;
	
		$listing = wpsight_get_listing( get_the_id() );
		
		$defaults = array(
			'image_size' => 'post-thumbnail'
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $display_image 		= isset( $instance['display_image'] ) ? (bool) $instance['display_image'] : true;
        $image_size			= isset( $instance['image_size'] ) ? strip_tags( $instance['image_size'] ) : 'post-thumbnail';
        $display_company	= isset( $instance['display_company'] ) ? (bool) $instance['display_company'] : true;
        $display_phone		= isset( $instance['display_phone'] ) ? (bool) $instance['display_phone'] : true;
		$display_social		= isset( $instance['display_social'] ) ? (bool) $instance['display_social'] : true;
		$display_archive	= isset( $instance['display_archive'] ) ? (bool) $instance['display_archive'] : true;

		// Echo before_widget
        echo $args['before_widget'];
        
        if( ! is_singular( wpsight_post_type() ) ) {		
			// Display notice when not on single listing page
			wpsight_get_template( 'listing-single-widget-notice.php', array( 'widget_args' => $args, 'widget_instance' => $instance ) );		
		} else {
			
        	if ( $title )
				echo $args['before_title'] . $title . $args['after_title'];
			
			// Display listing agent template
			wpsight_get_template( 'listing-single-agent.php', array( 'widget_args' => $args, 'widget_instance' => $instance ) );

        }
		
		// Echo after_widget
		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;
	    
	    $instance['title']				= strip_tags( $new_instance['title'] );
	    $instance['display_image'] 		= ! empty( $new_instance['display_image'] ) ? 1 : 0;
	    $instance['image_size'] 		= strip_tags( $new_instance['image_size'] );
	    $instance['display_company'] 	= ! empty( $new_instance['display_company'] ) ? 1 : 0;
	    $instance['display_phone'] 		= ! empty( $new_instance['display_phone'] ) ? 1 : 0;
	    $instance['display_social'] 	= ! empty( $new_instance['display_social'] ) ? 1 : 0;
	    $instance['display_archive'] 	= ! empty( $new_instance['display_archive'] ) ? 1 : 0;

        return $instance;

    }

	public function form( $instance ) {
		
		$defaults = array(
			'title' 		=> '',
			'image_size' 	=> 'post-thumbnail'
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title 				= $instance['title'];
        $display_image 		= isset( $instance['display_image'] ) ? (bool) $instance['display_image'] : true;
        $image_size			= strip_tags( $instance['image_size'] );
        $display_company	= isset( $instance['display_company'] ) ? (bool) $instance['display_company'] : true;
        $display_phone		= isset( $instance['display_phone'] ) ? (bool) $instance['display_phone'] : true;
		$display_social		= isset( $instance['display_social'] ) ? (bool) $instance['display_social'] : true;
		$display_archive	= isset( $instance['display_archive'] ) ? (bool) $instance['display_archive'] : true; ?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpcasa-madrid' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'display_image' ); ?>" name="<?php echo $this->get_field_name( 'display_image' ); ?>"<?php checked( $display_image ); ?> />
			<label for="<?php echo $this->get_field_id( 'display_image' ); ?>"><?php _e( 'Display agent image', 'wpcasa-madrid' ); ?></label></p>
		
		<div<?php if( ! $display_image ) echo ' style="display:none"'; ?>>
		
			<p><label for="<?php echo $this->get_field_id( 'image_size' ); ?>"><?php _e( 'Image Size', 'wpcasa-madrid' ); ?>:</label><br />
				<select class="widefat" id="<?php echo $this->get_field_id( 'image_size' ); ?>" name="<?php echo $this->get_field_name( 'image_size' ); ?>">			
					<?php foreach( get_intermediate_image_sizes() as $size ) : ?>
					<option value="<?php echo strip_tags( $size ); ?>"<?php selected( $image_size, $size ); ?>><?php echo strip_tags( $size ); ?></option>
					<?php endforeach; ?>
					<option value="full"<?php selected( $image_size, 'full' ); ?>>full (original)</option>
				</select></p>
		
		</div>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'display_company' ); ?>" name="<?php echo $this->get_field_name( 'display_company' ); ?>"<?php checked( $display_company ); ?> />
			<label for="<?php echo $this->get_field_id( 'display_company' ); ?>"><?php _e( 'Display agent company', 'wpcasa-madrid' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'display_phone' ); ?>" name="<?php echo $this->get_field_name( 'display_phone' ); ?>"<?php checked( $display_phone ); ?> />
			<label for="<?php echo $this->get_field_id( 'display_phone' ); ?>"><?php _e( 'Display agent phone', 'wpcasa-madrid' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'display_social' ); ?>" name="<?php echo $this->get_field_name( 'display_social' ); ?>"<?php checked( $display_social ); ?> />
			<label for="<?php echo $this->get_field_id( 'display_social' ); ?>"><?php _e( 'Display social profiles', 'wpcasa-madrid' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'display_archive' ); ?>" name="<?php echo $this->get_field_name( 'display_archive' ); ?>"<?php checked( $display_archive ); ?> />
			<label for="<?php echo $this->get_field_id( 'display_archive' ); ?>"><?php _e( 'Display agent archive link', 'wpcasa-madrid' ); ?></label></p><?php

	}

}
