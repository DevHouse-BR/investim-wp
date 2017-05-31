<?php
/**
 * Home call to action #1 template
 *
 * @package WPCasa Madrid
 */
$display = get_post_meta( get_the_id(), '_cta_1_display', true );

if( $display ) : ?>

<div id="home-cta-1" class="site-section site-section-cta home-section">
	
	<div class="container">

		<?php
			
			// Check some of the values
			$button_target	= get_post_meta( get_the_id(), '_cta_1_button_target', true );
			
			// Set up cta arguments
			
			$args = array(
				'title'			=> get_post_meta( get_the_id(), '_cta_1_title', true ),
				'description'	=> get_post_meta( get_the_id(), '_cta_1_description', true ),
				'link_text'		=> get_post_meta( get_the_id(), '_cta_1_button_label', true ),
				'link_url'		=> get_post_meta( get_the_id(), '_cta_1_button_url', true ),
				'link_blank'	=> $button_target ? true : false,
				'orientation'	=> get_post_meta( get_the_id(), '_cta_1_orientation', true ),
			);
			
		?>
		
		<?php wpsight_madrid_call_to_action( $args ); ?>
	
	</div><!-- .container -->

</div><!-- #home-cta-1 -->

<?php endif; ?>