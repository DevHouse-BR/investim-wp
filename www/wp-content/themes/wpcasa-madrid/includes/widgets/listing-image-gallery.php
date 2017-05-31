<?php
/**
 * Listing Gallery widget
 *
 * @package WPCasa Madrid
 */

/**
 * Register widget
 */
add_action( 'widgets_init', 'wpsight_madrid_register_widget_listing_image_gallery' );
 
function wpsight_madrid_register_widget_listing_image_gallery() {
	register_widget( 'WPSight_Madrid_Listing_Image_Gallery' );
}

/**
 * Listing gallery widget class
 */
class WPSight_Madrid_Listing_Image_Gallery extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listing_image_gallery',
			'description' => _x( 'Display listing images in thumbnail gallery.', 'listing wigdet', 'wpcasa-madrid' )
		);

		parent::__construct( 'wpsight_madrid_listing_image_gallery', '&rsaquo; ' . _x( 'Single Listing Image Gallery', 'listing widget', 'wpcasa-madrid' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		global $listing;
	
		$listing = wpsight_get_listing( get_the_id() );
		
		$defaults = array(
			'thumbs_columns' 		=> 6, // 12/6 = 2 columns
			'thumbs_columns_small'	=> 12, // 12/12 = 1 column
			'thumbs_gutter'			=> 40,
			'thumbs_size' 			=> 'wpsight-large',
			'thumbs_caption' 		=> true,
			'thumbs_link' 			=> true,
			'lightbox_size'	   		=> 'wpsight-full',
			'lightbox_mode'			=> 'fade',
			'lightbox_caption' 		=> true,
			'lightbox_prev_next'   	=> true,
			'lightbox_loop'			=> true,
			'lightbox_download'		=> false,
			'lightbox_zoom'		   	=> true,
			'lightbox_fullscreen'  	=> true,
			'lightbox_counter'	   	=> true,
			'lightbox_autoplay'	   	=> true,
			'lightbox_thumbs_size'	=> 'medium'
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		// Thumbnail settings
		
		$thumbs_columns 		= absint( $instance['thumbs_columns'] );
		$thumbs_columns_small	= absint( $instance['thumbs_columns_small'] );
		$thumbs_gutter 			= absint( $instance['thumbs_gutter'] );
		$thumbs_size			= strip_tags( $instance['thumbs_size'] );		
		$thumbs_caption 		= isset( $instance['thumbs_caption'] ) ? (bool) $instance['thumbs_caption'] : false;
		$thumbs_link 			= isset( $instance['thumbs_link'] ) ? (bool) $instance['thumbs_link'] : false;
		
		// Lightbox settings
		
		$lightbox_size			= strip_tags( $instance['lightbox_size'] );
		$lightbox_mode			= strip_tags( $instance['lightbox_mode'] );
		$lightbox_caption 		= isset( $instance['lightbox_caption'] ) ? (bool) $instance['lightbox_caption'] : false;
		$lightbox_loop			= isset( $instance['lightbox_loop'] ) ? (bool) $instance['lightbox_loop'] : false;
		$lightbox_prev_next 	= isset( $instance['lightbox_prev_next'] ) ? (bool) $instance['lightbox_prev_next'] : false;
		$lightbox_download 		= isset( $instance['lightbox_download'] ) ? (bool) $instance['lightbox_download'] : false;
		$lightbox_zoom 			= isset( $instance['lightbox_zoom'] ) ? (bool) $instance['lightbox_zoom'] : false;
		$lightbox_fullscreen 	= isset( $instance['lightbox_fullscreen'] ) ? (bool) $instance['lightbox_fullscreen'] : false;
		$lightbox_counter 		= isset( $instance['lightbox_counter'] ) ? (bool) $instance['lightbox_counter'] : false;
		$lightbox_autoplay 		= isset( $instance['lightbox_autoplay'] ) ? (bool) $instance['lightbox_autoplay'] : false;
		$lightbox_thumbs_size	= isset( $instance['lightbox_thumbs_size'] ) ? (bool) $instance['lightbox_thumbs_size'] : false;
		
		// Set up args for gallery

		$gallery_args = array(
			'class_gallery' 		=> 'wpsight-gallery-' . get_the_id() . ' wpsight-gallery',
			'class_row'				=> 'row',
			'class_unit' 			=> 'wpsight-gallery-unit col-xs-' . $thumbs_columns_small . ' col-sm-' . $thumbs_columns,
			'thumbs_columns' 		=> $thumbs_columns,
			'thumbs_columns_small'	=> $thumbs_columns_small,
			'thumbs_size' 			=> $thumbs_size,
			'thumbs_gutter'			=> $thumbs_gutter,
			'thumbs_caption'		=> $thumbs_caption,
			'thumbs_link'			=> $thumbs_link,
			'lightbox_size' 		=> $lightbox_size,
			'lightbox_mode'			=> $lightbox_mode,
			'lightbox_caption' 		=> $lightbox_caption,
			'lightbox_prev_next'   	=> $lightbox_prev_next,
			'lightbox_zoom'		   	=> $lightbox_zoom,
			'lightbox_fullscreen'  	=> $lightbox_fullscreen,
			'lightbox_download'	   	=> $lightbox_download,
			'lightbox_counter'	   	=> $lightbox_counter,
			'lightbox_autoplay'		=> $lightbox_autoplay,
			'lightbox_thumbs_size'	=> $lightbox_thumbs_size
		);
		
		// When no gallery, don't any produce output
			
		$gallery = get_post_meta( get_the_id(), '_gallery' );
		
		if( ! $gallery )
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
			wpsight_get_template( 'listing-single-gallery.php', array( 'widget_args' => $args, 'widget_instance' => $instance, 'gallery_args' => $gallery_args ) );
        	
        }
		
		// Echo after_widget
		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;
	    
	    $instance['title'] 					= strip_tags( $new_instance['title'] );
	    
	    // Thumbnail settings
	    
	    $instance['thumbs_columns'] 		= absint( $new_instance['thumbs_columns'] );
	    $instance['thumbs_columns_small']	= absint( $new_instance['thumbs_columns_small'] );
	    $instance['thumbs_gutter'] 			= absint( $new_instance['thumbs_gutter'] );
	    $instance['thumbs_size'] 			= strip_tags( $new_instance['thumbs_size'] );	    
	    $instance['thumbs_caption'] 		= ! empty( $new_instance['thumbs_caption'] ) ? 1 : 0;
	    $instance['thumbs_link'] 			= ! empty( $new_instance['thumbs_link'] ) ? 1 : 0;
	    
	    // Lightbox settings
	    
	    $instance['lightbox_size'] 			= strip_tags( $new_instance['lightbox_size'] );
	    $instance['lightbox_mode'] 			= strip_tags( $new_instance['lightbox_mode'] );
	    $instance['lightbox_caption'] 		= ! empty( $new_instance['lightbox_caption'] ) ? 1 : 0;
	    $instance['lightbox_prev_next'] 	= ! empty( $new_instance['lightbox_prev_next'] ) ? 1 : 0;
	    $instance['lightbox_zoom'] 			= ! empty( $new_instance['lightbox_zoom'] ) ? 1 : 0;
	    $instance['lightbox_fullscreen'] 	= ! empty( $new_instance['lightbox_fullscreen'] ) ? 1 : 0;
	    $instance['lightbox_download'] 		= ! empty( $new_instance['lightbox_download'] ) ? 1 : 0;
	    $instance['lightbox_counter'] 		= ! empty( $new_instance['lightbox_counter'] ) ? 1 : 0;
	    $instance['lightbox_autoplay'] 		= ! empty( $new_instance['lightbox_autoplay'] ) ? 1 : 0;
	    $instance['lightbox_thumbs_size'] 	= ! empty( $new_instance['lightbox_thumbs_size'] ) ? 1 : 0;

        return $instance;

    }

	public function form( $instance ) {
		
		$defaults = array(
			'thumbs_columns' 		=> 6, // 12/6 = 2 columns
			'thumbs_columns_small'	=> 12, // 12/12 = 1 column
			'thumbs_gutter'			=> 40,
			'thumbs_size' 			=> 'wpsight-large',
			'thumbs_caption' 		=> true,
			'thumbs_link' 			=> true,
			'lightbox_size'	   		=> 'wpsight-full',
			'lightbox_mode'			=> 'fade',
			'lightbox_caption' 		=> true,
			'lightbox_prev_next'   	=> true,
			'lightbox_loop'			=> true,
			'lightbox_download'		=> false,
			'lightbox_zoom'		   	=> true,
			'lightbox_fullscreen'  	=> true,
			'lightbox_counter'	   	=> true,
			'lightbox_autoplay'		=> true,
			'lightbox_thumbs_size'	=> true
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title = isset( $instance['title'] ) ? strip_tags( $instance['title'] ) : false;
		
		// Thumbnail settings
		
		$thumbs_columns 		= absint( $instance['thumbs_columns'] );
		$thumbs_columns_small	= absint( $instance['thumbs_columns_small'] );
		$thumbs_gutter 			= absint( $instance['thumbs_gutter'] );
		$thumbs_size			= strip_tags( $instance['thumbs_size'] );		
		$thumbs_caption 		= isset( $instance['thumbs_caption'] ) ? (bool) $instance['thumbs_caption'] : false;
		$thumbs_link 			= isset( $instance['thumbs_link'] ) ? (bool) $instance['thumbs_link'] : false;
		
		// Lightbox settings
		
		$lightbox_size			= strip_tags( $instance['lightbox_size'] );
		$lightbox_mode			= strip_tags( $instance['lightbox_mode'] );
		$lightbox_caption 		= isset( $instance['lightbox_caption'] ) ? (bool) $instance['lightbox_caption'] : false;
		$lightbox_loop			= isset( $instance['lightbox_loop'] ) ? (bool) $instance['lightbox_loop'] : false;
		$lightbox_prev_next 	= isset( $instance['lightbox_prev_next'] ) ? (bool) $instance['lightbox_prev_next'] : false;
		$lightbox_download 		= isset( $instance['lightbox_download'] ) ? (bool) $instance['lightbox_download'] : false;
		$lightbox_zoom 			= isset( $instance['lightbox_zoom'] ) ? (bool) $instance['lightbox_zoom'] : false;
		$lightbox_fullscreen 	= isset( $instance['lightbox_fullscreen'] ) ? (bool) $instance['lightbox_fullscreen'] : false;
		$lightbox_counter 		= isset( $instance['lightbox_counter'] ) ? (bool) $instance['lightbox_counter'] : false;
		$lightbox_autoplay 		= isset( $instance['lightbox_autoplay'] ) ? (bool) $instance['lightbox_autoplay'] : false;
		$lightbox_thumbs_size	= isset( $instance['lightbox_thumbs_size'] ) ? (bool) $instance['lightbox_thumbs_size'] : false; ?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpcasa-madrid' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'thumbs_columns' ); ?>"><?php _e( 'Columns', 'wpcasa-madrid' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'thumbs_columns' ); ?>" name="<?php echo $this->get_field_name( 'thumbs_columns' ); ?>">
				<option value="12"<?php selected( $thumbs_columns, 12 ); ?>>1 <?php _ex( 'column', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="6"<?php selected( $thumbs_columns, 6 ); ?>>2 <?php _ex( 'columns', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="4"<?php selected( $thumbs_columns, 4 ); ?>>3 <?php _ex( 'columns', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="3"<?php selected( $thumbs_columns, 3 ); ?>>4 <?php _ex( 'columns', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="2"<?php selected( $thumbs_columns, 2 ); ?>>6 <?php _ex( 'columns', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="1"<?php selected( $thumbs_columns, 1 ); ?>>12 <?php _ex( 'columns', 'listing widget', 'wpcasa-madrid' ); ?></option>
			</select><br />
			<span class="description"><?php _e( 'Please select the number of columns', 'wpcasa-madrid' ); ?></span></p>
		
		<p><label for="<?php echo $this->get_field_id( 'thumbs_columns_small' ); ?>"><?php _e( 'Columns', 'wpcasa-madrid' ); ?> (<?php _ex( 'on small screens', 'listing widget', 'wpcasa-madrid' ); ?>):</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'thumbs_columns_small' ); ?>" name="<?php echo $this->get_field_name( 'thumbs_columns_small' ); ?>">
				<option value="12"<?php selected( $thumbs_columns_small, 12 ); ?>>1 <?php _ex( 'column', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="6"<?php selected( $thumbs_columns_small, 6 ); ?>>2 <?php _ex( 'columns', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="4"<?php selected( $thumbs_columns_small, 4 ); ?>>3 <?php _ex( 'columns', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="3"<?php selected( $thumbs_columns_small, 3 ); ?>>4 <?php _ex( 'columns', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="2"<?php selected( $thumbs_columns_small, 2 ); ?>>6 <?php _ex( 'columns', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="1"<?php selected( $thumbs_columns_small, 1 ); ?>>12 <?php _ex( 'columns', 'listing widget', 'wpcasa-madrid' ); ?></option>
			</select><br />
			<span class="description"><?php _e( 'Please select the number of columns', 'wpcasa-madrid' ); ?> (<?php _ex( 'on small screens', 'listing widget', 'wpcasa-madrid' ); ?>)</span></p>
		
		<p><label for="<?php echo $this->get_field_id( 'thumbs_gutter' ); ?>"><?php _e( 'Gutter', 'wpcasa-madrid' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'thumbs_gutter' ); ?>" name="<?php echo $this->get_field_name( 'thumbs_gutter' ); ?>">
				<option value="0"<?php selected( $thumbs_gutter, 0 ); ?>>0px</option>
				<option value="10"<?php selected( $thumbs_gutter, 10 ); ?>>10px</option>
				<option value="20"<?php selected( $thumbs_gutter, 20 ); ?>>20px</option>
				<option value="30"<?php selected( $thumbs_gutter, 30 ); ?>>30px</option>
				<option value="40"<?php selected( $thumbs_gutter, 40 ); ?>>40px</option>
				<option value="50"<?php selected( $thumbs_gutter, 50 ); ?>>50px</option>
				<option value="60"<?php selected( $thumbs_gutter, 60 ); ?>>60px</option>
			</select><br />
			<span class="description"><?php _e( 'Please select the gutter width (space between thumbs)', 'wpcasa-madrid' ); ?></span></p>
		
		<p><label for="<?php echo $this->get_field_id( 'thumbs_size' ); ?>"><?php _e( 'Thumbnail Size', 'wpcasa-madrid' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'thumbs_size' ); ?>" name="<?php echo $this->get_field_name( 'thumbs_size' ); ?>">			
				<?php foreach( get_intermediate_image_sizes() as $size ) : ?>
				<option value="<?php echo strip_tags( $size ); ?>"<?php selected( $thumbs_size, $size ); ?>><?php echo strip_tags( $size ); ?></option>
				<?php endforeach; ?>
				<option value="full"<?php selected( $thumbs_size, 'full' ); ?>>full (original)</option>
			</select><br />
			<span class="description"><?php _e( 'Please select the thumbnail size', 'wpcasa-madrid' ); ?></span></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'thumbs_caption' ); ?>" name="<?php echo $this->get_field_name( 'thumbs_caption' ); ?>"<?php checked( $thumbs_caption ); ?> />
			<label for="<?php echo $this->get_field_id( 'thumbs_caption' ); ?>"><?php _e( 'Display thumbnail captions (if any)', 'wpcasa-madrid' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'thumbs_link' ); ?>" name="<?php echo $this->get_field_name( 'thumbs_link' ); ?>"<?php checked( $thumbs_link ); ?> />
			<label for="<?php echo $this->get_field_id( 'thumbs_link' ); ?>"><?php _e( 'Link thumbnails to image file (in lightbox)', 'wpcasa-madrid' ); ?></label></p>
		
		<div<?php if( ! $thumbs_link || apply_filters( 'wpsight_madrid_lightgallery', true ) != true ) echo ' style="display:none"'; ?>>
		
				<p><label for="<?php echo $this->get_field_id( 'lightbox_size' ); ?>"><?php _e( 'Lightbox Size', 'wpcasa-madrid' ); ?>:</label><br />
				<select class="widefat" id="<?php echo $this->get_field_id( 'lightbox_size' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_size' ); ?>">			
					<?php foreach( get_intermediate_image_sizes() as $size ) : ?>
					<option value="<?php echo strip_tags( $size ); ?>"<?php selected( $lightbox_size, $size ); ?>><?php echo strip_tags( $size ); ?></option>
					<?php endforeach; ?>
					<option value="full"<?php selected( $lightbox_size, 'full' ); ?>>full (original)</option>
				</select><br />
				<span class="description"><?php _e( 'Please select the image size for the lightbox', 'wpcasa-madrid' ); ?></span></p>
				
				<p><label for="<?php echo $this->get_field_id( 'lightbox_mode' ); ?>"><?php _e( 'Animation', 'wpcasa-madrid' ); ?>:</label><br />
				<select class="widefat" id="<?php echo $this->get_field_id( 'lightbox_mode' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_mode' ); ?>">
					<option value="fade"<?php selected( $lightbox_mode, 'fade' ); ?>><?php _ex( 'fade', 'listing widget', 'wpcasa-madrid' ); ?></option>
					<option value="slide"<?php selected( $lightbox_mode, 'slide' ); ?>><?php _ex( 'slide', 'listing widget', 'wpcasa-madrid' ); ?></option>
				</select></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_caption' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_caption' ); ?>"<?php checked( $lightbox_caption ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_caption' ); ?>"><?php _e( 'Show image descriptions in lightbox', 'wpcasa-madrid' ); ?></label></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_loop' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_loop' ); ?>"<?php checked( $lightbox_loop ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_loop' ); ?>"><?php _e( 'Loop lightbox images', 'wpcasa-madrid' ); ?></label></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_prev_next' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_prev_next' ); ?>"<?php checked( $lightbox_prev_next ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_prev_next' ); ?>"><?php _e( 'Show prev/next navigation in lightbox', 'wpcasa-madrid' ); ?></label></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_download' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_download' ); ?>"<?php checked( $lightbox_download ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_download' ); ?>"><?php _e( 'Show image download button', 'wpcasa-madrid' ); ?></label></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_zoom' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_zoom' ); ?>"<?php checked( $lightbox_zoom ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_zoom' ); ?>"><?php _e( 'Allow zoom option in lightbox', 'wpcasa-madrid' ); ?></label></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_fullscreen' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_fullscreen' ); ?>"<?php checked( $lightbox_fullscreen ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_fullscreen' ); ?>"><?php _e( 'Allow fullscreen toggle in lightbox', 'wpcasa-madrid' ); ?></label></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_counter' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_counter' ); ?>"<?php checked( $lightbox_counter ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_counter' ); ?>"><?php _e( 'Show image counter in lightbox', 'wpcasa-madrid' ); ?></label></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_autoplay' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_autoplay' ); ?>"<?php checked( $lightbox_autoplay ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_autoplay' ); ?>"><?php _e( 'Show autoplay controls in lightbox', 'wpcasa-madrid' ); ?></label></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_thumbs_size' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_thumbs_size' ); ?>"<?php checked( $lightbox_thumbs_size ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_thumbs_size' ); ?>"><?php _e( 'Show thumbnail previews in lightbox', 'wpcasa-madrid' ); ?></label></p>
		
		</div><?php

	}

}