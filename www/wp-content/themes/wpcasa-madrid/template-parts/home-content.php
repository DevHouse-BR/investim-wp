<?php
/**
 * Home content template
 *
 * @package WPCasa Madrid
 */
global $wp_query;

if( ! empty( $post->post_content ) ) : ?>

<div class="site-main">
	
	<?php do_action( 'wpsight_madrid_site_main_before', 'home', $wp_query ); ?>

	<div class="container">
	
		<div class="row gutter-60">

			<main class="content col-md-12" role="main" itemprop="mainContentOfPage">
			
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

		</div><!-- .row -->
	
	</div><!-- .container -->
	
	<?php do_action( 'wpsight_madrid_site_main_after', 'home', $wp_query ); ?>

</div><!-- .site-main -->

<?php endif; ?>