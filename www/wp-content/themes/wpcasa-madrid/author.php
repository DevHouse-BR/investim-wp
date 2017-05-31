<?php
/**
 * The template for displaying author archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WPCasa Madrid
 */
global $wp_query;

$archive = wpsight_is_listings_archive() ? 'archive-listings' : 'archive';

get_header(); ?>
	
	<?php get_template_part( 'template-parts/header', $archive ); ?>

	<div class="site-main">
	
		<?php do_action( 'wpsight_madrid_site_main_before', $archive, $wp_query ); ?>
	
		<div class="container">
		
			<div class="row gutter-60">

				<main class="content <?php if( is_active_sidebar( 'sidebar' ) ) : ?>col-md-8<?php else : ?>col-md-12<?php endif; ?>" role="main" itemprop="mainContentOfPage">
				
					<?php if( is_active_sidebar( 'content-top' ) ) : ?>
					<div class="content-top">				
						<?php dynamic_sidebar( 'content-top' ); ?>	
					</div><!-- .content-top -->
					<?php endif; ?>

					<?php if ( have_posts() ) : ?>
						
						<?php
							// Display agent info if listing archive
							
							if( wpsight_is_listing_agent_archive() ) {
								
								wpsight_get_template( 'list-agent.php', array( 'user' => get_queried_object(), 'args' => array(
									'display_image' 	=> true,
									'display_company'	=> true,
									'display_phone'		=> true,
									'display_social' 	=> true,
									'display_archive' 	=> false
								) ) );
								
								wpsight_panel( $wp_query );
								
							}
						?>

						<?php if( ! wpsight_is_listings_archive() ) : ?><div class="row gutter-60"><?php endif; ?>

						<?php while ( have_posts() ) : the_post(); ?>						
							<?php get_template_part( 'template-parts/content', get_post_format() ); ?>
						<?php endwhile; ?>
						
						<?php if( ! wpsight_is_listings_archive() ) : ?></div><?php endif; ?>
					
						<?php wpsight_pagination( $wp_query->max_num_pages ); ?>
					
					<?php else : ?>
					
						<?php if( wpsight_is_listing_agent_archive() ) : ?>
							<?php get_template_part( 'template-parts/content', 'none' ); ?>
						<?php else : ?>					
							<?php wpsight_get_template( 'listings-no.php' ); ?>
						<?php endif; ?>
					
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
	
		<?php do_action( 'wpsight_madrid_site_main_after', $archive, $wp_query ); ?>
	
	</div><!-- .site-main -->

<?php get_footer(); ?>
