<?php
/**
 *	Template: Section Title
 *	
 *	@package WPCasa Madrid
 *
 *	The variables $args and when used
 *	in widget also $widget_args are available.
 */
$title = isset( $args['title'] ) && ! empty( $args['title'] ) ? $args['title'] : false;
$description = isset( $args['description'] ) && ! empty( $args['description'] ) ? $args['description'] : false;

$align = isset( $args['align'] ) && in_array( $args['align'], array( 'left', 'center', 'right' ) ) ? $args['align'] : 'center';

if( $title || $description ) : ?>

<div class="wpsight-section-title section-title-align-<?php echo $align; ?>">
	<div class="wpsight-section-title-inner">

	<?php if( $title ) : ?>
		<h2 class="section-title"><?php echo strip_tags( $title, '<strong><b><i><em><span>' ); ?></h2>
	<?php endif; ?>
	
	<?php if( $description ) : ?>
		<div class="section-description">		
			<?php echo strip_tags( $description, '<strong><b><i><em><span>' ); ?>
		</div>
	<?php endif; ?>
	
	<?php $link_text	= isset( $args['link_text'] ) && ! empty( $args['link_text'] ) ? $args['link_text'] : false; ?>
	<?php $link_url		= isset( $args['link_url'] ) && ! empty( $args['link_url'] ) ? $args['link_url'] : false; ?>
	<?php $link_blank	= isset( $args['link_blank'] ) && ! empty( $args['link_blank'] ) ? $args['link_blank'] : false; ?>
	
	<?php if( $link_text && $link_url ) : ?>
		<div class="section-link">
			<a href="<?php echo esc_url( $link_url ); ?>" class="btn btn-primary"<?php if( $link_blank ) echo ' target="_blank"'; ?>><?php echo strip_tags( $link_text ); ?></a>
		</div>
	<?php endif; ?>

	</div><!-- .wpsight-section-title-inner -->
</div><!-- .wpsight-section-title -->

<?php endif; ?>