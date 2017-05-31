<div class="listing-<?php the_ID(); ?>-slider-wrap listing-slider-wrap listing-wrap" style="background-image: url(<?php echo esc_url( wpsight_get_listing_thumbnail_url( $post->ID, $args['image_size'] ) ); ?>)">

	<div id="listing-<?php the_ID(); ?>-slider" <?php wpsight_listing_class( 'wpsight-listing-archive wpsight-listing-slider' ); ?> itemscope itemtype="http://schema.org/Product">
	
		<meta itemprop="name" content="<?php echo esc_attr( $post->post_title ); ?>" />
		<meta itemprop="image" content="<?php echo esc_attr( wpsight_get_listing_thumbnail_url( $post->ID, 'wpsight-large' ) ); ?>" />
		
		<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="clearfix">
	
			<?php do_action( 'wpsight_listing_slider_before', $post ); ?>
			
			<div class="listing-content">
			
				<?php wpsight_listing_price( $post->ID ); ?>
			
				<?php wpsight_get_template( 'listing-archive-title.php' ); ?>
				
				<div class="listing-content-location-type">				
					<?php wpsight_listing_terms( 'location', $post->ID, ' &rsaquo; ' ); ?>		
					<?php if( wpsight_get_listing_terms( 'location', $post->ID ) && wpsight_get_listing_terms( 'listing-type', $post->ID ) ) : ?>
						<span class="separator">/</span>
					<?php endif; ?>				
					<?php wpsight_listing_terms( 'listing-type', $post->ID, ', ' ); ?>				
				</div>
				
				<div class="listing-content-button">
					<a href="<?php the_permalink(); ?>" rel="bookmark" class="btn btn-default">
						<?php _e( 'More Info', 'wpcasa-madrid' ); ?>
					</a>
				</div>
			
			</div><!-- .listing-content -->
			
			<?php do_action( 'wpsight_listing_slider_after', $post ); ?>
		
		</div>
	
	</div><!-- #listing-<?php the_ID(); ?> -->

</div><!-- .listing-wrap -->