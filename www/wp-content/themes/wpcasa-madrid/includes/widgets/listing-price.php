<?php
/**
 * Listing Price widget
 *
 * @package WPCasa Madrid
 */

/**
 * Register widget
 */
add_action( 'widgets_init', 'wpsight_madrid_register_widget_listing_price' );
 
function wpsight_madrid_register_widget_listing_price() {
	register_widget( 'WPSight_Madrid_Listing_Price' );
}

/**
 * Listing price widget class
 */
class WPSight_Madrid_Listing_Price extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listing_price',
			'description' => _x( 'Display info bar with price, ID and offer badge (sale, rent etc.) on single listing pages.', 'listing wigdet', 'wpcasa-madrid' )
		);

		parent::__construct( 'wpsight_madrid_listing_price', '&rsaquo; ' . _x( 'Single Listing Price', 'listing widget', 'wpcasa-madrid' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		global $listing;
	
		$listing = wpsight_get_listing( get_the_id() );
        
        $title			= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $display_id 	= isset( $instance['display_id'] ) ? (bool) $instance['display_id'] : true;
		$display_offer	= isset( $instance['display_offer'] ) ? (bool) $instance['display_offer'] : true;

		// Echo before_widget
        echo $args['before_widget'];
        
        if( ! is_singular( wpsight_post_type() ) ) {		
			// Display notice when not on single listing page
			wpsight_get_template( 'listing-single-widget-notice.php', array( 'widget_args' => $args, 'widget_instance' => $instance ) );		
		} else {        
        	
        	if ( $title )
				echo $args['before_title'] . $title . $args['after_title'];
			
			// Display listing info template
			wpsight_get_template( 'listing-single-info.php', array( 'widget_args' => $args, 'widget_instance' => $instance ) );
        	
        }
		
		// Echo after_widget
		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;
	    
	    $instance['title']			= strip_tags( $new_instance['title'] );
	    $instance['display_id'] 	= ! empty( $new_instance['display_id'] ) ? 1 : 0;
	    $instance['display_offer'] 	= ! empty( $new_instance['display_offer'] ) ? 1 : 0;

        return $instance;

    }

	public function form( $instance ) {
		
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );

		$title 			= $instance['title'];
		$display_id 	= isset( $instance['display_id'] ) ? (bool) $instance['display_id'] : true;
		$display_offer	= isset( $instance['display_offer'] ) ? (bool) $instance['display_offer'] : true; ?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpcasa-madrid' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'display_id' ); ?>" name="<?php echo $this->get_field_name( 'display_id' ); ?>"<?php checked( $display_id ); ?> />
			<label for="<?php echo $this->get_field_id( 'display_id' ); ?>"><?php _e( 'Display listing ID', 'wpcasa-madrid' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'display_offer' ); ?>" name="<?php echo $this->get_field_name( 'display_offer' ); ?>"<?php checked( $display_offer ); ?> />
			<label for="<?php echo $this->get_field_id( 'display_offer' ); ?>"><?php _e( 'Display listing offer (sale/rent etc.)', 'wpcasa-madrid' ); ?></label></p><?php

	}

}
