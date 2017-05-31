<div class="listing-<?php the_ID(); ?>-carousel-wrap listing-carousel-wrap listing-wrap">

	<div id="listing-<?php the_ID(); ?>-carousel" <?php wpsight_listing_class( 'wpsight-listing-archive wpsight-listing-carousel' ); ?> itemscope itemtype="http://schema.org/Product">
	
		<meta itemprop="name" content="<?php echo esc_attr( $post->post_title ); ?>" />
		
		<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="clearfix">
	
			<?php do_action( 'wpsight_listing_archive_before', $post ); ?>
				
			<?php wpsight_get_template( 'listing-archive-image.php' ); ?>
			
			<div class="listing-content equal" data-mh="listing-carousel-content">
				
				<?php wpsight_get_template( 'listing-archive-info.php' ); ?>
			
				<?php wpsight_get_template( 'listing-archive-title.php' ); ?>
				
				<?php wpsight_get_template( 'listing-archive-description.php' ); ?>
				
				<?php wpsight_get_template( 'listing-archive-compare.php' ); ?>
			
			</div><!-- .listing-content -->
				
			<?php wpsight_get_template( 'listing-archive-summary.php' ); ?>
			
			<?php do_action( 'wpsight_listing_archive_after', $post ); ?>
		
		</div>
	
	</div><!-- #listing-<?php the_ID(); ?> -->

</div><!-- .listing-wrap -->