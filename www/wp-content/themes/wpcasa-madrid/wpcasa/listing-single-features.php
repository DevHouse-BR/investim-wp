<?php
/**
 * Template: Single Listing Features
 *
 * When used in a widget, the variables
 * $widget_args and $widget_instance are available.
 */
global $listing;

$taxonomy	= isset( $widget_instance['taxonomy'] ) ? $widget_instance['taxonomy'] : 'feature';
$linked		= isset( $widget_instance['linked'] ) && ! $widget_instance['linked'] ? false : true; ?>

<?php if( wpsight_get_listing_terms( $taxonomy, $listing->ID ) ) : ?>

<div class="wpsight-listing-section wpsight-listing-section-features">
	
	<?php do_action( 'wpsight_listing_single_features_before', $listing->ID ); ?>

	<div class="wpsight-listing-features">		
		<?php wpsight_listing_terms( $taxonomy, $listing->ID, '', '<i class="fa fa-check" aria-hidden="true"></i>', '', $linked ); ?>		
	</div>
	
	<?php do_action( 'wpsight_listing_single_features_after', $listing->ID ); ?>

</div><!-- .wpsight-listing-section -->

<?php endif; ?>