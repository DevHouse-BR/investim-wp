<?php
/**
 * Home services template
 *
 * @package WPCasa Madrid
 */
$display			= get_post_meta( get_the_id(), '_services_display', true );
$display_features	= get_post_meta( get_the_id(), '_services_display_features', true );

if( $display ) : ?>

	<?php $services = get_post_meta( get_the_id(), '_services', true ); ?>
	
	<?php if( $services ) : ?>

	<div id="home-services" class="site-section site-section-dark home-section<?php if( $display_features ) echo ' home-section-features'; ?>">
		
		<div class="container">
		
			<div class="wpsight-services">
			
				<?php
					$args = array(
						'title'			=> get_post_meta( get_the_id(), '_services_title', true ),
						'description'	=> get_post_meta( get_the_id(), '_services_description', true ),
						'align'			=> 'center',
					);
					
					wpsight_madrid_section_title( $args );
				?>
				
				<div class="row gutter-60">
				
					<?php
						// Set column class depending on count
						
						$count = count( $services );
						
						$class = 'col-md-4';
						
						if( 1 == $count )
							$class = 'col-md-12';
						
						if( 2 == $count )
							$class = 'col-md-6';
					
					?>
					
					<?php foreach( $services as $key => $service ) : ?>
							
					<div class="<?php echo $class; ?>">
					
						<?php
							$url	= isset( $service['_service_url'] ) && ! empty( $service['_service_url'] ) ? $service['_service_url'] : false;
							$icon	= isset( $service['_service_icon'] ) && ! empty( $service['_service_icon'] ) ? $service['_service_icon'] : false;
							$label	= isset( $service['_service_label'] ) && ! empty( $service['_service_label'] ) ? $service['_service_label'] : false;
							$desc	= isset( $service['_service_desc'] ) && ! empty( $service['_service_desc'] ) ? $service['_service_desc'] : false;
							$button	= isset( $service['_service_button'] ) && ! empty( $service['_service_button'] ) ? $service['_service_button'] : false;
							$target	= isset( $service['_service_target'] ) && ! empty( $service['_service_target'] ) ? $service['_service_target'] : false;
							
							// Set up cta arguments
			
							$args = array(
								'title'			=> $label,
								'icon_class'	=> $icon,
								'description'	=> $desc,
								'link_text'		=> $button,
								'link_url'		=> $url,
								'link_blank'	=> $target,
							);
														
							if( $display_features ) {
								wpsight_madrid_feature_box( $args );
							} else {
								wpsight_madrid_service( $args );
							} ?>
					
					</div>
					
					<?php endforeach; ?>
				
				</div><!-- .row -->
			
			</div><!-- .wpsight-services -->
		
		</div><!-- .container -->
	
	</div><!-- #home-services -->
	
	<?php endif; ?>

<?php endif; ?>