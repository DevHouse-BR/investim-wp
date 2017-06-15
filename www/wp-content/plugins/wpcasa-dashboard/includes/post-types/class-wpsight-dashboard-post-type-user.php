<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Class WPSight_Dashboard_Post_Type_User
 */
class WPSight_Dashboard_Post_Type_User {
	
	/**
	 *	Initialize class
	 */
	public static function init() {

		add_filter( 'wpsight_meta_boxes', array( __CLASS__, 'meta_box_agent' ) );
		add_filter( 'wpsight_meta_boxes', array( __CLASS__, 'meta_box_agent_package' ) );

		add_action( 'init', array( __CLASS__, 'process_profile_form' ), 9999 );
		add_action( 'init', array( __CLASS__, 'process_change_password_form' ), 9999 );
		add_action( 'init', array( __CLASS__, 'process_login_form' ), 9999 );
		add_action( 'login_form_lostpassword', array( __CLASS__, 'process_reset_password_form' ) );
		add_action( 'init', array( __CLASS__, 'process_register_form' ), 9999 );
		
		add_action( 'user_register', array( __CLASS__, 'set_profile_data' ), 10, 1 );
		add_filter( 'cmb2_sanitize_text', array( __CLASS__, 'update_profile_data' ), 10, 5 );
		add_filter( 'cmb2_sanitize_text_unique_user_email', array( __CLASS__, 'update_profile_data' ), 10, 5 );
		
		add_filter( 'update_user_metadata', array( __CLASS__, 'update_package_valid' ), 10, 5 );

		add_filter( 'show_admin_bar', array( __CLASS__, 'maybe_disable_admin_bar' ) );
		add_action( 'admin_init', array( __CLASS__, 'maybe_no_admin_access' ), 100 );

	}
	
	/**
	 *	meta_box_agent()
	 *	
	 *	Create user agent meta box
	 *	
	 *	@param	array	$meta_boxes
	 *	@uses	wpsight_sort_array_by_priority()
	 *	@return	array
	 *	@see	wpsight_meta_boxes()
	 *	
	 *	@since 1.1.0
	 */
	public static function meta_box_agent( $meta_boxes ) {

		// Set meta box fields

		$fields = array(
			'general_title' => array(
				'id'        => 'general_title',
				'name'      => __( 'Agent Information', 'wpcasa-dashboard' ),
				'desc'		=> __( 'Apart from the default WordPress profile information above you can add additional agent details here.', 'wpcasa-dashboard' ),
				'type'      => 'title',
				'show_on_cb'=> array( 'WPSight_Meta_Boxes', 'meta_box_field_only_admin' ),
				'priority'  => 5
			),
			'user_login'	=> array(
				'id'        => 'user_login',
				'name'      => __( 'Username', 'wpcasa-dashboard' ),
				'type'      => 'text',
				'show_on_cb'=> array( 'WPSight_Meta_Boxes', 'meta_box_field_only_front_end' ),
				'attributes'=> array(
					'readonly' => 'readonly',
					'disabled' => 'disabled',
				),
				'default'	=> array( 'WPSight_Dashboard_Post_Type_User', 'show_username' ),
				'priority'  => 20
			),
			'first_name'	=> array(
				'id'        => 'first_name',
				'name'      => __( 'First Name', 'wpcasa-dashboard' ),
				'type'      => 'text',
				'show_on_cb'=> array( 'WPSight_Meta_Boxes', 'meta_box_field_only_front_end' ),
				'priority'  => 30
			),
			'last_name'	=> array(
				'id'        => 'last_name',
				'name'      => __( 'Last Name', 'wpcasa-dashboard' ),
				'type'      => 'text',
				'show_on_cb'=> array( 'WPSight_Meta_Boxes', 'meta_box_field_only_front_end' ),
				'priority'  => 40
			),
			'display_name'	=> array(
				'id'        => 'display_name',
				'name'      => __( 'Display Name', 'wpcasa-dashboard' ),
				'type'      => 'text',
				'show_on_cb'=> array( 'WPSight_Meta_Boxes', 'meta_box_field_only_front_end' ),
				'priority'  => 50
			),
			'description'	=> array(
				'id'        => 'description',
				'name'      => __( 'Description', 'wpcasa-dashboard' ),
				'type'      => 'textarea_small',
				'show_on_cb'=> array( 'WPSight_Meta_Boxes', 'meta_box_field_only_front_end' ),
				'priority'  => 60
			),
			'email'	=> array(
				'id'        => 'email',
				'name'      => __( 'E-mail', 'wpcasa-dashboard' ),
				'type'      => 'text_unique_user_email',
				'show_on_cb'=> array( 'WPSight_Meta_Boxes', 'meta_box_field_only_front_end' ),
				'priority'  => 70
			),
			'url'		=> array(
				'id'        => 'url',
				'name'      => __( 'Website', 'wpcasa-dashboard' ),
				'type'      => 'text',
				'show_on_cb'=> array( 'WPSight_Meta_Boxes', 'meta_box_field_only_front_end' ),
				'priority'  => 80
			),
		);

		// Apply filter and order fields by priority
		$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_agent_fields', $fields ) );

		// Set meta box

		$meta_box = array(
			'id'            => 'wpsight_agent',
			'title'			=> __( 'Agent', 'wpcasa-dashboard' ),
			'object_types'  => array( 'user' ),
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names'    => true,
			'fields'		=> $fields
		);
		
		// Add meta box to general meta box array		
		$meta_boxes = array_merge( array( 'wpsight_agent' => apply_filters( 'wpsight_meta_box_agent', $meta_box ) ), $meta_boxes );

		return $meta_boxes;

	}
	
	/**
	 *	meta_box_agent_package()
	 *	
	 *	Create user package meta box.
	 *	
	 *	@param	array	$meta_boxes
	 *	@uses	wpsight_get_option()
	 *	@uses	wpsight_sort_array_by_priority()
	 *	@return	array
	 *	
	 *	@since 1.1.0
	 */
	public static function meta_box_agent_package( $meta_boxes ) {
		
		if ( 'packages' == wpsight_get_option( 'dashboard_payment_options' ) ) {

			// Set meta box fields
			
			$fields = array(
				'package_title' => array(
					'id'        => 'package_title',
					'name'      => __( 'Package Information', 'wpcasa-dashboard' ),
					'type'      => 'title',
					'show_on_cb'=> array( 'WPSight_Meta_Boxes', 'meta_box_field_only_admin' ),
					'priority'  => 110
				),
				'package' => array(
					'id'        => 'package',
					'name'      => __( 'Package', 'wpcasa-dashboard' ),
					'type'      => 'select',
					'show_on_cb'=> array( 'WPSight_Meta_Boxes', 'meta_box_field_only_admin' ),
					'options'   => WPSight_Dashboard_Packages::get_packages_choices( true, true, true, true ),
					'priority'  => 120
				),
				'package_valid' => array(
					'id'        => 'package_valid',
					'name'      => __( 'Valid Until', 'wpcasa-dashboard' ),
					'type'      => 'text_date_timestamp',
					'show_on_cb'=> array( 'WPSight_Meta_Boxes', 'meta_box_field_only_admin' ),
					'after_field' => array( 'WPSight_Dashboard_Post_Type_User', 'after_package_valid' ),
					'priority'  => 130
				),
				'package_listings' => array(
					'id'        => 'package_listings',
					'name'      => __( 'Listings Left', 'wpcasa-dashboard' ),
					'type'      => 'text',
					'attributes'  => array(
						'readonly' => 'readonly',
						'disabled' => 'disabled',
					),
					'default'	=> array( 'WPSight_Dashboard_Post_Type_User', 'remaining_listings_count_for_user' ),
					'show_on_cb'=> array( 'WPSight_Meta_Boxes', 'meta_box_field_only_admin' ),
					'priority'  => 140
				)
			);
			
			// Apply filter and order fields by priority
			$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_agent_package_fields', $fields ) );
			
			// Set meta box
			
			$meta_box = array(
				'id'            => 'wpsight_agent_package',
				'title'			=> __( 'Package', 'wpcasa-dashboard' ),
				'object_types'  => array( 'user' ),
				'context'       => 'normal',
				'priority'      => 'high',
				'show_names'    => true,
				'fields'		=> $fields
			);
			
			// Add meta box to general meta box array		
			$meta_boxes = array_merge( $meta_boxes, array( 'wpsight_agent_package' => apply_filters( 'wpsight_meta_box_agent_package', $meta_box ) ) );
		
		}

		return $meta_boxes;

	}
	
	/**
	 *	update_package_valid()
	 *
	 *	Stop saving wrong package valid date. For unlimited time the value is "-1".
	 *	The text_date_timestamp will show 12/31/1969 (value -86400). We will not
	 *	save this date but stick with "-1".
	 *	
	 *	@access	public
	 *	@param	$null		null
	 *	@param	$object_id	integer
	 *	@param	$meta_key	string
	 *	@param	$meta_value	mixed
	 *	@param	$prev_value	mixed
	 *	@return	bool|null
	 *
	 *	@since	1.1.0
	 */
	public static function update_package_valid( $null, $object_id, $meta_key, $meta_value, $prev_value ) {
		
		if ( 'package_valid' == $meta_key && -86400 == $meta_value )			
			return true; // this means: stop saving the value into the database
		
		return null; // this means: go on with the normal execution in meta.php
		
	}
	
	/**
	 *	after_package_valid()
	 *
	 *	Display a note after valid date field to explain
	 *	why it displays a date in 1969.
	 *	
	 *	@access	public
	 *	@param	$field_args	array
	 *	@param	$field		object	CMB2_Field
	 *
	 *	@since	1.1.0
	 */
	public static function after_package_valid( $field_args, $field ) {
		
		if( '-1' == $field->value )
			echo '<p class="cmb2-metabox-description">' . __( '<strong>Please note!</strong> This date means that the value is -1 (unlimited). Do not change unless you want to set an expiry date.', 'wpcasa-dashboard' ) . '</p>';
		
	}
	
	/**
	 *	remaining_listings_count_for_user()
	 *
	 *	Display the remaining listings count in extra field
	 *	on the user profile page in the admin.
	 *	
	 *	@access	public
	 *	@param	$field_args	array
	 *	@param	$field		object	CMB2_Field
	 *	@return	integer
	 *
	 *	@since	1.1.0
	 */
	public static function remaining_listings_count_for_user( $field_args, $field ) {
		return WPSight_Dashboard_Packages::get_remaining_listings_count_for_user( $field->object_id );
	}
	
	/**
	 *	show_username()
	 *
	 *	Shows username on profile pages in readonly field.
	 *	
	 *	@access	public
	 *	@param	$field_args	array
	 *	@param	$field		object	CMB2_Field
	 *	@return string
	 *
	 *	@since	1.1.0
	 */
	public static function show_username( $field_args, $field ) {
		
		$object_id = $field->object_id;
		$user_data = get_userdata( $object_id );
		
		return is_user_logged_in() ? $user_data->user_login : '';

	}

	/**
	 *	set_profile_data()
	 *
	 *	Sets initial user profile data when
	 *	a user registers through the front end.
	 *	
	 *	@access	public
	 *	@param	integer	$user_id
	 *
	 *	@since	1.1.0
	 */
	public static function set_profile_data( $user_id ) {

		$user_info	= get_userdata( $user_id );

		$first_name	= $user_info->user_firstname;
		$last_name	= $user_info->last_name;
		$email		= $user_info->user_email;
		$url		= $user_info->user_url;

		update_user_meta( $user_id, 'first_name', $first_name );
		update_user_meta( $user_id, 'last_name', $last_name );
		update_user_meta( $user_id, 'email', $email );
		update_user_meta( $user_id, 'display_name', $first_name . ' ' . $last_name );
		update_user_meta( $user_id, 'url', $url );

	}

	/**
	 *	update_profile_data()
	 *
	 *	We update the WordPress profile data
	 *	through the front end using the sanitization callback.
	 *	
	 *	@access	public
	 *	@param	mixed	$override_value
	 *	@param	mixed	$value
	 *	@param	integer	$object_id
	 *	@param	array	$field_args
	 *	@param	object	$sanitizer_object
	 *	@see	http://cmb2.io/api//source-class-CMB2_Field.html#443
	 *	@return	string
	 *
	 *	@since	1.1.0
	 */
	public static function update_profile_data( $override_value, $value, $object_id, $field_args, $sanitizer_object ) {

		$object_type = $sanitizer_object->field->object_type;
		$field_id = $sanitizer_object->field->args['id'];

		if( $object_type != 'user' ) {
			return $value;
		}

		if ( $field_id == 'first_name' ) {
			wp_update_user( array( 'ID' => $object_id, 'first_name' => $value ) );
		}

		if ( $field_id == 'last_name' ) {
			wp_update_user( array( 'ID' => $object_id, 'last_name' => $value ) );
		}
		
		if ( $field_id == 'display_name' ) {
			wp_update_user( array( 'ID' => $object_id, 'display_name' => $value ) );
		}
		
		if ( $field_id == 'email' ) {
			wp_update_user( array( 'ID' => $object_id, 'user_email' => $value ) );
		}
		
		if ( $field_id == 'url' ) {
			wp_update_user( array( 'ID' => $object_id, 'user_url' => $value ) );
		}

		return $value;

	}

	/**
	 *	process_profile_form()
	 *
	 *	Process the user profile form.
	 *	
	 *	@access	public
	 *
	 *	@since	1.1.0
	 */
	public static function process_profile_form() {

		if ( ! empty( $_POST['submit-profile'] ) ) {

			$cmb = cmb2_get_metabox( 'wpsight_agent', get_current_user_id() );
			$cmb->save_fields( get_current_user_id(), 'user', $_POST );

			$_SESSION['messages'][] = array( 'success', __( 'Profile has been successfully updated.', 'wpcasa-dashboard' ) );
			
			wp_redirect( $_SERVER['HTTP_REFERER'] );
			exit();

		}

	}

	/**
	 *	process_change_password_form()
	 *
	 *	Process the change password form.
	 *	
	 *	@access	public
	 *
	 *	@since	1.1.0
	 */
	public static function process_change_password_form() {

		if ( ! isset( $_POST['change_password_form'] ) )
			return;

        $old_password		= $_POST['old_password'];
		$new_password		= $_POST['new_password'];
		$retype_password	= $_POST['retype_password'];

		if ( empty( $old_password ) || empty( $new_password ) || empty( $retype_password ) ) {
			$_SESSION['messages'][] = array( 'warning', __( 'All fields are required.', 'wpcasa-dashboard' ) );
			return;
		}

		$user = wp_get_current_user();

		if ( ! wp_check_password( $old_password, $user->data->user_pass, $user->ID ) ) {
			$_SESSION['messages'][] = array( 'warning', __( 'Your old password is not correct.', 'wpcasa-dashboard' ) );
			return;
		}

		if ( $new_password != $retype_password ) {
			$_SESSION['messages'][] = array( 'warning', __( 'New and retyped password are not same.', 'wpcasa-dashboard' ) );
			return;
		}

		wp_set_password( $new_password, $user->ID );
		$_SESSION['messages'][] = array( 'success', __( 'Your password has been successfully changed.', 'wpcasa-dashboard' ) );
		
		$user = wp_signon( array(
			'user_login'        => $user->user_login,
			'user_password'     => $new_password,
		), false );

		if ( is_wp_error( $user ) ) {
			$_SESSION['messages'][] = array( 'danger', $user->get_error_message() );
			wp_redirect( site_url() );
			exit();
		}

	}

	/**
	 *	process_login_form()
	 *
	 *	Process the user login form.
	 *	
	 *	@access	public
	 *
	 *	@since	1.1.0
	 */
	public static function process_login_form() {

		if ( ! isset( $_POST['login_form'] ) )
			return;
		
		$_SESSION['login'] = array();

		$redirect = site_url();

		if ( ! empty( $_SERVER['HTTP_REFERER'] ) )
			$redirect = $_SERVER['HTTP_REFERER'];

		if ( empty( $_POST['login'] ) || empty( $_POST['password'] ) ) {
			$_SESSION['messages'][] = array( 'warning', __( 'Login and password are required.', 'wpcasa-dashboard' ) );
			wp_redirect( $redirect );
			exit();
		}
		
		$is_recaptcha = WPSight_Dashboard_Recaptcha::is_recaptcha_enabled();
		$is_recaptcha_valid = array_key_exists( 'g-recaptcha-response', $_POST ) ? WPSight_Dashboard_Recaptcha::is_recaptcha_valid( $_POST['g-recaptcha-response'] ) : false;
		
		if ( $is_recaptcha && ! $is_recaptcha_valid ) {
			$_SESSION['messages'][] = array( 'danger', __( 'The captcha input was incorrect.', 'wpcasa-dashboard' ) );
			$_SESSION['login']['login'] = $_POST['login'];
			wp_redirect( $_SERVER['HTTP_REFERER'] );
			exit();
		}
		
		unset( $_SESSION['login'] );

		$user = wp_signon( array(
			'user_login'        => $_POST['login'],
			'user_password'     => $_POST['password'],
		), false );

		if ( is_wp_error( $user ) ) {
			$_SESSION['messages'][] = array( 'danger', $user->get_error_message() );
			wp_redirect( $redirect );
			exit();
		}

		$_SESSION['messages'][] = array( 'success', __( 'You have been successfully logged in.', 'wpcasa-dashboard' ) );

		// login page

		$login_required_page = wpsight_get_option( 'dashboard_login' );
		$login_required_page_url = $login_required_page ? get_permalink( $login_required_page ) : site_url();

		// after login page

		$after_login_page = wpsight_get_option( 'dashboard_page' );
		$after_login_page_url = $after_login_page ? get_permalink( $after_login_page ) : site_url();

		// if user logs in at login page, redirect him to after login page. Otherwise, redirect him back to previous URL.

		$protocol = is_ssl() ? 'https://' : 'http://';
		$current_url = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

		$after_login_url = $current_url == $login_required_page_url ? $after_login_page_url : $current_url;

		wp_redirect( $after_login_url );
		exit();

	}

	/**
	 *	process_reset_password_form()
	 *
	 *	Process the reset password form.
	 *	
	 *	@access	public
	 *
	 *	@since	1.1.0
	 */
	public static function process_reset_password_form() {

		if ( ! isset( $_POST['reset_form'] ) )
			return;

		$result = retrieve_password();

		if ( is_wp_error( $result ) ) {
			$_SESSION['messages'][] = array( 'danger', __( 'There was an error with the password reset.', 'wpcasa-dashboard' ) );
		} else {
			$_SESSION['messages'][] = array( 'success', __( 'Please check inbox for more information.', 'wpcasa-dashboard' ) );
		}

		wp_redirect( $_SERVER['HTTP_REFERER'] );
		exit();

	}

	/**
	 *	process_register_form()
	 *
	 *	Process the user registration form.
	 *	
	 *	@access	public
	 *
	 *	@since	1.1.0
	 */
	public static function process_register_form() {

		if ( ! isset( $_POST['register_form'] ) || ! get_option( 'users_can_register' ) )
			return;
		
		$_SESSION['registration'] = array();

		if ( empty( $_POST['name'] ) || empty( $_POST['email'] ) ) {
			$_SESSION['messages'][] = array( 'danger', __( 'Username and e-mail are required.', 'wpcasa-dashboard' ) );
			wp_redirect( $_SERVER['HTTP_REFERER'] );
			exit();
		}

		$user_id = username_exists( $_POST['name'] );

		if ( ! empty( $user_id ) ) {
			$_SESSION['messages'][] = array( 'danger', __( 'Username already exists.', 'wpcasa-dashboard' ) );
			$_SESSION['registration']['email'] = $_POST['email'];
			wp_redirect( $_SERVER['HTTP_REFERER'] );
			exit();
		}

		$user_id = email_exists( $_POST['email'] );

		if ( ! empty( $user_id ) ) {
			$_SESSION['messages'][] = array( 'danger', __( 'Email already exists.', 'wpcasa-dashboard' ) );
			$_SESSION['registration']['name'] = $_POST['name'];
			wp_redirect( $_SERVER['HTTP_REFERER'] );
			exit();
		}

		if ( $_POST['password'] != $_POST['password_retype'] ) {
			$_SESSION['messages'][] = array( 'danger', __( 'Passwords must be same.', 'wpcasa-dashboard' ) );
			$_SESSION['registration']['name'] = $_POST['name'];
			$_SESSION['registration']['email'] = $_POST['email'];
			wp_redirect( $_SERVER['HTTP_REFERER'] );
			exit();
		}

		$terms_id = wpsight_get_option( 'dashboard_terms' );

		if ( $terms_id && empty( $_POST['agree_terms'] ) ) {
			$_SESSION['messages'][] = array( 'danger', __( 'Please agree to our terms &amp; conditions.', 'wpcasa-dashboard' ) );
			$_SESSION['registration']['name'] = $_POST['name'];
			$_SESSION['registration']['email'] = $_POST['email'];
			wp_redirect( $_SERVER['HTTP_REFERER'] );
			exit();
		}
		
		$is_recaptcha = WPSight_Dashboard_Recaptcha::is_recaptcha_enabled();
		$is_recaptcha_valid = array_key_exists( 'g-recaptcha-response', $_POST ) ? WPSight_Dashboard_Recaptcha::is_recaptcha_valid( $_POST['g-recaptcha-response'] ) : false;
		
		if ( $is_recaptcha && ! $is_recaptcha_valid ) {
			$_SESSION['messages'][] = array( 'danger', __( 'The captcha input was incorrect.', 'wpcasa-dashboard' ) );
			$_SESSION['registration']['name'] = $_POST['name'];
			$_SESSION['registration']['email'] = $_POST['email'];
			wp_redirect( $_SERVER['HTTP_REFERER'] );
			exit();
		}
		
		unset( $_SESSION['registration'] );

		$user_login = sanitize_user( $_POST['name'], true );
		
		$new_user = array(
			'user_login' 	=> $user_login,
			'user_pass'  	=> $_POST['password'],
			'user_email' 	=> $_POST['email'],
			'role'       	=> wpsight_get_option( 'dashboard_role' ) ? wpsight_get_option( 'dashboard_role' ) : get_option( 'default_role' )
    	);
		
		// Finally create user
		$user_id = wp_insert_user( apply_filters( 'wpsight_dashboard_register_data', $new_user ) );

		if ( is_wp_error( $user_id ) ) {
			$_SESSION['messages'][] = array( 'danger', $user_id->get_error_message() );
			wp_redirect( site_url() );
			exit();
		}
		
		wp_new_user_notification( $user_id, null, 'both' );

		$_SESSION['messages'][] = array(
			'success',
			__( 'You have been successfully registered.', 'wpcasa-dashboard' ),
		);
		
		$user = get_user_by( 'login', $user_login );
		$log_in_after_registration = apply_filters( 'wpsight_dashboard_log_in_after_registration', true );

		// automatic user log in

		if ( $user && $log_in_after_registration ) {
			wp_set_current_user( $user->ID, $user_login );
			wp_set_auth_cookie( $user->ID, true, is_ssl() );
			do_action( 'wp_login', $user_login );
		}

		// registration page

		$registration_page = wpsight_get_option( 'dashboard_register' );
		$registration_page_url = $registration_page ? get_permalink( $registration_page ) : site_url();

		// after register page

		$after_register_page = wpsight_get_option( 'dashboard_register_after' );
		$after_register_page_url = $after_register_page ? get_permalink( $after_register_page ) : site_url();

		// if user registers at registration page, redirect him to after register page. Otherwise, redirect him back to previous URL.

		$protocol = is_ssl() ? 'https://' : 'http://';
		$current_url = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

		$after_register_url = $current_url == $registration_page_url ? $after_register_page_url : $current_url;

		wp_redirect( $after_register_url );
		exit();

	}

	/**
	 *	maybe_disable_admin_bar()
	 *
	 *	Hide the admin bar from listing agents.
	 *	
	 *	@access public
	 *	@param string $content
	 *	@return string
	 *
	 *	@since	1.1.0
	 */
	public static function maybe_disable_admin_bar( $content ) {

		if ( current_user_can( 'listing_agent' ) )
			return false;

		return $content;

	}
	
	/**
	 *	maybe_no_admin_access()
	 *
	 *	Don't let listing agents access wp-admin.
	 *	
	 *	@access	public
	 *
	 *	@since	1.1.0
	 */
	public static function maybe_no_admin_access() {

	    if ( current_user_can( 'listing_agent' ) && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
		    
		    $profile = wpsight_get_option( 'dashboard_profile' );
		    
		    $redirect = $profile ? get_permalink( $profile ) : home_url( '/' );
		    
		    wp_redirect( $redirect );
	        exit();

		}

	}

}

WPSight_Dashboard_Post_Type_User::init();
