<?php
/**
 * Listings Carousel widget
 *
 * @package WPCasa Madrid
 */

/**
 * Register widget
 */
 
add_action( 'widgets_init', 'wpsight_madrid_register_widget_listings_carousel' );
 
function wpsight_madrid_register_widget_listings_carousel() {
	register_widget( 'WPSight_Madrid_Listings_Carousel' );
}

/**
 * Listings carousel widget class
 *
 * @since 1.0.0
 */

class WPSight_Madrid_Listings_Carousel extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listings_carousel',
			'description' => _x( 'Display listings in a carousel.', 'listing wigdet', 'wpcasa-madrid' )
		);

		parent::__construct( 'wpsight_madrid_Listings_Carousel', '&rsaquo; ' . _x( 'Listings Carousel', 'listing widget', 'wpcasa-madrid' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		
		$title 				= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$nr 				= isset( $instance['nr'] ) ? absint( $instance['nr'] ) : '';

		$offer_filter		= isset( $instance['offer_filter'] ) ? strip_tags( $instance['offer_filter'] ) : false;
		$taxonomy_filters	= array();
		
		foreach( get_object_taxonomies( wpsight_post_type(), 'objects' ) as $key => $taxonomy )
			$taxonomy_filters[ $key ] = isset( $instance[ 'taxonomy_filter_' . $key ] ) ? strip_tags( $instance[ 'taxonomy_filter_' . $key ] ) : false;
		
		$defaults = array(
			'nr'						=> 10,
			'carousel_items' 			=> 3,
			'carousel_slide_by'			=> 1,
			'carousel_margin'			=> 40,
			'carousel_loop'				=> false,
			'carousel_nav'				=> true,
			'carousel_dots'				=> true,
			'carousel_autoplay'			=> false,
			'carousel_autoplay_time'	=> 5000
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		// Carousel settings
		
		$carousel_items 		= absint( $instance['carousel_items'] );
		$carousel_slide_by 		= absint( $instance['carousel_slide_by'] );
		$carousel_margin 		= absint( $instance['carousel_margin'] );
		$carousel_loop 			= isset( $instance['carousel_loop'] ) ? (bool) $instance['carousel_loop'] : false;
		$carousel_nav 			= isset( $instance['carousel_nav'] ) ? (bool) $instance['carousel_nav'] : false;
		$carousel_dots 			= isset( $instance['carousel_dots'] ) ? (bool) $instance['carousel_dots'] : false;
		$carousel_autoplay 		= isset( $instance['carousel_autoplay'] ) ? (bool) $instance['carousel_autoplay'] : false;
		$carousel_autoplay_time = absint( $instance['carousel_autoplay_time'] );
		
		// Set up args for carousel

		$carousel_args = array(
			'class_carousel'   			=> 'wpsight-listings-carousel-' . $this->id . ' wpsight-listings-carousel',
			'carousel_items'			=> $carousel_items,
			'carousel_slide_by'			=> $carousel_slide_by,
			'carousel_margin'			=> $carousel_margin,
			'carousel_loop'				=> $carousel_loop,
			'carousel_nav'				=> $carousel_nav,
			'carousel_dots'				=> $carousel_dots,
			'carousel_autoplay'			=> $carousel_autoplay,
			'carousel_autoplay_time'	=> $carousel_autoplay_time
		);
		
		$listings_args = array(
			'nr'				=> $nr,
			'offer'				=> $offer_filter,
			'meta_query'		=> array(
				array(
					'key'		=> '_thumbnail_id',
					'compare'	=> 'EXISTS'
				)
			),
			'show_paging'		=> false
		);
		
		// Merge taxonomy filters into args and apply filter hook
		$listings_args = apply_filters( 'wpsight_madrid_widget_listings_carousel_query_args', array_merge( $listings_args, $taxonomy_filters ), $instance, $this->id );
		
		// Finally get listings
		$listings = wpsight_get_listings( $listings_args );
		
		// When no listings, don't any produce output
		
		if( ! $listings )
			return;
		
		// Echo before_widget
        echo $args['before_widget'];
        
        if ( $title )
			echo $args['before_title'] . $title . $args['after_title'];
        
        // Echo listings carousel
		wpsight_madrid_listings_carousel( $listings, $carousel_args );
		
		// Echo after_widget
		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;
	    
	    $instance['title'] 					= strip_tags( $new_instance['title'] );
	    $instance['nr'] 					= absint( $new_instance['nr'] );
	    $instance['offer_filter'] 			= strip_tags( $new_instance['offer_filter'] );
	    
	    foreach( get_object_taxonomies( wpsight_post_type(), 'objects' ) as $key => $taxonomy )
			$instance[ 'taxonomy_filter_' . $key ] = strip_tags( $new_instance[ 'taxonomy_filter_' . $key ] );
	    
	    // Carousel settings
	    
	    $instance['carousel_items'] 		= absint( $new_instance['carousel_items'] );
	    $instance['carousel_slide_by'] 		= absint( $new_instance['carousel_slide_by'] );
	    $instance['carousel_margin'] 		= absint( $new_instance['carousel_margin'] );
	    $instance['carousel_loop'] 			= ! empty( $new_instance['carousel_loop'] ) ? 1 : 0;
	    $instance['carousel_nav'] 			= ! empty( $new_instance['carousel_nav'] ) ? 1 : 0;
	    $instance['carousel_dots'] 			= ! empty( $new_instance['carousel_dots'] ) ? 1 : 0;
	    $instance['carousel_autoplay'] 		= ! empty( $new_instance['carousel_autoplay'] ) ? 1 : 0;
	    $instance['carousel_autoplay_time'] = absint( $new_instance['carousel_autoplay_time'] );

        return $instance;

    }

	public function form( $instance ) {
		
		$defaults = array(
			'title' 					=> '',
			'nr'						=> 10,
			'carousel_items' 			=> 3,
			'carousel_slide_by'			=> 1,
			'carousel_margin'			=> 40,
			'carousel_loop'				=> false,
			'carousel_nav'				=> true,
			'carousel_dots'				=> true,
			'carousel_autoplay'			=> false,
			'carousel_autoplay_time'	=> 5000
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title 				= strip_tags( $instance['title'] );
		$nr 				= absint( $instance['nr'] );
		$offer_filter		= isset( $instance['offer_filter'] ) ? strip_tags( $instance['offer_filter'] ) : false;
		
		$taxonomy_filters	= array();
		
		foreach( get_object_taxonomies( wpsight_post_type(), 'objects' ) as $key => $taxonomy )
			$taxonomy_filters[ $key ] = isset( $instance[ 'taxonomy_filter_' . $key ] ) ? strip_tags( $instance[ 'taxonomy_filter_' . $key ] ) : false;
		
		// Slider settings
		
		$carousel_items 		= absint( $instance['carousel_items'] );
		$carousel_slide_by 		= absint( $instance['carousel_slide_by'] );
		$carousel_margin 		= absint( $instance['carousel_margin'] );
		$carousel_loop 			= isset( $instance['carousel_loop'] ) ? (bool) $instance['carousel_loop'] : false;
		$carousel_nav 			= isset( $instance['carousel_nav'] ) ? (bool) $instance['carousel_nav'] : false;
		$carousel_dots 			= isset( $instance['carousel_dots'] ) ? (bool) $instance['carousel_dots'] : false;
		$carousel_autoplay 		= isset( $instance['carousel_autoplay'] ) ? (bool) $instance['carousel_autoplay'] : false;
		$carousel_autoplay_time = absint( $instance['carousel_autoplay_time'] ); ?>
		
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
		
		<p><label for="<?php echo $this->get_field_id( 'carousel_items' ); ?>"><?php _e( 'Items', 'wpcasa-madrid' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'carousel_items' ); ?>" name="<?php echo $this->get_field_name( 'carousel_items' ); ?>">
				<option value="1"<?php selected( $carousel_items, 1 ); ?>>1 <?php _ex( 'item', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="2"<?php selected( $carousel_items, 2 ); ?>>2 <?php _ex( 'items', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="3"<?php selected( $carousel_items, 3 ); ?>>3 <?php _ex( 'items', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="4"<?php selected( $carousel_items, 4 ); ?>>4 <?php _ex( 'items', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="5"<?php selected( $carousel_items, 5 ); ?>>5 <?php _ex( 'items', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="6"<?php selected( $carousel_items, 6 ); ?>>6 <?php _ex( 'items', 'listing widget', 'wpcasa-madrid' ); ?></option>
			</select><br />
			<span class="description"><?php _e( 'Please select the number of visible carousel items', 'wpcasa-madrid' ); ?></span></p>
		
		<div<?php if( $carousel_items == 1 ) echo ' style="display:none"'; ?>>
			
			<p><label for="<?php echo $this->get_field_id( 'carousel_slide_by' ); ?>"><?php _e( 'Slide by', 'wpcasa-madrid' ); ?>:</label><br />
				<select class="widefat" id="<?php echo $this->get_field_id( 'carousel_slide_by' ); ?>" name="<?php echo $this->get_field_name( 'carousel_slide_by' ); ?>">
					<option value="1"<?php selected( $carousel_slide_by, 1 ); ?>>1 <?php _ex( 'item', 'listing widget', 'wpcasa-madrid' ); ?></option>
					<option value="2"<?php selected( $carousel_slide_by, 2 ); if( $carousel_items < 2 ) echo ' style="display:none"'; ?>>2 <?php _ex( 'items', 'listing widget', 'wpcasa-madrid' ); ?></option>
					<option value="3"<?php selected( $carousel_slide_by, 3 ); if( $carousel_items < 3 ) echo ' style="display:none"'; ?>>3 <?php _ex( 'items', 'listing widget', 'wpcasa-madrid' ); ?></option>
					<option value="4"<?php selected( $carousel_slide_by, 4 ); if( $carousel_items < 4 ) echo ' style="display:none"'; ?>>4 <?php _ex( 'items', 'listing widget', 'wpcasa-madrid' ); ?></option>
					<option value="5"<?php selected( $carousel_slide_by, 5 ); if( $carousel_items < 5 ) echo ' style="display:none"'; ?>>5 <?php _ex( 'items', 'listing widget', 'wpcasa-madrid' ); ?></option>
					<option value="6"<?php selected( $carousel_slide_by, 6 ); if( $carousel_items < 6 ) echo ' style="display:none"'; ?>>6 <?php _ex( 'items', 'listing widget', 'wpcasa-madrid' ); ?></option>
				</select><br />
				<span class="description"><?php _e( 'Please select the number items to slide', 'wpcasa-madrid' ); ?></span></p>
		
		</div>
		
		<p><label for="<?php echo $this->get_field_id( 'carousel_margin' ); ?>"><?php _e( 'Margin', 'wpcasa-madrid' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'carousel_margin' ); ?>" name="<?php echo $this->get_field_name( 'carousel_margin' ); ?>">
				<option value="0"<?php selected( $carousel_margin, 0 ); ?>>0px</option>
				<option value="10"<?php selected( $carousel_margin, 10 ); ?>>10px</option>
				<option value="20"<?php selected( $carousel_margin, 20 ); ?>>20px</option>
				<option value="30"<?php selected( $carousel_margin, 30 ); ?>>30px</option>
				<option value="40"<?php selected( $carousel_margin, 40 ); ?>>40px</option>
				<option value="50"<?php selected( $carousel_margin, 50 ); ?>>50px</option>
				<option value="60"<?php selected( $carousel_margin, 60 ); ?>>60px</option>
			</select><br />
			<span class="description"><?php _e( 'Please select the space between carousel items', 'wpcasa-madrid' ); ?></span></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'carousel_loop' ); ?>" name="<?php echo $this->get_field_name( 'carousel_loop' ); ?>"<?php checked( $carousel_loop ); ?> />
				<label for="<?php echo $this->get_field_id( 'carousel_loop' ); ?>"><?php _e( 'Loop carousel to be infinite', 'wpcasa-madrid' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'carousel_nav' ); ?>" name="<?php echo $this->get_field_name( 'carousel_nav' ); ?>"<?php checked( $carousel_nav ); ?> />
				<label for="<?php echo $this->get_field_id( 'carousel_nav' ); ?>"><?php _e( 'Show prev/next carousel navigation', 'wpcasa-madrid' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'carousel_dots' ); ?>" name="<?php echo $this->get_field_name( 'carousel_dots' ); ?>"<?php checked( $carousel_dots ); ?> />
				<label for="<?php echo $this->get_field_id( 'carousel_dots' ); ?>"><?php _e( 'Show <em>dots</em> carousel navigation', 'wpcasa-madrid' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'carousel_autoplay' ); ?>" name="<?php echo $this->get_field_name( 'carousel_autoplay' ); ?>"<?php checked( $carousel_autoplay ); ?> />
				<label for="<?php echo $this->get_field_id( 'carousel_autoplay' ); ?>"><?php _e( 'Slide through items automatically', 'wpcasa-madrid' ); ?></label></p>
		
		<div<?php if( ! $carousel_autoplay ) echo ' style="display:none"'; ?>>
		
			<p><label for="<?php echo $this->get_field_id( 'carousel_autoplay_time' ); ?>"><?php _e( 'Autoplay interval', 'wpcasa-madrid' ); ?>:</label><br />
				<select class="widefat" id="<?php echo $this->get_field_id( 'carousel_autoplay_time' ); ?>" name="<?php echo $this->get_field_name( 'carousel_autoplay_time' ); ?>">
					<option value="2000"<?php selected( $carousel_autoplay_time, 2000 ); ?>>2 <?php _ex( 'seconds', 'listing widget', 'wpcasa-madrid' ); ?></option>
					<option value="4000"<?php selected( $carousel_autoplay_time, 4000 ); ?>>4 <?php _ex( 'seconds', 'listing widget', 'wpcasa-madrid' ); ?></option>
					<option value="6000"<?php selected( $carousel_autoplay_time, 6000 ); ?>>6 <?php _ex( 'seconds', 'listing widget', 'wpcasa-madrid' ); ?></option>
					<option value="8000"<?php selected( $carousel_autoplay_time, 8000 ); ?>>8 <?php _ex( 'seconds', 'listing widget', 'wpcasa-madrid' ); ?></option>
					<option value="10000"<?php selected( $carousel_autoplay_time, 10000 ); ?>>10 <?php _ex( 'seconds', 'listing widget', 'wpcasa-madrid' ); ?></option>
				</select><br />
				<span class="description"><?php _e( 'Please select the autoplay interval timeout', 'wpcasa-madrid' ); ?></span></p>
		
		</div><?php

	}

}