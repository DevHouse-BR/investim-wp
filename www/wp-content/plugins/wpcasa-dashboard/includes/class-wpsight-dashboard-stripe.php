<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

require_once WPSIGHT_DASHBOARD_PLUGIN_DIR . '/includes/libraries/stripe-php/init.php';

use Stripe\Stripe;

/**
 * Class WPSight_Dashboard_Stripe
 */
class WPSight_Dashboard_Stripe {

    /**
     *	Initialize class
     */
    public static function init() {
        add_action( 'init', array( __CLASS__, 'process_payment' ), 9999 );
        add_filter( 'wpsight_dashboard_payment_gateways', array( __CLASS__, 'payment_gateways' ) );
    }

    /**
	 *	payment_gateways()
	 *
     *	Add Stripe to payments gateways.
     *	
     *	@access	public
     *	@param	array	$gateways
     *	@return	array
     *
     *	@since	1.1.0
     */
    public static function payment_gateways( $gateways ) {

        if (  self::is_active() ) {
	        
	        ob_start();
			wpsight_get_template( 'stripe-checkout.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
			$content = ob_get_clean();

            $gateways['stripe_checkout'] = array(
                'id'      => 'stripe-checkout',
                'title'   => __( 'Stripe Checkout', 'wpcasa-dashboard' ),
                'proceed' => true,
                'content' => $content,
				'priority'=> 10
            );

        }

        return $gateways;

    }

    /**
	 *	get_stripe_config()
	 *
     *	Get Stripe configuration, secret and publishable
     *	keys set in options.
     *	
     *	@access	public
     *	@return	array|bool
     *
     *	@since	1.1.0
     */
    public static function get_stripe_config() {
	    
	    $live = wpsight_get_option( 'dashboard_stripe_live' );
	    
	    if( $live ) {	    
        	$secret_key = wpsight_get_option( 'dashboard_stripe_secret' );
			$publishable_key = wpsight_get_option( 'dashboard_stripe_publishable' );
        } else {
	        $secret_key = wpsight_get_option( 'dashboard_stripe_secret_test' );
			$publishable_key = wpsight_get_option( 'dashboard_stripe_publishable_test' );
        }

        if ( empty( $secret_key ) || empty( $publishable_key ) ) {
            return false;
        }

        $stripe_config = array(
            "secret_key"      => $secret_key,
            "publishable_key" => $publishable_key
        );

        return $stripe_config;

    }

    /**
	 *	process_payment()
	 *
     *	Process Stripe payment form.
     *	
     *	@access	public
     *
     *	@since	1.1.0
     */
    public static function process_payment() {

        $config = self::get_stripe_config();

        if ( ! isset( $_POST['stripeToken'] ) ) {
            return;
        }

        Stripe::setApiKey($config['secret_key']);

        $token = ! empty( $_POST['stripeToken'] ) ? $_POST['stripeToken'] : null;
        $token_type = ! empty( $_POST['stripeTokenType'] ) ? $_POST['stripeTokenType'] : null;
        $email = ! empty( $_POST['stripeEmail'] ) ? $_POST['stripeEmail'] : null;

        $settings = array(
            'payment_type'  => ! empty( $_POST['payment_type'] ) ? $_POST['payment_type'] : '',
            'object_id'  	=> ! empty( $_POST['object_id'] ) ? $_POST['object_id'] : '',
            'currency'  	=> ! empty( $_POST['currency'] ) ? $_POST['currency'] : '',
            'price'  	    => ! empty( $_POST['price'] ) ? $_POST['price'] : '',
        );

        // billing details
        $settings['billing_details'] = WPSight_Dashboard_Billing::get_billing_details_from_context( $_POST );

        try {
            $customer = \Stripe\Customer::create( array(
                'email' => $email,
                'card'  => $token
            ));

            $charge = \Stripe\Charge::create( array(
                'customer' => $customer->id,
                'amount'   => WPSight_Dashboard_Price::get_price_in_cents( $settings["price"] ),
                'currency' => $settings["currency"]
            ) );

            // process successful result
            self::process_result( true, $settings, $charge->id, $token );

        } catch(\Stripe\Error\Card $e) {
            // The card has been declined
            $_SESSION['messages'][] = array( 'danger', __( 'The card has been declined.', 'wpcasa-dashboard' ) );
        } catch(\Stripe\Error\InvalidRequest $e) {
            // The card has been declined or token was used than once
            $_SESSION['messages'][] = array( 'danger', $e->__toString() );
        }

        // process error result
        self::process_result( false, $settings, null, $token );

    }

    /**
	 *	process_result()
	 *
     *	Process Stripe payment result.
     *	
     *	@access	public
     *	@param	bool	$success
     *	@param	array	$settings
     *	@param	integer	$payment_id
     *	@param	string	$token
     *
     *	@since	1.1.0
     */
    public static function process_result( $success, $settings, $payment_id, $token ) {

        $gateway = 'stripe-checkout';

        $post = get_post( $settings['object_id'] );
        $user_id = get_current_user_id();

        // validate payment
        $is_valid = true;

        if ( $success ) {

            $is_valid = ! WPSight_Dashboard_Transactions::does_transaction_exist( array( 'stripe-checkout' ), $payment_id );

            // if params are present, validate them
            if ( $is_valid ) {
                $is_valid = self::is_stripe_payment_valid( $payment_id );
            }

            // if payment is invalid, it is not successful transaction
            $success = $is_valid;

        }

        // prepare transaction data
        $data = array(
            'success'           => $success,
            'price'             => $settings['price'],
            'price_formatted'   => $settings['price'],
            'currency_code'     => $settings['currency'],
            'currency_sign'     => '',
            'token'             => $token,
            'paymentId'         => $payment_id
        );

        // create transaction
        WPSight_Dashboard_Transactions::create_transaction( $gateway, $success, $user_id, $settings['payment_type'], $payment_id, $settings['object_id'], $settings['price'], $settings['currency'], $data );

        // fire after payment hook
        do_action( 'wpsight_dashboard_payment_processed', $success, $gateway, $settings['payment_type'], $payment_id, $settings['object_id'], $settings['price'], $settings['currency'], $user_id, $settings['billing_details'] );
        
        // redirect to profile or transactions or homepage
        
		$transactions_page	= wpsight_get_option( 'dashboard_transactions' );
		$profile_page		= wpsight_get_option( 'dashboard_profile' );
		
		if( $transactions_page ) {
			$redirect_url = get_permalink( $transactions_page );
		} elseif( $profile_page ) {
			$redirect_url = get_permalink( $profile_page );
		} else {
			$redirect_url = site_url();
		}

        // handle payment
        if ( $success ) {
            if ( ! $is_valid ) {
                $_SESSION['messages'][] = array( 'danger', __( 'Payment is invalid.', 'wpcasa-dashboard' ) );
            } else if ( ! in_array( $settings['payment_type'], WPSight_Dashboard_Payments::payment_types() ) ) {
                $_SESSION['messages'][] = array( 'danger', __( 'Undefined payment type.', 'wpcasa-dashboard' ) );
            } else {
                $_SESSION['messages'][] = array( 'success', __( 'Payment has been successful.', 'wpcasa-dashboard' ) );
            }
        } else {
            $_SESSION['messages'][] = array( 'danger', __( 'Payment failed, was canceled or is invalid.', 'wpcasa-dashboard' ) );
			$redirect_url = $profile_page ? get_permalink( $profile_page ) : $redirect_url;
        }

        wp_redirect( esc_url( $redirect_url ) );
        exit();

    }

    /**
	 *	get_data()
	 *
     *	Prepare Stripe payment data.
     *	
     *	@access	public
     *	@param	$payment_type
     *	@param	$object_id
     *	@return	array|bool
     *
     *	@since	1.1.0
     */
    public static function get_data( $payment_type, $object_id ) {

        if ( empty( $payment_type ) || empty( $object_id ) )
            return false;

        if ( ! in_array( $payment_type, WPSight_Dashboard_Payments::payment_types() ) )
            return false;

        $payment_data = apply_filters( 'wpsight_dashboard_prepare_payment', array(), $payment_type, $object_id );

        $config = self::get_stripe_config();
        $publishable_key = $config['publishable_key'];
        
        $data = array(
            'key'               => $publishable_key,
            'name'              => $payment_data['action_title'],
            'description'       => $payment_data['description'],
            'amount'            => WPSight_Dashboard_Price::get_price_in_cents( $payment_data['price'] ),
            'currency'          => WPSight_Dashboard_Price::default_currency_code(),
            'locale'            => 'auto'
        );

        return $data;

    }

    /**
	 *	is_stripe_payment_valid()
	 *
     *	Check if Stripe payment is valid.
     *	
     *	@access	public
     *	@param	string	$payment_id
     *	@param	string	$token
     *	@return bool
     *
     *	@since	1.1.0
     */
    public static function is_stripe_payment_valid( $payment_id ) {

        $config = self::get_stripe_config();

        try {
            Stripe::setApiKey($config['secret_key']);

            $charge = \Stripe\Charge::retrieve( $payment_id );

            if ( $charge->id != $payment_id ) {
                return false;
            }

        } catch (Exception $ex) {
            return false;
        }

        return true;

    }

    /**
	 *	is_active()
	 *
     *	Check if Stripe is active.
     *	
     *	@access	public
     *	@return	bool
     *
     *	@since	1.1.0
     */
    public static function is_active() {

        $config = self::get_stripe_config();
        return ( ! empty( $config ) && is_array( $config ) );

    }

    /**
	 *	get_supported_currencies()
	 *
     *	Return supported currencies by Stripe.
     *	
     *	@access	public
     *	@param	string	$payment
     *	@see	https://support.stripe.com/questions/which-currencies-does-stripe-support
     *	@return	array
     *
     *	@since	1.1.0
     */
    public static function get_supported_currencies( $payment ) {

        $currency_group_1 = array(
            "AED", "ALL", "ANG", "ARS", "AUD", "AWG", "BBD", "BDT", "BIF", "BMD", "BND", "BOB", "BRL", "BSD", "BWP",
            "BZD", "CAD", "CHF", "CLP", "CNY", "COP", "CRC", "CVE", "CZK", "DJF", "DKK", "DOP", "DZD", "EGP", "ETB",
            "EUR", "FJD", "FKP", "GBP", "GIP", "GMD", "GNF", "GTQ", "GYD", "HKD", "HNL", "HRK", "HTG", "HUF", "IDR",
            "ILS", "INR", "ISK", "JMD", "JPY", "KES", "KHR", "KMF", "KRW", "KYD", "KZT", "LAK", "LBP", "LKR", "LRD",
            "MAD", "MDL", "MNT", "MOP", "MRO", "MUR", "MVR", "MWK", "MXN", "MYR", "NAD", "NGN", "NIO", "NOK", "NPR",
            "NZD", "PAB", "PEN", "PGK", "PHP", "PKR", "PLN", "PYG", "QAR", "RUB", "SAR", "SBD", "SCR", "SEK", "SGD",
            "SHP", "SLL", "SOS", "STD", "SVC", "SZL", "THB", "TOP", "TTD", "TWD", "TZS", "UAH", "UGX", "USD", "UYU",
            "UZS", "VND", "VUV", "WST", "XAF", "XOF", "XPF", "YER", "ZAR"
        );

        $currency_group_2 = array(
            "AFN", "AMD", "AOA", "AZN", "BAM", "BGN", "GEL", "CDF", "KGS", "LSL", "MGA", "MKD", "MZN", "RON", "RSD",
            "SRD", "TJS", "TRY", "XCD", "ZMW"
        );

        return array_merge( $currency_group_1, $currency_group_2 );

    }
}

WPSight_Dashboard_Stripe::init();
