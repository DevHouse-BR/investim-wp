<?php
/**
 * Template: Single Listing Info
 *
 * When used in a widget, the variables
 * $widget_args and $widget_instance are available.
 *
 * @since 1.0.0
 */
global $listing;

// Loop through actions and check if activated in widget

$actions = wpsight_get_listing_actions();

if( isset( $widget_instance ) ) {

	foreach( $actions as $action => $v ) {	
		$widget_instance[ 'action_' . $action ] = isset( $widget_instance[ 'action_' . $action ] ) ? $widget_instance[ 'action_' . $action ] : '';	
		// If action inactive, hide it (set to false)
		if( empty( $widget_instance[ 'action_' . $action ] ) )
			$actions[ $action ] = false;
	}

} ?>

<div class="wpsight-listing-section wpsight-listing-section-title">
	
	<?php do_action( 'wpsight_listing_single_title_before', $listing->ID ); ?>
	
	<header class="page-header">
		<?php wpsight_listing_title( $listing->ID, $actions ); ?>
	</header><!-- .page-header -->
	
	<?php do_action( 'wpsight_listing_single_title_after', $listing->ID ); ?>

</div><!-- .wpsight-listing-section-title -->
