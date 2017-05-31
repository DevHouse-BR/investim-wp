<?php
/**
 *	The header for our theme.
 *	
 *	Displays all of the <head> section and the theme's header section.
 *	
 *	@package WPCasa Madrid
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>

<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

</head>

<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

	<?php do_action( 'wpsight_madrid_site_wrapper_before' ); ?>

	<div id="top" class="site-wrapper">

		<?php if( is_active_sidebar( 'top-left' ) || is_active_sidebar( 'top-right' ) ) : ?>
		<div class="site-top">
			<div class="container">
				<div class="site-top-inner">
		
					<?php $class = is_active_sidebar( 'top-left' ) && is_active_sidebar( 'top-right' ) ? 'site-top-half' : 'site-top-full'; ?>
		
					<?php if( is_active_sidebar( 'top-left' ) ) : ?>
					<div class="site-top-left <?php echo esc_attr( $class ); ?>">
						<?php dynamic_sidebar( 'top-left' ); ?>
					</div>
					<?php endif; ?>
		
					<?php if( is_active_sidebar( 'top-right' ) ) : ?>
					<div class="site-top-right <?php echo esc_attr( $class ); ?>">
						<?php dynamic_sidebar( 'top-right' ); ?>
					</div>
					<?php endif; ?>
		
				</div><!-- .site-top-inner -->
			</div><!-- .container -->
		</div><!-- .site-top -->
		<?php endif; ?>
		
		<?php get_template_part( 'template-parts/header', 'top' ); ?>
		
		<?php do_action( 'wpsight_madrid_site_container_before' ); ?>
		
		<div class="site-container">
		
			<div class="header-menu">
				<div class="container">
				
					<div class="header-menu-inner">
						
						<?php wpsight_madrid_menu(); ?>
						
						<div class="menu-btn menu-btn--right hidden-md hidden-lg">
							<span class="sr-only"><?php _e( 'Toggle navigation', 'wpcasa-madrid' ); ?></span>
							<i class="fa fa-fw fa-navicon"></i>
						</div>
					
					</div><!-- .header-menu-inner -->
				
				</div><!-- .container -->
			</div><!-- .header-menu -->
