<?php
/**
 *	Template: Feature Box
 *	
 *	@package WPCasa Madrid
 *
 *	The variables $args and when used
 *	in widget also $widget_args are available.
 */
$full_width = isset( $widget_args['id'] ) && in_array( $widget_args['id'], array( 'header-full', 'footer-full' ) ) ? true : false; ?>

<div class="wpsight-feature-box">
	<?php if( $full_width ) : ?><div class="container"><?php endif; ?>
		<div class="wpsight-feature-box-inner">
		
		<div class="feature-box-icon">
			<i class="<?php echo esc_attr( $args['icon_class'] ); ?>"></i>
		</div>
		
		<div class="feature-box-info equal">
		
			<?php if( $args['title'] ) : ?>
			<h4 class="feature-box-title"><?php echo strip_tags( $args['title'] ); ?></h4>
			<?php endif; ?>
		
			<?php echo wpsight_format_content( wp_kses_post( $args['description'] ) ); ?>
			
			<?php if( $args['link_text'] && $args['link_url'] ) : ?>
			<div class="feature-box-link">
				<a href="<?php echo esc_url( $args['link_url'] ); ?>" <?php if( $args['link_blank'] ) echo ' target="_blank"'; ?> class="btn btn-primary btn-sm"><?php echo strip_tags( $args['link_text'] ); ?></a>
			</div>
			<?php endif; ?>
		
		</div>
		
		</div>
	<?php if( $full_width ) : ?></div><!-- .container --><?php endif; ?>
</div><!-- .wpsight-feature-box -->
