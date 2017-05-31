<?php
/**
 * WPCasa Bootstrap listings slider
 *
 * @package WPCasa Madrid
 */

/**
 * wpsight_madrid_listings_slider()
 *
 * Echo wpsight_madrid_get_listings_slider()
 *
 * @param object|array $listings Query object or post IDs array
 * @param array $args Optional slider arguments
 *
 * @uses wpsight_madrid_get_listings_slider()
 *
 * @since 1.0.0
 */
function wpsight_madrid_listings_slider( $listings = array(), $args = array() ) {
	echo wpsight_madrid_get_listings_slider( $listings, $args );
}

/**
 * wpsight_madrid_get_listings_slider()
 *
 * Create listings slider.
 *
 * @param object|array $listings Query object or post IDs array
 * @param array $args Optional slider arguments
 *
 * @uses get_post_meta()
 * @uses get_post()
 * @uses wp_get_attachment_image_src()
 *
 * @return string HTML markup of thumbnail gallery with lightbox
 *
 * @since 1.0.0
 */
function wpsight_madrid_get_listings_slider( $listings = array(), $args = array() ) {
	global $post;
	
	// If we have a query object or ID array
	
	if( is_array( $listings ) ) {		
		$listings = array_map( 'absint', $listings );		
	} elseif( is_object( $listings ) ) {		
		$listings = wp_list_pluck( $listings->posts, 'ID' );		
	}
		
	// Set gallery ID
	$slider_id = 'wpsight-listings-slider-' . implode( '-', $listings );
	
	// If there are listings, create the slider
	
	if( $listings ) {
		
		// Set some defaults
		
		$defaults = array(
			'class_slider'				=> $slider_id . ' wpsight-listings-slider',
			'image_size' 	   			=> 'full',
			'slider_animation'			=> 'slide',
			'slider_loop'				=> true,
			'slider_speed'				=> 500,
			'slider_autoplay'			=> false,
			'slider_autoplay_time'		=> 6000,
			'slider_hover_pause'		=> true,
			'slider_dots'				=> true,
			'slider_dots_thumbs'		=> false,
			'slider_dots_thumbs_size'	=> 'medium',
			'slider_nav'				=> true,
			'slider_keyboard'			=> true,
			'slider_context'			=> ''
		);
		
		// Merge args with defaults and apply filter
		$args = apply_filters( 'wpsight_madrid_get_listings_slider_args', wp_parse_args( $args, $defaults ), $listings, $args );
		
		// Sanitize slider class
		
		$class_slider = ! is_array( $args['class_slider'] ) ? explode( ' ', $args['class_slider'] ) : $args['class_slider'];		
		$class_slider = array_map( 'sanitize_html_class', $class_slider );
		$class_slider = implode( ' ', $class_slider );
		
		// Check image size
		$args['image_size'] = in_array( $args['image_size'], get_intermediate_image_sizes() ) || 'full' == $args['image_size'] ? $args['image_size'] : 'wpsight-large';
		
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
					thumbItem:		-1,
					gallery:		<?php echo $args['slider_dots_thumbs']; ?>,
					galleryMargin:	0,
					thumbMargin:	10,
					currentPagerPosition: 'middle'
				});
				
				<?php if( $args['slider_nav'] ) : ?>
				$('#<?php echo esc_attr( $slider_id ); ?>-wrap .slider-prev').click(function(){
    			    slider.goToPrevSlide(); 
    			});
    			$('#<?php echo esc_attr( $slider_id ); ?>-wrap .slider-next').click(function(){
    			    slider.goToNextSlide(); 
    			});
    			<?php endif; ?>
			    
			});
		</script>
		
		<div id="<?php echo esc_attr( $slider_id ); ?>-wrap" class="<?php echo $class_slider; ?>-wrap cS-hidden">
		
			<ul id="<?php echo esc_attr( $slider_id ); ?>" class="<?php echo $class_slider; ?>">
			
				<?php foreach( $listings as $listing ) : $post = get_post( $listing ); setup_postdata( $post ); ?>				
					<li data-thumb="<?php echo esc_attr( wpsight_get_listing_thumbnail_url( $post->ID, $args['slider_dots_thumbs_size'] ) ); ?>">
						<?php wpsight_get_template( 'listing-slider.php', array( 'post' => $post, 'counter' => $i, 'args' => $args ) ); ?>
					</li>							
				<?php $i++; endforeach; wp_reset_postdata(); ?>

			</ul><!-- .<?php echo $class_slider; ?> -->
			
			<?php if( $args['slider_nav'] ) : ?>
			<div class="wpsight-listings-slider-prev-next">
				<div class="slider-prev"><i class="fa fa-angle-left"></i></div>
				<div class="slider-next"><i class="fa fa-angle-right"></i></div>
			</div>
			<?php endif; ?>
		
		</div><?php
		
		return apply_filters( 'wpsight_madrid_get_listings_slider', ob_get_clean(), $listings, $args );
	
	}
	
}
