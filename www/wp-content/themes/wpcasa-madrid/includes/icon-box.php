<?php
/**
 *	Custom icon box
 *	
 *	@package WPCasa Madrid
 */

/**
 *	wpsight_madrid_icon_box()
 *	
 *	Echo wpsight_madrid_get_icon_box()
 *	
 *	@param	array	$args
 *	@param	array	$widget_args
 *	@uses	wpsight_madrid_get_icon_box()
 *	
 *	@since	1.0.0
 */
function wpsight_madrid_icon_box( $args = array(), $widget_args = array() ) {
	echo wpsight_madrid_get_icon_box( $args, $widget_args );
}

/**
 *	wpsight_madrid_get_icon_box()
 *	
 *	Create thumbnail gallery.
 *	
 *	@param	array	$args
 *	@param	array	$widget_args
 *	@uses	wpsight_get_template()
 *	
 *	@return	mixed
 *	
 *	@since 1.0.0
 */
function wpsight_madrid_get_icon_box( $args = array(), $widget_args = array() ) {

	// Set some defaults
		
	$defaults = array(
		'title'			=> false,
		'icon_class'	=> 'fa fa-check',
		'text_1'		=> __( 'Here you can place your icon box description.', 'wpcasa-madrid' ),
		'text_2'		=> false,
	);
	
	// Merge args with defaults and apply filter
	$args = apply_filters( 'wpsight_madrid_get_icon_box_args', wp_parse_args( $args, $defaults ), $args );
	
	ob_start();

	wpsight_get_template( 'icon-box.php', array( 'args' => $args, 'widget_args' => $widget_args ) );
		
	return apply_filters( 'wpsight_madrid_get_icon_box', ob_get_clean(), $args );

}
