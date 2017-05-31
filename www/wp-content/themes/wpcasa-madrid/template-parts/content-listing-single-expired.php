<?php
/**
 * The template for expired listings.
 *
 * @package WPCasa Madrid
 */

get_header(); ?>

	<div class="site-main">
		
		<div class="wrap">
		
			<div class="wpsight-alert wpsight-alert-expired bs-callout bs-callout-danger">
				<?php _e( 'This listing has expired.', 'wpcasa-madrid' ); ?>
			</div>
		
		</div><!-- .wrap -->
	
	</div><!-- .site-main -->

<?php get_footer(); ?>
