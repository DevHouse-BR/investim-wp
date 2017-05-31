<?php
/**
 * Listing Terms widget
 *
 * @package WPCasa Madrid
 */

/**
 * Register widget
 */
add_action( 'widgets_init', 'wpsight_madrid_register_widget_listing_terms' );
 
function wpsight_madrid_register_widget_listing_terms() {
	register_widget( 'WPSight_Madrid_Listing_Terms' );
}

/**
 * Listing terms widget class
 */
class WPSight_Madrid_Listing_Terms extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listing_terms',
			'description' => _x( 'Display listing taxonomy terms on single listing pages.', 'listing wigdet', 'wpsight-stage' )
		);

		parent::__construct( 'wpsight_madrid_listing_terms', '&rsaquo; ' . _x( 'Single Listing Terms', 'listing widget', 'wpsight-stage' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		global $listing;
	
		$listing = wpsight_get_listing( get_the_id() );
		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		
		$taxonomy = isset( $instance['taxonomy'] ) ? $instance['taxonomy'] : 'feature';
		
		$terms = wpsight_get_listing_terms( $taxonomy, get_the_id() );
		
		if( empty( $terms ) )
			return;

		// Echo before_widget
        echo $args['before_widget'];
        
        if( ! is_singular( wpsight_post_type() ) ) {		
			// Display notice when not on single listing page
			wpsight_get_template( 'listing-single-widget-notice.php', array( 'widget_args' => $args, 'widget_instance' => $instance ) );		
		} else {
        	
        	if ( $title )
				echo $args['before_title'] . $title . $args['after_title'];
			
			// Display listing features template
			wpsight_get_template( 'listing-single-features.php', array( 'widget_args' => $args, 'widget_instance' => $instance ) );
        	
        }
		
		// Echo after_widget
		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;
	    
	    $instance['title'] 		 = strip_tags( $new_instance['title'] );
	    $instance['taxonomy'] 	 = in_array( $new_instance['taxonomy'], get_object_taxonomies( wpsight_post_type() ) ) ? $new_instance['taxonomy'] : 'feature';
	    $instance['linked'] 	 = ! empty( $new_instance['linked'] ) ? 1 : 0;

        return $instance;

    }

	public function form( $instance ) {
		
		$defaults = array(
			'title' 		=> '',
			'taxonomy' 		=> 'feature',
			'linked'		=> true
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title 		 = strip_tags( $instance['title'] );
		$taxonomy 	 = in_array( $instance['taxonomy'], get_object_taxonomies( wpsight_post_type() ) ) ? $instance['taxonomy'] : 'feature';
		$linked		 = ! empty( $instance['linked'] ) ? true : false; ?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpsight-stage' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'taxonomy' ); ?>"><?php _e( 'Taxonomy', 'wpsight-stage' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'taxonomy' ); ?>" name="<?php echo $this->get_field_name( 'taxonomy' ); ?>">			
				<?php foreach( get_object_taxonomies( wpsight_post_type(), 'objects' ) as $key => $tax ) : ?>
				<option value="<?php echo strip_tags( $key ); ?>"<?php selected( $taxonomy, $key ); ?>><?php echo strip_tags( $tax->label ); ?></option>
				<?php endforeach; ?>
			</select><br />
			<span class="description"><?php _e( 'Please select the term taxonomy', 'wpsight-stage' ); ?></span></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'linked' ); ?>" name="<?php echo $this->get_field_name( 'linked' ); ?>"<?php checked( $linked ); ?> />
			<label for="<?php echo $this->get_field_id( 'linked' ); ?>"><?php _e( 'Link terms to archive pages', 'wpsight-stage' ); ?></label></p><?php

	}

}
