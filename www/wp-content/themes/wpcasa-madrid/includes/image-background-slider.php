<?php
/**
 * Custom image slider
 *
 * @package WPCasa Madrid
 */

/**
 * wpsight_madrid_image_background_slider()
 *
 * Echo wpsight_madrid_get_slider()
 *
 * @param integer|array $post_id Post ID or array of attachment IDs
 * @param array $args Optional slider arguments
 *
 * @uses wpsight_madrid_get_image_background_slider()
 *
 * @since 1.0.0
 */
function wpsight_madrid_image_background_slider( $post_id = '', $args = array() ) {
	echo wpsight_madrid_get_image_background_slider( $post_id, $args );
}

/**
 * wpsight_madrid_get_image_background_slider()
 *
 * Create image slider.
 *
 * @param integer|array $post_id Post ID or array of attachment IDs
 * @param array $args Optional slider arguments
 *
 * @uses get_post_meta()
 * @uses get_post()
 * @uses wp_get_attachment_image_src()
 *
 * @return string HTML markup of image slider
 *
 * @since 1.0.0
 */
function wpsight_madrid_get_image_background_slider( $post_id = '', $args = array() ) {
	
	// If we have a post ID, get _slider post meta
	
	if( ! is_array( $post_id ) ) {
	
		// Set default post ID
    	
    	if( ! $post_id )
			$post_id = get_the_ID();
		
		// Get slider images		
		$images = array_keys( get_post_meta( absint( $post_id ), '_gallery', true ) );
		
		// Set slider ID
		$slider_id = 'wpsight-slider-' . absint( $post_id ) . '-' . uniqid();
			
	// Else set array of image attachment IDs
	
	} else {
		
		$images = array_map( 'absint', $post_id );
		
		// Set slider ID
		$slider_id = 'wpsight-slider-' . implode( '-', $images ) . '-' . uniqid();
		
	}
	
	// If there are images, create the slider
	
	if( $images ) {
		
		// Set default classes
		
		$default_class_slider	= isset( $args['class_slider'] ) ? $args['class_slider'] : $slider_id . ' wpsight-image-background-slider';
		
		// Set some defaults
		
		$defaults = array(
			'class_slider'    			=> $default_class_slider,
			'class_item'	   			=> 'wpsight-image-background-slider-item',
			'image_size' 	   			=> 'full',
			'image_caption'    			=> false,
			'slider_animation'			=> 'slide',
			'slider_loop'				=> true,
			'slider_speed'				=> 500,
			'slider_autoplay'			=> true,
			'slider_autoplay_time'		=> 6000,
			'slider_hover_pause'		=> true,
			'slider_dots'				=> true,
			'slider_dots_thumbs'		=> false,
			'slider_dots_thumbs_items'	=> 4,
			'slider_dots_thumbs_margin'	=> 10,
			'slider_dots_thumbs_size'	=> 'medium',
			'slider_nav'				=> true,
			'slider_keyboard'			=> true
		);
		
		// Merge args with defaults and apply filter
		$args = apply_filters( 'wpsight_madrid_get_image_background_slider_args', wp_parse_args( $args, $defaults ), $post_id, $args );
		
		// Sanitize slider class
		
		$class_slider = ! is_array( $args['class_slider'] ) ? explode( ' ', $args['class_slider'] ) : $args['class_slider'];		
		$class_slider = array_map( 'sanitize_html_class', $class_slider );
		$class_slider = implode( ' ', $class_slider );
		
		// Sanitize item class
		
		$class_item = ! is_array( $args['class_item'] ) ? explode( ' ', $args['class_item'] ) : $args['class_item'];		
		$class_item = array_map( 'sanitize_html_class', $class_item );
		$class_item = implode( ' ', $class_item );
		
		// Check image size
		$args['image_size'] = in_array( $args['image_size'], get_intermediate_image_sizes() ) || 'full' == $args['image_size'] ? $args['image_size'] : 'wpsight-large';
		
		// Check image caption
		$args['image_caption'] = $args['image_caption'] == true ? true : false;
		
		// Check slider animation
		$args['slider_animation'] = in_array( $args['slider_animation'], array( 'fade', 'slide' ) ) ? $args['slider_animation'] : 'fade';
		
		// Check slider loop
		$args['slider_loop'] = $args['slider_loop'] == true ? 'true' : 'false';
		
		// Check slider autoplay
		$args['slider_autoplay'] = $args['slider_autoplay'] == true ? 'true' : 'false';
		
		// Check slider hover pause
		$args['slider_hover_pause'] = $args['slider_hover_pause'] == true ? 'true' : 'false';
		
		// Check slider dots nav
		$args['slider_dots'] = $args['slider_dots'] == true ? 'true' : 'false';
		
		// Check slider dots thumbs nav
		$args['slider_dots_thumbs'] = $args['slider_dots_thumbs'] == true ? 'true' : 'false';
		
		// Check slider dots thumbs size
		$args['slider_dots_thumbs_size'] = in_array( $args['slider_dots_thumbs_size'], get_intermediate_image_sizes() ) || 'full' == $args['slider_dots_thumbs_size'] ? $args['slider_dots_thumbs_size'] : 'medium';
		
		// Check slider keyboard
		$args['slider_keyboard'] = $args['slider_keyboard'] == true ? 'true' : 'false';
		
		// Set counter
		$i = 0;
		
		ob_start(); ?>
		
		<script type="text/javascript">
			jQuery(document).ready(function($){
    
			    var slider = $('#<?php echo $slider_id; ?>').lightSlider({
				    mode:			'<?php echo $args['slider_animation']; ?>',
					item:			1,
					loop:			<?php echo $args['slider_loop']; ?>,
					pager:			<?php echo $args['slider_dots']; ?>,
					auto:			<?php echo $args['slider_autoplay']; ?>,
					pause:			<?php echo absint( $args['slider_autoplay_time'] ); ?>,
					slideMove:		1,
					slideMargin:	0,
					cssEasing:		'cubic-bezier(0.25, 0, 0.25, 1)',
					easing:			'linear',
					speed:			<?php echo absint( $args['slider_speed'] ); ?>,
					pauseOnHover:	true,
					controls:		false,
					rtl:			false,
					onSliderLoad:	function() {
					    $('#<?php echo esc_attr( $slider_id ); ?>-wrap').removeClass('cS-hidden');
					},
					keyPress:		<?php echo $args['slider_keyboard']; ?>,
					thumbItem:		<?php echo absint( $args['slider_dots_thumbs_items'] ); ?>,
					gallery:		<?php echo $args['slider_dots_thumbs']; ?>,
					galleryMargin:	<?php echo absint( $args['slider_dots_thumbs_margin'] ); ?>,
					thumbMargin:	<?php echo absint( $args['slider_dots_thumbs_margin'] ); ?>,
					currentPagerPosition: 'middle',
				});
				
				<?php if( $args['slider_nav'] ) : ?>
				$('#<?php echo esc_attr( $slider_id ); ?>-wrap .slider-prev').click(function(){
    			    slider.goToPrevSlide(); 
    			});
    			$('#<?php echo esc_attr( $slider_id ); ?>-wrap .slider-next').click(function(){
    			    slider.goToNextSlide(); 
    			});
    			$('#<?php echo esc_attr( $slider_id ); ?>-wrap .wpsight-image-background-slider-prev-next').insertAfter('#<?php echo esc_attr( $slider_id ); ?>');
    			<?php endif; ?>
			    
			});
		</script>
	
		<div id="<?php echo esc_attr( $slider_id ); ?>-wrap" class="<?php echo $class_slider; ?>-wrap cS-hidden">
		
			<ul id="<?php echo esc_attr( $slider_id ); ?>" class="<?php echo $class_slider; ?>" itemscope itemtype="http://schema.org/ImageGallery">
				
				<?php foreach( $images as $image ) : ?>
				
					<?php
						$attachment 	= get_post( absint( $image ) );
						$attachment_src = wp_get_attachment_image_src( $attachment->ID, $args['image_size'] );
					?>
					
					<?php if( $attachment !== NULL && $attachment->post_type == 'attachment' ) : ?>
					
						<?php $attachment_thumb = wp_get_attachment_image_src( $attachment->ID, $args['slider_dots_thumbs_size'] ); ?>
					
						<li class="wpsight-image-background-slider-item" data-thumb="<?php echo esc_url( $attachment_thumb[0] ); ?>" style="background-image:url(<?php echo esc_url( $attachment_src[0] ); ?>)"></li>
					
					<?php $i++; endif; ?>
				
				<?php endforeach; ?>
			
			</ul><!-- #<?php echo esc_attr( $slider_id ); ?> -->
			
			<?php if( $args['slider_nav'] ) : ?>
			<div class="wpsight-image-background-slider-prev-next">
				<div class="slider-prev"><i class="fa fa-angle-left"></i></div>
				<div class="slider-next"><i class="fa fa-angle-right"></i></div>
			</div>
			<?php endif; ?>
		
		</div><!-- #<?php echo esc_attr( $slider_id ); ?>-wrap --><?php
		
		return apply_filters( 'wpsight_madrid_get_image_background_slider', ob_get_clean(), $post_id, $args, $slider_id );
	
	}
	
}
