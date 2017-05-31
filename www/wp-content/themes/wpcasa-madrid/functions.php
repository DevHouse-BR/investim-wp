<?php
/**
 * WPCasa Bootstrap theme functions and definitions
 *
 * @package WPCasa Madrid
 */

/**
 * Define theme constants
 */
add_action( 'after_setup_theme', 'wpsight_madrid_constants' );

function wpsight_madrid_constants() {
	
	if ( ! defined( 'WPSIGHT_NAME' ) )
		define( 'WPSIGHT_NAME', 'WPCasa' );

	if ( ! defined( 'WPSIGHT_DOMAIN' ) )
		define( 'WPSIGHT_DOMAIN', 'wpcasa' );
	
	if ( ! defined( 'WPSIGHT_SHOP_URL' ) )
		define( 'WPSIGHT_SHOP_URL', 'https://wpcasa.com' );

	if ( ! defined( 'WPSIGHT_AUTHOR' ) )
		define( 'WPSIGHT_AUTHOR', 'WPSight' );

	// General theme constants
	
	define( 'WPSIGHT_MADRID_NAME', 'WPCasa Madrid' );
	define( 'WPSIGHT_MADRID_DOMAIN', 'wpcasa-madrid' );
	define( 'WPSIGHT_MADRID_VERSION', '1.1.0' );

}

/**
 * Require necessary and recommend useful plugins
 */
require_once get_template_directory() . '/includes/install-plugins.php';

/**
 * Enqueue scripts and styles
 */
add_action( 'wp_enqueue_scripts', 'wpsight_madrid_scripts' );

function wpsight_madrid_scripts() {
	
	// Script debugging?
	$suffix = SCRIPT_DEBUG ? '' : '.min';
	
	// Enqueue theme CSS
	wp_enqueue_style( 'wpcasa-madrid', get_stylesheet_uri() );
	
	// Enqueue theme JS
	wp_enqueue_script( 'wpcasa-madrid', get_template_directory_uri() . '/assets/js/wpcasa-madrid.js', array( 'jquery' ), WPSIGHT_MADRID_VERSION, true );
	
	// Enqueue Bootstrap library if desired
	
	if( apply_filters( 'wpsight_madrid_bootstrap', true ) == true ) {

		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/vendor/twbs/bootstrap/css/bootstrap.css', false, '3.3.6', 'all'  );
		wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/vendor/twbs/bootstrap/js/bootstrap.js', array( 'jquery' ), '3.3.6', true );
		
		wp_enqueue_style( 'bootstrap-dropdownhover', get_template_directory_uri() . '/vendor/kybarg/bootstrap-dropdown-hover/css/bootstrap-dropdownhover' . $suffix . '.css', false, '1.0.0', 'all'  );
		wp_enqueue_script( 'bootstrap-dropdownhover', get_template_directory_uri() . '/vendor/kybarg/bootstrap-dropdown-hover/js/bootstrap-dropdownhover' . $suffix . '.js', array( 'jquery', 'bootstrap' ), '1.0.0', true );
		
		wp_enqueue_style( 'bootstrap-select', get_template_directory_uri() . '/vendor/silviomoreto/bootstrap-select/css/bootstrap-select' . $suffix . '.css', false, '1.10.0', 'all'  );
		wp_enqueue_script( 'bootstrap-select', get_template_directory_uri() . '/vendor/silviomoreto/bootstrap-select/js/bootstrap-select' . $suffix . '.js', array( 'jquery' ), '1.10.0', true );
		
		wp_enqueue_style( 'awesome-bootstrap-checkbox', get_template_directory_uri() . '/vendor/flatlogic/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox' . $suffix . '.css', false, '0.3.7', 'all'  );
	}
	
	// Enqueue Pushy menu if desired
	
	if( apply_filters( 'wpsight_madrid_pushy', true ) == true ) {		
		wp_enqueue_style( 'pushy', get_template_directory_uri() . '/assets/css/pushy.css', false, WPSIGHT_MADRID_VERSION, 'all'  );
		wp_enqueue_script( 'pushy', get_template_directory_uri() . '/vendor/christophery/pushy/js/pushy.min.js', array( 'jquery' ), '0.9.2', true );		
	}
	
	// Enqueue lightSlider if desired
	
	if( apply_filters( 'wpsight_madrid_lightslider', true ) == true ) {		
		wp_enqueue_style( 'lightslider', get_template_directory_uri() . '/vendor/sachinchoolur/lightslider/css/lightslider' . $suffix . '.css', false, '1.1.5', 'all'  );
		wp_enqueue_script( 'lightslider', get_template_directory_uri() . '/vendor/sachinchoolur/lightslider/js/lightslider' . $suffix . '.js', array( 'jquery' ), '1.1.5', true );		
	}
	
	// Enqueue lightGallery if desired
	
	if( apply_filters( 'wpsight_madrid_lightgallery', true ) == true ) {		
		wp_enqueue_style( 'lightgallery', get_template_directory_uri() . '/vendor/sachinchoolur/lightgallery/css/lightgallery' . $suffix . '.css', false, '1.2.15', 'all'  );
		wp_enqueue_script( 'lightgallery', get_template_directory_uri() . '/vendor/sachinchoolur/lightgallery/js/lightgallery' . $suffix . '.js', array( 'jquery' ), '1.2.15', true );
		wp_enqueue_script( 'lightgallery-thumbnail', get_template_directory_uri() . '/vendor/sachinchoolur/lightgallery/js/lg-thumbnail' . $suffix . '.js', array( 'jquery', 'lightgallery' ), '1.2.15', true );
		wp_enqueue_script( 'lightgallery-fullscreen', get_template_directory_uri() . '/vendor/sachinchoolur/lightgallery/js/lg-fullscreen' . $suffix . '.js', array( 'jquery', 'lightgallery' ), '1.2.15', true );
		wp_enqueue_script( 'lightgallery-zoom', get_template_directory_uri() . '/vendor/sachinchoolur/lightgallery/js/lg-zoom' . $suffix . '.js', array( 'jquery', 'lightgallery' ), '1.2.15', true );
		wp_enqueue_script( 'lightgallery-autoplay', get_template_directory_uri() . '/vendor/sachinchoolur/lightgallery/js/lg-autoplay' . $suffix . '.js', array( 'jquery', 'lightgallery' ), '1.2.15', true );
	}
	
	// Enqueue Match Height if desired
	
	if( apply_filters( 'wpsight_madrid_equal_height', true ) == true ) {
		$other_suffix = SCRIPT_DEBUG ? '' : '-min';
		wp_enqueue_script( 'jquery-match-height', get_template_directory_uri() . '/vendor/liabru/jquery-match-height/jquery.matchHeight' . $other_suffix . '.js', array( 'jquery' ), '0.7.0', true );
	}
	
	// Enqueue Smooth Scroll if desired
	
	if( apply_filters( 'wpsight_madrid_smooth_scroll', true ) == true )
		wp_enqueue_script( 'jquery-smooth-scroll', get_template_directory_uri() . '/assets/js/jquery.smooth-scroll.min.js', array( 'jquery' ), '1.5.6', true );
	
	// Enqueue animate.css
	wp_enqueue_style( 'animate', get_template_directory_uri() . '/assets/css/animate' . $suffix . '.css', false, WPSIGHT_MADRID_VERSION, 'all'  );
	
	// Load Dashicons on the front end
	wp_enqueue_style( 'dashicons' );
	
	// Enqueue Google Fonts if desired
	
	if( apply_filters( 'wpsight_madrid_google_fonts', true ) == true ) {
		
		$query_args = array(
			'family' => 'Oxygen',
			'subset' => 'latin'
		);

		wp_enqueue_style( 'google-fonts', add_query_arg( $query_args, "//fonts.googleapis.com/css" ), array(), null );
	
	}
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

}

/**
 * Implement theme setup.
 */
require get_template_directory() . '/includes/theme-setup.php';

/**
 * Implement custom menus.
 */
require get_template_directory() . '/includes/custom-menus.php';

/**
 * Implement custom widget areas.
 */
require get_template_directory() . '/includes/widget-areas.php';

/**
 * Implement custom image sizes.
 */
require get_template_directory() . '/includes/image-sizes.php';

/**
 * Implement custom widgets.
 */
require get_template_directory() . '/includes/widgets.php';

/**
 * Implement template tags.
 */
require get_template_directory() . '/includes/template-tags.php';

/**
 * Implement image gallery.
 */
require get_template_directory() . '/includes/image-gallery.php';

/**
 * Implement image slider.
 */
require get_template_directory() . '/includes/image-slider.php';

/**
 * Implement image background slider.
 */
require get_template_directory() . '/includes/image-background-slider.php';

/**
 * Implement listings slider.
 */
require get_template_directory() . '/includes/listings-slider.php';

/**
 * Implement listings carousel.
 */
require get_template_directory() . '/includes/listings-carousel.php';

/**
 * Implement meta boxes.
 */
require get_template_directory() . '/includes/meta-boxes.php';

/**
 * Implement feature box.
 */
require get_template_directory() . '/includes/feature-box.php';

/**
 * Implement icon box.
 */
require get_template_directory() . '/includes/icon-box.php';

/**
 * Implement call to action.
 */
require get_template_directory() . '/includes/call-to-action.php';

/**
 * Implement section title.
 */
require get_template_directory() . '/includes/section-title.php';

/**
 * Implement service.
 */
require get_template_directory() . '/includes/service.php';

/**
 * Implement newseltter box.
 */
require get_template_directory() . '/includes/newsletter-box.php';

/**
 * Implement theme customizer.
 */
require get_template_directory() . '/includes/theme-customizer.php';

/**
 * Implement add-on support (filters etc.).
 */
require get_template_directory() . '/includes/addon-support.php';

/**
 * Implement one-click demo import.
 */
require get_template_directory() . '/includes/class-wpsight-demo-import.php';

/**
 * Load theme updater functions.
 */
add_action( 'after_setup_theme', 'wpsight_madrid_theme_updater' );

function wpsight_madrid_theme_updater() {
	require( get_template_directory() . '/includes/updater/theme-updater.php' );
}

/**
 * Setup CMB2 Conditionals.
 */
require get_template_directory() . '/vendor/jcchavezs/cmb2-conditionals/cmb2-conditionals.php';
