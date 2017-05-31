<?php
/**
 * Template: Single Listing Slider
 *
 * When used in a widget, the variables
 * $widget_args and $widget_instance are available.
 */
global $listing;

$slider_args = isset( $slider_args ) ? $slider_args : array();

$slider = get_post_meta( $listing->ID, '_gallery', true ); ?>

<?php if( $slider ) : ?>

	<div class="wpsight-listing-section wpsight-listing-section-slider">
		
		<?php do_action( 'wpsight_listing_single_slider_before' ); ?>
	
		<div class="wpsight-listing-slider">
			<?php wpsight_madrid_image_slider( $listing->ID, $slider_args ); ?>
		</div>
		
		<?php do_action( 'wpsight_listing_single_slider_after' ); ?>
	
	</div><!-- .wpsight-listing-section -->

<?php endif; ?>