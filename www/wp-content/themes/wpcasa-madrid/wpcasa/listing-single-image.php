<?php
/**
 * Template: Single Listing Image
 *
 * When used in a widget, the variables
 * $widget_args and $widget_instance are available.
 */
global $listing;

$image_size = isset( $widget_args['id'] ) && ( $widget_args['id'] == 'listing-top' || $widget_args['id'] == 'listing-bottom' ) ? 'wpsight-full' : 'large';
$image_size = isset( $widget_args['id'] ) && ! is_active_sidebar( 'sidebar-listing' ) ? 'wpsight-full' : $image_size; ?>

<?php if( has_post_thumbnail( $listing->ID ) ) : ?>

	<meta itemprop="image" content="<?php echo esc_attr( wpsight_listing_thumbnail_url( $listing->ID, $image_size ) ); ?>" />

	<div class="wpsight-listing-section wpsight-listing-section-image">
		
		<?php do_action( 'wpsight_listing_single_image_before' ); ?>
	
		<div class="wpsight-listing-image">
			<?php wpsight_listing_thumbnail( $listing->ID, $image_size ); ?>
		</div>
		
		<?php do_action( 'wpsight_listing_single_image_after' ); ?>
	
	</div><!-- .wpsight-listing-section -->

<?php endif; ?>