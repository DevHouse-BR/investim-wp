<div class="wpsight-listing-section wpsight-listing-section-info">
	
	<?php do_action( 'wpsight_listing_archive_info_before' ); ?>

	<div class="wpsight-listing-info">
		
		<div class="row gutter-40">
	
	    	<div class="col-xs-6">
	    	    <?php wpsight_listing_price(); ?>
	    	</div>
	    	
	    	<div class="col-xs-6">
	    	    <div class="wpsight-listing-status">
	    	    	<?php $listing_offer = wpsight_get_listing_offer( get_the_id(), false ); ?>
			    	<span class="label label-<?php echo esc_attr( $listing_offer ); ?>" style="background-color:<?php echo esc_attr( wpsight_get_offer_color( $listing_offer ) ); ?>"><?php wpsight_listing_offer(); ?></span>
			    </div>
	    	</div>
	    
	    </div><!-- .row -->
	    
	</div>
	
	<?php do_action( 'wpsight_listing_archive_info_after' ); ?>

</div><!-- .wpsight-listing-section-info -->