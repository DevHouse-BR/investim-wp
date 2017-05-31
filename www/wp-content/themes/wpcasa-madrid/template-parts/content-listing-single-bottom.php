<?php
/**
 *	The template part for displaying full-width content
 *	on the single listing page.
 *	
 *	@package WPCasa Madrid
 */
?>

<?php if( is_active_sidebar( 'listing-bottom' ) ) : ?>

	<div class="site-main-bottom listing-bottom">
		
		<div class="container">			
			<?php dynamic_sidebar( 'listing-bottom' ); ?>				
		</div><!-- .container -->
		
	</div><!-- .main-bottom -->

<?php endif; ?>
