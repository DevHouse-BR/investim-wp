<?php
/**
 *	Template: Icon Box
 *	
 *	@package WPCasa Madrid
 *
 *	The variables $args and when used
 *	in widget also $widget_args are available.
 */
$full_width = isset( $widget_args['id'] ) && in_array( $widget_args['id'], array( 'header-full', 'footer-full' ) ) ? true : false; ?>

<div class="wpsight-icon-box">
	<?php if( $full_width ) : ?><div class="container"><?php endif; ?>
		<div class="wpsight-icon-box-inner clearfix">
		
		<div class="icon-box-icon">
			<i class="<?php echo esc_attr( $args['icon_class'] ); ?>"></i>
		</div>
		
		<div class="icon-box-text">
		
			<?php if( $args['title'] ) : ?>
			<h4 class="icon-box-title"><?php echo strip_tags( $args['title'] ); ?></h4>
			<?php endif; ?>
			
			<?php if( $args['text_1'] ) : ?>
			<span class="icon-box-description icon-box-text-1"><?php echo strip_tags( do_shortcode( $args['text_1'] ), '<a><br><br /><em><i><strong><span>' ); ?></span>
			<?php endif; ?>
			
			<?php if( $args['text_2'] ) : ?>
			<span class="icon-box-description icon-box-text-2"><?php echo strip_tags( do_shortcode( $args['text_2'] ), '<a><br><br /><em><i><strong><span>' ); ?></span>
			<?php endif; ?>
		
		</div>
		
		</div>
	<?php if( $full_width ) : ?></div><!-- .container --><?php endif; ?>
</div><!-- .wpsight-icon-box -->
