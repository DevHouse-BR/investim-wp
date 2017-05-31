<?php
/**
 * The template used for displaying post content.
 *
 * @package WPCasa Madrid
 */
global $post;

// Set post class
$class = is_singular() ? 'clearfix' : 'col-md-6 clearfix'; ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>
	
	<?php $page = null !== get_query_var( 'page' ) ? get_query_var( 'page' ) : false; ?>
	
	<?php if( has_post_thumbnail() && $page < 2 ) : ?>
		<?php if( ! is_singular() ) : ?>
		<div class="entry-image"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_post_thumbnail( 'wpsight-large', array( 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?></a></div>
		<?php elseif( ! $post->_featured_image_remove ) : ?>
		<div class="entry-image"><?php the_post_thumbnail( 'wpsight-large', array( 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?></div>
		<?php endif; ?>		
	<?php endif; ?>

	<div class="entry-content equal">
	
		<?php if( ! is_singular() ) : ?>
	
			<?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
			
			<div class="entry-meta">
				<?php wpsight_madrid_posted_on(); ?>
			</div><!-- .entry-meta -->

			<?php wpsight_madrid_the_excerpt( get_the_ID(), true, apply_filters( 'wpsight_madrid_excerpt_length', 25 ) ); ?>
		
		<?php else : ?>
			
			<?php if( ! $post->_page_title_remove ) : ?>
			<?php the_title( '<header class="entry-header page-header"><h1 class="entry-title">', '</h1></header>' ); ?>
			<?php endif; ?>

			<?php the_content(); ?>
		
			<?php
				wp_link_pages( array(
					'before'		=> '<div class="page-links">' . esc_html__( 'Pages', 'wpcasa-madrid' ) . ':',
					'after'			=> '</div>',
					'link_before' 	=> '<span>',
					'link_after'	=> '</span>'
				) );
			?>
			
			<?php if ( get_the_tags() ) : ?>
			<div class="tags-links">
			<?php foreach( get_the_tags() as $tag ) : ?>
				<span class="post-tag-wrap">
					<i class="fa fa-fw fa-tag" aria-hidden="true"></i>
					<a href="<?php echo esc_attr( get_term_link( $tag->term_id, 'post_tag' ) ); ?>" class="post-tag"><?php echo esc_attr( $tag->name ); ?></a>
				</span>
			<?php endforeach; ?>
			</div>
			<?php endif; ?>
		
			<div class="entry-meta">
				<?php wpsight_madrid_posted_on(); ?>
			</div><!-- .entry-meta -->
		
		<?php endif; ?>

	</div><!-- .entry-content -->

</article><!-- #post-## -->
