<?php
/**
 * Template: Single Listing Agent
 *
 * When used in a widget, the variables
 * $widget_args and $widget_instance are available.
 */
global $listing;

$display_image		= isset( $widget_instance['display_image'] ) && ! $widget_instance['display_image'] ? false : true;
$image_size			= isset( $widget_instance['image_size'] ) ? $widget_instance['image_size'] : 'post-thumbnail';
$display_company	= isset( $widget_instance['display_company'] ) && ! $widget_instance['display_company'] ? false : true;
$display_phone		= isset( $widget_instance['display_phone'] ) && ! $widget_instance['display_phone'] ? false : true;
$display_social		= isset( $widget_instance['display_social'] ) && ! $widget_instance['display_social'] ? false : true;
$display_archive	= isset( $widget_instance['display_archive'] ) && ! $widget_instance['display_archive'] ? false : true; ?>

<div class="wpsight-listing-section wpsight-listing-section-agent" itemprop="seller" itemscope itemtype="http://schema.org/RealEstateAgent">
	
	<?php do_action( 'wpsight_listing_single_agent_before', $listing->ID ); ?>

	<div class="wpsight-listing-agent clearfix">
	
		<?php if( wpsight_get_listing_agent_image( $listing->ID ) && $display_image ) : ?>
	
	    <div class="wpsight-listing-agent-image">
	        <span itemprop="image"><?php wpsight_listing_agent_image( $listing->ID, $image_size ); ?></span>
	    </div><!-- .wpsight-listing-agent-image -->
	    
	    <?php endif; ?>
	    
	    <div class="wpsight-listing-agent-info">

	        <div class="wpsight-listing-agent-name" itemprop="name">

	        	<?php wpsight_listing_agent_name( $listing->ID ); ?>

	        	<?php if( wpsight_get_listing_agent_company( $listing->ID ) && $display_company ) : ?>
	        	<span class="wpsight-listing-agent-company">(<?php wpsight_listing_agent_company( $listing->ID ); ?>)</span>
	        	<?php endif; ?>
	        	
	        	<?php if( wpsight_get_listing_agent_phone( $listing->ID ) && $display_phone ) : ?>
	        	<span class="wpsight-listing-agent-phone"><?php wpsight_listing_agent_phone( $listing->ID ); ?></span>
	        	<?php endif; ?>

	        </div>
	        
	        <?php if( $display_social ) : ?>
	        
	        <div class="wpsight-listing-agent-links">
	        
	        	<?php if( wpsight_get_listing_agent_website( $listing->ID ) ) : ?>
	        	<a href="<?php wpsight_listing_agent_website( $listing->ID ); ?>" class="agent-website" title="<?php echo esc_attr( wpsight_get_listing_agent_website( $listing->ID ) ); ?>" itemprop="url" target="_blank" rel="nofollow" data-toggle="tooltip" data-placement="top"><i class="fa fa-link"></i></a>
	        	<?php endif; ?>
	        	
	        	<?php if( wpsight_get_listing_agent_twitter( $listing->ID ) ) : ?>
	        	<a href="<?php wpsight_listing_agent_twitter( $listing->ID, 'url' ); ?>" class="agent-twitter" title="@<?php echo esc_attr( wpsight_get_listing_agent_twitter( $listing->ID ) ); ?>" target="_blank" rel="nofollow" data-toggle="tooltip" data-placement="top"><i class="fa fa-twitter"></i></a>
	        	<?php endif; ?>
	        	
	        	<?php if( wpsight_get_listing_agent_facebook( $listing->ID ) ) : ?>
	        	<a href="<?php wpsight_listing_agent_facebook( $listing->ID, 'url' ); ?>" class="agent-facebook" title="<?php echo esc_attr( wpsight_get_listing_agent_facebook( $listing->ID ) ); ?>" target="_blank" rel="nofollow" data-toggle="tooltip" data-placement="top"><i class="fa fa-facebook"></i></a>
	        	<?php endif; ?>

	        </div>
	        
	        <?php endif; ?>

	        <div class="wpsight-listing-agent-description" itemprop="description">
	        	<?php wpsight_listing_agent_description( $listing->ID ); ?>
	        </div>
	        
	        <?php if( wpsight_get_listing_agent_archive( $listing->ID ) && $display_archive ) : ?>	        
	        <div class="wpsight-listing-agent-archive">
	        	<a href="<?php wpsight_listing_agent_archive( $listing->ID ); ?>" class="btn btn-primary"><?php _e( 'My Listings', 'wpcasa' ); ?></a>
	        </div>
	        <?php endif; ?>
	    
	    </div><!-- .wpsight-listing-agent-info -->
	    
	</div><!-- .wpsight-listing-agent -->
	
	<?php do_action( 'wpsight_listing_single_agent_after', $listing->ID ); ?>

</div><!-- .wpsight-listing-section-agent -->