<?php
/**
 * Template Name: Listings Query
 *
 * This is a template to display listing queries.
 *
 * @package WPCasa Madrid
 */
global $wp_query, $post;

$listings_page = wpsight_get_option( 'listings_page' );

$instance = array(
	'nr'			=> get_post_meta( get_the_id(), '_listings_query_nr', true ),
	'offer_filter'	=> get_post_meta( get_the_id(), '_listings_query_offer', true ),
	'show_panel'	=> get_post_meta( get_the_id(), '_listings_query_panel', true ),
	'show_paging'	=> get_post_meta( get_the_id(), '_listings_query_paging', true ),
);

foreach( wpsight_taxonomies() as $key => $taxonomy ) {						
	$value = get_post_meta( get_the_id(), '_listings_query_taxonomy_' . $key, true );
	$instance[ 'taxonomy_filter_' . $key ] = $value ? $value : false;
}

// Process carousel instance

$nr 				= isset( $instance['nr'] ) ? absint( $instance['nr'] ) : false;
$offer_filter		= isset( $instance['offer_filter'] ) ? strip_tags( $instance['offer_filter'] ) : false;
$show_panel			= isset( $instance['show_panel'] ) && empty( $instance['show_panel'] ) ? false : true;
$show_paging		= isset( $instance['show_paging'] ) && empty( $instance['show_paging'] ) ? false : true;
$taxonomy_filters	= array();

foreach( wpsight_taxonomies() as $key => $taxonomy ) {			
	if( $instance[ 'taxonomy_filter_' . $key ] != false )
		$taxonomy_filters[ $key ] =  strip_tags( $instance[ 'taxonomy_filter_' . $key ] );		
}

$defaults = array(
	'nr'			=> 10,
	'show_panel'	=> true,
	'show_paging'	=> true,
);

$instance = wp_parse_args( (array) $instance, $defaults );

$listings_args = array(
	'nr'			=> $nr,
	'offer'			=> $offer_filter,
	'show_panel'	=> $show_panel,
	'show_paging'	=> $show_paging,
);

// Merge taxonomy filters into args and apply filter hook
$listings_args = apply_filters( 'wpsight_madrid_listings_page_template_query_args', array_merge( $listings_args, $taxonomy_filters ), $instance, get_the_id() );

get_header(); ?>

	<?php get_template_part( 'template-parts/header', 'listings-query' ); ?>

	<div class="site-main">
	
		<?php do_action( 'wpsight_madrid_site_main_before', 'listings-query', wpsight_get_listings( $listings_args ) ); ?>
	
		<div class="container">
		
			<div class="row gutter-60">

				<main class="content <?php if( is_active_sidebar( 'sidebar' ) ) : ?>col-md-8<?php else : ?>col-md-12<?php endif; ?>" role="main" itemprop="mainContentOfPage">
		
					<?php if( get_the_id() == $listings_page && current_user_can( 'administrator' ) ) : ?>
					<div class="bs-callout bs-callout-danger" style="margin: 0 0 40px">
						<p><?php _e( '<strong>Please notice!</strong> This page should not be your listings search results page set on the WPCasa settings page. Different queries would be mixed up.', 'wpcasa-madrid' ); ?></p>	
					</div>
					<?php endif; ?>
				
					<?php if( is_active_sidebar( 'content-top' ) ) : ?>
					<div class="content-top">				
						<?php dynamic_sidebar( 'content-top' ); ?>	
					</div><!-- .content-top -->
					<?php endif; ?>
			
					<?php if ( have_posts() ) : ?>
						<?php while ( have_posts() ) : the_post(); ?>
							
							<?php if( ! empty( $post->post_content ) ) : ?>
							
								<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								
									<?php if( has_post_thumbnail() && ! $post->_featured_image_remove ) : ?>
										<div class="entry-image"><?php the_post_thumbnail( 'wpsight-large', array( 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?></div>
									<?php endif; ?>
									
									<?php if( ! $post->_page_title_remove ) : ?>
									<?php the_title( '<header class="entry-header page-header"><h1 class="entry-title">', '</h1></header>' ); ?>
									<?php endif; ?>
								
									<div class="entry-content">
								
										<?php the_content(); ?>
								
									</div><!-- .entry-content -->
								
								</article><!-- #post-## -->
							
							<?php endif; ?>
							
							<?php wpsight_listings( $listings_args ); ?>
							
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
	
		<?php do_action( 'wpsight_madrid_site_main_after', 'listings-query', wpsight_get_listings( $listings_args ) ); ?>
	
	</div><!-- .site-main -->

<?php get_footer(); ?>