<?php
/**
 *	Template: Service
 *	
 *	@package WPCasa Madrid
 *
 *	The variables $args and when used
 *	in widget also $widget_args are available.
 */
?>

<div class="wpsight-service">
	<div class="wpsight-service-inner">
	
	<?php $icon_class	= isset( $args['icon_class'] ) && ! empty( $args['icon_class'] ) ? $args['icon_class'] : false; ?>	
	<?php $link_text	= isset( $args['link_text'] ) && ! empty( $args['link_text'] ) ? $args['link_text'] : false; ?>
	<?php $link_url		= isset( $args['link_url'] ) && ! empty( $args['link_url'] ) ? $args['link_url'] : false; ?>
	<?php $link_blank	= isset( $args['link_blank'] ) && ! empty( $args['link_blank'] ) ? $args['link_blank'] : false; ?>

	<?php if( $icon_class ) : ?>
		<?php if( $link_url ) : ?>
		<a href="<?php echo esc_url( $link_url ); ?>"<?php if( $link_blank ) echo ' target="_blank"'; ?>>
			<span class="service-icon <?php echo esc_attr( $icon_class ); ?>"></span>
		</a>
		<?php else : ?>
		<span class="service-icon <?php echo esc_attr( $icon_class ); ?>"></span>
		<?php endif; ?>
	<?php endif; ?>
	
	<?php $title = isset( $args['title'] ) && ! empty( $args['title'] ) ? $args['title'] : false; ?>

	<?php if( $title ) : ?>
		<h4 class="service-title"><?php echo strip_tags( $title, '<strong><b><i><em><span>' ); ?></h4>
	<?php endif; ?>
	
	<?php $description = isset( $args['description'] ) && ! empty( $args['description'] ) ? $args['description'] : false; ?>
	
	<?php if( $description ) : ?>
		<div class="service-description">		
			<?php echo strip_tags( $description, '<strong><b><i><em><span>' ); ?>
		</div>
	<?php endif; ?>
	
	<?php if( $link_text && $link_url ) : ?>
		<div class="service-link">
			<a href="<?php echo esc_url( $link_url ); ?>" class="btn btn-primary btn-sm"<?php if( $link_blank ) echo ' target="_blank"'; ?>><?php echo strip_tags( $link_text ); ?></a>
		</div>
	<?php endif; ?>

	</div><!-- .wpsight-service-inner -->
</div><!-- .wpsight-service -->
