<?php
/**
 * Header template.
 *
 * @package WPCasa Madrid
 */
global $post, $wpsight_query;

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
		$background_image = ' style="background-image: url(' . wpsight_get_listing_thumbnail_url( get_the_id(), 'wpsight-featured' ) . ');"';
	} elseif( 'tagline' == $header_display && $tagline_bg ) {		
		$background_image = ' style="background-image: url(' . esc_url( $tagline_bg ) . ');"';
	}
	
	if( 'page_title' == $header_display ) {
		$title_right = get_post_meta( get_the_id(), '_title_right', true );
	}
	
}

?>

<?php if( ( 'image_slider' != $header_display || ! $header_gallery ) && ! in_array( $header_display, array( 'listings_slider', 'listings_map' ) ) ) : ?>

<header class="site-header<?php if( $header_display ) echo ' site-header-' . esc_attr( $header_display ); if( $header_filter ) echo ' site-header-no-filter'; ?>" role="banner" itemscope itemtype="http://schema.org/WPHeader"<?php echo $background_image; ?>>
	<div class="site-header-inner">
		
		<?php if( 'page_title' == $header_display ) : ?>		
			<div class="header-title">
				<div class="container">
				
					<?php if( $title_right ) : ?>
					
					<div class="header-title-inner">
				
						<div class="header-title-title">
							<h1><?php the_title(); ?></h1>
						</div>
						
						<div class="header-title-right">				
							<?php echo wp_kses_post( wpsight_format_content( $title_right ) ); ?>
						</div>
					
					</div>
					
					<?php else : ?>
					
					<div class="header-title-inner">
					
						<div class="header-title-title">
							<h1><?php the_title(); ?></h1>
						</div>
						
						<?php if( is_page_template( 'page-tpl-listings-query.php' ) || is_page_template( 'page-tpl-listings-query-full.php' ) ) : ?>
						<div class="header-title-right">				
							<?php wpsight_orderby(); ?>
						</div>
						<?php endif; ?>
					
					</div>
					
					<?php endif; ?>
	
				</div>
			</div><!-- .header-title -->
		<?php endif; ?>
		
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
	
	<?php if( ! in_array( $header_display, array( 'listings_slider', 'listings_map' ) ) ) : ?>

		<header class="site-header site-header-gallery<?php echo $header_filter ? ' site-header-no-filter' : ''; ?>" role="banner">
			<div class="site-header-inner">
				<?php wpsight_madrid_image_background_slider(); ?>			
			</div><!-- .site-header-inner -->
		</header><!-- .site-header -->
	
	<?php else : ?>
	
		<?php
			$instance = array(
				'nr'			=> get_post_meta( get_the_id(), '_listings_nr', true ),
				'offer_filter'	=> get_post_meta( get_the_id(), '_listings_offer', true ),
			);
			
			foreach( wpsight_taxonomies() as $key => $taxonomy ) {						
				$value = get_post_meta( get_the_id(), '_listings_taxonomy_' . $key, true );
				$instance[ 'taxonomy_filter_' . $key ] = $value ? $value : false;
			}
			
			// Process listings arguments
		
			$nr 				= isset( $instance['nr'] ) ? absint( $instance['nr'] ) : false;
			$offer_filter		= isset( $instance['offer_filter'] ) ? strip_tags( $instance['offer_filter'] ) : false;
			$taxonomy_filters	= array();
			
			foreach( wpsight_taxonomies() as $key => $taxonomy ) {			
				if( $instance[ 'taxonomy_filter_' . $key ] != false )
					$taxonomy_filters[ $key ] =  strip_tags( $instance[ 'taxonomy_filter_' . $key ] );		
			}
			
			$defaults = array(
				'nr'	=> 10,
			);
			
			$instance = wp_parse_args( (array) $instance, $defaults );
			
			$listings_args = array(
				'nr'			=> $nr,
				'offer'			=> $offer_filter,
				'show_panel'	=> false,
				'show_paging'	=> false,
			);
			
			// Merge taxonomy filters into args and apply filter hook
			$listings_args = apply_filters( 'wpsight_madrid_header_listings_query_args', array_merge( $listings_args, $taxonomy_filters ), $instance, get_the_id() );
			
			// Process slider arguments
			
			$slider_args = array(				
				'slider_animation'		=> get_post_meta( get_the_id(), '_listings_animation', true ),
				'slider_loop'			=> get_post_meta( get_the_id(), '_listings_loop', true ),
				'slider_nav'			=> get_post_meta( get_the_id(), '_listings_nav', true ),
				'slider_dots'			=> get_post_meta( get_the_id(), '_listings_dots', true ),
				'slider_dots_thumbs'	=> get_post_meta( get_the_id(), '_listings_dots_thumbs', true ),
				'slider_autoplay'		=> get_post_meta( get_the_id(), '_listings_autoplay', true ),
				'slider_autoplay_time'	=> get_post_meta( get_the_id(), '_listings_autoplay_time', true ),				
			);
			
			$slider_args = apply_filters( 'wpsight_madrid_header_listings_slider_args', $slider_args, $instance, get_the_id() );
		?>
	
		<header class="site-header site-header-listings<?php echo $header_filter ? ' site-header-no-filter' : ''; ?>" role="banner">
			<div class="site-header-inner">
				<?php if( 'listings_map' == $header_display && function_exists( 'wpsight_listings_map' ) ) : ?>
				
				<?php $map_args = array(
					'map_id'		=> uniqid( 'listings-map-' ),
					'height'		=> 650,
					'toggle'		=> false,
					'toggle_button'	=> false,
				); ?>
				<?php wpsight_listings_map( $map_args, wpsight_get_listings( $listings_args ) ); ?>
				<?php wp_reset_query(); ?>
				<?php else : ?>
				<?php wpsight_madrid_listings_slider( wpsight_get_listings( $listings_args ), $slider_args ); ?>
				<?php endif; ?>
			</div><!-- .site-header-inner -->
		</header><!-- .site-header -->
	
	<?php endif; ?>

</div><!-- .site-header-wrap -->

<?php endif; ?>

<?php get_template_part( 'template-parts/header', 'widgets' ); ?>
