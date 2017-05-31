<?php
/**
 * The template used for displaying page content
 * in page-tpl-listings.php and page-tpl-listings-full.php
 *
 * @package WPCasa Madrid
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-listings' ); ?>>

	<div class="entry-content">

		<?php the_content(); ?>

	</div><!-- .entry-content -->

</article><!-- #post-## -->
