<?php
/**
 * Listing Slider widget
 *
 * @package WPCasa Madrid
 */

/**
 * Register widget
 */
add_action( 'widgets_init', 'wpsight_madrid_register_widget_listing_image_slider' );
 
function wpsight_madrid_register_widget_listing_image_slider() {
	register_widget( 'WPSight_Madrid_Listing_Image_Slider' );
}

/**
 * Listing slider widget class
 */
class WPSight_Madrid_Listing_Image_Slider extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listing_image_slider',
			'description' => _x( 'Display listing images in slider.', 'listing wigdet', 'wpcasa-madrid' )
		);

		parent::__construct( 'wpsight_madrid_listing_image_slider', '&rsaquo; ' . _x( 'Single Listing Image Slider', 'listing widget', 'wpcasa-madrid' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		global $listing;
	
		$listing = wpsight_get_listing( get_the_id() );
		
		$defaults = array(
			'image_size' 				=> 'wpsight-large',
			'image_caption' 			=> false,
			'slider_animation'			=> 'fade',
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

		$image_size			= strip_tags( $instance['image_size'] );		
		$image_caption 		= isset( $instance['image_caption'] ) ? (bool) $instance['image_caption'] : false;
		
		// Slider settings
		
		$slider_animation			= strip_tags( $instance['slider_animation'] );
		$slider_loop 				= isset( $instance['slider_loop'] ) ? (bool) $instance['slider_loop'] : false;
		$slider_autoplay 			= isset( $instance['slider_autoplay'] ) ? (bool) $instance['slider_autoplay'] : false;
		$slider_autoplay_time 		= absint( $instance['slider_autoplay_time'] );
		$slider_dots 				= isset( $instance['slider_dots'] ) ? (bool) $instance['slider_dots'] : true;
		$slider_dots_thumbs			= isset( $instance['slider_dots_thumbs'] ) ? (bool) $instance['slider_dots_thumbs'] : false;
		$slider_dots_thumbs_items 	= absint( $instance['slider_dots_thumbs_items'] );
		$slider_dots_thumbs_margin 	= absint( $instance['slider_dots_thumbs_margin'] );
		$slider_nav 				= isset( $instance['slider_nav'] ) ? (bool) $instance['slider_nav'] : true;
		$slider_keyboard			= isset( $instance['slider_keyboard'] ) ? (bool) $instance['slider_keyboard'] : true;
		
		// Set up args for slider

		$slider_args = array(
			'class_slider'    			=> 'wpsight-image-slider-' . get_the_id() . ' wpsight-image-slider',
			'class_item'	   			=> 'wpsight-image-slider-item',
			'image_size' 	   			=> $image_size,
			'image_caption'    			=> $image_caption,
			'slider_animation'			=> $slider_animation,
			'slider_loop'				=> $slider_loop,
			'slider_autoplay'			=> $slider_autoplay,
			'slider_autoplay_time'		=> $slider_autoplay_time,
			'slider_dots'				=> $slider_dots,
			'slider_dots_thumbs'		=> $slider_dots_thumbs,
			'slider_dots_thumbs_items'	=> $slider_dots_thumbs_items,
			'slider_dots_thumbs_margin'	=> $slider_dots_thumbs_margin,
			'slider_nav'				=> $slider_nav,
			'slider_keyboard'			=> $slider_keyboard
		);
		
		// When no gallery, don't any produce output
			
		$images = get_post_meta( get_the_id(), '_gallery', true );
		
		if( ! $images )
			return;
		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		
		// Echo before_widget
        echo $args['before_widget'];
        
        if( ! is_singular( wpsight_post_type() ) ) {		
			// Display notice when not on single listing page
			wpsight_get_template( 'listing-single-widget-notice.php', array( 'widget_args' => $args, 'widget_instance' => $instance ) );		
		} else {        
        	
        	if ( $title )
				echo $args['before_title'] . $title . $args['after_title'];
			
			// Display listing image template
			wpsight_get_template( 'listing-single-slider.php', array( 'widget_args' => $args, 'widget_instance' => $instance, 'slider_args' => $slider_args ) );
        	
        }
		
		// Echo after_widget
		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;
	    
	    $instance['title'] 					= strip_tags( $new_instance['title'] );
	    
	    // Thumbnail settings
	    
	    $instance['image_size'] 			= strip_tags( $new_instance['image_size'] );
	    $instance['image_caption'] 			= ! empty( $new_instance['image_caption'] ) ? 1 : 0;
	    
	    // Slider settings
	    
	    $instance['slider_animation'] 			= strip_tags( $new_instance['slider_animation'] );
	    $instance['slider_loop'] 				= ! empty( $new_instance['slider_loop'] ) ? 1 : 0;
	    $instance['slider_autoplay'] 			= ! empty( $new_instance['slider_autoplay'] ) ? 1 : 0;
	    $instance['slider_autoplay_time'] 		= absint( $new_instance['slider_autoplay_time'] );
	    $instance['slider_dots'] 				= ! empty( $new_instance['slider_dots'] ) ? 1 : 0;
	    $instance['slider_dots_thumbs']			= ! empty( $new_instance['slider_dots_thumbs'] ) ? 1 : 0;
	    $instance['slider_dots_thumbs_items'] 	= absint( $new_instance['slider_dots_thumbs_items'] );
	    $instance['slider_dots_thumbs_margin'] 	= absint( $new_instance['slider_dots_thumbs_margin'] );
	    $instance['slider_nav'] 				= ! empty( $new_instance['slider_nav'] ) ? 1 : 0;
	    $instance['slider_keyboard'] 			= ! empty( $new_instance['slider_keyboard'] ) ? 1 : 0;
	    

        return $instance;

    }

	public function form( $instance ) {
		
		$defaults = array(
			'title' 					=> '',
			'image_size' 				=> 'wpsight-large',
			'image_caption' 			=> false,
			'slider_animation'			=> 'fade',
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
		
		$title 				= strip_tags( $instance['title'] );
		
		// Image settings

		$image_size			= strip_tags( $instance['image_size'] );		
		$image_caption 		= isset( $instance['image_caption'] ) ? (bool) $instance['image_caption'] : false;
		
		// Slider settings
		
		$slider_animation			= strip_tags( $instance['slider_animation'] );
		$slider_loop 				= isset( $instance['slider_loop'] ) ? (bool) $instance['slider_loop'] : false;
		$slider_autoplay 			= isset( $instance['slider_autoplay'] ) ? (bool) $instance['slider_autoplay'] : false;
		$slider_autoplay_time 		= absint( $instance['slider_autoplay_time'] );		
		$slider_dots 				= isset( $instance['slider_dots'] ) ? (bool) $instance['slider_dots'] : true;
		$slider_dots_thumbs			= isset( $instance['slider_dots_thumbs'] ) ? (bool) $instance['slider_dots_thumbs'] : true;
		$slider_dots_thumbs_items 	= absint( $instance['slider_dots_thumbs_items'] );
		$slider_dots_thumbs_margin 	= absint( $instance['slider_dots_thumbs_margin'] );
		$slider_nav 				= isset( $instance['slider_nav'] ) ? (bool) $instance['slider_nav'] : true;
		$slider_keyboard			= isset( $instance['slider_keyboard'] ) ? (bool) $instance['slider_keyboard'] : true; ?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpcasa-madrid' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
		
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
					
			<div<?php if( ! $slider_dots_thumbs ) echo ' style="display:none"'; ?>>
			
				<p><label for="<?php echo $this->get_field_id( 'slider_dots_thumbs_items' ); ?>"><?php _e( 'Number of thumbnail items', 'wpcasa-madrid' ); ?>:</label><br />
					<select class="widefat" id="<?php echo $this->get_field_id( 'slider_dots_thumbs_items' ); ?>" name="<?php echo $this->get_field_name( 'slider_dots_thumbs_items' ); ?>">
						<?php for( $i = 1; $i <= 12; $i++ ) : ?>
						<option value="<?php echo esc_attr( $i ); ?>"<?php selected( $slider_dots_thumbs_items, $i ); ?>><?php echo esc_attr( $i ); ?></option>
						<?php endfor; ?>
					</select></p>
				
				<p><label for="<?php echo $this->get_field_id( 'slider_dots_thumbs_margin' ); ?>"><?php _e( 'Margin between thumbnail items', 'wpcasa-madrid' ); ?>:</label><br />
					<select class="widefat" id="<?php echo $this->get_field_id( 'slider_dots_thumbs_margin' ); ?>" name="<?php echo $this->get_field_name( 'slider_dots_thumbs_margin' ); ?>">
						<option value="0"<?php selected( $slider_dots_thumbs_margin, 0 ); ?>>0px</option>
						<?php for( $i = 1; $i <= 6; $i++ ) : ?>
						<option value="<?php echo esc_attr( $i . 0 ); ?>"<?php selected( $slider_dots_thumbs_margin, $i . 0 ); ?>><?php echo esc_attr( $i ) . 0; ?>px</option>
						<?php endfor; ?>
					</select></p>
			
			</div>
		
		</div>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'slider_nav' ); ?>" name="<?php echo $this->get_field_name( 'slider_nav' ); ?>"<?php checked( $slider_nav ); ?> />
				<label for="<?php echo $this->get_field_id( 'slider_nav' ); ?>"><?php _e( 'Show prev/next slider navigation', 'wpcasa-madrid' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'slider_keyboard' ); ?>" name="<?php echo $this->get_field_name( 'slider_keyboard' ); ?>"<?php checked( $slider_keyboard ); ?> />
				<label for="<?php echo $this->get_field_id( 'slider_keyboard' ); ?>"><?php _e( 'Allow slider navigating via keyboard', 'wpcasa-madrid' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'image_caption' ); ?>" name="<?php echo $this->get_field_name( 'image_caption' ); ?>"<?php checked( $image_caption ); ?> />
			<label for="<?php echo $this->get_field_id( 'image_caption' ); ?>"><?php _e( 'Display image captions (if any)', 'wpcasa-madrid' ); ?></label></p>	
		
		<?php

	}

}
