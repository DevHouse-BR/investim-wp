<?php
/**
 * Template: List Agent
 */

$display_image		= isset( $args['display_image'] ) && ! $args['display_image'] ? false : true;
$image_size			= isset( $args['image_size'] ) ? $args['image_size'] : 'post-thumbnail';
$display_company	= isset( $args['display_company'] ) && ! $args['display_company'] ? false : true;
$display_phone		= isset( $args['display_phone'] ) && ! $args['display_phone'] ? false : true;
$display_social		= isset( $args['display_social'] ) && ! $args['display_social'] ? false : true;
$display_archive	= isset( $args['display_archive'] ) && ! $args['display_archive'] ? false : true; ?>

<div class="wpsight-list-agent-section" itemprop="seller" itemscope itemtype="http://schema.org/RealEstateAgent">
	
	<?php do_action( 'wpsight_list_agent_before', $user->ID ); ?>

	<div class="wpsight-list-agent clearfix">
	
		<?php if( wpsight_get_agent_image( $user->ID ) && $display_image ) : ?>
	
	    <div class="wpsight-list-agent-image">
	        <span itemprop="image"><?php wpsight_agent_image( $user->ID, $image_size ); ?></span>
	    </div><!-- .wpsight-list-agent-image -->
	    
	    <?php endif; ?>
	    
	    <div class="wpsight-list-agent-info">

	        <div class="wpsight-list-agent-name" itemprop="name">

	        	<?php wpsight_agent_name( $user->ID ); ?>

	        	<?php if( wpsight_get_agent_company( $user->ID ) && $display_company ) : ?>
	        	<span class="wpsight-list-agent-company">(<?php wpsight_agent_company( $user->ID ); ?>)</span>
	        	<?php endif; ?>
	        	
	        	<?php if( wpsight_get_agent_phone( $user->ID ) && $display_phone ) : ?>
	        	<span class="wpsight-list-agent-phone"><?php wpsight_agent_phone( $user->ID ); ?></span>
	        	<?php endif; ?>

	        </div>
	        
	        <?php if( $display_social ) : ?>
	        
	        <div class="wpsight-list-agent-links">
	        
	        	<?php if( wpsight_get_agent_website( $user->ID ) ) : ?>
	        	<a href="<?php wpsight_agent_website( $user->ID ); ?>" class="agent-website" title="<?php echo esc_attr( wpsight_get_agent_website( $user->ID ) ); ?>" itemprop="url" target="_blank" rel="nofollow" data-toggle="tooltip" data-placement="top"><i class="fa fa-link"></i></a>
	        	<?php endif; ?>
	        	
	        	<?php if( wpsight_get_agent_twitter( $user->ID ) ) : ?>
	        	<a href="<?php wpsight_agent_twitter( $user->ID, 'url' ); ?>" class="agent-twitter" title="@<?php echo esc_attr( wpsight_get_agent_twitter( $user->ID ) ); ?>" target="_blank" rel="nofollow" data-toggle="tooltip" data-placement="top"><i class="fa fa-twitter"></i></a>
	        	<?php endif; ?>
	        	
	        	<?php if( wpsight_get_agent_facebook( $user->ID ) ) : ?>
	        	<a href="<?php wpsight_agent_facebook( $user->ID, 'url' ); ?>" class="agent-facebook" title="<?php echo esc_attr( wpsight_get_agent_facebook( $user->ID ) ); ?>" target="_blank" rel="nofollow" data-toggle="tooltip" data-placement="top"><i class="fa fa-facebook"></i></a>
	        	<?php endif; ?>

	        </div>
	        
	        <?php endif; ?>

	        <div class="wpsight-list-agent-description" itemprop="description">
	        	<?php wpsight_agent_description( $user->ID ); ?>
	        </div>
	        
	        <?php if( wpsight_get_agent_archive( $user->ID ) && $display_archive ) : ?>	        
	        <div class="wpsight-list-agent-archive">
	        	<a href="<?php wpsight_agent_archive( $user->ID ); ?>" class="btn btn-primary"><?php _e( 'My Listings', 'wpcasa' ); ?></a>
	        </div>
	        <?php endif; ?>
	    
	    </div><!-- .wpsight-list-agent-info -->
	    
	</div><!-- .wpsight-list-agent -->
	
	<?php do_action( 'wpsight_list_agent_after', $user->ID ); ?>

</div><!-- .wpsight-list-agent-section -->
