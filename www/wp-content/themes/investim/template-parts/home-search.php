<?php
/**
 * Home search template
 *
 * @package WPCasa Madrid
 */
$display = get_post_meta( get_the_id(), '_search_display', true );

$display_on_header = get_post_meta( get_the_id(), '_search_display_on_header', false );

if( $display ) : 
	if (!$display_on_header):
	?>

<div id="home-search" class="site-section home-section">
	
	<div class="container">

		<?php
			$args = array();
			
			// Get orientation	
			$orientation = get_post_meta( get_the_id(), '_search_orientation', true );
			
			// Add to arguments
			if( $orientation && in_array( $orientation, array( 'horizontal', 'vertical' ) ) )
				$args['orientation'] = $orientation;
		?>
		
		<?php wpsight_search( $args ); ?>
	
	</div><!-- .container -->

</div><!-- #home-search -->
<?php endif; ?>
<?php endif; ?>