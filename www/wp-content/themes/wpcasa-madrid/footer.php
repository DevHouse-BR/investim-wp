<?php
/**
 * The template for displaying the footer.
 *
 * @package WPCasa Madrid
 */
?>
			<?php if( is_active_sidebar( 'footer-columns' ) || is_active_sidebar( 'footer-main' ) || is_active_sidebar( 'footer-full' ) ) : ?>
				<footer class="site-footer">
					<div class="site-footer-inner">
						
						<?php if( is_active_sidebar( 'footer-main' ) ) : ?>
						<div class="footer-main">				
							<div class="container">
								<?php dynamic_sidebar( 'footer-main' ); ?>
							</div><!-- .container -->				
						</div><!-- .footer-main -->
						<?php endif; ?>
					
						<?php if( is_active_sidebar( 'footer-columns' ) ) : ?>
						<div class="footer-main">				
							<div class="container">
								<div class="row gutter-60">
									<?php dynamic_sidebar( 'footer-columns' ); ?>
								</div>
							</div><!-- .container -->				
						</div><!-- .footer-main -->
						<?php endif; ?>
						
						<?php if( is_active_sidebar( 'footer-full' ) ) : ?>
						<div class="footer-full-width">				
							<?php dynamic_sidebar( 'footer-full' ); ?>				
						</div><!-- .footer-full-width -->
						<?php endif; ?>
				
					</div><!-- .site-footer-inner -->
				</footer><!-- .site-footer -->			
			<?php endif; ?>
		
			<?php $bottom_left = sprintf( __( 'Copyright &copy; <span itemprop="copyrightYear">%s</span> &middot; <a href="%s" rel="home" itemprop="copyrightHolder">%s</a>', 'wpcasa-madrid' ), date( 'Y' ), esc_url( home_url( '/' ) ), get_bloginfo( 'name' ) ); ?>
			<?php $bottom_right = __( 'Built on <a href="http://wordpress.org">WordPress</a> &amp; <a href="https://wpcasa.com">WPCasa</a>', 'wpcasa-madrid' ); ?>
			
			<?php if( get_theme_mod( 'wpcasa_site_bottom_left', $bottom_left ) || get_theme_mod( 'wpcasa_site_bottom_right', $bottom_right ) ) : ?>
			<div class="site-bottom" role="contentinfo" itemscope="itemscope" itemtype="http://schema.org/WPFooter">
				<div class="site-bottom-inner">

					<div class="container">
					
						<div class="row gutter-60">
						
							<?php $class = get_theme_mod( 'wpcasa_site_bottom_left', $bottom_left ) && get_theme_mod( 'wpcasa_site_bottom_right', $bottom_right ) ? 'col-sm-6' : 'col-sm-12 text-center'; ?>
							
							<?php if( get_theme_mod( 'wpcasa_site_bottom_left', $bottom_left ) ) : ?>
							<div class="site-bottom-left <?php echo esc_attr( $class ); ?>">
								<?php echo do_shortcode( wp_kses_post( get_theme_mod( 'wpcasa_site_bottom_left', $bottom_left ) ) ); ?>
							</div><!-- .site-bottom-left -->
							<?php endif; ?>
							
							<?php if( get_theme_mod( 'wpcasa_site_bottom_right', $bottom_right ) ) : ?>
							<div class="site-bottom-right <?php echo esc_attr( $class ); ?>">
								<?php echo do_shortcode( wp_kses_post( get_theme_mod( 'wpcasa_site_bottom_right', $bottom_right ) ) ); ?>
							</div><!-- .site-bottom-left -->
							<?php endif; ?>
						
						</div>
			
					</div><!-- .container -->

				</div><!-- .site-bottom-inner -->
			</div><!-- .site-bottom -->
			<?php endif; ?>
		
		</div><!-- .site-container -->
		
		<?php do_action( 'wpsight_madrid_site_container_after' ); ?>
		
	</div><!-- .site-wrapper -->
	
	<?php do_action( 'wpsight_madrid_site_wrapper_after' ); ?>

	<?php wp_footer(); ?>

</body>
</html>