<?php
/**
 * The default page template file
 *
 * @package WPCasa Madrid
 */
global $wp_query;

get_header(); ?>

	<?php get_template_part( 'template-parts/header', 'page' ); ?>

	<div class="site-main">
	
		<?php do_action( 'wpsight_madrid_site_main_before', 'page', $wp_query ); ?>
	
		<div class="container">
		
			<div class="row gutter-60">

				<main class="content <?php if( is_active_sidebar( 'sidebar' ) ) : ?>col-md-8<?php else : ?>col-md-12<?php endif; ?>" role="main" itemprop="mainContentOfPage">
				
					<?php if( is_active_sidebar( 'content-top' ) ) : ?>
					<div class="content-top">				
						<?php dynamic_sidebar( 'content-top' ); ?>	
					</div><!-- .content-top -->
					<?php endif; ?>
			
					<?php if ( have_posts() ) : ?>
						<?php while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'template-parts/content', 'page' ); ?>
						<?php endwhile; ?>
					<?php else : ?>
						<?php get_template_part( 'template-parts/content', 'none' ); ?>
					<?php endif; ?>
			
					<?php if( is_active_sidebar( 'content-bottom' ) ) : ?>
					<div class="content-bottom">
						<?php dynamic_sidebar( 'content-bottom' ); ?>
					</div><!-- .content-bottom -->
					<?php endif; ?>

				</main><!-- .content -->
			
				<?php get_sidebar(); ?>

			</div><!-- .row -->
		
		</div><!-- .container -->
	
		<?php do_action( 'wpsight_madrid_site_main_after', 'page', $wp_query ); ?>
	
	</div><!-- .site-main -->

<?php get_footer(); ?>