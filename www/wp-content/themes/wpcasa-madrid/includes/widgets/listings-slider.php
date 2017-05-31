<?php
/**
 * Listings Carousel widget
 *
 * @package WPCasa Madrid
 */

/**
 * Register widget
 */
 
add_action( 'widgets_init', 'wpsight_madrid_register_widget_listings_slider' );
 
function wpsight_madrid_register_widget_listings_slider() {
	register_widget( 'WPSight_Madrid_Listings_Slider' );
}

/**
 * Listings slider widget class
 *
 * @since 1.0.0
 */

class WPSight_Madrid_Listings_Slider extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listings_slider',
			'description' => _x( 'Display listings in a slider.', 'listing wigdet', 'wpcasa-madrid' )
		);

		parent::__construct( 'wpsight_madrid_Listings_Slider', '&rsaquo; ' . _x( 'Listings Slider', 'listing widget', 'wpcasa-madrid' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		
		$title 				= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$nr 				= absint( $instance['nr'] );

		$offer_filter		= isset( $instance['offer_filter'] ) ? strip_tags( $instance['offer_filter'] ) : false;
		$taxonomy_filters	= array();
		
		foreach( get_object_taxonomies( wpsight_post_type(), 'objects' ) as $key => $taxonomy )
			$taxonomy_filters[ $key ] = isset( $instance[ 'taxonomy_filter_' . $key ] ) ? strip_tags( $instance[ 'taxonomy_filter_' . $key ] ) : false;
		
		$defaults = array(
			'nr'						=> 10,
			'image_size' 				=> 'full',
			'image_caption' 			=> false,
			'slider_animation'			=> 'slide',
			'slider_loop'				=> true,
			'slider_autoplay'			=> false,
			'slider_autoplay_time'		=> 6000,
			'slider_dots'				=> true,
			'slider_dots_thumbs'		=> false,
			'slider_dots_thumbs_items'	=> 4,
			'slider_dots_thumbs_margin'	=> 10,
			'slider_nav'				=> true,
			'slider_keyboard'			=> true
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		// Image settings

		$image_size					= strip_tags( $instance['image_size'] );
		
		// Slider settings
		
		$slider_animation			= strip_tags( $instance['slider_animation'] );
		$slider_loop 				= isset( $instance['slider_loop'] ) ? (bool) $instance['slider_loop'] : false;
		$slider_autoplay 			= isset( $instance['slider_autoplay'] ) ? (bool) $instance['slider_autoplay'] : false;
		$slider_autoplay_time 		= absint( $instance['slider_autoplay_time'] );
		$slider_dots 				= isset( $instance['slider_dots'] ) ? (bool) $instance['slider_dots'] : true;
		$slider_dots_thumbs			= isset( $instance['slider_dots_thumbs'] ) ? (bool) $instance['slider_dots_thumbs'] : false;
		$slider_nav 				= isset( $instance['slider_nav'] ) ? (bool) $instance['slider_nav'] : true;
		$slider_keyboard			= isset( $instance['slider_keyboard'] ) ? (bool) $instance['slider_keyboard'] : true;
		
		// Set up args for slider

		$slider_args = array(
			'class_slider'    			=> 'wpsight-listings-slider-' . get_the_id() . ' wpsight-listings-slider',
			'image_size' 	   			=> $image_size,
			'slider_animation'			=> $slider_animation,
			'slider_loop'				=> $slider_loop,
			'slider_autoplay'			=> $slider_autoplay,
			'slider_autoplay_time'		=> $slider_autoplay_time,
			'slider_dots'				=> $slider_dots,
			'slider_dots_thumbs'		=> $slider_dots_thumbs,
			'slider_nav'				=> $slider_nav,
			'slider_keyboard'			=> $slider_keyboard
		);
		
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
			'show_paging'		=> false
		);
		
		// Merge taxonomy filters into args and apply filter hook
		$listings_args = apply_filters( 'wpsight_madrid_widget_listings_slider_query_args', array_merge( $listings_args, $taxonomy_filters ), $instance, $this->id );
		
		// Finally get listings
		$listings = wpsight_get_listings( $listings_args );
		
		// When no listings, don't any produce output
		
		if( ! $listings )
			return;
		
		// Echo before_widget
        echo $args['before_widget'];
        
        if ( $title )
			echo $args['before_title'] . $title . $args['after_title'];
        
        // Echo listings slider
		wpsight_madrid_listings_slider( $listings, $slider_args );
		
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
	    
	    // Image settings
	    
	    $instance['image_size'] 				= strip_tags( $new_instance['image_size'] );
	    
	    // Slider settings
	    
	    $instance['slider_animation'] 			= strip_tags( $new_instance['slider_animation'] );
	    $instance['slider_loop'] 				= ! empty( $new_instance['slider_loop'] ) ? 1 : 0;
	    $instance['slider_autoplay'] 			= ! empty( $new_instance['slider_autoplay'] ) ? 1 : 0;
	    $instance['slider_autoplay_time'] 		= absint( $new_instance['slider_autoplay_time'] );
	    $instance['slider_dots'] 				= ! empty( $new_instance['slider_dots'] ) ? 1 : 0;
	    $instance['slider_dots_thumbs']			= ! empty( $new_instance['slider_dots_thumbs'] ) ? 1 : 0;
	    $instance['slider_nav'] 				= ! empty( $new_instance['slider_nav'] ) ? 1 : 0;
	    $instance['slider_keyboard'] 			= ! empty( $new_instance['slider_keyboard'] ) ? 1 : 0;

        return $instance;

    }

	public function form( $instance ) {
		
		$defaults = array(
			'title' 					=> '',
			'nr'						=> 10,
			'image_size' 				=> 'full',
			'slider_animation'			=> 'slide',
			'slider_loop'				=> true,
			'slider_autoplay'			=> false,
			'slider_autoplay_time'		=> 6000,
			'slider_dots'				=> true,
			'slider_dots_thumbs'		=> false,
			'slider_nav'				=> true,
			'slider_keyboard'			=> true
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title 				= strip_tags( $instance['title'] );
		$nr 				= absint( $instance['nr'] );
		$offer_filter		= isset( $instance['offer_filter'] ) ? strip_tags( $instance['offer_filter'] ) : false;
		
		$taxonomy_filters	= array();
		
		foreach( get_object_taxonomies( wpsight_post_type(), 'objects' ) as $key => $taxonomy )
			$taxonomy_filters[ $key ] = isset( $instance[ 'taxonomy_filter_' . $key ] ) ? strip_tags( $instance[ 'taxonomy_filter_' . $key ] ) : false;
		
		// Image settings

		$image_size					= strip_tags( $instance['image_size'] );
		
		// Slider settings
		
		$slider_animation			= strip_tags( $instance['slider_animation'] );
		$slider_loop 				= isset( $instance['slider_loop'] ) ? (bool) $instance['slider_loop'] : false;
		$slider_autoplay 			= isset( $instance['slider_autoplay'] ) ? (bool) $instance['slider_autoplay'] : false;
		$slider_autoplay_time 		= absint( $instance['slider_autoplay_time'] );		
		$slider_dots 				= isset( $instance['slider_dots'] ) ? (bool) $instance['slider_dots'] : true;
		$slider_dots_thumbs			= isset( $instance['slider_dots_thumbs'] ) ? (bool) $instance['slider_dots_thumbs'] : true;
		$slider_nav 				= isset( $instance['slider_nav'] ) ? (bool) $instance['slider_nav'] : true;
		$slider_keyboard			= isset( $instance['slider_keyboard'] ) ? (bool) $instance['slider_keyboard'] : true; ?>
		
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
		
		<p><label for="<?php echo $this->get_field_id( 'image_size' ); ?>"><?php _e( 'Image Size', 'wpcasa-madrid' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'image_size' ); ?>" name="<?php echo $this->get_field_name( 'image_size' ); ?>">			
				<?php foreach( get_intermediate_image_sizes() as $size ) : ?>
				<option value="<?php echo strip_tags( $size ); ?>"<?php selected( $image_size, $size ); ?>><?php echo strip_tags( $size ); ?></option>
				<?php endforeach; ?>
				<option value="full"<?php selected( $image_size, 'full' ); ?>>full (original)</option>
			</select></p>
			
		<p><label for="<?php echo $this->get_field_id( 'slider_animation' ); ?>"><?php _e( 'Animation', 'wpcasa-madrid' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'slider_animation' ); ?>" name="<?php echo $this->get_field_name( 'slider_animation' ); ?>">
				<option value="fade"<?php selected( $slider_animation, 'fade' ); ?>><?php _ex( 'fade', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="slide"<?php selected( $slider_animation, 'slide' ); ?>><?php _ex( 'slide', 'listing widget', 'wpcasa-madrid' ); ?></option>
			</select></p>
			
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'slider_loop' ); ?>" name="<?php echo $this->get_field_name( 'slider_loop' ); ?>"<?php checked( $slider_loop ); ?> />
				<label for="<?php echo $this->get_field_id( 'slider_loop' ); ?>"><?php _e( 'Loop slider to be infinite', 'wpcasa-madrid' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'slider_autoplay' ); ?>" name="<?php echo $this->get_field_name( 'slider_autoplay' ); ?>"<?php checked( $slider_autoplay ); ?> />
				<label for="<?php echo $this->get_field_id( 'slider_autoplay' ); ?>"><?php _e( 'Animate slider automatically', 'wpcasa-madrid' ); ?></label></p>
			
		<div<?php if( ! $slider_autoplay ) echo ' style="display:none"'; ?>>
		
			<p><label for="<?php echo $this->get_field_id( 'slider_autoplay_time' ); ?>"><?php _e( 'Autoplay interval', 'wpcasa-madrid' ); ?>:</label><br />
				<select class="widefat" id="<?php echo $this->get_field_id( 'slider_autoplay_time' ); ?>" name="<?php echo $this->get_field_name( 'slider_autoplay_time' ); ?>">
					<option value="2000"<?php selected( $slider_autoplay_time, 2000 ); ?>>2 <?php _ex( 'seconds', 'listing widget', 'wpcasa-madrid' ); ?></option>
					<option value="4000"<?php selected( $slider_autoplay_time, 4000 ); ?>>4 <?php _ex( 'seconds', 'listing widget', 'wpcasa-madrid' ); ?></option>
					<option value="6000"<?php selected( $slider_autoplay_time, 6000 ); ?>>6 <?php _ex( 'seconds', 'listing widget', 'wpcasa-madrid' ); ?></option>
					<option value="8000"<?php selected( $slider_autoplay_time, 8000 ); ?>>8 <?php _ex( 'seconds', 'listing widget', 'wpcasa-madrid' ); ?></option>
					<option value="10000"<?php selected( $slider_autoplay_time, 10000 ); ?>>10 <?php _ex( 'seconds', 'listing widget', 'wpcasa-madrid' ); ?></option>
				</select></p>
		
		</div>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'slider_dots' ); ?>" name="<?php echo $this->get_field_name( 'slider_dots' ); ?>"<?php checked( $slider_dots ); ?> />
				<label for="<?php echo $this->get_field_id( 'slider_dots' ); ?>"><?php _e( 'Show <em>dots</em> slider navigation', 'wpcasa-madrid' ); ?></label></p>
		
		<div<?php if( ! $slider_dots ) echo ' style="display:none"'; ?>>
		
			<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'slider_dots_thumbs' ); ?>" name="<?php echo $this->get_field_name( 'slider_dots_thumbs' ); ?>"<?php checked( $slider_dots_thumbs ); ?> />
					<label for="<?php echo $this->get_field_id( 'slider_dots_thumbs' ); ?>"><?php _e( 'Show thumbnails instead of <em>dots</em>', 'wpcasa-madrid' ); ?></label></p>
		
		</div>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'slider_nav' ); ?>" name="<?php echo $this->get_field_name( 'slider_nav' ); ?>"<?php checked( $slider_nav ); ?> />
				<label for="<?php echo $this->get_field_id( 'slider_nav' ); ?>"><?php _e( 'Show prev/next slider navigation', 'wpcasa-madrid' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'slider_keyboard' ); ?>" name="<?php echo $this->get_field_name( 'slider_keyboard' ); ?>"<?php checked( $slider_keyboard ); ?> />
				<label for="<?php echo $this->get_field_id( 'slider_keyboard' ); ?>"><?php _e( 'Allow slider navigating via keyboard', 'wpcasa-madrid' ); ?></label></p><?php

	}

}