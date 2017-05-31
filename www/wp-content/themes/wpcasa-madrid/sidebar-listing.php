<?php
/**
 * The listing sidebar containing a widget area.
 *
 * @package WPCasa Madrid
 */
?>

<?php if( is_active_sidebar( 'sidebar-listing' ) || ( ! is_active_sidebar( 'listing' ) && is_active_sidebar( 'sidebar' ) ) ) : ?>

<aside class="sidebar col-md-4" role="complementary" itemscope itemtype="http://schema.org/WPSideBar">
	
	<?php if( ! post_password_required() ) : ?>
	
		<?php if( is_active_sidebar( 'sidebar-listing' ) ) : ?>	
			<?php dynamic_sidebar( 'sidebar-listing' ); ?>		
		<?php else : ?>		
			<?php dynamic_sidebar( 'sidebar' ); ?>		
		<?php endif; ?>
	
	<?php else : ?>
	
		<div class="wpsight-alert wpsight-alert-password bs-callout bs-callout-primary">
			<?php _e( 'This page is password protected.', 'wpcasa-madrid' ); ?>
		</div>
	
	<?php endif; ?>

</aside>

<?php endif; ?>