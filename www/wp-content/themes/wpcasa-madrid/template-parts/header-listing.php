<?php
/**
 * Header template.
 *
 * @package WPCasa Madrid
 */
global $post;

$header_display 	= false;
$header_filter		= false;
$header_gallery 	= false;
$tagline_text		= false;
$tagline_bg			= false;
$title_right		= false;
$background_image	= false;

if( is_singular() ) {
	
	$header_display = get_post_meta( get_the_id(), '_header_display', true );
	
	if( in_array( $header_display, array( 'image_slider', 'featured_image', 'tagline' ) ) )	
		$header_filter = get_post_meta( get_the_id(), '_header_filter', true );
	
	$header_gallery	= get_post_meta( get_the_id(), '_gallery', true );
	
	$tagline_text	= get_post_meta( get_the_id(), '_tagline_text', true );
	$tagline_bg		= get_post_meta( get_the_id(), '_tagline_bg', true );
	
	$background_image = '';
	
	if( 'featured_image' == $header_display && get_the_post_thumbnail() ) {
		$background_image = ' style="background-image: url(' . wpsight_get_listing_thumbnail_url( get_the_id(), 'slider' ) . ');"';
	} elseif( 'tagline' == $header_display && $tagline_bg ) {		
		$background_image = ' style="background-image: url(' . esc_url( $tagline_bg ) . ');"';
	}
	
	if( 'page_title' == $header_display ) {
		$title_right = get_post_meta( get_the_id(), '_title_right', true );
	}
	
} ?>

<?php if( 'image_slider' != $header_display || ! $header_gallery ) : ?>

<header class="site-header<?php if( $header_display ) echo ' site-header-' . esc_attr( $header_display ); if( $header_filter ) echo ' site-header-no-filter'; ?>" role="banner" itemscope itemtype="http://schema.org/WPHeader"<?php echo $background_image; ?>>
	<div class="site-header-inner">
		
		<?php if( 'tagline' == $header_display ) : ?>			
			<?php if( ! empty( $tagline_text ) ) : ?>		
			<div class="header-tagline">
				<div class="container">
					<div id="tagline"><?php echo wp_kses_post( $tagline_text ); ?></div>
				</div>
			</div>			
			<?php endif; ?>		
		<?php endif; ?>
		
		<?php if( 'featured_image' == $header_display ) : ?>
		<div class="header-featured-image">
			<div class="container">
				<h1><?php the_title(); ?></h1>
			</div>
		</div>			
		<?php endif; ?>

	</div><!-- .site-header-inner -->
</header><!-- .site-header -->

<?php else : ?>

<div class="site-header-wrap<?php echo $header_filter ? ' site-header-wrap-no-filter' : ''; ?>">
	
	<header class="site-header site-header-gallery<?php echo $header_filter ? ' site-header-no-filter' : ''; ?>" role="banner">
		<div class="site-header-inner">
			<?php wpsight_madrid_image_background_slider(); ?>			
		</div><!-- .site-header-inner -->
	</header><!-- .site-header -->

</div><!-- .site-header-wrap -->

<?php endif; ?>

<?php get_template_part( 'template-parts/header', 'widgets' ); ?>
