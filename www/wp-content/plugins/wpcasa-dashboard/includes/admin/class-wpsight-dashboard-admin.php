<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Dashboard_Admin class
 */
class WPSight_Dashboard_Admin {

	/**
	 * Initialize class
	 */
	public static function init() {
		
		// Enqueue scripts
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ), 20 );

		// Add add-on options to general plugin settings
		add_filter( 'wpsight_options', array( __CLASS__, 'dashboard_options' ) );
		
		// Add JS to dashboard settings page
		add_action( 'wpsight_settings_scripts', array( __CLASS__, 'dashboard_options_js' ) );
		
		// Create dashboard pages
		add_action( 'admin_init', array( __CLASS__, 'create_pages' ) );
		
		// Add addon license to licenses page
		add_filter( 'wpsight_licenses', array( __CLASS__, 'license' ) );
		
		// Add plugin updater
		add_action( 'admin_init', array( __CLASS__, 'update' ), 0 );

	}
	
	/**
	 *	admin_enqueue_scripts()
	 *	
	 *	Enqueue scripts and styles used
	 *	on WordPress admin pages.
	 *	
	 *	@access	public
	 *	@uses	get_current_screen()
	 *	@uses	wp_enqueue_style()
	 *	@uses	wp_register_script()
	 *	@uses	wp_enqueue_script()
	 *	
	 *	@since 1.0.0
	 */
	public static function admin_enqueue_scripts() {
		
		// Script debugging?
		$suffix = SCRIPT_DEBUG ? '' : '.min';

		$screen		= get_current_screen();
		$post_type	= 'package';

		if ( in_array( $screen->id, array( 'edit-' . $post_type, $post_type ) ) )
			wp_enqueue_style( 'wpsight-meta-boxes', WPSIGHT_PLUGIN_URL . '/assets/css/wpsight-meta-boxes' . $suffix . '.css' );
		
		// Enqueue CMB2 Conditionals if desired
	
		if( apply_filters( 'wpsight_dashboard_cmb2_conditionals', true ) )
			wp_enqueue_script( 'cmb2-conditionals', WPSIGHT_PLUGIN_URL . '/vendor/jcchavezs/cmb2-conditionals/cmb2-conditionals.js', false, '1.0.4', true );

	}

	/**
	 *	dashboard_options()
	 *
	 *	Add add-on options tab to
	 *	general plugin settings.
	 *
	 *	@param	array
	 *	@uses	is_admin()
	 *	@uses	get_editable_roles()
	 *	@uses	get_option()
	 *	@uses	WPSight_Dashboard_Submission::submission_types()
	 *	@uses	WPSight_Dashboard_Packages::get_packages_choices()
	 *	@uses	WPSight_Dashboard_Payments::get_payment_gateways_choices()
	 *	@return array
	 *
	 *	@since 1.0.0
	 */
	public static function dashboard_options( $options ) {

		// Prepare roles option

		$roles = is_admin() ? get_editable_roles() : array();
		$dashboard_roles = array();

		foreach ( $roles as $key => $role ) {
			if ( 'administrator' == $key )
				continue;
			$dashboard_roles[ $key ] = $role['name'];
		}

		$options_dashboard = array(
		
			'dashboard_pages_assign' => array(
				'name'		=> __( 'Pages', 'wpcasa-dashboard' ),
				'cb_label'	=> __( 'Activate to set dashboard pages', 'wpcasa-dashboard' ),
				'id'		=> 'dashboard_pages_assign',
				'type'		=> 'checkbox'
			),
			'dashboard_pages_create' => array(
				'name'		=> __( 'Create Pages', 'wpcasa-dashboard' ),
				'cb_label'	=> __( 'Activate to automatically create and assign dashboard pages.', 'wpcasa-dashboard' ),
				'desc' 		=> __( '<span style="color:red">Please handle with care!</span> You can change the pages to your needs after creating them. Only unassigned pages will be created.', 'wpcasa-dashboard' ),
				'id'		=> 'dashboard_pages_create',
				'type'		=> 'checkbox',
				'class'		=> 'hidden'
			),
			'dashboard_page' => array(
				'name' 		=> __( 'Dashboard Page', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please select the page that contains the dashboard shortcode.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_page',
				'type' 		=> 'pages',
				'class'		=> 'hidden'
			),
			'dashboard_submit' => array(
				'name' 		=> __( 'Submit Page', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please select the page that contains the dashboard submit shortcode.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_submit',
				'type' 		=> 'pages',
				'class'		=> 'hidden'
			),
			'dashboard_edit' => array(
				'name' 		=> __( 'Edit Page', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please select another page that contains the dashboard submit shortcode.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_edit',
				'type' 		=> 'pages',
				'class'		=> 'hidden'
			),
			'dashboard_remove' => array(
				'name' 		=> __( 'Remove Page', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please select the page that contains the dashboard remove shortcode.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_remove',
				'type' 		=> 'pages',
				'class'		=> 'hidden'
			),
			'dashboard_register' => array(
				'name' 		=> __( 'Registration Page', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please select the page that contains the dashboard registration shortcode.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_register',
				'type' 		=> 'pages',
				'class'		=> 'hidden'
			),
			'dashboard_register_after' => array(
				'name' 		=> __( 'After Registration Page', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please select the page a user should be redirected to after registration.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_register_after',
				'type' 		=> 'pages',
				'class'		=> 'hidden'
			),
			'dashboard_login' => array(
				'name' 		=> __( 'Login Page', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please select the page that contains the dashboard login shortcode.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_login',
				'type' 		=> 'pages',
				'class'		=> 'hidden'
			),
			'dashboard_logout' => array(
				'name' 		=> __( 'Logout Page', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please select the page that contains the dashboard logout shortcode.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_logout',
				'type' 		=> 'pages',
				'class'		=> 'hidden'
			),
			'dashboard_profile' => array(
				'name' 		=> __( 'Profile Page', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please select the page that contains the dashboard profile shortcode.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_profile',
				'type' 		=> 'pages',
				'class'		=> 'hidden'
			),
			'dashboard_payment' => array(
				'name' 		=> __( 'Payment Page', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please select the page that contains the payment shortcode.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_payment',
				'type' 		=> 'pages',
				'class'		=> 'hidden'
			),
			'dashboard_package' => array(
				'name' 		=> __( 'Package Page', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please select the page that contains the package shortcode.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_package',
				'type' 		=> 'pages',
				'class'		=> 'hidden'
			),
			'dashboard_transactions' => array(
				'name' 		=> __( 'Transactions Page', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please select the page that contains the transactions shortcode.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_transactions',
				'type' 		=> 'pages',
				'class'		=> 'hidden'
			),
			'dashboard_terms' => array(
				'name' 		=> __( 'Terms Page', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please select a page with terms and conditions. If selected, users must agree before they can register or make a payment.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_terms',
				'type' 		=> 'pages',
				'class'		=> 'hidden'
			),
			'dashboard_role' => array(
				'name'		=> __( 'Account Role', 'wpcasa-dashboard' ),
				'desc'		=> __( 'Select a role for users that register via the dashboard form.', 'wpcasa-dashboard' ),
				'id'		=> 'dashboard_role',
				'default'	=> 'listing_agent',
				'type'		=> 'select',
				'options'	=> $dashboard_roles
			),
			'dashboard_approval' => array(
				'name'		=> __( 'Approval Required', 'wpcasa-dashboard' ),
				'cb_label'	=> __( 'Listing submissions require approval', 'wpcasa-dashboard' ),
				'desc'		=> __( 'New listings need to be approved by an admin or listing admin.', 'wpcasa-dashboard' ),
				'id'		=> 'dashboard_approval',
				'default'	=> '1',
				'type'		=> 'checkbox'
			),
			'dashboard_edit_active' => array(
				'name'		=> __( 'Edit Active', 'wpcasa-dashboard' ),
				'cb_label'	=> __( 'Allow users to edit active listings', 'wpcasa-dashboard' ),
				'desc'		=> __( 'Keep in mind that admins and listing admins can always edit published listings.', 'wpcasa-dashboard' ),
				'id'		=> 'dashboard_edit_active',
				'type'		=> 'checkbox'
			),
			'dashboard_listing_id' => array(
				'name'		=> __( 'Listing ID', 'wpcasa-dashboard' ),
				'cb_label'	=> __( 'Allow users to set and edit the listing ID', 'wpcasa-dashboard' ),
				'desc'		=> __( 'Keep in mind that admins and listing admins can always edit listing IDs.', 'wpcasa-dashboard' ),
				'id'		=> 'dashboard_listing_id',
				'type'		=> 'checkbox'
			),
			'dashboard_payment_options' => array(
				'name' 		=> __( 'Payment Options', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please select a payment option.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_payment_options',
				'type'		=> 'select',
				'options'	=> WPSight_Dashboard_Submission::submission_types(),
				'default'	=> 'free'
			),
			'dashboard_default_package' => array(
				'name' 		=> __( 'Default Package', 'wpcasa-dashboard' ),
				'desc' 		=> sprintf( __( 'Please select a default package. You can manage packages <a href="%s">here</a>.', 'wpcasa-dashboard' ), admin_url( 'edit.php?post_type=package' ) ),
				'id' 		=> 'dashboard_default_package',
				'type'		=> 'select',
				'options'	=> WPSight_Dashboard_Packages::get_packages_choices( true, true, true )
			),
			'dashboard_paypal' => array(
				'name'		=> __( 'PayPal', 'wpcasa-dashboard' ),
				'cb_label'	=> __( 'Activate to set PayPal payment gateway', 'wpcasa-dashboard' ),
				'id'		=> 'dashboard_paypal',
				'type'		=> 'checkbox'
			),
			'dashboard_paypal_id' => array(
				'name' 		=> __( 'PayPal Client ID', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please enter your PayPal client ID.', 'wpcasa-dashboard' ) . ' ' . __( 'More information at <a href="https://developer.paypal.com/docs/integration/admin/manage-apps/" target="_blank">PayPal Developer</a>.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_paypal_id',
				'type'		=> 'text',
				'class'		=> 'hidden'
			),
			'dashboard_paypal_secret' => array(
				'name' 		=> __( 'PayPal Secret Key', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please enter your PayPal secret key.', 'wpcasa-dashboard' ) . ' ' . __( 'More information at <a href="https://developer.paypal.com/docs/integration/admin/manage-apps/" target="_blank">PayPal Developer</a>.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_paypal_secret',
				'type'		=> 'text',
				'class'		=> 'hidden'
			),
			'dashboard_paypal_live' => array(
				'name'		=> __( 'PayPal Live Mode', 'wpcasa-dashboard' ),
				'cb_label'	=> __( 'Activate PayPal live mode', 'wpcasa-dashboard' ),
				'desc'		=> __( 'If not activated, PayPal is used in sandbox mode.', 'wpcasa-dashboard' ),
				'id'		=> 'dashboard_paypal_live',
				'type'		=> 'checkbox',
				'class'		=> 'hidden'
			),
			'dashboard_paypal_cc' => array(
				'name'		=> __( 'PayPal Credit Card', 'wpcasa-dashboard' ),
				'cb_label'	=> __( 'Activate PayPal credit cards', 'wpcasa-dashboard' ),
				'desc'		=> __( 'Allow payments with credit cards through PayPal. Please note that the direct credit card API is <a href="https://developer.paypal.com/docs/integration/direct/rest-api-payment-country-currency-support/" target="_blank">not available everywhere</a>.', 'wpcasa-dashboard' ),
				'id'		=> 'dashboard_paypal_cc',
				'type'		=> 'checkbox',
				'class'		=> 'hidden'
			),
			'dashboard_stripe' => array(
				'name'		=> __( 'Stripe', 'wpcasa-dashboard' ),
				'cb_label'	=> __( 'Activate to set Stripe payment gateway', 'wpcasa-dashboard' ),
				'id'		=> 'dashboard_stripe',
				'type'		=> 'checkbox'
			),
			'dashboard_stripe_secret' => array(
				'name' 		=> __( 'Stripe Secret Key (live)', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please enter your Stripe live secret key.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_stripe_secret',
				'type'		=> 'text',
				'class'		=> 'hidden'
			),
			'dashboard_stripe_publishable' => array(
				'name' 		=> __( 'Stripe Publishable Key (live)', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please enter your Stripe live publishable key.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_stripe_publishable',
				'type'		=> 'text',
				'class'		=> 'hidden'
			),
			'dashboard_stripe_secret_test' => array(
				'name' 		=> __( 'Stripe Secret Key (test)', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please enter your Stripe test secret key.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_stripe_secret_test',
				'type'		=> 'text',
				'class'		=> 'hidden'
			),
			'dashboard_stripe_publishable_test' => array(
				'name' 		=> __( 'Stripe Publishable Key (test)', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please enter your Stripe test publishable key.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_stripe_publishable_test',
				'type'		=> 'text',
				'class'		=> 'hidden'
			),
			'dashboard_stripe_live' => array(
				'name'		=> __( 'Stripe Live Mode', 'wpcasa-dashboard' ),
				'cb_label'	=> __( 'Activate Stripe live mode', 'wpcasa-dashboard' ),
				'desc'		=> __( 'If not activated, Stripe is used in test mode.', 'wpcasa-dashboard' ),
				'id'		=> 'dashboard_stripe_live',
				'type'		=> 'checkbox',
				'class'		=> 'hidden'
			),
			'dashboard_default_gateway' => array(
				'name' 		=> __( 'Default Payment Gateway', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please select a default payment gateway. Make sure it is activated above.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_default_gateway',
				'type'		=> 'select',
				'options'	=> WPSight_Dashboard_Payments::get_payment_gateways_choices( true )
			),
			'dashboard_recaptcha' => array(
				'name'		=> __( 'reCAPTCHA', 'wpcasa-dashboard' ),
				'cb_label'	=> __( 'Activate to set reCAPTCHA keys', 'wpcasa-dashboard' ),
				'id'		=> 'dashboard_recaptcha',
				'type'		=> 'checkbox'
			),
			'dashboard_recaptcha_key' => array(
				'name' 		=> __( 'reCAPTCHA Site Key', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please enter your reCAPTCHA site key.', 'wpcasa-dashboard' ) . ' ' . __( 'You can <a href="https://www.google.com/recaptcha/" target="_blank">get it here</a>.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_recaptcha_key',
				'type'		=> 'text',
				'class'		=> 'hidden'
			),
			'dashboard_recaptcha_key_secret' => array(
				'name' 		=> __( 'reCAPTCHA Secret Key', 'wpcasa-dashboard' ),
				'desc' 		=> __( 'Please enter your reCAPTCHA secret key.', 'wpcasa-dashboard' ) . ' ' . __( 'You can <a href="https://www.google.com/recaptcha/" target="_blank">get it here</a>.', 'wpcasa-dashboard' ),
				'id' 		=> 'dashboard_recaptcha_key_secret',
				'type'		=> 'text',
				'class'		=> 'hidden'
			)

		);

		$options['dashboard'] = array(
			__( 'Dashboard', 'wpcasa-dashboard' ),
			apply_filters( 'wpsight_options_dashboard', $options_dashboard )
		);

		return $options;

	}
	
	/**
	 *	dashboard_options_js()
	 *	
	 *	We add some JS to the settings page to show
	 *	less options by default for better UX.
	 *	
	 *	@access	public
	 *	@param	string	$settings_name
	 *	
	 *	@since 1.1.0
	 */
	public static function dashboard_options_js( $settings_name ) { ?>
	
	<script type="text/javascript">

			var totoggle_pages = '.setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_pages_create-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_page-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_login-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_register-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_register_after-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_profile-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_logout-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_submit-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_edit-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_remove-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_terms-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_payment-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_package-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_transactions-tr';	
	
			jQuery('#setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_pages_assign').click(function() {
  				jQuery(totoggle_pages).fadeToggle(150);
			});
			
			if (jQuery('#setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_pages_assign:checked').val() !== undefined) {
				jQuery(totoggle_pages).show();
			}
			
			var totoggle_paypal = '.setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_paypal_id-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_paypal_secret-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_paypal_live-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_paypal_cc-tr';	
	
			jQuery('#setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_paypal').click(function() {
  				jQuery(totoggle_paypal).fadeToggle(150);
			});
			
			if (jQuery('#setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_paypal:checked').val() !== undefined) {
				jQuery(totoggle_paypal).show();
			}
			
			var totoggle_stripe = '.setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_stripe_secret-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_stripe_publishable-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_stripe_secret_test-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_stripe_publishable_test-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_stripe_live-tr';	
	
			jQuery('#setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_stripe').click(function() {
  				jQuery(totoggle_stripe).fadeToggle(150);
			});
			
			if (jQuery('#setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_stripe:checked').val() !== undefined) {
				jQuery(totoggle_stripe).show();
			}
			
			var totoggle_recaptcha = '.setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_recaptcha_key-tr, .setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_recaptcha_key_secret-tr';
			
			jQuery('#setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_recaptcha').click(function() {
  				jQuery(totoggle_recaptcha).fadeToggle(150);
			});
			
			if (jQuery('#setting-<?php echo esc_attr( $settings_name ); ?>_dashboard_recaptcha:checked').val() !== undefined) {
				jQuery(totoggle_recaptcha).show();
			}
			
		</script>		
		
	<?php

	}
	
	/**
	 *	create_pages()
	 *	
	 *	Automatically create and assign dashboard pages.
	 *	
	 *	@uses	wpsight_delete_option()
	 *	@uses	wpsight_get_option()
	 *	@uses	wpsight_add_option()
	 *	@uses	wp_insert_post()
	 *	
	 *	@since 1.1.0
	 */
	public static function create_pages() {
		
		$settings_name	= WPSIGHT_DOMAIN;
		
		$_post = isset( $_POST[ $settings_name ]['dashboard_pages_create'] ) && '1' == $_POST[ $settings_name ]['dashboard_pages_create'] ? true : false;
		
		// run the process only once per site and if desired
		if ( $_post ) {

			// Create dashboard page

			$dashboard_page_data = array(
				'post_title'		=> _x( 'Dashboard', 'dashboard page title', 'wpcasa-dashboard' ),
				'post_content'		=> '[wpsight_dashboard]',
				'post_type'			=> 'page',
				'post_status'	 	=> 'publish',
				'comment_status'	=> 'closed',
				'ping_status'		=> 'closed'
			);
			
			$dashboard_page = ! wpsight_get_option( 'dashboard_page' ) ? wp_insert_post( $dashboard_page_data ) : wpsight_get_option( 'dashboard_page' );
			
			// Create submission page

			$submit_page_data = array(
				'post_title'		=> _x( 'New Listing', 'dashboard page title', 'wpcasa-dashboard' ),
				'post_name'			=> 'new',
				'post_parent'		=> $dashboard_page,
				'post_content'		=> '[wpsight_dashboard_submit]',
				'post_type'			=> 'page',
				'post_status'	 	=> 'publish',
				'comment_status'	=> 'closed',
				'ping_status'		=> 'closed'
			);
			
			$submit_page = ! wpsight_get_option( 'dashboard_submit' ) ? wp_insert_post( $submit_page_data ) : wpsight_get_option( 'dashboard_submit' );
			
			// Create edit page

			$edit_page_data = array(
				'post_title'		=> _x( 'Edit Listing', 'dashboard page title', 'wpcasa-dashboard' ),
				'post_name'			=> 'edit',
				'post_parent'		=> $dashboard_page,
				'post_content'		=> '[wpsight_dashboard_submit]',
				'post_type'			=> 'page',
				'post_status'	 	=> 'publish',
				'comment_status'	=> 'closed',
				'ping_status'		=> 'closed'
			);
			
			$edit_page = ! wpsight_get_option( 'dashboard_edit' ) ? wp_insert_post( $edit_page_data ) : wpsight_get_option( 'dashboard_edit' );
			
			// Create remove page

			$remove_page_data = array(
				'post_title'		=> _x( 'Remove Listing', 'dashboard page title', 'wpcasa-dashboard' ),
				'post_name'			=> 'remove',
				'post_parent'		=> $dashboard_page,
				'post_content'		=> '[wpsight_dashboard_remove]',
				'post_type'			=> 'page',
				'post_status'	 	=> 'publish',
				'comment_status'	=> 'closed',
				'ping_status'		=> 'closed'
			);
			
			$remove_page = ! wpsight_get_option( 'dashboard_remove' ) ? wp_insert_post( $remove_page_data ) : wpsight_get_option( 'dashboard_remove' );
			
			// Create registration page

			$register_page_data = array(
				'post_title'		=> _x( 'Register', 'dashboard page title', 'wpcasa-dashboard' ),
				'post_parent'		=> $dashboard_page,
				'post_content'		=> '[wpsight_dashboard_register]',
				'post_type'			=> 'page',
				'post_status'	 	=> 'publish',
				'comment_status'	=> 'closed',
				'ping_status'		=> 'closed'
			);
			
			$register_page = ! wpsight_get_option( 'dashboard_register' ) ? wp_insert_post( $register_page_data ) : wpsight_get_option( 'dashboard_register' );
			
			// Create login page

			$login_page_data = array(
				'post_title'		=> _x( 'Login', 'dashboard page title', 'wpcasa-dashboard' ),
				'post_parent'		=> $dashboard_page,
				'post_content'		=> '[wpsight_dashboard_login]',
				'post_type'			=> 'page',
				'post_status'	 	=> 'publish',
				'comment_status'	=> 'closed',
				'ping_status'		=> 'closed'
			);
			
			$login_page = ! wpsight_get_option( 'dashboard_login' ) ? wp_insert_post( $login_page_data ) : wpsight_get_option( 'dashboard_login' );
			
			// Create logout page

			$logout_page_data = array(
				'post_title'		=> _x( 'Logout', 'dashboard page title', 'wpcasa-dashboard' ),
				'post_parent'		=> $dashboard_page,
				'post_content'		=> '[wpsight_dashboard_logout]',
				'post_type'			=> 'page',
				'post_status'	 	=> 'publish',
				'comment_status'	=> 'closed',
				'ping_status'		=> 'closed'
			);
			
			$logout_page = ! wpsight_get_option( 'dashboard_logout' ) ? wp_insert_post( $logout_page_data ) : wpsight_get_option( 'dashboard_logout' );
			
			// Create profile page

			$profile_page_data = array(
				'post_title'		=> _x( 'Profile', 'dashboard page title', 'wpcasa-dashboard' ),
				'post_parent'		=> $dashboard_page,
				'post_content'		=> '[wpsight_dashboard_profile]',
				'post_type'			=> 'page',
				'post_status'	 	=> 'publish',
				'comment_status'	=> 'closed',
				'ping_status'		=> 'closed'
			);
			
			$profile_page = ! wpsight_get_option( 'dashboard_profile' ) ? wp_insert_post( $profile_page_data ) : wpsight_get_option( 'dashboard_profile' );
			
			// Create password page

			$password_page_data = array(
				'post_title'		=> _x( 'Change Password', 'dashboard page title', 'wpcasa-dashboard' ),
				'post_parent'		=> $dashboard_page,
				'post_content'		=> '[wpsight_dashboard_password]',
				'post_type'			=> 'page',
				'post_status'	 	=> 'publish',
				'comment_status'	=> 'closed',
				'ping_status'		=> 'closed'
			);
			
			$password_page = ! wpsight_get_option( 'dashboard_password' ) ? wp_insert_post( $password_page_data ) : wpsight_get_option( 'dashboard_password' );
			
			// Create reset password page

			$password_reset_page_data = array(
				'post_title'		=> _x( 'Reset Password', 'dashboard page title', 'wpcasa-dashboard' ),
				'post_parent'		=> $dashboard_page,
				'post_content'		=> '[wpsight_dashboard_password_reset]',
				'post_type'			=> 'page',
				'post_status'	 	=> 'publish',
				'comment_status'	=> 'closed',
				'ping_status'		=> 'closed'
			);
			
			$password_reset_page = ! wpsight_get_option( 'dashboard_password_reset' ) ? wp_insert_post( $password_reset_page_data ) : wpsight_get_option( 'dashboard_password_reset' );
			
			// Create payment page

			$payment_page_data = array(
				'post_title'		=> _x( 'Payment', 'dashboard page title', 'wpcasa-dashboard' ),
				'post_parent'		=> $dashboard_page,
				'post_content'		=> '[wpsight_dashboard_payment]',
				'post_type'			=> 'page',
				'post_status'	 	=> 'publish',
				'comment_status'	=> 'closed',
				'ping_status'		=> 'closed'
			);
			
			$payment_page = ! wpsight_get_option( 'dashboard_payment' ) ? wp_insert_post( $payment_page_data ) : wpsight_get_option( 'dashboard_payment' );
			
			// Create transactions page

			$transactions_page_data = array(
				'post_title'		=> _x( 'Transactions', 'dashboard page title', 'wpcasa-dashboard' ),
				'post_parent'		=> $dashboard_page,
				'post_content'		=> '[wpsight_dashboard_transactions]',
				'post_type'			=> 'page',
				'post_status'	 	=> 'publish',
				'comment_status'	=> 'closed',
				'ping_status'		=> 'closed'
			);
			
			$transactions_page = ! wpsight_get_option( 'dashboard_transactions' ) ? wp_insert_post( $transactions_page_data ) : wpsight_get_option( 'dashboard_transactions' );
			
			// Create terms page

			$terms_page_data = array(
				'post_title'		=> _x( 'Terms & Conditions', 'dashboard page title', 'wpcasa-dashboard' ),
				'post_name'			=> 'terms',
				'post_parent'		=> $dashboard_page,
				'post_content'		=> __( 'Add your terms and conditions here.', 'wpcasa-dashboard' ),
				'post_type'			=> 'page',
				'post_status'	 	=> 'publish',
				'comment_status'	=> 'closed',
				'ping_status'		=> 'closed'
			);
			
			$terms_page = ! wpsight_get_option( 'dashboard_terms' ) ? wp_insert_post( $terms_page_data ) : wpsight_get_option( 'dashboard_terms' );
			
			// Create package page

			$package_page_data = array(
				'post_title'		=> _x( 'Package', 'dashboard page title', 'wpcasa-dashboard' ),
				'post_parent'		=> $dashboard_page,
				'post_content'		=> '[wpsight_dashboard_package]',
				'post_type'			=> 'page',
				'post_status'	 	=> 'publish',
				'comment_status'	=> 'closed',
				'ping_status'		=> 'closed'
			);
			
			$package_page = ! wpsight_get_option( 'dashboard_package' ) ? wp_insert_post( $package_page_data ) : wpsight_get_option( 'dashboard_package' );
			
			// Assign pages in options

			$options = array(
				'dashboard_page'			=> $dashboard_page,
				'dashboard_submit'			=> $submit_page,
				'dashboard_edit'			=> $edit_page,
				'dashboard_remove'			=> $remove_page,
				'dashboard_register'		=> $register_page,
				'dashboard_register_after'	=> $profile_page,
				'dashboard_login'			=> $login_page,
				'dashboard_logout'			=> $logout_page,
				'dashboard_profile'			=> $profile_page,
				'dashboard_payment'			=> $payment_page,
				'dashboard_package'			=> $package_page,
				'dashboard_transactions'	=> $transactions_page,
				'dashboard_terms'			=> $terms_page,
			);
			
			foreach( $options as $option => $value ) {
			
				if( wpsight_get_option( $option ) )
					continue;
			
				$_POST[ $settings_name ][ $option ] = $value;
				wpsight_add_option( $option, $value );
			
			}
			
			$_POST[ $settings_name ]['dashboard_pages_create'] = '';
			wpsight_delete_option( 'dashboard_pages_create' );

		}
	
	}
	
	/**
	 *	license()
	 *	
	 *	Add addon license to licenses page
	 *	
	 *	@param	array	$licenses
	 *	@uses	wpsight_underscores()
	 *	@return	array	$licenses
	 *	
	 *	@since 1.0.0
	 */
	public static function license( $licenses ) {
		
		$licenses['dashboard'] = array(
			'name' => WPSIGHT_DASHBOARD_NAME,
			'desc' => sprintf( __( 'For premium support and seamless updates for %s please activate your license.', 'wpcasa-dashboard' ), WPSIGHT_DASHBOARD_NAME ),
			'id'   => wpsight_underscores( WPSIGHT_DASHBOARD_DOMAIN )
		);
		
		return $licenses;
	
	}
	
	/**
	 *	update()
	 *	
	 *	Set up EDD plugin updater.
	 *	
	 *	@uses	class_exists()
	 *	@uses	get_option()
	 *	@uses	wpsight_underscores()
	 *	
	 *	@since 1.0.0
	 */
	public static function update() {
		
		if( ! class_exists( 'EDD_SL_Plugin_Updater' ) )
			return;

		// Get license option
		$licenses = get_option( 'wpsight_licenses' );		
		$key = wpsight_underscores( WPSIGHT_DASHBOARD_DOMAIN );
	
		// Setup the updater
		$edd_updater = new EDD_SL_Plugin_Updater( WPSIGHT_SHOP_URL, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/wpcasa-dashboard.php', array(
				'version' 	=> WPSIGHT_DASHBOARD_VERSION,
				'license' 	=> isset( $licenses[ $key ] ) ? trim( $licenses[ $key ] ) : false,
				'item_name' => WPSIGHT_DASHBOARD_NAME,
				'author' 	=> WPSIGHT_AUTHOR
			)
		);
	
	}

}

WPSight_Dashboard_Admin::init();
