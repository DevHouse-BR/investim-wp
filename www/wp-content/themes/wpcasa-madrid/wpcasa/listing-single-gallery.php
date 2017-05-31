<?php
/**
 * Template: Single Listing Gallery
 *
 * When used in a widget, the variables
 * $widget_args and $widget_instance are available.
 */
global $listing;

$gallery_args = isset( $gallery_args ) ? $gallery_args : array();

$gallery = get_post_meta( $listing->ID, '_gallery', true ); ?>

<?php if( $gallery ) : ?>

	<div class="wpsight-listing-section wpsight-listing-section-gallery">
		
		<?php do_action( 'wpsight_listing_single_gallery_before' ); ?>
	
		<div class="wpsight-listing-gallery">
			<?php wpsight_madrid_image_gallery( $listing->ID, $gallery_args ); ?>
		</div>
		
		<?php do_action( 'wpsight_listing_single_gallery_after' ); ?>
	
	</div><!-- .wpsight-listing-section -->

<?php endif; ?>