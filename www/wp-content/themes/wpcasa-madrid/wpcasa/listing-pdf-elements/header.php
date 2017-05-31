<?php
/**
 *	PDF header section
 */
do_action( 'wpsight_listing_pdf_header_before', $listing );

// Check if element enabled
if( isset( $elements['header']['display'] ) && true == $elements['header']['display'] ) {

	// Check if page break enabled
	$page_break = isset( $elements['header']['pagebreak'] ) && true == $elements['header']['pagebreak'] ? ' page-break' : ''; ?>

	<div class="pdf-header<?php echo $page_break; ?>">
	
		<?php $title = isset( $elements['header']['title'] ) ? $elements['header']['title'] : false; ?>
		
		<?php if( $title ) : ?>
		<h2 class="title"><?php echo strip_tags( $title ); ?></h2>
		<?php endif; ?>
		
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
	
		<table class="site-logo">
			<tr>		
				<td class="site-title">
					<?php if( $logo ) : ?>
						<img src="<?php echo esc_url( $logo ); ?>" alt="logo">
					<?php else : ?>
						<?php
							/**
							 * If there is no image logo, we will display the
							 * site title or the text logo.
							 */
							$logo_text = get_theme_mod( 'wpcasa_logo_text' );
							
							$text = $logo_text ? $logo_text : get_bloginfo( 'name' );
						?>
						<h2><?php echo strip_tags( $text ); ?></h2>
						<?php bloginfo( 'description' ); ?>
					<?php endif; ?>
				</td>
				<?php wpsight_get_template( 'header-icon-boxes-pdf.php' ); ?>		
			</tr>
		</table>
		
	</div><!-- .pdf-header -->

<?php }
	
do_action( 'wpsight_listing_pdf_header_after', $listing ); ?>
