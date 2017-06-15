<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Class WPSight_Dashboard_Recaptcha
 */
class WPSight_Dashboard_Recaptcha {
	
	/**
	 *	is_recaptcha_enabled()
	 *
	 *	Check if reCAPTCHA is enabled, necessary
	 *	options are set.
	 *	
	 *	@access	public
	 *	@uses	wpsight_get_option()
	 *	@return	bool
	 *
	 *	@since	1.1.0
	 */
	public static function is_recaptcha_enabled() {

		$site_key = wpsight_get_option( 'dashboard_recaptcha_key' );
		$secret_key = wpsight_get_option( 'dashboard_recaptcha_key_secret' );

		if ( $site_key && $secret_key )
			return true;

		return false;

	}

	/**
	 *	is_recaptcha_valid()
	 *
	 *	Check if reCAPTCHA is valid.
	 *	
	 *	@access	public
	 *	@param	$recaptcha_response string
	 *	@uses	wpsight_get_option()
	 *	@uses	json_decode()
	 *	@return	bool
	 *
	 *	@since	1.1.0
	 */
	public static function is_recaptcha_valid( $recaptcha_response ) {

		$secret_key = wpsight_get_option( 'dashboard_recaptcha_key_secret' );
		$url = apply_filters( 'wpsight_dashboard_recaptcha_url', 'https://www.google.com/recaptcha/api/siteverify' ) . '?secret=' . $secret_key . '&response=' . $recaptcha_response;

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );

		$output = curl_exec( $ch );
		$result = json_decode( $output, true );

		if ( array_key_exists( 'success', $result ) && 1 == $result['success'] )
			return true;

		return false;

	}

}
