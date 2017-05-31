<?php
/**
 * Home carousel template
 *
 * @package WPCasa Madrid
 */
$display = get_post_meta( get_the_id(), '_carousel_display', true );

if( $display ) : ?>
	
	<?php
		// Set up carousel instance

		$instance = array(
			'nr'						=> get_post_meta( get_the_id(), '_carousel_nr', true ),
			'offer_filter'				=> get_post_meta( get_the_id(), '_carousel_offer', true ),			
			'carousel_items' 			=> get_post_meta( get_the_id(), '_carousel_items', true ),
			'carousel_slide_by'			=> get_post_meta( get_the_id(), '_carousel_slide_by', true ),
			'carousel_margin'			=> get_post_meta( get_the_id(), '_carousel_margin', true ),
			'carousel_loop'				=> get_post_meta( get_the_id(), '_carousel_loop', true ),
			'carousel_nav'				=> get_post_meta( get_the_id(), '_carousel_nav', true ),
			'carousel_dots'				=> get_post_meta( get_the_id(), '_carousel_dots', true ),
			'carousel_autoplay'			=> get_post_meta( get_the_id(), '_carousel_autoplay', true ),
			'carousel_autoplay_time'	=> get_post_meta( get_the_id(), '_carousel_autoplay_time', true )
		);
		
		foreach( get_object_taxonomies( wpsight_post_type(), 'objects' ) as $key => $taxonomy ) {						
			$value = get_post_meta( get_the_id(), '_carousel_taxonomy_' . $key, true );
			$instance[ 'taxonomy_filter_' . $key ] = $value ? $value : false;
		}
		
		// Process carousel instance
		
		$nr 				= absint( $instance['nr'] );
		$offer_filter		= isset( $instance['offer_filter'] ) ? strip_tags( $instance['offer_filter'] ) : false;
		$taxonomy_filters	= array();
		
		foreach( get_object_taxonomies( wpsight_post_type(), 'objects' ) as $key => $taxonomy ) {			
			if( $instance[ 'taxonomy_filter_' . $key ] != false )
				$taxonomy_filters[ $key ] =  strip_tags( $instance[ 'taxonomy_filter_' . $key ] );		
		}
		
		$defaults = array(
			'nr'						=> 10,
			'carousel_items' 			=> 4,
			'carousel_slide_by'			=> 2,
			'carousel_margin'			=> 40,
			'carousel_loop'				=> true,
			'carousel_nav'				=> true,
			'carousel_dots'				=> true,
			'carousel_autoplay'			=> false,
			'carousel_autoplay_time'	=> 6000
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		// Carousel settings
		
		$carousel_items 		= absint( $instance['carousel_items'] );
		$carousel_slide_by 		= absint( $instance['carousel_slide_by'] );
		$carousel_margin 		= absint( $instance['carousel_margin'] );
		$carousel_loop 			= isset( $instance['carousel_loop'] ) ? (bool) $instance['carousel_loop'] : false;
		$carousel_nav 			= isset( $instance['carousel_nav'] ) ? (bool) $instance['carousel_nav'] : false;
		$carousel_dots 			= isset( $instance['carousel_dots'] ) ? (bool) $instance['carousel_dots'] : false;
		$carousel_autoplay 		= isset( $instance['carousel_autoplay'] ) ? (bool) $instance['carousel_autoplay'] : false;
		$carousel_autoplay_time = absint( $instance['carousel_autoplay_time'] );
		
		// Set up args for carousel

		$carousel_args = array(
			'class_carousel'   			=> 'wpsight-listings-carousel-home wpsight-listings-carousel',
			'class_item'	   			=> 'wpsight-listings-carousel-item',			
			'carousel_items'			=> $carousel_items,
			'carousel_slide_by'			=> $carousel_slide_by,
			'carousel_margin'			=> $carousel_margin,
			'carousel_loop'				=> $carousel_loop,
			'carousel_nav'				=> $carousel_nav,
			'carousel_dots'				=> $carousel_dots,
			'carousel_autoplay'			=> $carousel_autoplay,
			'carousel_autoplay_time'	=> $carousel_autoplay_time
		);
		
		// Apply filter hook
		$carousel_args = apply_filters( 'wpsight_madrid_listings_carousel_args', $carousel_args, $instance, 'home' );
		
		// Set up query args for carousel
		
		$listings_args = array(
			'nr'				=> $nr,
			'offer'				=> $offer_filter,
			'meta_query'		=> array(
				array(
					'key'		=> '_thumbnail_id',
					'compare'	=> 'EXISTS'
				)
			)
		);
		
		// Merge taxonomy filters into args and apply filter hook
		$listings_args = apply_filters( 'wpsight_madrid_listings_carousel_query_args', array_merge( $listings_args, $taxonomy_filters ), $instance, 'home' );
		
		// Finally get listings
		$listings = wpsight_get_listings( $listings_args );
		
	?>
	
	<?php if( $listings ) : ?>
	
	<div id="home-carousel" class="site-section home-section">
	
		<div class="container">
			<?php wpsight_madrid_listings_carousel( $listings, $carousel_args ); ?>		
		</div><!-- .container -->
	
	</div><!-- #home-carousel -->
	
	<?php endif; ?>

<?php endif; ?>