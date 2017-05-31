<?php
/**
 *	Template: Call to Action
 *	
 *	@package WPCasa Madrid
 *
 *	The variables $args and when used
 *	in widget also $widget_args are available.
 */
$full_width = isset( $widget_args['id'] ) && in_array( $widget_args['id'], array( 'site-section', 'header-full', 'footer-full' ) ) ? true : false; ?>

<div class="wpsight-cta wpsight-cta-<?php echo esc_attr( $args['orientation'] ); ?>">
	<?php if( $full_width ) : ?><div class="container"><?php endif; ?>
		<div class="wpsight-cta-inner">
		
			<?php $title		= isset( $args['title'] ) && ! empty( $args['title'] ) ? $args['title'] : false; ?>
			<?php $description	= isset( $args['description'] ) && ! empty( $args['description'] ) ? $args['description'] : false; ?>
		
			<?php if( $title || $description ) : ?>
			<div class="cta-info">
		
				<?php if( $title ) : ?>
				<h3 class="cta-title"><?php echo strip_tags( $title ); ?></h3>
				<?php endif; ?>
				
				<?php if( $description ) : ?>
				<div class="cta-description">		
				<?php echo wpsight_format_content( wp_kses_post( $description ) ); ?>
				</div>
				<?php endif; ?>
			
			</div>
			<?php endif; ?>
			
			<?php $link_text	= isset( $args['link_text'] ) && ! empty( $args['link_text'] ) ? $args['link_text'] : false; ?>
			<?php $link_url		= isset( $args['link_url'] ) && ! empty( $args['link_url'] ) ? $args['link_url'] : false; ?>
			<?php $link_blank	= isset( $args['link_blank'] ) && ! empty( $args['link_blank'] ) ? $args['link_blank'] : false; ?>
			
			<?php if( $link_text && $link_url ) : ?>
			<div class="cta-link">
				<a href="<?php echo esc_url( $link_url ); ?>" class="btn btn-primary btn-lg"<?php if( $link_blank ) echo ' target="_blank"'; ?>><?php echo strip_tags( $link_text ); ?></a>
			</div>
			<?php endif; ?>
		
		</div>
	<?php if( $full_width ) : ?></div><!-- .container --><?php endif; ?>
</div><!-- .wpsight-cta -->
