<?php
/**
 * This is image attachment template.
 *
 * @package WPCasa Madrid
 */
global $wp_query, $post;

get_header(); ?>

	<?php get_template_part( 'template-parts/header', 'attachment' ); ?>

	<div class="site-main">
	
		<?php do_action( 'wpsight_madrid_site_main_before', 'attachment', $wp_query ); ?>
	
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
						
							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>
							
								<div class="entry-content">
									
									<?php the_title( '<header class="entry-header page-header"><h1 class="entry-title">', '</h1></header>' ); ?>
							
									<?php $att_image = wp_get_attachment_image_src( get_the_ID(), 'wpsight-full' ); ?>
									
									<p>
										<img src="<?php echo $att_image[0];?>" width="<?php echo $att_image[1];?>" height="<?php echo $att_image[2];?>" alt="<?php the_title(); ?>" />
									</p>
									
									<?php if( ! empty( $post->post_excerpt ) ) : ?>
										<div class="image-caption"><?php the_content(); ?></div>
									<?php endif; ?>
							
								</div><!-- .entry-content -->
							
							</article><!-- #post-## -->
							
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

			</div><!-- .row -->
		
		</div><!-- .container -->
	
		<?php do_action( 'wpsight_madrid_site_main_after', 'attachment', $wp_query ); ?>
	
	</div><!-- .site-main -->

<?php get_footer(); ?>