<?php
/**
 * This template shows a print-friendly
 * version of a single listing page
 *
 * @package WPSight
 * @since 1.0.0
 */

$listing_id = absint( $_GET['print'] );
$listing = get_post( $listing_id );

$listing_offer = wpsight_get_listing_offer( $listing->ID, false ); ?>
<!DOCTYPE html>
<html>
	
<head>
	<title><?php echo strip_tags( $listing->_listing_title ); ?></title>
	<?php do_action( 'wpsight_head_print' ); ?>
	<?php wp_site_icon(); ?>
</head>

<body class="print<?php if( is_rtl() ) echo ' rtl'; ?>">

	<div class="actions clearfix">
	
		<div class="alignleft">
			<a href="<?php echo esc_url_raw( get_permalink( $listing->ID ) ); ?>" class="button back">&laquo; <?php _ex( 'Back to Listing', 'listing print', 'wpcasa' ); ?></a>
		</div>
		
		<?php $bg_dark = get_theme_mod( 'dark_accent_color', '#43454a' ); ?>
		
		<div class="alignright">
			<a href="#" onclick="window.print();return false" class="button printnow" style="background-color:<?php echo esc_attr( $bg_dark ); ?>"><?php _ex( 'Print Now', 'listing print', 'wpcasa' ); ?></a>
		</div>
	
	</div><!-- .actions -->

	<page size="A4">
	
		<div class="wrap">
		
			<div class="listing-print-logo">
			
				<table cellpadding="0" cellspacing="0" width="100%">
				
					<tr>
					
						<td>
			
							<?php
								/**
								 * We check individual logo settings here. If there is a print,
								 * we use it. If not the regular logo is used.
								 */					 
								$logo		= get_theme_mod( 'wpcasa_logo' );
								$logo_print	= get_theme_mod( 'wpcasa_logo_print' );
								
								if( $logo_print )
									$logo = $logo_print;
								
								$logo = apply_filters( 'wpsight_madrid_logo_print', $logo );
							?>
							
							<?php if( $logo ) : ?>
								<div class="site-title site-title-logo"><img src="<?php echo esc_url( $logo ); ?>" alt="logo"></div>
							<?php else : ?>
								<?php
									/**
									 * If there is no image logo, we will display the
									 * site title or the text logo.
									 */
									$logo_text = get_theme_mod( 'wpcasa_logo_text' );
									
									$text = $logo_text ? $logo_text : get_bloginfo( 'name' );
								?>
								<h1 class="site-title site-title-text" itemprop="headline"><?php echo strip_tags( $text ); ?></h1>
							<?php endif; ?>
							
							<?php if( get_bloginfo( 'description' ) && ! get_theme_mod( 'deactivate_tagline', false ) ) : ?>
							<div class="site-description"><?php bloginfo( 'description' ); ?></div>
							<?php endif; ?>
				
						</td>
						
						<?php wpsight_get_template( 'header-icon-boxes-print.php' ); ?>
					
					</tr>
				
				</table>

			</div>
			
			<div class="listing-print-title">
			
				<table cellpadding="0" cellspacing="0" width="100%">
					<tr>					
						<td width="66%">				
							<h1><?php echo get_the_title( $listing ); ?></h1>
						</td>
						
						<td>
							<div class="listing-print-price-id">
								<?php wpsight_listing_price( $listing->ID ); ?>
								<div class="listing-print-id">
									<?php wpsight_listing_id( $listing->ID ); ?> - <?php wpsight_listing_offer( $listing->ID ); ?>
								</div>
							</div>
						</td>
					</tr>					
				</table>
							
			</div><!-- .listing-print-title -->
			
			<div class="listing-print-image">			
				<?php wpsight_listing_thumbnail( $listing->ID, 'wpsight-print' ); ?>			
			</div><!-- .listing-print-image -->
			
			<div class="listing-print-details-description">
			
				<table>
					<tr>					
						<td width="33%">				
							<div class="listing-print-details">
								<?php wpsight_listing_details( $listing->ID ); ?>
							</div><!-- .listing-print-details -->							
						</td>
						
						<td>						
							<div class="listing-print-description">			
								<?php if( wpsight_is_listing_not_available() ) : ?>
									<div class="wpsight-alert wpsight-alert-small wpsight-alert-not-available">
										<?php _e( 'This property is currently not available.', 'wpcasa' ); ?>
									</div>
								<?php endif; ?>				
								<div class="wpsight-listing-description" itemprop="description">
									<?php echo apply_filters( 'wpsight_listing_description', wpsight_format_content( $listing->post_content ) ); ?>
								</div>			
							</div><!-- .listing-print-description -->				
						</td>					
					</tr>					
				</table>
			
			</div><!-- .listing-print-details-description -->
			
			<div class="listing-print-features">			
				<?php wpsight_listing_terms( 'feature', $listing->ID, '', '', '', false ); ?>			
			</div><!-- .listing-print-features -->
			
			<div class="listing-print-agent clearfix">			
				<div class="alignleft">
					<?php wpsight_listing_agent_image( $listing->ID, array( 50, 50 ) ); ?>
			        <?php wpsight_listing_agent_name( $listing->ID ); ?>
					<?php if( wpsight_get_listing_agent_company( $listing->ID ) ) : ?>
					<span class="wpsight-listing-agent-company">(<?php wpsight_listing_agent_company( $listing->ID ); ?>)</span>
					<?php endif; ?>
					<?php if( wpsight_get_listing_agent_phone( $listing->ID ) ) : ?>
					<br /><strong><?php wpsight_listing_agent_phone( $listing->ID ); ?></strong>
					<?php endif; ?>			        
			    </div>			
			    <div class="alignright">			    	
			    	<img src="<?php echo esc_url_raw( 'http://chart.apis.google.com/chart?cht=qr&chs=100x100&chld=H|0&chl=' . urlencode( get_permalink( $listing->ID ) ) ); ?>" width="100" height="100" alt="" />
			    </div>			
			</div><!-- .listing-print-agent -->
		
		</div><!-- .wrap -->
	
	</page>

</body>
</html>