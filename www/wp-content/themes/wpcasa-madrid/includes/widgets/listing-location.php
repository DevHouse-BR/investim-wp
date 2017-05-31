<?php
/**
 * Listing Location widget
 *
 * @package WPCasa Madrid
 */

/**
 * Register widget
 */
add_action( 'widgets_init', 'wpsight_madrid_register_widget_listing_location' );
 
function wpsight_madrid_register_widget_listing_location() {
	register_widget( 'WPSight_Madrid_Listing_Location' );
}

/**
 * Listing location widget class
 */
class WPSight_Madrid_Listing_Location extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listing_location',
			'description' => _x( 'Display Google Map with marker on single listing pages.', 'listing wigdet', 'wpsight-stage' )
		);

		parent::__construct( 'wpsight_madrid_listing_location', '&rsaquo; ' . _x( 'Single Listing Location', 'listing widget', 'wpsight-stage' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		global $listing;
	
		$listing = wpsight_get_listing( get_the_id() );
		
		$lat  = get_post_meta( get_the_id(), '_geolocation_lat', true );
		$long = get_post_meta( get_the_id(), '_geolocation_long', true );
        
        if( $lat && $long ) {
	        
			$map_height			= isset( $instance['map_height'] ) ? absint( $instance['map_height'] ) : 555;
			$map_type			= isset( $instance['map_type'] ) ? strip_tags( $instance['map_type'] ) : 'ROADMAP';
			$map_zoom			= isset( $instance['map_zoom'] ) ? strip_tags( $instance['map_zoom'] ) : 14;
			$map_no_streetview 	= isset( $instance['map_no_streetview'] ) ? (bool) $instance['map_no_streetview'] : true;
			$map_control_type 	= isset( $instance['map_control_type'] ) ? (bool) $instance['map_control_type'] : true;
			$map_control_nav 	= isset( $instance['map_control_nav'] ) ? (bool) $instance['map_control_nav'] : true;
			$map_scrollwheel 	= isset( $instance['map_scrollwheel'] ) ? (bool) $instance['map_scrollwheel'] : false;
			$display_note 		= isset( $instance['display_note'] ) ? (bool) $instance['display_note'] : true;
			
			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
	        
	        // Echo before_widget
			echo $args['before_widget'];
			
			if( ! is_singular( wpsight_post_type() ) ) {		
				// Display notice when not on single listing page
				wpsight_get_template( 'listing-single-widget-notice.php', array( 'widget_args' => $args, 'widget_instance' => $instance ) );		
			} else {        
        		
        		if ( $title )
					echo $args['before_title'] . $title . $args['after_title'];
				
				// Display listing location template
				wpsight_get_template( 'listing-single-location.php', array( 'widget_args' => $args, 'widget_instance' => $instance ) );
        		
        	}
					
			// Echo after_widget
			echo $args['after_widget'];
		
		} // endif $lat && $long

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;
	    
	    $instance['title']				= strip_tags( $new_instance['title'] );
	    $instance['map_height']			= absint( $new_instance['map_height'] );
	    $instance['map_type']			= strip_tags( $new_instance['map_type'] );
	    $instance['map_zoom']			= strip_tags( $new_instance['map_zoom'] );	    
	    $instance['map_no_streetview']	= ! empty( $new_instance['map_no_streetview'] ) ? 1 : 0;
	    $instance['map_control_type']	= ! empty( $new_instance['map_control_type'] ) ? 1 : 0;
	    $instance['map_control_nav']	= ! empty( $new_instance['map_control_nav'] ) ? 1 : 0;
	    $instance['map_scrollwheel']	= ! empty( $new_instance['map_scrollwheel'] ) ? 1 : 0;
	    $instance['display_note']		= ! empty( $new_instance['display_note'] ) ? 1 : 0;

        return $instance;

    }

	public function form( $instance ) {
	    
		$title				= isset( $instance['title'] ) ? strip_tags( $instance['title'] ) : '';
		$map_height			= isset( $instance['map_height'] ) ? absint( $instance['map_height'] ) : 555;
		$map_type			= isset( $instance['map_type'] ) ? strip_tags( $instance['map_type'] ) : 'ROADMAP';
		$map_zoom			= isset( $instance['map_zoom'] ) ? strip_tags( $instance['map_zoom'] ) : 14;
		$map_streetview 	= isset( $instance['map_no_streetview'] ) ? (bool) $instance['map_no_streetview'] : true;
		$map_control_type 	= isset( $instance['map_control_type'] ) ? (bool) $instance['map_control_type'] : true;
		$map_control_nav 	= isset( $instance['map_control_nav'] ) ? (bool) $instance['map_control_nav'] : true;
		$map_scrollwheel 	= isset( $instance['map_scrollwheel'] ) ? (bool) $instance['map_scrollwheel'] : false;
		$display_note 		= isset( $instance['display_note'] ) ? (bool) $instance['display_note'] : true; ?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpsight-stage' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'map_height' ); ?>"><?php _e( 'Map Height (px)', 'wpsight-stage' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'map_height' ); ?>" name="<?php echo $this->get_field_name( 'map_height' ); ?>" type="text" value="<?php echo esc_attr( $map_height ); ?>" /></label></p>
	
		<p><label for="<?php echo $this->get_field_id( 'map_type' ); ?>"><?php _e( 'Map Type', 'wpcasa-madrid' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'map_type' ); ?>" name="<?php echo $this->get_field_name( 'map_type' ); ?>">
				<option value="ROADMAP"<?php selected( $map_type, 'ROADMAP' ); ?>><?php _ex( 'Roadmap', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="SATELLITE"<?php selected( $map_type, 'SATELLITE' ); ?>><?php _ex( 'Satellite', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="HYBRID"<?php selected( $map_type, 'HYBRID' ); ?>><?php _ex( 'Hybrid', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="TERRAIN"<?php selected( $map_type, 'TERRAIN' ); ?>><?php _ex( 'Terrain', 'listing widget', 'wpcasa-madrid' ); ?></option>
			</select></p>
		
		<p><label for="<?php echo $this->get_field_id( 'map_zoom' ); ?>"><?php _e( 'Map Zoom', 'wpcasa-madrid' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'map_zoom' ); ?>" name="<?php echo $this->get_field_name( 'map_zoom' ); ?>">
				<?php for( $i = 1; $i <= 20; $i++ ) : ?>
				<option value="<?php echo $i; ?>"<?php selected( $map_zoom, $i ); ?>><?php echo $i; ?></option>
				<?php endfor; ?>
			</select></p>

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'map_no_streetview' ); ?>" name="<?php echo $this->get_field_name( 'map_no_streetview' ); ?>"<?php checked( $map_streetview ); ?> />
			<label for="<?php echo $this->get_field_id( 'map_no_streetview' ); ?>"><?php _e( 'Disable streetview', 'wpcasa-madrid' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'map_control_type' ); ?>" name="<?php echo $this->get_field_name( 'map_control_type' ); ?>"<?php checked( $map_control_type ); ?> />
			<label for="<?php echo $this->get_field_id( 'map_control_type' ); ?>"><?php _e( 'Enable type control', 'wpcasa-madrid' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'map_control_nav' ); ?>" name="<?php echo $this->get_field_name( 'map_control_nav' ); ?>"<?php checked( $map_control_nav ); ?> />
			<label for="<?php echo $this->get_field_id( 'map_control_nav' ); ?>"><?php _e( 'Enable nav control', 'wpcasa-madrid' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'map_scrollwheel' ); ?>" name="<?php echo $this->get_field_name( 'map_scrollwheel' ); ?>"<?php checked( $map_scrollwheel ); ?> />
			<label for="<?php echo $this->get_field_id( 'map_scrollwheel' ); ?>"><?php _e( 'Enable scrollwheel', 'wpcasa-madrid' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'display_note' ); ?>" name="<?php echo $this->get_field_name( 'display_note' ); ?>"<?php checked( $display_note ); ?> />
			<label for="<?php echo $this->get_field_id( 'display_note' ); ?>"><?php _e( 'Display public note', 'wpcasa-madrid' ); ?></label></p>
		
		<?php

	}

}
