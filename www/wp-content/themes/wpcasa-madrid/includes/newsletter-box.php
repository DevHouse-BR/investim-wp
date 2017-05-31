<?php
/**
 *	Newsletter Box
 *	
 *	@package WPCasa Madrid
 */

/**
 *	wpsight_madrid_newsletter_box()
 *	
 *	Echo wpsight_madrid_get_newsletter_box()
 *	
 *	@param	array	$args
 *	@param	array	$widget_args
 *	@uses	wpsight_madrid_get_newsletter_box()
 *	
 *	@since	1.0.0
 */
function wpsight_madrid_newsletter_box( $args = array(), $widget_args = array() ) {
	echo wpsight_madrid_get_newsletter_box( $args, $widget_args );
}

/**
 *	wpsight_madrid_get_newsletter_box()
 *	
 *	Build newsletter box.
 *	
 *	@param	array	$args
 *	@param	array	$widget_args
 *	@uses	wpsight_get_template()
 *	
 *	@return	mixed
 *	
 *	@since 1.0.0
 */
function wpsight_madrid_get_newsletter_box( $args = array(), $widget_args = array() ) {

	// Set some defaults
		
	$defaults = array(
		'title'			=> __( 'Signup for Our Newsletter', 'wpcasa-madrid' ),
		'description'	=> __( 'Stay updated and get our latest news right into your inbox. No spam.', 'wpcasa-madrid' ),
		'form'			=> false,
		'icon_class'	=> 'fa fa-envelope-o',
	);
	
	// Merge args with defaults and apply filter
	$args = apply_filters( 'wpsight_madrid_get_newsletter_box_args', wp_parse_args( $args, $defaults ), $args );
	
	ob_start();

	wpsight_get_template( 'newsletter-box.php', array( 'args' => $args, 'widget_args' => $widget_args ) );
		
	return apply_filters( 'wpsight_madrid_get_newsletter_box', ob_get_clean(), $args );

}
