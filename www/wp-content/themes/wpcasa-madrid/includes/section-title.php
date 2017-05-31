<?php
/**
 *	Section Title
 *	
 *	@package WPCasa Madrid
 */

/**
 *	wpsight_madrid_section_title()
 *	
 *	Echo wpsight_madrid_get_section_title()
 *	
 *	@param	array	$args
 *	@param	array	$widget_args
 *	@uses	wpsight_madrid_get_section_title()
 *	
 *	@since	1.0.0
 */
function wpsight_madrid_section_title( $args = array(), $widget_args = array() ) {
	echo wpsight_madrid_get_section_title( $args, $widget_args );
}

/**
 *	wpsight_madrid_get_section_title()
 *	
 *	Build section title.
 *	
 *	@param	array	$args
 *	@param	array	$widget_args
 *	@uses	wpsight_get_template()
 *	
 *	@return	mixed
 *	
 *	@since 1.0.0
 */
function wpsight_madrid_get_section_title( $args = array(), $widget_args = array() ) {

	// Set some defaults
		
	$defaults = array(
		'title'			=> false,
		'separator'		=> true,
		'description'	=> false,
		'align'			=> 'center',
		'link_text'		=> false,
		'link_url'		=> false,
		'link_blank'	=> false,
	);
	
	// Merge args with defaults and apply filter
	$args = apply_filters( 'wpsight_madrid_get_section_title_args', wp_parse_args( $args, $defaults ), $args );
	
	ob_start();

	wpsight_get_template( 'section-title.php', array( 'args' => $args, 'widget_args' => $widget_args ) );
		
	return apply_filters( 'wpsight_madrid_get_section_title', ob_get_clean(), $args );

}
