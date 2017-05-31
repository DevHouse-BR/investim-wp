<?php
/**
 * WPCasa Bootstrap listings carousel
 *
 * @package WPCasa Madrid
 */

/**
 * wpsight_madrid_listings_carousel()
 *
 * Echo wpsight_madrid_get_listings_carousel()
 *
 * @param object|array $listings Query object or post IDs array
 * @param array $args Optional carousel arguments
 *
 * @uses wpsight_madrid_get_listings_carousel()
 *
 * @since 1.0.0
 */
function wpsight_madrid_listings_carousel( $listings = array(), $args = array() ) {
	echo wpsight_madrid_get_listings_carousel( $listings, $args );
}

/**
 * wpsight_madrid_get_listings_carousel()
 *
 * Create listings carousel.
 *
 * @param object|array $listings Query object or post IDs array
 * @param array $args Optional carousel arguments
 *
 * @uses get_post_meta()
 * @uses get_post()
 * @uses wp_get_attachment_image_src()
 *
 * @return string HTML markup of thumbnail gallery with lightbox
 *
 * @since 1.0.0
 */
function wpsight_madrid_get_listings_carousel( $listings = array(), $args = array() ) {
	global $post;
	
	// If we have a query object or ID array
	
	if( is_array( $listings ) ) {		
		$listings = array_map( 'absint', $listings );		
	} elseif( is_object( $listings ) ) {		
		$listings = wp_list_pluck( $listings->posts, 'ID' );		
	}
		
	// Set gallery ID
	$carousel_id = 'wpsight-listings-carousel-' . implode( '-', $listings );
	
	// If there are listings, create the carousel
	
	if( $listings ) {
		
		// Set some defaults
		
		$defaults = array(
			'class_carousel'   			=> $carousel_id . ' wpsight-listings-carousel',
			'carousel_items'			=> 3,
			'carousel_slide_by'			=> 1,
			'carousel_margin'			=> 40,
			'carousel_loop'				=> false,
			'carousel_nav'				=> true,
			'carousel_dots'				=> true,
			'carousel_autoplay'			=> true,
			'carousel_autoplay_time'	=> 5000,
			'carousel_context'			=> ''
		);
		
		// Merge args with defaults and apply filter
		$args = apply_filters( 'wpsight_madrid_get_listings_carousel_args', wp_parse_args( $args, $defaults ), $listings, $args );
		
		// Sanitize gallery class
		
		$class_carousel = ! is_array( $args['class_carousel'] ) ? explode( ' ', $args['class_carousel'] ) : $args['class_carousel'];		
		$class_carousel = array_map( 'sanitize_html_class', $class_carousel );
		$class_carousel = implode( ' ', $class_carousel );
		
		// Set counter
		$i = 0;
		
		// Set unique ID
		$unique_id = $carousel_id . '-' . uniqid();
		
		ob_start(); ?>
		
		<script type="text/javascript">
			jQuery(document).ready(function($){
    
			    var slider = $('#<?php echo $unique_id; ?>').lightSlider({
					item:			<?php echo absint( $args['carousel_items'] ); ?>,
					loop:			<?php echo $args['carousel_loop'] == true ? 'true' : 'false'; ?>,
					pager:			<?php echo $args['carousel_dots'] == true ? 'true' : 'false'; ?>,
					auto:			<?php echo $args['carousel_autoplay'] == true ? 'true' : 'false'; ?>,
					pause:			<?php echo absint( $args['carousel_autoplay_time'] ); ?>,
					slideMove:		<?php echo absint( $args['carousel_slide_by'] ); ?>,
					slideMargin:	<?php echo absint( $args['carousel_margin'] ); ?>,
					cssEasing:		'cubic-bezier(0.25, 0, 0.25, 1)',
					easing:			'linear',
					speed:			500,
					pauseOnHover:	true,
					controls:		false,
					rtl:			false,
					responsive : [
					    {
							breakpoint:991,
							settings: {
								item: 1,
								slideMove: 1
							}
						}
					],
					onSliderLoad:	function() {
					    $('#<?php echo esc_attr( $unique_id ); ?>-wrap').removeClass('cS-hidden');
					}
				});
				
				<?php if( $args['carousel_nav'] ) : ?>
				$('#<?php echo esc_attr( $unique_id ); ?>-wrap .carousel-prev').click(function(){
    			    slider.goToPrevSlide(); 
    			});
    			$('#<?php echo esc_attr( $unique_id ); ?>-wrap .carousel-next').click(function(){
    			    slider.goToNextSlide(); 
    			});
    			<?php endif; ?>
			    
			});
		</script>
		
		<div id="<?php echo esc_attr( $unique_id ); ?>-wrap" class="<?php echo $class_carousel; ?>-wrap cS-hidden">
		
			<ul id="<?php echo esc_attr( $unique_id ); ?>" class="<?php echo $class_carousel; ?>">			
				<?php foreach( $listings as $listing ) : $post = get_post( $listing ); setup_postdata( $post ); ?>				
					<li><?php wpsight_get_template( 'listing-carousel.php', array( 'post' => $post, 'counter' => $i, 'args' => $args ) ); ?></li>							
				<?php $i++; endforeach; wp_reset_postdata(); ?>			
			</ul><!-- .<?php echo $class_carousel; ?> -->
			
			<?php if( $args['carousel_nav'] ) : ?>
			<div class="wpsight-listings-carousel-prev-next">
				<div class="carousel-prev"><i class="fa fa-angle-left"></i></div>
				<div class="carousel-next"><i class="fa fa-angle-right"></i></div>
			</div>
			<?php endif; ?>
		
		</div><?php
		
		return apply_filters( 'wpsight_madrid_get_listings_carousel', ob_get_clean(), $listings, $args );
	
	}
	
}
