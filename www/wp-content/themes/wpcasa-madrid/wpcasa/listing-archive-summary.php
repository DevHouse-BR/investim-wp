<div class="wpsight-listing-section wpsight-listing-section-summary">
	
	<?php do_action( 'wpsight_listing_archive_summary_before' ); ?>

	<?php wpsight_listing_summary( get_the_id(), array( 'details_1', 'details_2', 'details_3', 'details_4' ) ); ?>
	
	<?php do_action( 'wpsight_listing_archive_summary_after' ); ?>

</div><!-- .wpsight-listing-section-summary -->