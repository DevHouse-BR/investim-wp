<div class="wpsight-listing-section wpsight-listing-section-summary">
	
	<?php do_action( 'wpsight_listing_archive_summary_before' ); ?>

	<?php wpsight_listing_summary( get_the_id(), array( 'faturamento_mensal', 'margem_lucro', 'lucro_liquido', 'lucro_bruto' ) ); ?>
	
	<?php do_action( 'wpsight_listing_archive_summary_after' ); ?>

</div><!-- .wpsight-listing-section-summary -->