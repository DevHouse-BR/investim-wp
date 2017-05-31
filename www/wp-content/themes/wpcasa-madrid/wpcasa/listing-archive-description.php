<div class="wpsight-listing-section wpsight-listing-section-description">
	
	<?php if( wpsight_is_listing_not_available() ) : ?>
		<div class="wpsight-alert wpsight-alert-small wpsight-alert-not-available bs-callout bs-callout-small bs-callout-primary">
			<?php _e( 'This property is currently not available.', 'wpcasa' ); ?>
		</div>
	<?php endif; ?>
	
	<?php do_action( 'wpsight_listing_archive_description_before' ); ?>

	<div class="wpsight-listing-description" itemprop="description">	
		<?php echo apply_filters( 'wpsight_listing_description', wpsight_madrid_the_excerpt( get_the_id(), true, 20 ) ); ?>
	</div>
	
	<?php do_action( 'wpsight_listing_archive_description_after' ); ?>

</div><!-- .wpsight-listing-section -->