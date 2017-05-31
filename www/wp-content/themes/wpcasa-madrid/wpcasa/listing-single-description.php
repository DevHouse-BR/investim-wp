<?php
/**
 * Template: Single Listing Description
 */
global $listing; ?>

<div class="wpsight-listing-section wpsight-listing-section-description">
	
	<?php if( wpsight_is_listing_not_available() ) : ?>
		<div class="wpsight-alert wpsight-alert-small wpsight-alert-not-available bs-callout bs-callout-primary">
			<?php _e( 'This property is currently not available.', 'wpcasa' ); ?>
		</div>
	<?php endif; ?>
	
	<?php do_action( 'wpsight_listing_single_description_before', $listing->ID ); ?>

	<div class="wpsight-listing-description" itemprop="description">
		<?php echo apply_filters( 'wpsight_listing_description', wpsight_format_content( $listing->post_content ) ); ?>
	</div>
	
	<?php do_action( 'wpsight_listing_single_description_after', $listing->ID ); ?>

</div><!-- .wpsight-listing-section -->