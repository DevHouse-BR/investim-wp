<?php
/**
 * Header template.
 *
 * @package WPCasa Madrid
 */
?>

<?php if( is_active_sidebar( 'header-full' ) || is_active_sidebar( 'header-main' ) || is_active_sidebar( 'header-columns' ) ) : ?>
	<div class="header-widgets">	
			
		<?php if( is_active_sidebar( 'header-full' ) ) : ?>
		<div class="header-full-width">
			<?php dynamic_sidebar( 'header-full' ); ?>
		</div><!-- .header-full-width -->
		<?php endif; ?>
		
		<?php if( is_active_sidebar( 'header-main' ) ) : ?>
		<div class="header-main">				
			<div class="container">
				<?php dynamic_sidebar( 'header-main' ); ?>
			</div><!-- .container -->
		</div><!-- .header-main -->
		<?php endif; ?>
		
		<?php if( is_active_sidebar( 'header-columns' ) ) : ?>
		<div class="header-main">
			<div class="container">
				<div class="row gutter-60">
					<?php dynamic_sidebar( 'header-columns' ); ?>
				</div>
			</div><!-- .container -->
		</div><!-- .header-main -->
		<?php endif; ?>
	
	</div><!-- .header-widgets -->
<?php endif; ?>
