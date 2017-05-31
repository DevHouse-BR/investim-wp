<?php
/**
 * Listings Search widget
 *
 * @package WPCasa Madrid
 */

/**
 * Register widget
 */
add_action( 'widgets_init', 'wpsight_madrid_register_widget_listings_search' );
 
function wpsight_madrid_register_widget_listings_search() {
	register_widget( 'WPSight_Madrid_Listings_Search' );
}

/**
 * Listing image widget class
 */
class WPSight_Madrid_Listings_Search extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listings_search',
			'description' => _x( 'Display the listings search form.', 'listing wigdet', 'wpcasa-madrid' )
		);

		parent::__construct( 'wpsight_madrid_listings_search', '&rsaquo; ' . _x( 'Listings Search', 'listing widget', 'wpcasa-madrid' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		
		$defaults = array(
			'orientation' => 'horizontal'
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$instance['orientation'] = isset( $instance['orientation'] ) && in_array( $instance['orientation'], array( 'horizontal', 'vertical' ) ) ? $instance['orientation'] : 'horizontal';

		// Echo before_widget
        echo $args['before_widget'];
        
        if ( $title )
			echo $args['before_title'] . $title . $args['after_title'];
			
		// Display listings search
		wpsight_search( $instance );
		
		// Echo after_widget
		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;
	    
	    $instance['title']			= strip_tags( $new_instance['title'] );
	    $instance['orientation']	= strip_tags( $new_instance['orientation'] );

        return $instance;

    }

	public function form( $instance ) {
		
		$defaults = array(
			'title'			=> '',
			'orientation' 	=> 'horizontal',
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title 				= strip_tags( $instance['title'] );
		$orientation		= isset( $instance['orientation'] ) ? strip_tags( $instance['orientation'] ) : 'horizontal'; ?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpcasa-madrid' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'orientation' ); ?>"><?php _e( 'Orientation', 'wpcasa-madrid' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'orientation' ); ?>" name="<?php echo $this->get_field_name( 'orientation' ); ?>">
				<option value="horizontal"<?php selected( $orientation, 'horizontal' ); ?>><?php _ex( 'horizontal', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="vertical"<?php selected( $orientation, 'vertical' ); ?>><?php _ex( 'vertical', 'listing widget', 'wpcasa-madrid' ); ?></option>
			</select><br />
			<span class="description"><?php _e( 'Please select the orientation', 'wpcasa-madrid' ); ?></span></p><?php

	}

}
