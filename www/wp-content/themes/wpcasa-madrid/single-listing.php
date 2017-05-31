<?php
/**
 * The template for single listings.
 *
 * @package WPCasa Madrid
 */
get_header(); ?>

	<?php get_template_part( 'template-parts/header', 'listing' ); ?>

	<?php if( ! wpsight_is_listing_expired() || wpsight_user_can_edit_listing( get_the_id() ) ) : ?>

		<?php get_template_part( 'template-parts/content-listing', 'single-top' ); ?>
		
		<div class="site-main">
	
			<div class="container">
			
				<div class="row gutter-60">
			
					<main class="content <?php if( is_active_sidebar( 'sidebar-listing' ) || ( ! is_active_sidebar( 'listing' ) && is_active_sidebar( 'sidebar' ) ) ) : ?>col-md-8<?php else : ?>col-md-12<?php endif; ?>" role="main" itemprop="mainContentOfPage">
		
						<?php while ( have_posts() ) : the_post(); ?>						
							<?php get_template_part( 'template-parts/content-listing', 'single' ); ?>						
						<?php endwhile; // end of the loop. ?>
					
					</main>
					
					<?php get_sidebar( 'listing' ); ?>
				
				</div><!-- .row -->
			
			</div><!-- .container -->
		
		</div><!-- .site-main -->
		
		<?php get_template_part( 'template-parts/content-listing', 'single-bottom' ); ?>
	
	<?php else: ?>
	
		<?php get_template_part( 'template-parts/content-listing', 'single-expired' ); ?>
	
	<?php endif; ?>

<?php get_footer(); ?>
