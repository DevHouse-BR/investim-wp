<?php
/**
 * Listing Title widget
 *
 * @package WPCasa Madrid
 */

/**
 * Register widget
 */
add_action( 'widgets_init', 'wpsight_madrid_register_widget_listing_title' );
 
function wpsight_madrid_register_widget_listing_title() {
	register_widget( 'WPSight_Madrid_Listing_Title' );
}

/**
 * Listing title widget class
 */
class WPSight_Madrid_Listing_Title extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listing_title',
			'description' => _x( 'Display title and listing actions on single listing pages.', 'listing wigdet', 'wpcasa-madrid' )
		);

		parent::__construct( 'wpsight_madrid_listing_title', '&rsaquo; ' . _x( 'Single Listing Title', 'listing widget', 'wpcasa-madrid' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		global $listing;
		
		$listing = get_post( get_the_id() );

		// Echo before_widget
        echo $args['before_widget'];
        
        if( ! is_singular( wpsight_post_type() ) ) {		
			// Display notice when not on single listing page
			wpsight_get_template( 'listing-single-widget-notice.php', array( 'widget_args' => $args, 'widget_instance' => $instance ) );		
		} else {        
        	// Display listing title template
        	wpsight_get_template( 'listing-single-title.php', array( 'widget_args' => $args, 'widget_instance' => $instance ) );        
        }
		
		// Echo after_widget
		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;
	    
	    // get registered actions
		$actions = wpsight_get_listing_actions();
		
		foreach( $actions as $action => $v )	    
	    	$instance[ 'action_' . $action ] = ! empty( $new_instance[ 'action_' . $action ] ) ? 1 : 0;

        return $instance;

    }

	public function form( $instance ) {
		
		// get registered actions
		$actions = wpsight_get_listing_actions();
		
		if( is_array( $actions ) ) { ?>
			<p><?php _e( 'Please select the listing actions that you want to be displayed along the title.', 'wpcasa-madrid' ); ?></p><?php			
		} else { ?>		
			<p><?php _e( 'Currently there are no listing actions to be displayed along the title.', 'wpcasa-madrid' ); ?></p><?php		
		}
		
		// Loop through actions and display checkbox
			
		foreach( $actions as $action => $v ) {
			
			$checked = isset( $instance[ 'action_' . $action ] ) ? (bool) $instance[ 'action_' . $action ] : false; ?>

            <p>
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'action_' . $action ); ?>" name="<?php echo $this->get_field_name( 'action_' . $action ); ?>"<?php checked( $checked ); ?> />
                <label for="<?php echo $this->get_field_id( 'action_' . $action ); ?>"><?php echo ucfirst( $action ); ?></label>		
            </p><?php

	    }

	}

}
