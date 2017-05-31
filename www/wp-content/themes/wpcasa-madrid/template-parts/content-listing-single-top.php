<?php
/**
 * The template part for displaying full-width content
 * above the regular content on the single listing page.
 *
 * @package WPCasa Madrid
 */
?>

<?php if( is_active_sidebar( 'listing-top' ) ) : ?>

	<div class="site-main-top listing-top">
		
		<div class="container">			
			<?php dynamic_sidebar( 'listing-top' ); ?>				
		</div><!-- .container -->
		
	</div><!-- .main-top -->

<?php endif; ?>
