<?php
/**
 * Template: Single Listing Info
 *
 * When used in a widget, the variables
 * $widget_args and $widget_instance are available.
 */
global $listing;

$display_id		= isset( $widget_instance['display_id'] ) && ! $widget_instance['display_id'] ? false : true;
$display_offer	= isset( $widget_instance['display_offer'] ) && ! $widget_instance['display_offer'] ? false : true;

// Get listing offer key
$listing_offer = wpsight_get_listing_offer( $listing->ID, false ); ?>

<div class="wpsight-listing-section wpsight-listing-section-info">
	
	<?php do_action( 'wpsight_listing_single_info_before', $listing->ID ); ?>

	<div class="wpsight-listing-info clearfix">
	
		<div class="row gutter-40">
		
			<?php if( $display_id || $display_offer ) : ?>
	
	    		<div class="col-xs-6">
	    		    <?php wpsight_listing_price( $listing->ID ); ?>
	    		</div>
				
	    		<div class="col-xs-6">
	    			<?php if( $display_id ) : ?>
	    			<div class="wpsight-listing-id">
	    				<?php wpsight_listing_id( $listing->ID ); ?>
	    			</div>
	    			<?php endif; ?>
	    			<?php if( $display_offer ) : ?>
	    		    <div class="wpsight-listing-offer">
				    	<span class="label label-<?php echo esc_attr( $listing_offer ); ?>" style="background-color:<?php echo esc_attr( wpsight_get_offer_color( $listing_offer ) ); ?>"><?php wpsight_listing_offer( $listing->ID ); ?></span>
				    </div>
				    <?php endif; ?>
	    		</div>
	    	
	    	<?php else : ?>
	    	
	    		<div class="col-xs-12">
				    <?php wpsight_listing_price( $listing->ID ); ?>
				</div>
	    	
	    	<?php endif; ?>
	    
	    </div><!-- .row -->

	</div>
	
	<?php do_action( 'wpsight_listing_single_info_after', $listing->ID ); ?>

</div><!-- .wpsight-listing-section-info -->