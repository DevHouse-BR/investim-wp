<?php
/**
 * Custom image gallery
 *
 * @package WPCasa Madrid
 */

/**
 * wpsight_madrid_image_gallery()
 *
 * Echo wpsight_madrid_get_gallery()
 *
 * @param integer|array $post_id Post ID or array of attachment IDs
 * @param array $args Optional gallery arguments
 *
 * @uses wpsight_madrid_get_image_gallery()
 *
 * @since 1.0.0
 */
function wpsight_madrid_image_gallery( $post_id = '', $args = array() ) {
	echo wpsight_madrid_get_image_gallery( $post_id, $args );
}

/**
 * wpsight_madrid_get_image_gallery()
 *
 * Create thumbnail gallery.
 *
 * @param integer|array $post_id Post ID or array of attachment IDs
 * @param array $args Optional gallery arguments
 *
 * @uses get_post_meta()
 * @uses get_post()
 * @uses wp_get_attachment_image_src()
 * @uses wpsight_madrid_image_gallery_root()
 * @uses wpsight_madrid_image_gallery_js()
 *
 * @return string HTML markup of thumbnail gallery with lightbox
 *
 * @since 1.0.0
 */
function wpsight_madrid_get_image_gallery( $post_id = '', $args = array() ) {
	
	// If we have a post ID, get _gallery post meta
	
	if( ! is_array( $post_id ) ) {
	
		// Set default post ID
    	
    	if( ! $post_id )
			$post_id = get_the_ID();
		
		// Get gallery imgages		
		$images = array_keys( get_post_meta( absint( $post_id ), '_gallery', true ) );
		
		// Set gallery ID
		$gallery_id = 'wpsight-gallery-' . absint( $post_id );
			
	// Else set array of image attachment IDs
	
	} else {
		
		$images = array_map( 'absint', $post_id );
		
		// Set gallery ID
		$gallery_id = 'wpsight-gallery-' . implode( '-', $images );
		
	}
	
	// If there are images, create the gallery
	
	if( $images ) {
		
		// Set default classes
		
		$default_class_gallery	= isset( $args['class_gallery'] ) ? $args['class_gallery'] : $gallery_id . ' wpsight-gallery';
		$default_class_row		= isset( $args['class_row'] ) ? $args['class_row'] : 'row';
		$default_class_unit		= isset( $args['class_unit'] ) ? $args['class_unit'] : 'wpsight-gallery-unit col-xs-12 col-sm-6';
		
		// Set some defaults
		
		$defaults = array(
			'class_gallery'    			=> $default_class_gallery,
			'class_row' 	   			=> $default_class_row,
			'class_unit' 	   			=> $default_class_unit,
			'class_item'	   			=> 'wpsight-gallery-item',
			'thumbs_gutter'	   			=> 40,
			'thumbs_size' 	   			=> 'wpsight-large',
			'thumbs_columns'			=> 6,
			'thumbs_columns_small'		=> 12,
			'thumbs_caption'    		=> true,
			'thumbs_link'	   			=> true,
			'lightbox_size'	   			=> 'wpsight-full',
			'lightbox_mode'				=> 'fade',
			'lightbox_caption' 			=> true,
			'lightbox_prev_next'   		=> true,
			'lightbox_loop'				=> true,
			'lightbox_download'			=> false,
			'lightbox_zoom'		   		=> true,
			'lightbox_fullscreen'  		=> true,
			'lightbox_close'	   		=> false,
			'lightbox_counter'	   		=> true,
			'lightbox_autoplay'			=> false,
			'lightbox_autoplay_pause'	=> 5000,
			'lightbox_thumbs_size'		=> 'medium'
		);
		
		// Merge args with defaults and apply filter
		$args = apply_filters( 'wpsight_madrid_get_image_gallery_args', wp_parse_args( $args, $defaults ), $post_id, $args );
		
		// Sanitize gallery class
		
		$class_gallery = ! is_array( $args['class_gallery'] ) ? explode( ' ', $args['class_gallery'] ) : $args['class_gallery'];		
		$class_gallery = array_map( 'sanitize_html_class', $class_gallery );
		$class_gallery = implode( ' ', $class_gallery );
		
		// Sanitize unit class
		
		$class_unit = ! is_array( $args['class_unit'] ) ? explode( ' ', $args['class_unit'] ) : $args['class_unit'];		
		$class_unit = array_map( 'sanitize_html_class', $class_unit );
		$class_unit = implode( ' ', $class_unit );
		
		// Sanitize row class
		
		$class_row = ! is_array( $args['class_row'] ) ? explode( ' ', $args['class_row'] ) : $args['class_row'];		
		$class_row = array_map( 'sanitize_html_class', $class_row );
		$class_row = implode( ' ', $class_row );
		
		// Sanitize item class
		
		$class_item = ! is_array( $args['class_item'] ) ? explode( ' ', $args['class_item'] ) : $args['class_item'];		
		$class_item = array_map( 'sanitize_html_class', $class_item );
		$class_item = implode( ' ', $class_item );
		
		// Check thumbnail gutter
		$args['thumbs_gutter'] = in_array( $args['thumbs_gutter'], array( 0, 10, 20, 30, 40, 50, 60 ) ) ? $args['thumbs_gutter'] : 40;
		
		// Check thumbnail size
		$args['thumbs_size'] = in_array( $args['thumbs_size'], get_intermediate_image_sizes() ) || 'full' == $args['thumbs_size'] ? $args['thumbs_size'] : 'post-thumbnail';
		
		// Check lightbox thumbs size
		$args['lightbox_thumbs_size'] = in_array( $args['lightbox_thumbs_size'], get_intermediate_image_sizes() ) ? $args['lightbox_thumbs_size'] : false;
		
		// Check lightbox size
		$args['lightbox_size'] = in_array( $args['lightbox_size'], get_intermediate_image_sizes() ) ? $args['lightbox_size'] : 'full';
		
		// Check lightbox size
		$args['lightbox_mode'] = in_array( $args['lightbox_mode'], array( 'fade', 'slide' ) ) ? $args['lightbox_mode'] : 'fade';
		
		// Check thumbnail loop
		$args['lightbox_loop'] = $args['lightbox_loop'] == true ? 'true' : 'false';
		
		// Check thumbnail caption
		$args['thumbs_caption'] = $args['thumbs_caption'] == true ? true : false;
		
		// Check thumbnail links
		$args['thumbs_link'] = $args['thumbs_link'] == true ? true : false;
		
		// Check thumbnail caption
		$args['lightbox_caption'] = $args['lightbox_caption'] == true ? true : false;
		
		// Check show prev_next
		$args['lightbox_prev_next'] = $args['lightbox_prev_next'] == true ? 'true' : 'false';
		
		// Check show prev_next
		$args['lightbox_download'] = $args['lightbox_download'] == true ? 'true' : 'false';
		
		// Check show zoom
		$args['lightbox_zoom'] = $args['lightbox_zoom'] == true ? 'true' : 'false';
		
		// Check show fullscreen toggle
		$args['lightbox_fullscreen'] = $args['lightbox_fullscreen'] == true ? 'true' : 'false';
		
		// Check show close button
		$args['lightbox_counter'] = $args['lightbox_counter'] == true ? 'true' : 'false';
		
		// Check show autoplay button
		$args['lightbox_autoplay'] = $args['lightbox_autoplay'] == true ? 'true' : 'false';
		
		// Set counter
		$i = 0;
		
		ob_start(); ?>
		
		<script type="text/javascript">
			jQuery(document).ready(function($){
				$("#<?php echo $gallery_id; ?>").lightGallery({
					speed: 400,
					selector: '.<?php echo $class_item; ?> a',
					mode: 'lg-<?php echo $args['lightbox_mode']; ?>',
					loop: <?php echo $args['lightbox_loop']; ?>,
					controls: <?php echo $args['lightbox_prev_next']; ?>,
					download: <?php echo $args['lightbox_download']; ?>,
					counter: <?php echo $args['lightbox_counter']; ?>,
					fullScreen: <?php echo $args['lightbox_fullscreen']; ?>,
					zoom: <?php echo $args['lightbox_zoom']; ?>,
					autoplay: false,
					autoplayControls: <?php echo $args['lightbox_autoplay']; ?>,
					pause: <?php echo $args['lightbox_autoplay_pause']; ?>,
					thumbnail: <?php echo $args['lightbox_thumbs_size'] !== false ? 'true' : 'false'; ?>,
					thumbWidth: 79,
					thumbContHeight: 101,
					thumbMargin: 20,
				});			    
			});
		</script>
	
		<div id="<?php echo $gallery_id; ?>" class="<?php echo $class_gallery; ?> gallery-gutter-<?php echo absint( $args['thumbs_gutter'] ); ?> gallery-columns-<?php echo 12 / absint( $args['thumbs_columns'] ); ?> gallery-columns-small-<?php echo 12 / absint( $args['thumbs_columns_small'] ); ?>" itemscope itemtype="http://schema.org/ImageGallery">
					
			<div class="<?php echo $class_row; ?> gutter-<?php echo absint( $args['thumbs_gutter'] ); ?>">
		
				<?php foreach( $images as $image ) : ?>
				
					<?php
						$attachment 	= get_post( absint( $image ) );
						$attachment_src = wp_get_attachment_image_src( $attachment->ID, $args['lightbox_size'] );
						$thumbnail_src  = wp_get_attachment_image_src( $attachment->ID, $args['thumbs_size'] );
					?>
					
					<?php if( $attachment !== NULL && $attachment->post_type == 'attachment' ) : ?>
				
						<div class="<?php echo $class_unit; ?>">
						
							<figure class="<?php echo $class_item; ?>" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
						
								<?php if( $args['thumbs_link'] === true ) : ?>								
								<a href="<?php echo esc_url( $attachment_src[0] ); ?>" itemprop="contentUrl" data-size="<?php echo absint( $attachment_src[1] ); ?>x<?php echo absint( $attachment_src[2] ); ?>" data-counter="<?php echo absint( $i ); ?>"<?php if( $attachment->post_content && $args['lightbox_caption'] === true ) : ?> data-sub-html="#wpsight-lightbox-caption-<?php echo esc_attr( $i ); ?>"<?php endif; ?>>
								<?php endif; ?>
						
									<img src="<?php echo esc_url( $thumbnail_src[0] ); ?>" itemprop="thumbnail" alt="<?php echo esc_attr( get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ) ); ?>" />
						
									<?php if( $attachment->post_content && $args['lightbox_caption'] === true ) : ?>
									<meta itemprop="title" content="<?php echo esc_attr( $attachment->post_content ); ?>">
									<?php endif; ?>
						
									<meta itemprop="width" content="<?php echo absint( $thumbnail_src[1] ); ?>">
									<meta itemprop="height" content="<?php echo absint( $thumbnail_src[2] ); ?>">
								
								<?php if( $args['thumbs_link'] === true ) : ?>
								</a>
								<?php endif; ?>
								
								<?php if( $attachment->post_content && $args['lightbox_caption'] === true ) : ?>
								<div id="wpsight-lightbox-caption-<?php echo esc_attr( $i ); ?>" class="wpsight-lightbox-caption"><?php echo wp_kses_post( $attachment->post_content ); ?></div>
								<?php endif; ?>
								
								<?php if( $attachment->post_excerpt && $args['thumbs_caption'] === true ) : ?>
								<figcaption class="wpsight-gallery-caption" itemprop="caption description"><?php echo $attachment->post_excerpt; ?></figcaption>
								<?php endif; ?>
						
							</figure>
						
						</div>
					
					<?php $i++; endif; ?>
				
				<?php endforeach; ?>
						
			</div><!-- .<?php echo $class_row; ?> -->
		
		</div><?php
		
		return apply_filters( 'wpsight_madrid_get_image_gallery', ob_get_clean(), $post_id, $args, $gallery_id );
	
	}
	
}
