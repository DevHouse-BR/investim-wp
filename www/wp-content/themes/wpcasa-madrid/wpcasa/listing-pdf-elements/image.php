<?php
/**
 *	PDF image section
 */
do_action( 'wpsight_listing_pdf_image_before', $listing );

// Check if element enabled
if( isset( $elements['image']['display'] ) && true == $elements['image']['display'] ) {

	// Check if page break enabled
	$page_break = isset( $elements['image']['pagebreak'] ) && true == $elements['image']['pagebreak'] ? ' page-break' : ''; ?>

	<div class="pdf-image<?php echo $page_break; ?>">
	
		<?php $title = isset( $elements['image']['title'] ) ? $elements['image']['title'] : false; ?>
		
		<?php if( $title ) : ?>
		<h2 class="title"><?php echo strip_tags( $title ); ?></h2>
		<?php endif; ?>

	    <?php wpsight_listing_thumbnail( $listing->ID, 'wpsight-print' ); ?>

	</div><!-- .pdf-image -->

<?php }
	
do_action( 'wpsight_listing_pdf_image_after', $listing ); ?>
