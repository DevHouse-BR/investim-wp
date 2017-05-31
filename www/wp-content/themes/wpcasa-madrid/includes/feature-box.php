<?php
/**
 *	Custom feature box
 *	
 *	@package WPCasa Madrid
 */

/**
 *	wpsight_madrid_feature_box()
 *	
 *	Echo wpsight_madrid_get_feature_box()
 *	
 *	@param	array	$args
 *	@param	array	$widget_args
 *	@uses	wpsight_madrid_get_feature_box()
 *	
 *	@since	1.0.0
 */
function wpsight_madrid_feature_box( $args = array(), $widget_args = array() ) {
	echo wpsight_madrid_get_feature_box( $args, $widget_args );
}

/**
 *	wpsight_madrid_get_feature_box()
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
function wpsight_madrid_get_feature_box( $args = array(), $widget_args = array() ) {

	// Set some defaults
		
	$defaults = array(
		'title'			=> false,
		'icon_class'	=> 'fa fa-check',
		'description'	=> __( 'Here you can place your feature box description.', 'wpcasa-madrid' ),
		'link_text'		=> false,
		'link_url'		=> false,
		'link_blank'	=> false
	);
	
	// Merge args with defaults and apply filter
	$args = apply_filters( 'wpsight_madrid_get_feature_box_args', wp_parse_args( $args, $defaults ), $args );
	
	ob_start();

	wpsight_get_template( 'feature-box.php', array( 'args' => $args, 'widget_args' => $widget_args ) );
		
	return apply_filters( 'wpsight_madrid_get_feature_box', ob_get_clean(), $args );

}
