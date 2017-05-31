<?php if( wpsight_get_listing_thumbnail_url( get_the_id() ) ) : ?>
<meta itemprop="image" content="<?php echo esc_attr( wpsight_get_listing_thumbnail_url( get_the_id(), 'wpsight-large' ) ); ?>" />
<?php endif; ?>

<div class="wpsight-listing-section wpsight-listing-section-image">
	
	<?php do_action( 'wpsight_listing_archive_image_before' ); ?>

	<div class="wpsight-listing-image">
		<a href="<?php the_permalink(); ?>" rel="bookmark">
			<?php wpsight_listing_thumbnail( get_the_id(), 'wpsight-large', array( 'title' => '', 'data-mh' => 'listing-thumbnail' ), '<span class="listing-image-default"><i class="fa fa-home" aria-hidden="true"></i></span>' ); ?>
		</a>
	</div>
	
	<?php do_action( 'wpsight_listing_archive_image_after' ); ?>

</div><!-- .wpsight-listing-section -->