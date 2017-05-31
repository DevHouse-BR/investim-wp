<?php
/**
 *	Template: Newsletter Box
 *	
 *	@package WPCasa Madrid
 *
 *	The variables $args and when used
 *	in widget also $widget_args are available.
 */
$full_width = isset( $widget_args['id'] ) && in_array( $widget_args['id'], array( 'header-full', 'footer-full' ) ) ? true : false; ?>

<div class="wpsight-newsletter-box">
	<?php if( $full_width ) : ?><div class="container"><?php endif; ?>
		<div class="wpsight-newsletter-box-inner">
		
			<?php if( isset( $args['title'] ) && ! empty( $args['title'] ) ) : ?>
			<h3 class="newsletter-box-title"><?php echo strip_tags( $args['title'] ); ?></h3>
			<?php endif; ?>
			
			<?php if( isset( $args['description'] ) && ! empty( $args['description'] ) ) : ?>
			<div class="newsletter-box-description">		
			<?php echo wpsight_format_content( wp_kses_post( $args['description'] ) ); ?>
			</div>
			<?php endif; ?>
			
			<?php if( isset( $args['form'] ) && ! empty( $args['form'] ) ) : ?>
			<div class="newsletter-box-form">		
			<?php echo do_shortcode( $args['form'] ); ?>
			</div>
			<?php endif; ?>
		
		</div>
			
		<?php if( isset( $args['icon_class'] ) && ! empty( $args['icon_class'] ) ) : ?>
		<i class="<?php echo esc_attr( $args['icon_class'] ); ?>"></i>
		<?php endif; ?>

	<?php if( $full_width ) : ?></div><!-- .container --><?php endif; ?>
</div><!-- .wpsight-newsletter-box -->
