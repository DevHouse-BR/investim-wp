<?php
/**
 *	Custom widget areas
 *	
 *	@package WPCasa Madrid
 */

/**
 *	wpsight_madrid_widget_areas_register()
 *
 *	Register all widget areas held
 *	in wpsight_madrid_widget_areas().
 *
 *	@uses	wpsight_madrid_widget_areas()
 *	@uses	register_sidebar()
 *	
 *	@since	1.0.0
 */
add_action( 'widgets_init', 'wpsight_madrid_widget_areas_register' );

function wpsight_madrid_widget_areas_register() {

	foreach( wpsight_madrid_widget_areas() as $widget_area )
		register_sidebar( $widget_area );

}

/**
 *	wpsight_madrid_widget_areas()
 *
 *	Define widget areas in central array.
 *
 *	@uses	wpsight_sort_array_by_priority()
 *	@uses	wpsight_madrid_count_widgets()
 *	@return	array
 *
 *	@since 1.0.0
 */
function wpsight_madrid_widget_areas() {

	$widget_areas = array(
	
		'top-left' => array(
			'name' 			=> __( 'Top Left', 'wpcasa-madrid' ),
			'description' 	=> __( 'Display content outside of the main site container.', 'wpcasa-madrid' ),
			'id' 			=> 'top-left',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 10
		),
		
		'top-right' => array(
			'name' 			=> __( 'Top Right', 'wpcasa-madrid' ),
			'description' 	=> __( 'Display content outside of the main site container.', 'wpcasa-madrid' ),
			'id' 			=> 'top-right',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 20
		),
		
		'header-full' => array(
			'name' 			=> __( 'Header Full', 'wpcasa-madrid' ),
			'description' 	=> __( 'Display full-width content at the top of the site.', 'wpcasa-madrid' ),
			'id' 			=> 'header-full',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 30
		),
		
		'header-main' => array(
			'name' 			=> __( 'Header', 'wpcasa-madrid' ),
			'description' 	=> __( 'Display content at the top of the site.', 'wpcasa-madrid' ),
			'id' 			=> 'header-main',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 40
		),
		
		'header-columns' => array(
			'name' 			=> __( 'Header (columns)', 'wpcasa-madrid' ),
			'description' 	=> __( 'Display content columns at the top of the site.', 'wpcasa-madrid' ),
			'id' 			=> 'header-columns',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s ' . wpsight_madrid_count_widgets( 'header-columns' ) . '"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 50
		),
		
		'sidebar' => array(
			'name' 			=> __( 'Sidebar', 'wpcasa-madrid' ),
			'description' 	=> __( 'Display content in the general sidebar widget area.', 'wpcasa-madrid' ),
			'id' 			=> 'sidebar',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 60
		),
		
		'content-top' => array(
			'name' 			=> __( 'Content Top', 'wpcasa-madrid' ),
			'description' 	=> __( 'Display content before the main content area.', 'wpcasa-madrid' ),
			'id' 			=> 'content-top',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 70
		),
		
		'content-bottom' => array(
			'name' 			=> __( 'Content Bottom', 'wpcasa-madrid' ),
			'description' 	=> __( 'Display content after the main content area.', 'wpcasa-madrid' ),
			'id' 			=> 'content-bottom',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 80
		),
		
		'listing-top' => array(
			'name' 			=> __( 'Listing Top', 'wpcasa-madrid' ),
			'description' 	=> __( 'Display full-width content above the regular content on single listing pages.', 'wpcasa-madrid' ),
			'id' 			=> 'listing-top',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h2 class="widget-title">',
			'after_title' 	=> '</h2>',
			'priority'		=> 90
		),
		
		'listing' => array(
			'name' 			=> __( 'Listing Content', 'wpcasa-madrid' ),
			'description' 	=> __( 'Display main content on single listing pages.', 'wpcasa-madrid' ),
			'id' 			=> 'listing',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h2 class="widget-title">',
			'after_title' 	=> '</h2>',
			'priority'		=> 100
		),
		
		'sidebar-listing' => array(
			'name' 			=> __( 'Listing Sidebar', 'wpcasa-madrid' ),
			'description'	=> __( 'Display content in the sidebar on single listing pages.', 'wpcasa-madrid' ),
			'id' 			=> 'sidebar-listing',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 110
		),
		
		'listing-bottom' => array(
			'name' 			=> __( 'Listing Bottom', 'wpcasa-madrid' ),
			'description' 	=> __( 'Display full-width content below the regular content on single listing pages.', 'wpcasa-madrid' ),
			'id' 			=> 'listing-bottom',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h2 class="widget-title">',
			'after_title' 	=> '</h2>',
			'priority'		=> 120
		),
		
		'footer-main' => array(
			'name' 			=> __( 'Footer', 'wpcasa-madrid' ),
			'description' 	=> __( 'Display content in the footer widget area.', 'wpcasa-madrid' ),
			'id' 			=> 'footer-main',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 130
		),
		
		'footer-columns' => array(
			'name' 			=> __( 'Footer (columns)', 'wpcasa-madrid' ),
			'description' 	=> __( 'Display content columns at the bottom of the site.', 'wpcasa-madrid' ),
			'id' 			=> 'footer-columns',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s ' . wpsight_madrid_count_widgets( 'footer-columns' ) . '"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 140
		),
		
		'footer-full' => array(
			'name' 			=> __( 'Footer Full', 'wpcasa-madrid' ),
			'description' 	=> __( 'Display full-width content in the footer widget area.', 'wpcasa-madrid' ),
			'id' 			=> 'footer-full',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 150
		),
	
	);
	
	$widget_areas = apply_filters( 'wpsight_madrid_widget_areas', $widget_areas );
	
	// Sort array by position
	
	if( function_exists( 'wpsight_sort_array_by_priority') )
    	$widget_areas = wpsight_sort_array_by_priority( $widget_areas );
	
	return $widget_areas;

}

/**
 *	wpsight_madrid_count_widgets()
 *
 *	Count number of widgets in a sidebar
 *	Used to add classes to widget areas so widgets can be displayed one, two, three or four per row
 *
 *	@param	string	$sidebar_id
 *	@return	string	$widget_classes	
 *	@see	https://gist.github.com/slobodan/6156076
 *
 *	@since	1.0.0
 */
function wpsight_madrid_count_widgets( $sidebar_id ) {
	// If loading from front page, consult $_wp_sidebars_widgets rather than options
	// to see if wp_convert_widget_settings() has made manipulations in memory.
	global $_wp_sidebars_widgets;
	if ( empty( $_wp_sidebars_widgets ) ) :
		$_wp_sidebars_widgets = get_option( 'sidebars_widgets', array() );
	endif;
	
	$sidebars_widgets_count = $_wp_sidebars_widgets;
	
	if ( isset( $sidebars_widgets_count[ $sidebar_id ] ) ) :
	
		foreach( $sidebars_widgets_count[ $sidebar_id ] as $key => $widget_id ) {						
			if( false !== strpos( $widget_id, '_section_title' ) )
				unset( $sidebars_widgets_count[ $sidebar_id ][ $key ] );
		}
	
		$widget_count = count( $sidebars_widgets_count[ $sidebar_id ] );
		$widget_classes = 'widget-count-' . count( $sidebars_widgets_count[ $sidebar_id ] );
		
		if ( $widget_count == 1 ) :
			$widget_classes .= ' col-sm-12';
		elseif ( 2 == $widget_count ) :
			$widget_classes .= ' col-sm-12 col-md-6';
		elseif ( $widget_count >= 3 ) :
			$widget_classes .= ' col-sm-12 col-md-4';
		endif; 

		return $widget_classes;
	endif;

}

/**
 *	wpsight_madrid_is_active_sidebar()
 *
 *	Check if a specific widget area actually produces output.
 *	Only checking with is_active_sidebar() is sometimes not enough. When
 *	we activate the gallery widget for example in a widget area but a
 *	listing does not have images, the sidebar is active but not output
 *	is produced.
 *
 *	@param	bool	$is_active_sidebar
 *	@param	string	$sidebar_id
 *	@return	bool
 *
 *	@since 1.0.0
 */
add_filter( 'is_active_sidebar', 'wpsight_madrid_is_active_sidebar', 10, 2 );

function wpsight_madrid_is_active_sidebar( $is_active_sidebar, $sidebar_id ) {

	if( is_dynamic_sidebar( $sidebar_id ) ) {
		
		ob_start();
		dynamic_sidebar( $sidebar_id );
		$output = ob_get_clean();
		
		if( empty( $output ) )
			$is_active_sidebar = false;
		
	}
	
	return $is_active_sidebar;	

}
