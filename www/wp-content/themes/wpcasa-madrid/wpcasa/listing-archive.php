<?php
	
$col	= 'col-sm-6';
$equal	= true;

if( ! is_page_template( 'page-tpl-full.php' ) && is_active_sidebar( 'sidebar' ) )	
	$col = 'col-sm-12';

if( is_page_template( 'page-tpl-home.php' ) )	
	$col = 'col-sm-6';

if( is_page_template( 'page-tpl-listings-query-full.php' ) )	
	$col = 'col-sm-6';

if( is_array( $args ) && isset( $args['context'] ) ) {
	
	if( 'sidebar-listing' == $args['context'] ) {
		$col	= 'col-sm-12';
		$equal	= false;
	}
	
	if( 'sidebar' == $args['context'] ) {
		$col	= 'col-sm-12';
		$equal	= false;
	}
	
	if( in_array( $args['context'], array( 'header-main', 'footer-main', 'header-full', 'footer-full' ) ) ) {
		$col	= 'col-sm-6';
		$equal	= true;
	}
	
} ?>

<div id="listing-<?php the_ID(); ?>" class="listing-wrap <?php echo esc_attr( $col ); ?>">

	<div <?php wpsight_listing_class( 'wpsight-listing-archive' ); ?> itemscope itemtype="http://schema.org/Product">
	
		<meta itemprop="name" content="<?php echo esc_attr( $post->post_title ); ?>" />
		
		<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="clearfix">
	
			<?php do_action( 'wpsight_listing_archive_before', $post ); ?>
				
			<?php wpsight_get_template( 'listing-archive-image.php' ); ?>
			
			<div class="listing-content<?php if( true == $equal ) echo ' equal'; ?>" data-mh="listing-content">
				
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