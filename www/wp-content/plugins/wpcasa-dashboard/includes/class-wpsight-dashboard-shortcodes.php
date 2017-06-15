<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Class WPSight_Dashboard_Shortcodes
 */
class WPSight_Dashboard_Shortcodes {

	/**
	 *	Initialize class
	 */
	public static function init() {

		add_action( 'wp', array( __CLASS__, 'check_logout' ) );
		
		add_shortcode( 'wpsight_dashboard', array( __CLASS__, 'submission_list' ) );
		add_shortcode( 'wpsight_dashboard_submit', array( __CLASS__, 'submission' ) );
		add_shortcode( 'wpsight_dashboard_remove', array( __CLASS__, 'submission_remove' ) );
		
		add_shortcode( 'wpsight_dashboard_register', array( __CLASS__, 'register' ) );
		add_shortcode( 'wpsight_dashboard_login', array( __CLASS__, 'login' ) );
		add_shortcode( 'wpsight_dashboard_logout', array( __CLASS__, 'logout' ) );
		add_shortcode( 'wpsight_dashboard_profile', array( __CLASS__, 'profile' ) );
				
		add_shortcode( 'wpsight_dashboard_password', array( __CLASS__, 'password' ) );
		add_shortcode( 'wpsight_dashboard_password_reset', array( __CLASS__, 'password_reset' ) );

		add_shortcode( 'wpsight_dashboard_payment', array( __CLASS__, 'payment' ) );
		add_shortcode( 'wpsight_dashboard_transactions', array( __CLASS__, 'transactions' ) );
		add_shortcode( 'wpsight_dashboard_package', array( __CLASS__, 'package_info' ) );

	}

	/**
	 *	check_logout()
	 *
	 *	Check if we are on logout page and
	 *	log user out, then redirect to home page.
	 *	
	 *	@access	public
	 *	@param	$wp
	 *	@return	void
	 *
	 *	@since	1.1.0
	 */
	public static function check_logout( $wp ) {
		$post = get_post();

		if ( is_object( $post ) ) {
			if ( strpos( $post->post_content, '[wpsight_dashboard_logout]' ) !== false ) {
				if( is_user_logged_in() )
					$_SESSION['messages'][] = array( 'success', __( 'You have been successfully logged out.', 'wpcasa-dashboard' ) );
				wp_redirect( html_entity_decode( wp_logout_url( home_url( '/' ) ) ) );
				exit();
			}
		}
	}

	/**
	 *	logout()
	 *
	 *	Logout shortcode. We don't need any functionality
	 *	here as we log out and redirect the user when he
	 *	visits this page including the logout shortcode.
	 *	
	 *	@access	public
	 *	@param	array	$atts
	 *	@return	void
	 *
	 *	@since	1.1.0
	 */
	public static function logout( $atts = array() ) {}

	/**
	 *	login()
	 *
	 *	Get the template for the login shortcode.
	 *	
	 *	@access	public
	 *	@param	array	$atts
	 *	@uses	wpsight_get_template()
	 *	@return	mixed
	 *
	 *	@since	1.1.0
	 */
	public static function login( $atts = array() ) {
		wpsight_get_template( 'account-login.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
	}

	/**
	 *	password_reset()
	 *
	 *	Get the template for the password_reset shortcode.
	 *	
	 *	@access	public
	 *	@param	array	$atts
	 *	@uses	wpsight_get_template()
	 *	@return	mixed
	 *
	 *	@since	1.1.0
	 */
	public static function password_reset( $atts = array() ) {
		wpsight_get_template( 'password-reset.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
	}

	/**
	 *	register()
	 *
	 *	Get the template for the register shortcode.
	 *	
	 *	@access	public
	 *	@param	array	$atts
	 *	@uses	wpsight_get_template()
	 *	@return	mixed
	 *
	 *	@since	1.1.0
	 */
	public static function register( $atts = array() ) {
		wpsight_get_template( 'account-register.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
	}

	/**
	 *	submission()
	 *
	 *	Process the submission shortcode and
	 *	get the right template depending on user rights etc.
	 *	
	 *	@access	public
	 *	@param	array	$atts
	 *	@uses	is_user_logged_in()
	 *	@uses	WPSight_Dashboard_Submission::is_user_allowed_to_add_submission()
	 *	@uses	WPSight_Dashboard_Submission::is_user_allowed_to_edit_submission()
	 *	@uses	wpsight_get_template()
	 *	@uses	wpsight_post_type()
	 *	@uses	WPSight_Dashboard_Submission::get_submission_steps()
	 *	@uses	cmb2_get_metabox()
	 *	@return	mixed
	 *
	 *	@since	1.1.0
	 */
	public static function submission( $atts = array() ) {
		if ( ! is_user_logged_in() ) {
			wpsight_get_template( 'access-no.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
			return;
		}

        $object_id = ! empty( $_GET['id'] ) ? $_GET['id'] : false;
        if ( empty( $post_id ) && ! empty( $_POST['object_id'] ) ) {
            $object_id = $_POST['object_id'];
        }

        if ( empty( $object_id ) && ! WPSight_Dashboard_Submission::is_user_allowed_to_add_submission( get_current_user_id() ) ) {
            wpsight_get_template( 'access-no.php' , array(
                'message' => __( 'Please check your package.', 'wpcasa-dashboard' )
            ), WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
            return;
        }
        
        if( ! empty( $object_id ) && ! WPSight_Dashboard_Submission::is_user_allowed_to_edit_submission( get_current_user_id(), $object_id ) ) {
	        wpsight_get_template( 'access-no.php' , array(
                'message' => __( 'This listing cannot be edited at the moment.', 'wpcasa-dashboard' )
            ), WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
            return;
        }

        // if object_id is empty, user wants to submit new post
        if ( empty( $object_id ) ) {
            $object_id = 'fake-id';
        }

		$steps = WPSight_Dashboard_Submission::get_submission_steps( wpsight_post_type() );

		$current_step = ! empty( $_GET['step'] ) ? $_GET['step'] : $steps[0]['id'];
		
		wpsight_get_template( 'submission-steps.php', array(
			'steps'         => $steps,
			'post_type'     => wpsight_post_type(),
			'current_step'  => $current_step,
		), WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
		
		$meta_box = cmb2_get_metabox( $current_step, $object_id );
		$title = wpsight_get_template( 'submission-step-title.php', array(
			'steps'         => $steps,
			'current_step'  => $current_step,
		), WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
		
		$next_step = WPSight_Dashboard_Submission::get_next_step( wpsight_post_type(), $current_step );

		$save_button = empty( $_GET['id'] ) && false !== $next_step ? __( 'Next step', 'wpcasa-dashboard' ) : __( 'Submit listing', 'wpcasa-dashboard' );
		$save_button = ! empty( $_GET['id'] ) ? __( 'Update listing', 'wpcasa-dashboard' ) : $save_button;
		
		$action = empty( $_GET['id'] ) ? '' : '&action=save';

		return cmb2_get_metabox_form( $meta_box, $object_id, array(
			'form_format' => '<form action="//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $action . '" class="cmb-form wpsight-dashboard-form" method="post" id="%1$s" enctype="multipart/form-data" encoding="multipart/form-data"> ' . $title . '<input type="hidden" name="object_id" value="%2$s">%3$s<input type="submit" name="submit-submission" value="%4$s" class="button"></form>',
			'save_button' => $save_button,
		) );
	}

	/**
	 *	submission_remove()
	 *
	 *	Get the template for the remove listing shortcode.
	 *	
	 *	@access	public
	 *	@param	array	$atts
	 *	@uses	wpsight_get_template()
	 *	@uses	get_current_user_id()
	 *	@uses	WPSight_Dashboard_Submission::is_user_allowed_to_remove_submission()
	 *	@uses	WPSight_Listings::get_listing()
	 *	@return	mixed
	 *
	 *	@since	1.1.0
	 */
	public static function submission_remove( $atts = array() ) {

		if ( ! is_user_logged_in() ) {
			wpsight_get_template( 'access-no.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
			return;
		}
		
		$object_id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : false;

		$is_allowed = WPSight_Dashboard_Submission::is_user_allowed_to_remove_submission( get_current_user_id(), $object_id );

		if ( $object_id && ! $is_allowed ) {
			wpsight_get_template( 'access-no.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
			return;
		}

		$atts = array(
			'listing' => WPSight_Listings::get_listing( $object_id )
		);

		wpsight_get_template( 'submission-form-remove.php', $atts, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );

	}

	/**
	 *	submission_list()
	 *
	 *	Get the template for the general dashboard shortcode
	 *	showing the listings list ist is i.
	 *	
	 *	@access	public
	 *	@param	array	$atts
	 *	@uses	wpsight_get_template()
	 *	@uses	is_user_logged_in()
	 *	@return	mixed
	 *
	 *	@since	1.1.0
	 */
	public static function submission_list( $atts = array() ) {

        if ( ! is_user_logged_in() ) {
            wpsight_get_template( 'access-no.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
            return;
		}

        wpsight_get_template( 'submission-list.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );

	}

	/**
	 *	payment()
	 *
	 *	Get the template for the payment shortcode.
	 *	
	 *	@access	public
	 *	@param	array	$atts
	 *	@uses	wpsight_get_template()
	 *	@uses	is_user_logged_in()
	 *	@return	mixed
	 *
	 *	@since	1.1.0
	 */
	public static function payment( $atts = array() ) {

		if ( ! is_user_logged_in() ) {
			wpsight_get_template( 'access-no.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
			return;
		}

		wpsight_get_template( 'payment-form.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );

	}

	/**
	 *	transactions()
	 *
	 *	Get the template for the transactions shortcode.
	 *	
	 *	@access	public
	 *	@param	array	$atts
	 *	@uses	wpsight_get_template()
	 *	@uses	is_user_logged_in()
	 *	@return	mixed
	 *
	 *	@since	1.1.0
	 */
	public static function transactions( $atts = array() ) {

		if ( ! is_user_logged_in() ) {
			wpsight_get_template( 'access-no.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
			return;
		}

		wpsight_get_template( 'payment-transactions.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );

	}

	/**
	 *	password()
	 *
	 *	Get the template for the change password shortcode.
	 *	
	 *	@access	public
	 *	@param	array	$atts
	 *	@uses	wpsight_get_template()
	 *	@uses	is_user_logged_in()
	 *	@return	mixed
	 *
	 *	@since	1.1.0
	 */
	public static function password( $atts = array() ) {

		if ( ! is_user_logged_in() ) {
			wpsight_get_template( 'access-no.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
			return;
		}

		wpsight_get_template( 'password-change.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );

	}

	/**
	 *	profile()
	 *
	 *	Get the template for the profile shortcode.
	 *	
	 *	@access	public
	 *	@param	array	$atts
	 *	@uses	wpsight_get_template()
	 *	@uses	is_user_logged_in()
	 *	@uses	get_current_user_id()
	 *	@uses	cmb2_get_metabox_form()
	 *	@return	mixed
	 *
	 *	@since	1.1.0
	 */
	public static function profile( $atts = array() ) {

		if ( ! is_user_logged_in() ) {
			wpsight_get_template( 'access-no.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
			return;
		}

		$form = cmb2_get_metabox_form( 'wpsight_agent', get_current_user_id(), array(
			'form_format' => '<form action="//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '" class="cmb-form wpsight-dashboard-form" method="post" id="%1$s" enctype="multipart/form-data" encoding="multipart/form-data"><input type="hidden" name="object_id" value="%2$s">%3$s<input type="submit" name="submit-profile" value="%4$s" class="button"></form>',
			'save_button' => __( 'Save profile', 'wpcasa-dashboard' ),
		) );

		wpsight_get_template( 'account-profile.php', array(
			'form' => $form,
		), WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );

	}
	
	/**
	 *	package_info()
	 *
	 *	Get the template for the package_info shortcode.
	 *	
	 *	@access	public
	 *	@param	array	$atts
	 *	@uses	wpsight_get_template()
	 *	@uses	is_user_logged_in()
	 *	@return	mixed
	 *
	 *	@since	1.1.0
	 */
    public static function package_info( $atts = array() ) {

        if ( ! is_user_logged_in() ) {
			if( get_the_id() != wpsight_get_option( 'dashboard_profile' ) )
				wpsight_get_template( 'access-no.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
            return;
        }

        wpsight_get_template( 'package-info.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );

    }

}

WPSight_Dashboard_Shortcodes::init();
