<?php
/**
 * The template used for displaying page content.
 *
 * @package WPCasa Madrid
 */
global $post;

$header_display = get_post_meta( get_the_id(), '_header_display', true );
$image_size = is_page_template( 'page-tpl-full.php' ) || is_page_template( 'page-tpl-listings-query-full.php' ) ? 'wpsight-full' : 'wpsight-large'; ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php $page = null !== get_query_var( 'page' ) ? get_query_var( 'page' ) : false; ?>
	
	<?php if( has_post_thumbnail() && $page < 2 && ! $post->_featured_image_remove ) : ?>
		<div class="entry-image"><?php the_post_thumbnail( $image_size, array( 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?></div>
	<?php endif; ?>

	<div class="entry-content">
		
		<?php if( ! $post->_page_title_remove ) : ?>
		<?php the_title( '<header class="entry-header page-header"><h1 class="entry-title">', '</h1></header>' ); ?>
		<?php endif; ?>

		<?php the_content(); ?>

		<?php
			wp_link_pages( array(
				'before'		=> '<div class="page-links">' . esc_html__( 'Pages:', 'wpcasa-sylt' ),
				'after'			=> '</div>',
				'link_before' 	=> '<span>',
				'link_after'	=> '</span>'
			) );
		?>

	</div><!-- .entry-content -->

</article><!-- #post-## -->
