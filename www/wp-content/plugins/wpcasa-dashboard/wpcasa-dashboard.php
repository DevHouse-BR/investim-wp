<?php
/*
Plugin Name: WPCasa Dashboard
Plugin URI: https://wpcasa.com/downloads/wpcasa-dashboard
Description: Let agents add and manage properties in a login area on the front end. Optionally charge users before they can submit listings.
Version: 1.1.0
Author: WPSight
Author URI: http://wpsight.com
Requires at least: 4.0
Tested up to: 4.5.3
Text Domain: wpcasa-dashboard
Domain Path: /languages
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Dashboard class
 */
class WPSight_Dashboard {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Define constants
		
		if ( ! defined( 'WPSIGHT_NAME' ) )
			define( 'WPSIGHT_NAME', 'WPCasa' );

		if ( ! defined( 'WPSIGHT_DOMAIN' ) )
			define( 'WPSIGHT_DOMAIN', 'wpcasa' );
		
		if ( ! defined( 'WPSIGHT_SHOP_URL' ) )
			define( 'WPSIGHT_SHOP_URL', 'https://wpcasa.com' );

		if ( ! defined( 'WPSIGHT_AUTHOR' ) )
			define( 'WPSIGHT_AUTHOR', 'WPSight' );

		define( 'WPSIGHT_DASHBOARD_NAME', 'WPCasa Dashboard' );
		define( 'WPSIGHT_DASHBOARD_DOMAIN', 'wpcasa-dashboard' );
		define( 'WPSIGHT_DASHBOARD_VERSION', '1.1.0' );
		define( 'WPSIGHT_DASHBOARD_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'WPSIGHT_DASHBOARD_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		
		// Includes classes
		
		include( WPSIGHT_DASHBOARD_PLUGIN_DIR . '/includes/class-wpsight-dashboard-general.php' );
		include( WPSIGHT_DASHBOARD_PLUGIN_DIR . '/includes/class-wpsight-dashboard-meta-boxes.php' );
		
		include( WPSIGHT_DASHBOARD_PLUGIN_DIR . '/includes/class-wpsight-dashboard-post-types.php' );
		include( WPSIGHT_DASHBOARD_PLUGIN_DIR . '/includes/class-wpsight-dashboard-price.php' );
		
		include( WPSIGHT_DASHBOARD_PLUGIN_DIR . '/includes/class-wpsight-dashboard-packages.php' );
		include( WPSIGHT_DASHBOARD_PLUGIN_DIR . '/includes/class-wpsight-dashboard-transactions.php' );
		include( WPSIGHT_DASHBOARD_PLUGIN_DIR . '/includes/class-wpsight-dashboard-submission.php' );
		include( WPSIGHT_DASHBOARD_PLUGIN_DIR . '/includes/class-wpsight-dashboard-billing.php' );
		include( WPSIGHT_DASHBOARD_PLUGIN_DIR . '/includes/class-wpsight-dashboard-shortcodes.php' );
		
		include( WPSIGHT_DASHBOARD_PLUGIN_DIR . '/includes/class-wpsight-dashboard-payments.php' );
		include( WPSIGHT_DASHBOARD_PLUGIN_DIR . '/includes/class-wpsight-dashboard-paypal.php' );
		include( WPSIGHT_DASHBOARD_PLUGIN_DIR . '/includes/class-wpsight-dashboard-stripe.php' );
		
		include( WPSIGHT_DASHBOARD_PLUGIN_DIR . '/includes/class-wpsight-dashboard-recaptcha.php' );
		
		// Include admin part
		
		if ( is_admin() )
			include( WPSIGHT_DASHBOARD_PLUGIN_DIR . '/includes/admin/class-wpsight-dashboard-admin.php' );

		// Actions
		
		add_action( 'init', array( __CLASS__, 'start_session' ), 1 );		
		add_action( 'init', array( __CLASS__, 'load_plugin_textdomain' ) );		
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'frontend_scripts' ) );
		add_action( 'wp_footer', array( __CLASS__, 'enqueue_footer' ) );
		
		// Display custom meta boxes on front end
		add_action( 'cmb2_init', array( __CLASS__, 'meta_boxes' ) );

	}

	/**
	 *	init()
	 *	
	 *	Initialize the plugin when WPCasa is loaded
	 *	
	 *	@param	object	$wpsight
	 *	@return	object	$wpsight->dashboard
	 *	
	 *	@since 1.0.0
	 */
	public static function init( $wpsight ) {
		
		if ( ! isset( $wpsight->dashboard ) )
			$wpsight->dashboard = new self();

		do_action_ref_array( 'wpsight_init_dashboard', array( &$wpsight ) );

		return $wpsight->dashboard;
	}

	/**
	 *	start_session()
	 *
	 *	@access	public
	 */
	public static function start_session() {
	    if( ! session_id() ) {
	        session_start();
	    }
	}

	/**
	 *	load_plugin_textdomain()
	 *	
	 *	Set up localization for this plugin
	 *	loading the text domain.
	 *	
	 *	@uses	load_plugin_textdomain()
	 *	
	 *	@since 1.0.0
	 */
	public static function load_plugin_textdomain() {
		load_plugin_textdomain( 'wpcasa-dashboard', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 *	frontend_scripts()
	 *	
	 *	Register and enqueue JS scripts and CSS styles.
	 *	Also localize some JS to use PHP constants.
	 *	
	 *	@uses	wp_enqueue_script()
	 *	@uses	wp_enqueue_style()
	 *	@uses	wp_register_script()
	 *	@uses	WPSight_Dashboard_Recaptcha::is_recaptcha_enabled()
	 *	
	 *	@since 1.0.0
	 */
	public static function frontend_scripts() {

		// Only on front end

		if( is_admin() )
			return;
		
		// Script debugging?
		$suffix = SCRIPT_DEBUG ? '' : '.min';

		if( true == apply_filters( 'wpsight_dashboard_css', true ) )
			wp_enqueue_style( 'wpsight-dashboard', WPSIGHT_DASHBOARD_PLUGIN_URL . '/assets/css/wpsight-dashboard' . $suffix . '.css', array( 'dashicons' ) );
		
		wp_enqueue_script( 'wpsight-dashboard', WPSIGHT_DASHBOARD_PLUGIN_URL . '/assets/js/wpsight-dashboard' . $suffix . '.js', array( 'jquery' ), WPSIGHT_DASHBOARD_VERSION, true );
		
		if ( WPSight_Dashboard_Recaptcha::is_recaptcha_enabled() ) {
			wp_register_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit', array( 'jquery' ), false, true );
			wp_enqueue_script( 'recaptcha' );
		}

	}
	
	/**
	 *	enqueue_footer()
	 *
	 *	Loads reCAPTCHA javascript into footer.
	 *
	 *	@access	public
	 *	@uses	WPSight_Dashboard_Recaptcha::is_recaptcha_enabled()
	 *
	 *	@since	1.1.0
	 */
	public static function enqueue_footer() {
		if ( WPSight_Dashboard_Recaptcha::is_recaptcha_enabled() && ! is_admin() ) {
			?>
            <script type="text/javascript">
                var onloadCallback = function() {
                    jQuery('.g-recaptcha').each(function() {

                        var id = jQuery(this).attr('id');
                        var sitekey = jQuery(this).data('sitekey');
                        
                        grecaptcha.render(id, {
        				  'sitekey' : sitekey
        				});
                    });
                };
            </script>
        <?php
		}
	}
	
	/**
	 *	admin_meta_boxes()
	 *	
	 *	Hook custom meta boxes into cmb2_init to make
	 *	sure they can be displayed on front end. By
	 *	default they are activated on cmb2_admin_init.
	 *	
	 *	@access	public
	 *	@uses	wpsight_meta_boxes()
	 *	@uses	new_cmb2_box()
	 *	@uses	$cmb->add_field()
	 *	@uses	$cmb->add_group_field()
	 *	@see	/functions/wpsight-meta-boxes.php
	 *	
	 *	@since	1.1.0
	 */
	public static function meta_boxes() {

		$meta_boxes = wpsight_meta_boxes();

		foreach ( $meta_boxes as $metabox ) {
			if( $metabox ) {				
				$cmb = new_cmb2_box( $metabox );
		    	foreach ( $metabox['fields'] as $field ) {
		    		if ( 'group' == $field['type'] ) {
			    		$group_field_id = $cmb->add_field( $field );
		    			foreach ( $field['group_fields'] as $group_field ) {
		    				$cmb->add_group_field( $group_field_id, $group_field );
		    			}
		    		} else {
			    		$cmb->add_field( $field );
		    		}
		    	}
		    }
		}	

	}

	/**
	 *	activation()
	 *	
	 *	Callback for register_activation_hook to create some
	 *	default options to be used by this plugin.
	 *	
	 *	@uses	wpsight_get_option()
	 *	@uses	wpsight_add_option()
	 *	
	 *	@since 1.0.0
	 */
	public static function activation() {

		// Add some default options

		$options = array(
			'dashboard_role'			=> 'listing_agent',
			'dashboard_approval'		=> '1',
			'dashboard_payment_options'	=> 'free',
		);

		foreach( $options as $option => $value ) {

			if( wpsight_get_option( $option ) )
				continue;

			wpsight_add_option( $option, $value );

		}

	}

}

// Activation Hook
register_activation_hook( __FILE__, array( 'WPSight_Dashboard', 'activation' ) );

// Initialize plugin on wpsight_init
add_action( 'wpsight_init', array( 'WPSight_Dashboard', 'init' ) );
