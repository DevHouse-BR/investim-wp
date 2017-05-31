<?php
/**
 * Listings Query widget
 *
 * @package WPCasa Madrid
 */

/**
 * Register widget
 */
 
add_action( 'widgets_init', 'wpsight_madrid_register_widget_listings_query' );
 
function wpsight_madrid_register_widget_listings_query() {
	register_widget( 'WPSight_Madrid_Listings_Query' );
}

/**
 * Listings query widget class
 *
 * @since 1.0.0
 */

class WPSight_Madrid_Listings_Query extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listings_query',
			'description' => _x( 'Display a certain listings query.', 'listing wigdet', 'wpcasa-madrid' )
		);

		parent::__construct( 'wpsight_madrid_Listings_Query', '&rsaquo; ' . _x( 'Listings Query', 'listing widget', 'wpcasa-madrid' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		
		$title 				= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$nr 				= absint( $instance['nr'] );

		$offer_filter		= isset( $instance['offer_filter'] ) ? strip_tags( $instance['offer_filter'] ) : false;
		$taxonomy_filters	= array();
		
		foreach( get_object_taxonomies( wpsight_post_type(), 'objects' ) as $key => $taxonomy )
			$taxonomy_filters[ $key ] = isset( $instance[ 'taxonomy_filter_' . $key ] ) ? strip_tags( $instance[ 'taxonomy_filter_' . $key ] ) : false;
		
		$defaults = array(
			'nr'		=> 3,
			'teasers'	=> false
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$teasers = isset( $instance['teasers'] ) ? (bool) $instance['teasers'] : false;
		
		// Set up args for listing query
		
		$listings_args = array(
			'nr'				=> $nr,
			'offer'				=> $offer_filter,
			'meta_query'		=> array(
				array(
					'key'		=> '_thumbnail_id',
					'compare'	=> 'EXISTS'
				)
			),
			'show_panel'		=> false,
			'show_paging'		=> false,
			'context'			=> $args['id']
		);
		
		// Merge taxonomy filters into args and apply filter hook
		$listings_args = apply_filters( 'wpsight_madrid_widget_listings_query_query_args', array_merge( $listings_args, $taxonomy_filters ), $instance, $this->id );
		
		// Finally get listings
		$listings = wpsight_get_listings( $listings_args );
		
		// When no listings, don't any produce output
		
		if( ! $listings )
			return;
		
		// Echo before_widget
        echo $args['before_widget'];
        
        if ( $title )
			echo $args['before_title'] . $title . $args['after_title'];
		
		if( ! $teasers ) {
        
        	// Echo listings query
			wpsight_listings( $listings_args );
		
		} else {
			
			// Echo listings query
			wpsight_listing_teasers( $listings_args );
			
		}
		
		// Echo after_widget
		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;
	    
	    $instance['title'] 			= strip_tags( $new_instance['title'] );
	    $instance['nr'] 			= absint( $new_instance['nr'] );
	    $instance['offer_filter'] 	= strip_tags( $new_instance['offer_filter'] );
	    
	    foreach( get_object_taxonomies( wpsight_post_type(), 'objects' ) as $key => $taxonomy )
			$instance[ 'taxonomy_filter_' . $key ] = strip_tags( $new_instance[ 'taxonomy_filter_' . $key ] );
		
		$instance['teasers']		= ! empty( $new_instance['teasers'] ) ? 1 : 0;

        return $instance;

    }

	public function form( $instance ) {
		
		$defaults = array(
			'title'	=> '',
			'nr'   	=> 3
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title 				= strip_tags( $instance['title'] );
		$nr 				= absint( $instance['nr'] );
		$offer_filter		= isset( $instance['offer_filter'] ) ? strip_tags( $instance['offer_filter'] ) : false;
		
		$taxonomy_filters	= array();
		
		foreach( get_object_taxonomies( wpsight_post_type(), 'objects' ) as $key => $taxonomy )
			$taxonomy_filters[ $key ] = isset( $instance[ 'taxonomy_filter_' . $key ] ) ? strip_tags( $instance[ 'taxonomy_filter_' . $key ] ) : false;
			
		$teasers			= isset( $instance['teasers'] ) ? (bool) $instance['teasers'] : false; ?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpcasa-madrid' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'nr' ); ?>"><?php _e( 'Listings', 'wpcasa-madrid' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'nr' ); ?>" name="<?php echo $this->get_field_name( 'nr' ); ?>" type="text" value="<?php echo esc_attr( $nr ); ?>" /></label><br />
			<span class="description"><?php _e( 'Please enter the number of listings', 'wpcasa-madrid' ); ?></span></p>
		
		<p><label><?php _e( 'Filters', 'wpcasa-madrid' ); ?>:</label></p>
		
		<p><select class="widefat" id="<?php echo $this->get_field_id( 'offer_filter' ); ?>" name="<?php echo $this->get_field_name( 'offer_filter' ); ?>">
				<option value=""<?php selected( $offer_filter, '' ); ?>><?php _e( 'All Offers', 'wpcasa-madrid' ); ?></option>
				<?php foreach( wpsight_offers() as $key => $offer ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $offer_filter, $key ); ?>><?php echo esc_attr( $offer ); ?></option>
				<?php endforeach; ?>
			</select></p>
		
		<?php foreach( get_object_taxonomies( wpsight_post_type(), 'objects' ) as $key => $taxonomy ) : ?>
		
			<p><select class="widefat" id="<?php echo $this->get_field_id( 'taxonomy_filter_' . $key ); ?>" name="<?php echo $this->get_field_name( 'taxonomy_filter_' . $key ); ?>">
				<option value=""<?php selected( 'taxonomy_filter_' . $key, '' ); ?>><?php printf( __( 'All %s', 'wpcasa-madrid' ), esc_attr( $taxonomy->label ) ); ?></option>
				<?php
			    	// Add taxonomy term options
			    	$terms = get_terms( array( $key ), array( 'hide_empty' => 0 ) );
			    	foreach( $terms as $term ) {
			    	   echo '<option value="' . esc_attr( $term->slug ) . '"' . selected( $taxonomy_filters[ $key ], $term->slug ) . '>' . esc_attr( $term->name ) . '</option>';
			    	}
			    ?>
				</select></p>
		
		<?php endforeach; ?>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'teasers' ); ?>" name="<?php echo $this->get_field_name( 'teasers' ); ?>"<?php checked( $teasers ); ?> />
				<label for="<?php echo $this->get_field_id( 'teasers' ); ?>"><?php _e( 'Display smaller listing teasers', 'wpcasa-madrid' ); ?></label></p>
		
		<?php

	}

}