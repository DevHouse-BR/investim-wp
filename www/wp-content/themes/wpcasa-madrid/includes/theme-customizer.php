<?php
/**
 * Theme Customizer
 *
 * @package WPCasa Madrid
 */

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @uses wp_enqueue_script()
 * @uses get_template_directory_uri()
 *
 * @since 1.0
 */
add_action( 'customize_preview_init', 'wpsight_madrid_customize_preview_js' );

function wpsight_madrid_customize_preview_js() {
	
	// Script debugging?
	$suffix = SCRIPT_DEBUG ? '' : '.min';
	
	wp_enqueue_script( 'wpsight_madrid_customizer', get_template_directory_uri() . '/assets/js/wpsight-customizer' . $suffix . '.js', array( 'jquery','customize-preview' ), '1112345897', true );
}

/**
 * Add postMessage support for site description
 * and background color for the Theme Customizer.
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->get_setting()
 *
 * @since 1.0
 */
add_action( 'customize_register', 'wpsight_madrid_customize_register', 11 );

function wpsight_madrid_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';
	
	$wp_customize->remove_control( 'display_header_text' );
}

/**
 * Register customizer logo theme_mods
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->add_setting()
 *
 * @since 1.0.1
 */
add_action( 'customize_register', 'wpsight_madrid_customize_register_logo' );

function wpsight_madrid_customize_register_logo( $wp_customize ) {
	
	// Set tagline deactivation option

	$wp_customize->add_setting( 'deactivate_tagline', array(
		'default'			=> false,
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));

	$wp_customize->add_control( 'customize_tagline', array(
    	'section'   		=> 'title_tagline',
		'label'     		=> __( 'Hide tagline', 'wpcasa-madrid' ),
		'type'      		=> 'checkbox',
		'settings'			=> 'deactivate_tagline'
    ));
	
	// Set text logo
	
	$wp_customize->add_setting( 'wpcasa_logo_text', array(
        'capability'		=> 'edit_theme_options',
		'sanitize_callback'	=> 'wp_kses_post',
		'default'			=> get_bloginfo( 'name' ),
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	// Add text logo control
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_logo_text', array(
		'label'				=> __( 'Text Logo', 'wpcasa-madrid' ),
		'section'			=> 'title_tagline',
		'description'		=> __( 'It will only be displayed if no regular logo image is provided.', 'wpcasa-madrid' ),
		'settings'			=> 'wpcasa_logo_text'
	)));
	
	// Set logo image
	
	$wp_customize->add_setting( 'wpcasa_logo', array(
        'capability'		=> 'edit_theme_options',
		'sanitize_callback'	=> 'esc_url',
		'type'				=> 'theme_mod',
		'default'			=> get_template_directory_uri() . '/assets/images/logo.png',
	));
	
	// Add logo upload control
	
	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'wpcasa_logo', array(
		'label'				=> __( 'Logo', 'wpcasa-madrid' ),
		'section'			=> 'title_tagline',
		'settings'			=> 'wpcasa_logo'
	)));
	
	// Set print logo image
	
	$wp_customize->add_setting( 'wpcasa_logo_print', array(
        'capability'		=> 'edit_theme_options',
		'sanitize_callback'	=> 'esc_url',
		'type'				=> 'theme_mod',
	));
	
	// Add print logo upload control
	
	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'wpcasa_logo_print', array(
		'label'				=> __( 'Print Logo', 'wpcasa-madrid' ),
		'section'			=> 'title_tagline',
		'description'		=> __( 'Optionally upload a print logo that will be displayed in listing print versions or PDFs.', 'wpcasa-madrid' ),
		'settings'			=> 'wpcasa_logo_print'
	)));

}

/**
 * Register customizer top section theme_mods
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->add_section()
 *
 * @since 1.0.1
 */

add_action( 'customize_register', 'wpsight_madrid_customize_register_top' );

function wpsight_madrid_customize_register_top( $wp_customize ) {

	// Add section 'Site Top'
	
	$wp_customize->add_section( 'wpsight_madrid_site_top' , array(
	    'title'				=> __( 'Site Top', 'wpcasa-madrid' ),
		'priority'			=> 40
	));
	
	// Set site top icon box #1
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_1_title', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Get in touch', 'wpcasa-madrid' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_1_title', array(
		'label'				=> sprintf( __( 'Icon Box #%s', 'wpcasa-madrid' ), '1' ),
		'description'		=> __( 'Title', 'wpcasa-madrid' ),
		'section'			=> 'wpsight_madrid_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_1_title'
	)));
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_1_icon', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> 'fa fa-phone fa-fw',
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_1_icon', array(
		'label'				=> false,
		'description'		=> __( 'Icon Class', 'wpcasa-madrid' ) . ' <small>(' . __( 'For example <a href="https://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a>', 'wpcasa-madrid' ) . ')</small>',
		'section'			=> 'wpsight_madrid_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_1_icon'
	)));
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_1_text_1', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Put some text here', 'wpcasa-madrid' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_1_text_1', array(
		'label'				=> false,
		'description'		=> __( 'Text Line', 'wpcasa-madrid' ),
		'section'			=> 'wpsight_madrid_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_1_text_1'
	)));
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_1_text_2', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Put some text here', 'wpcasa-madrid' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_1_text_2', array(
		'label'				=> false,
		'description'		=> __( 'Text Line', 'wpcasa-madrid' ),
		'section'			=> 'wpsight_madrid_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_1_text_2'
	)));

	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_1_display', array(
		'default'			=> true,
		'type'				=> 'theme_mod',
	));

	$wp_customize->add_control( 'wpcasa_site_top_icon_box_1_display', array(
    	'section'   => 'wpsight_madrid_site_top',
		'label'     => sprintf( __( 'Display Box #%s', 'wpcasa-madrid' ), '1' ),
		'type'      => 'checkbox',
		'settings'	=> 'wpcasa_site_top_icon_box_1_display'
    ));
	
	// Set site top icon box #2
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_2_title', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Office hours', 'wpcasa-madrid' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_2_title', array(
		'label'				=> sprintf( __( 'Icon Box #%s', 'wpcasa-madrid' ), '2' ),
		'description'		=> __( 'Title', 'wpcasa-madrid' ),
		'section'			=> 'wpsight_madrid_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_2_title'
	)));
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_2_icon', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> 'fa fa-clock-o fa-fw',
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_2_icon', array(
		'label'				=> false,
		'description'		=> __( 'Icon Class', 'wpcasa-madrid' ) . ' <small>(' . __( 'For example <a href="https://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a>', 'wpcasa-madrid' ) . ')</small>',
		'section'			=> 'wpsight_madrid_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_2_icon'
	)));
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_2_text_1', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Put some text here', 'wpcasa-madrid' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_2_text_1', array(
		'label'				=> false,
		'description'		=> __( 'Text Line', 'wpcasa-madrid' ),
		'section'			=> 'wpsight_madrid_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_2_text_1'
	)));
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_2_text_2', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Put some text here', 'wpcasa-madrid' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_2_text_2', array(
		'label'				=> false,
		'description'		=> __( 'Text Line', 'wpcasa-madrid' ),
		'section'			=> 'wpsight_madrid_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_2_text_2'
	)));

	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_2_display', array(
		'default'			=> true,
		'type'				=> 'theme_mod',
	));

	$wp_customize->add_control( 'wpcasa_site_top_icon_box_2_display', array(
    	'section'   => 'wpsight_madrid_site_top',
		'label'     => sprintf( __( 'Display Box #%s', 'wpcasa-madrid' ), '2' ),
		'type'      => 'checkbox',
		'settings'	=> 'wpcasa_site_top_icon_box_2_display'
    ));
	
	// Set site top icon box #3
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_3_title', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Our location', 'wpcasa-madrid' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_3_title', array(
		'label'				=> sprintf( __( 'Icon Box #%s', 'wpcasa-madrid' ), '3' ),
		'description'		=> __( 'Title', 'wpcasa-madrid' ),
		'section'			=> 'wpsight_madrid_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_3_title'
	)));
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_3_icon', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> 'fa fa-map-marker fa-fw',
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_3_icon', array(
		'label'				=> false,
		'description'		=> __( 'Icon Class', 'wpcasa-madrid' ) . ' <small>(' . __( 'For example <a href="https://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a>', 'wpcasa-madrid' ) . ')</small>',
		'section'			=> 'wpsight_madrid_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_3_icon'
	)));
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_3_text_1', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Put some text here', 'wpcasa-madrid' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_3_text_1', array(
		'label'				=> false,
		'description'		=> __( 'Text Line', 'wpcasa-madrid' ),
		'section'			=> 'wpsight_madrid_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_3_text_1'
	)));
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_3_text_2', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Put some text here', 'wpcasa-madrid' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_3_text_2', array(
		'label'				=> false,
		'description'		=> __( 'Text Line', 'wpcasa-madrid' ),
		'section'			=> 'wpsight_madrid_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_3_text_2'
	)));

	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_3_display', array(
		'default'			=> true,
		'type'				=> 'theme_mod',
	));

	$wp_customize->add_control( 'wpcasa_site_top_icon_box_3_display', array(
    	'section'   => 'wpsight_madrid_site_top',
		'label'     => sprintf( __( 'Display Box #%s', 'wpcasa-madrid' ), '3' ),
		'type'      => 'checkbox',
		'settings'	=> 'wpcasa_site_top_icon_box_3_display'
    ));

	$wp_customize->add_setting( 'wpcasa_site_top_vertical', array(
		'default'			=> false,
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));

	$wp_customize->add_control( 'wpcasa_site_top_vertical', array(
    	'section'   => 'wpsight_madrid_site_top',
		'label'     => __( 'Show logo and icons vertically on small screens', 'wpcasa-madrid' ),
		'type'      => 'checkbox',
		'settings'	=> 'wpcasa_site_top_vertical'
    ));

}

/**
 * Register customizer bottom section theme_mods
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->add_section()
 *
 * @since 1.0.1
 */

add_action( 'customize_register', 'wpsight_madrid_customize_register_bottom' );

function wpsight_madrid_customize_register_bottom( $wp_customize ) {

	// Add section 'Site Bottom'
	
	$wp_customize->add_section( 'wpsight_madrid_site_bottom' , array(
	    'title'				=> __( 'Site Bottom', 'wpcasa-madrid' ),
		'priority'			=> 40
	));
	
	// Set site bottom left
	
	$wp_customize->add_setting( 'wpcasa_site_bottom_left', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> sprintf( __( 'Copyright &copy; <span itemprop="copyrightYear">%s</span> &middot; <a href="%s" rel="home" itemprop="copyrightHolder">%s</a>', 'wpcasa-madrid' ), date( 'Y' ), esc_url( home_url( '/' ) ), get_bloginfo( 'name' ) ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new wpsight_madrid_Customize_Textarea_Control( $wp_customize, 'site_bottom_left', array(
		'label'				=> __( 'Site Bottom Left', 'wpcasa-madrid' ),
		'section'			=> 'wpsight_madrid_site_bottom',
		'settings'			=> 'wpcasa_site_bottom_left'
	)));
	
	// Set site bottom right
	
	$wp_customize->add_setting( 'wpcasa_site_bottom_right', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Built on <a href="http://wordpress.org">WordPress</a> &amp; <a href="https://wpcasa.com">WPCasa</a>', 'wpcasa-madrid' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new wpsight_madrid_Customize_Textarea_Control( $wp_customize, 'site_bottom_right', array(
		'label'				=> __( 'Site Bottom Right', 'wpcasa-madrid' ),
		'section'			=> 'wpsight_madrid_site_bottom',
		'settings'			=> 'wpcasa_site_bottom_right'
	)));

}

/**
 * Register custom customizer color options
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->add_setting()
 *
 * @since 1.0.1
 */
add_action( 'customize_register', 'wpsight_madrid_customize_register_color' );

function wpsight_madrid_customize_register_color( $wp_customize ) {
	
	// Set light section background color
	
	$wp_customize->add_setting( 'main_text_color', array(
		'default' 			=> '#555',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'main_text_color', array(
	    'label'    => __( 'Text Color', 'wpcasa-madrid' ),
	    'section'  => 'colors',
	    'settings' => 'main_text_color',
	)));
	
	// Set light section background color
	
	$wp_customize->add_setting( 'light_background_color', array(
		'default' 			=> '#ffffff',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'light_background_color', array(
	    'label'    => __( 'Section Background Color (light)', 'wpcasa-madrid' ),
	    'section'  => 'colors',
	    'settings' => 'light_background_color',
	)));
	
	// Set dark section background color #1
	
	$wp_customize->add_setting( 'dark_background_color_1', array(
		'default' 			=> '#f6f6f8',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_background_color_1', array(
	    'label'    		=> __( 'Section Background Color (dark)', 'wpcasa-madrid' ),
		'description'	=> __( 'Gradient start', 'wpcasa-madrid' ),
	    'section'		=> 'colors',
	    'settings'		=> 'dark_background_color_1',
	)));
	
	// Set dark section background color #2
	
	$wp_customize->add_setting( 'dark_background_color_2', array(
		'default' 			=> '#f0f0f3',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_background_color_2', array(
	    'label'    		=> __( 'Section Background Color (dark)', 'wpcasa-madrid' ),
		'description'	=> __( 'Gradient end', 'wpcasa-madrid' ),
	    'section'		=> 'colors',
	    'settings'		=> 'dark_background_color_2',
	)));
	
	// Set light accent color
	
	$wp_customize->add_setting( 'light_accent_color', array(
		'default' 			=> '#8e9eab',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'light_accent_color', array(
	    'label'    => __( 'Accent Color (light)', 'wpcasa-madrid' ),
	    'section'  => 'colors',
	    'settings' => 'light_accent_color',
	)));
	
	// Set dark accent color
	
	$wp_customize->add_setting( 'dark_accent_color', array(
		'default' 			=> '#444444',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_accent_color', array(
	    'label'    => __( 'Accent Color (dark)', 'wpcasa-madrid' ),
	    'section'  => 'colors',
	    'settings' => 'dark_accent_color',
	)));
	
	// Set custom header image overlay color
	
	$wp_customize->add_setting( 'header_overlay_color', array(
		'default' 			=> '#444444',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_overlay_color', array(
	    'label'    			=> __( 'Header Overlay Color', 'wpcasa-madrid' ),
	    'section'  			=> 'colors',
	    'settings' 			=> 'header_overlay_color',
	)));
	
	// Set image overlay deactivation option

	$wp_customize->add_setting( 'deactivate_header_overlay', array(
		'default'			=> false,
		'type'				=> 'theme_mod',
	));

	$wp_customize->add_control( 'customize_header_overlay', array(
    	'section'   => 'colors',
		'label'     => __( 'Deactivate header overlay', 'wpcasa-madrid' ),
		'type'      => 'checkbox',
		'settings'	=> 'deactivate_header_overlay'
    ));

}

/**
 * Add theme mods CSS from
 * theme options to header
 *
 * @uses wpsight_madrid_generate_css()
 *
 * @since 1.0
 */
add_action( 'wp_head', 'wpsight_madrid_do_theme_mods_css' );

function wpsight_madrid_do_theme_mods_css() {

	$mods 	= '';
	$colors = '';
	
	// Hide tagline
	
	$hide_tagline = get_theme_mod( 'deactivate_tagline' );
	
	if( $hide_tagline )
		$mods .= '.site-description { display: none; }';
	
	// Deactivate header overlay

	$header_overlay = get_theme_mod( 'deactivate_header_overlay' );
	
	if( $header_overlay )
		$mods .= '.site-header-inner, .wpsight-listings-slider-wrap .listing-slider-wrap:after { background-color: transparent !important; } .site-header-gallery .wpsight-image-background-slider-item { opacity: 1; }';
	
	// Set header image overlay color	
	$colors .= wpsight_madrid_generate_css( '.site-header-inner, .wpsight-listings-slider-wrap .listing-slider-wrap:after', 'background-color', 'header_overlay_color', '', '', false, '.35' );
	
	$colors .= wpsight_madrid_generate_css( '.site-header-gallery .site-header-inner, .wpsight-image-background-slider-item', 'background-color', 'header_overlay_color' );
	
	// Set text color	
	$colors .= wpsight_madrid_generate_css( 'body.wpsight-madrid, .site-title-text, .site-description, .site-top .menu a:hover, .site-sub-bottom .menu a:hover, .site-wrapper .btn-group .dropdown-menu > li > a, .site-wrapper .btn-group .dropdown-menu > li > a:hover, .site-wrapper .btn-group .dropdown-menu > li > a:focus, .wpsight-listings-search, .wpsight-listings .listing-content, .wpsight-listings-carousel .listing-content, .wpsight-listings .wpsight-listing-summary, .single-listing .wpsight-listing-title .actions-print:before, .single-listing .wpsight-listing-title .favorites-add:before, .single-listing .wpsight-listing-title .favorites-see:before, .wpsight-listings-carousel .wpsight-listing-summary, .single-listing .wpsight-listing-title .actions-print:hover:before, .single-listing .wpsight-listing-title .favorites-add:hover:before, .single-listing .wpsight-listing-title .favorites-see:hover:before, .icon-box-icon, .site-section-cta, .wpsight-cta, .wpsight-pricing-table .corner-ribbon.default, .social-icons .fa', 'color', 'main_text_color' );
	
	$colors .= wpsight_madrid_generate_css( '.site-wrapper .dropdown-toggle.btn-default, .site-wrapper .dropdown-toggle.btn-default:hover', 'color', 'main_text_color', '', ' !important' );
	
	// Set light background color
	$colors .= wpsight_madrid_generate_css( '.site-container, .site-header-page_title + .header-widgets .header-main', 'background-color', 'light_background_color' );
	
	$colors .= wpsight_madrid_generate_css( '.single-listing .wpsight-listing-features .listing-term-wrap, .wpsight-pagination .pagination > li > a, .wpsight-pagination .pagination > li > a:hover, .wpsight-pagination .pagination > li > span:hover, .wpsight-pagination .pagination > li > a:focus, .wpsight-pagination .pagination > li > span:focus.wpsight-pagination .pagination > li > span', 'border-color', 'light_background_color' );
	
	// Set gradients with dark background color
	
	$background_dark_1	= get_theme_mod( 'dark_background_color_1' );
	$background_dark_2	= get_theme_mod( 'dark_background_color_2' );
	
	if( ( $background_dark_1 && '#f6f6f8' != $background_dark_1 ) || ( $background_dark_2 && '#f0f0f3' != $background_dark_2 ) ) {
		
		$colors .= '.light-gradient, .header-main, .site-main-top, .single-listing .wpsight-listing-features, .single-listing .wpsight-listing-agent, .wpsight-list-agents-sc .wpsight-list-agent, .archive.author .wpsight-list-agent, .bs-callout, .site-section-cta, .wpsight-cta, .wpsight-newsletter-box, .wpsight-infobox .wpsight-listing-summary {
			background: ' . $background_dark_1 . ';
			background: -moz-linear-gradient(top,  ' . $background_dark_1 . ' 0%, ' . $background_dark_2 . ' 100%);
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,' . $background_dark_1 . '), color-stop(100%,' . $background_dark_2 . '));
			background: -webkit-linear-gradient(top,  ' . $background_dark_1 . ' 0%,' . $background_dark_2 . ' 100%);
			background: -o-linear-gradient(top,  ' . $background_dark_1 . ' 0%,' . $background_dark_2 . ' 100%);
			background: -ms-linear-gradient(top,  ' . $background_dark_1 . ' 0%,' . $background_dark_2 . ' 100%);
			background: linear-gradient(to bottom,  ' . $background_dark_1 . ' 0%,' . $background_dark_2 . ' 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'' . $background_dark_1 . '\', endColorstr=\'' . $background_dark_2 . '\',GradientType=0 );
		}';
	
	}
	
	// Set light accent color
	$colors .= wpsight_madrid_generate_css( '.site-header-page_title .site-header-inner, .site-header-archive_title .site-header-inner, .site-top .accent, .site-sub-bottom .accent, .content-title:after, .site-section .listings-title-info:after, article .page-header:after, .widget-title:after, .single-listing .page-header:after, .single-listing .wpsight-listing-features .listing-term-wrap i, .wpsight-pagination .pagination > .active > a, .wpsight-pagination .pagination > .active > span, .wpsight-pagination .pagination > .active > a:hover, .wpsight-pagination .pagination > .active > span:hover, .wpsight-pagination .pagination > .active > a:focus, .wpsight-pagination .pagination > .active > span:focus, .comment-body:after, .bs-callout-primary:before, .feature-box-icon, .section-title:after, .site-wrapper #home-search .checkbox-primary input[type="checkbox"]:checked + label::before, .site-wrapper #home-search .checkbox-primary input[type="radio"]:checked + label::before', 'background-color', 'light_accent_color' );
	
	$colors .= wpsight_madrid_generate_css( '.wpsight-nav .dropdown-menu', 'background-color', 'light_accent_color', '', '', false, '.9' );
	
	$colors .= wpsight_madrid_generate_css( '.site-wrapper .label-primary, .site-wrapper .btn-primary, .site-wrapper .btn-primary:active, .site-wrapper .btn-primary.active, .site-wrapper .open > .dropdown-toggle.btn-primary', 'background-color', 'light_accent_color', '', ' !important' );
	
	$colors .= wpsight_madrid_generate_css( '.site-wrapper a, .site-wrapper a:focus, .site-wrapper a:hover, .accent, .post .entry-title a:hover, .page .entry-title a:hover, .wpsight-listings .wpsight-listing-title .entry-title a:hover, .wpsight-listings-carousel .wpsight-listing-title .entry-title a:hover, .wpsight-listings .wpsight-listing-summary .listing-details-detail:before, .wpsight-listings-carousel .wpsight-listing-summary .listing-details-detail:before, .site-footer .listing-teaser-location-type a, .wpsight-infobox .wpsight-listing-summary .listing-details-detail:before', 'color', 'light_accent_color' );
	
	$colors .= wpsight_madrid_generate_css( '.site-wrapper #home-search .checkbox-primary input[type="checkbox"]:checked + label::before, .site-wrapper #home-search .checkbox-primary input[type="radio"]:checked + label::before', 'border-color', 'light_accent_color' );
	
	// Set dark accent color
	$colors .= wpsight_madrid_generate_css( '.wpsight-madrid .lg-backdrop, .site-header, .header-menu, .site-header-gallery .site-header-inner, #home-search, .site-section-dark, .tags-links .post-tag-wrap i, .site-footer, .site-bottom, .wpsight-listings-slider-wrap .listing-content .wpsight-listing-price, .site-wrapper .checkbox-primary input[type="checkbox"]:checked + label::before, .site-wrapper .checkbox-primary input[type="radio"]:checked + label::before, .site-wrapper .radio-primary input[type="radio"]:checked + label::after, .cmb2-wrap input.btn:focus, .cmb2-wrap input.btn:active, .header-main .wpsight-listings-search, .site-main-top .wpsight-listings-search, .header-main .listings-search-reset, .header-main .listings-search-advanced-toggle, .site-main-top .listings-search-reset, .site-main-top .listings-search-advanced-toggle', 'background-color', 'dark_accent_color' );
	
	$colors .= wpsight_madrid_generate_css( '.site-wrapper .btn-default:not(.dropdown-toggle), .site-wrapper .btn-default:not(.dropdown-toggle):hover, .site-wrapper .btn-default:not(.dropdown-toggle):active, .site-wrapper .btn-default:not(.dropdown-toggle).active, #home-search .listings-search-reset, #home-search .listings-search-advanced-toggle', 'background-color', 'dark_accent_color', '', ' !important' );
	
	$colors .= wpsight_madrid_generate_css( '.site-wrapper .checkbox-primary input[type="checkbox"]:checked + label::before, .site-wrapper .checkbox-primary input[type="radio"]:checked + label::before, .site-wrapper .radio-primary input[type="radio"]:checked + label::before', 'border-color', 'dark_accent_color' );
	
	if( ! empty( $colors ) )
		$mods .= $colors;
	
	if( ! empty( $mods ) ) {	
	
		$css  = '<style type="text/css" media="screen">';
		$css .= $mods;
		$css .= '</style>' . "\n";
		
		echo $css;
		
	}

}

/**
 * Helper function to display
 * theme_mods CSS
 *
 * @param string $selector CSS selector
 * @param string $style CSS style
 * @param string $mod_name Key of the modification
 * @param string $prefix Prefix for CSS style
 * @param string $postfix Postfix for CSS style
 * @param bool $echo Return or echo
 * @param bool|string Opacity for rgba() colors
 * @uses wpsight_madrid_hex2rgb()
 * @uses sprintf()
 *
 * @since 1.0.1
 */
function wpsight_madrid_generate_css( $selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = false, $opacity = false ) {

	$output = '';
	$mod = get_theme_mod( $mod_name );
	
	if ( ! empty( $mod ) ) {		
	
		if( $opacity !== false ) {
			$rgb = wpsight_madrid_hex2rgb( $mod );
			$mod = 'rgba(' . $rgb . ',' . $opacity . ')';
		}
	
	   $output = "\n\t" . sprintf( '%s { %s:%s; }', $selector, $style, $prefix . $mod . $postfix ) . "\n";
	   
	   if ( $echo )
	      echo $output;
	}
	
	return $output;

}

/**
 * Helper function to convert
 * hex color in RGBA
 *
 * @param string $hex Hex color code
 * @uses str_replace()
 * @uses strlen()
 * @uses hexdec()
 * @uses substr()
 * @return string RGB color code
 *
 * @since 1.0.1
 */
function wpsight_madrid_hex2rgb( $hex ) {

	$hex = str_replace( '#', '', $hex );
	
	if( strlen( $hex ) == 3 ) {
	
	   $r = hexdec( substr( $hex,0,1 ) . substr( $hex,0,1 ) );
	   $g = hexdec( substr( $hex,1,1 ) . substr( $hex,1,1 ) );
	   $b = hexdec( substr( $hex,2,1 ) . substr( $hex,2,1 ) );
	   
	} else {
	
	   $r = hexdec( substr( $hex,0,2 ) );
	   $g = hexdec( substr( $hex,2,2 ) );
	   $b = hexdec( substr( $hex,4,2 ) );

	}
	
	$rgb = array( $r, $g, $b );
	
	return implode( ',', $rgb );
}

/**
 * Add wpsight_madrid_Customize_Textarea_Control
 *
 * @uses esc_html()
 * @uses esc_textarea()
 *
 * @since 1.0.1
 */
 
add_action( 'customize_register', 'wpsight_madrid_customize_register_class_textarea', 9 );

function wpsight_madrid_customize_register_class_textarea() {

	class WPSight_Madrid_Customize_Textarea_Control extends WP_Customize_Control {
	
	    public $type = 'textarea';
	 
	    public function render_content() {
	        ?>
	        <label>
	        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
	        <textarea rows="6" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
	        </label>
	        <?php
	    }
	}

}
