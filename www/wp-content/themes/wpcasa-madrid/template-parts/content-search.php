<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WPCasa Madrid
 */
global $post; ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'col-sm-12 clearfix' ); ?>>
	
	<?php if( has_post_thumbnail() ) : ?>
		<div class="entry-image"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_post_thumbnail( 'wpsight-large', array( 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?></a></div>
	<?php endif; ?>

	<div class="entry-content">
	
		<?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
			
		<div class="entry-meta">
			<?php if( 'post' == get_post_type() ) wpsight_madrid_posted_on( true, true, false ); ?>
			<?php if( 'post' == get_post_type() ) printf( '<span class="label label-default">%s</span>', _x( 'Article', 'search results type', 'wpcasa-madrid' ) ); ?>
			<?php if( 'page' == get_post_type() ) printf( '<span class="label label-default">%s</span>', _x( 'Page', 'search results type', 'wpcasa-madrid' ) ); ?>
		</div><!-- .entry-meta -->

		<?php wpsight_madrid_the_excerpt( get_the_ID(), true, apply_filters( 'wpsight_madrid_excerpt_length', 25 ) ); ?>

	</div><!-- .entry-content -->

</article><!-- #post-## -->
