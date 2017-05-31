<?php
/**
 * The template for displaying single posts.
 *
 * @package WPCasa Madrid
 */
global $wp_query;

get_header(); ?>

	<?php get_template_part( 'template-parts/header', 'post' ); ?>

	<div class="site-main">
	
		<?php do_action( 'wpsight_madrid_site_main_before', 'post', $wp_query ); ?>
	
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

							<?php get_template_part( 'template-parts/content', get_post_format() ); ?>
							
							<?php
								// If comments are open or we have at least one comment, load up the comment template
								if ( comments_open() || get_comments_number() ) :
									comments_template();
								endif;
							?>
							
						<?php endwhile; ?>

					<?php else : ?>

						<?php get_template_part( 'templates/content', 'none' ); ?>

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
	
		<?php do_action( 'wpsight_madrid_site_main_after', 'post', $wp_query ); ?>
	
	</div><!-- .site-main -->

<?php get_footer(); ?>