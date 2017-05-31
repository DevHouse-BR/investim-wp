<div class="wpsight-listing-teaser entry-content" itemscope itemtype="http://schema.org/Product">

	<meta itemprop="name" content="<?php echo esc_attr( $post->post_title ); ?>" />
	
	<div itemprop="offers" class="clearfix" itemscope itemtype="http://schema.org/Offer">

		<?php do_action( 'wpsight_listing_teaser_before' ); ?>

		<div class="wpsight-listing-teaser-left">
			
			<?php if( wpsight_get_listing_thumbnail_url( $post->ID ) ) : ?>
			<meta itemprop="image" content="<?php echo esc_attr( wpsight_listing_thumbnail_url( $post->ID, 'large' ) ); ?>" />
			<?php endif; ?>
				
			<div class="wpsight-listing-teaser-image">
				<a href="<?php the_permalink(); ?>" rel="bookmark">
					<?php wpsight_listing_thumbnail( $post->ID, 'thumbnail', array( 'title' => '', 'data-mh' => 'listing-teaser-thumbnail' ), '<span class="listing-image-default"><i class="fa fa-home" aria-hidden="true"></i></span>' ); ?>
				</a>
			</div>

		</div>
		
		<div class="wpsight-listing-teaser-right">
			
			<?php wpsight_listing_price( $post->ID ); ?>
				
			<div class="wpsight-listing-teaser-title">
				<h4 class="entry-title">
					<a href="<?php echo get_permalink( $post->ID ); ?>" rel="bookmark"><?php echo get_the_title( $post->ID ); ?></a>
				</h4>
			</div>
			
			<div class="listing-teaser-location-type">				
				<?php wpsight_listing_terms( 'location', $post->ID, ' &rsaquo; ' ); ?>		
				<?php if( wpsight_get_listing_terms( 'location', $post->ID ) && wpsight_get_listing_terms( 'listing-type', $post->ID ) ) : ?>
					<span class="separator">/</span>
				<?php endif; ?>				
				<?php wpsight_listing_terms( 'listing-type', $post->ID, ', ' ); ?>				
			</div>
		
		</div>

		<?php do_action( 'wpsight_listing_teaser_after' ); ?>

	</div>

</div><!-- .wpsight-listing -->