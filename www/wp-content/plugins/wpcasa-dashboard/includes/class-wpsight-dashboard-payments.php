<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Class WPSight_Dashboard_Payments
 */
class WPSight_Dashboard_Payments {

    /**
	 *	Initialize class
	 */
	public static function init() {
		add_filter( 'wpsight_dashboard_prepare_payment', array( __CLASS__, 'prepare_payment' ), 10, 3 );		
		add_action( 'wpsight_dashboard_payment_form_before', array( __CLASS__, 'payment_form_before' ), 10, 3 );
        add_action( 'wpsight_dashboard_payment_form_fields', array( __CLASS__, 'payment_form_fields' ), 10, 3 );
        add_action( 'wpsight_dashboard_payment_processed', array( __CLASS__, 'catch_payment' ), 10, 9 );        
        add_filter( 'wpsight_dashboard_payment_form_price_value', array( __CLASS__, 'payment_price_value' ), 10, 3 );
    }
    
    /**
	 *	payment_gateways()
	 *
     *	Central array to define payment gateways.
     *	By default the array is empty. Gateways are
     *	added using the wpsight_dashboard_payment_gateways
     *	filter hook.
     *	
     *	@access	public
     *	@uses	wpsight_sort_array_by_priority()
     *	@return array
     *
     *	@since	1.1.0
     */
    public static function payment_gateways() {	    
        return wpsight_sort_array_by_priority( apply_filters( 'wpsight_dashboard_payment_gateways', array() ) );
    }
    
    /**
	 *	get_payment_gateways_choices()
	 *
     *	Return a list of payment gateway IDs and titles.
     *	
     *	@access	public
     *	@param	bool $show_none
     *	@uses	self::payment_gateways()
     *	@return	array
     *
     *	@since	1.1.0
     */
    public static function get_payment_gateways_choices( $show_none = false ) {

        $payment_gateways = self::payment_gateways();

        $choices = array();

        if ( $show_none )
            $choices[''] = __( 'None', 'wpcasa-dashboard' );

        foreach ( $payment_gateways as $payment_gateway ) {
	        $id = $payment_gateway['id'];	        
            $choices[ $id ] = $payment_gateway['title'];
        }

        return $choices;

    }
    
    /**
	 *	get_payment_gateway_title()
	 *
     *	Get the title of a specific payment gateway by key.
     *	
     *	@access	public
     *	@uses	self::payment_gateways()
     *	@return	string
     *
     *	@since	1.1.0
     */
    public static function get_payment_gateway_title( $gateway_key ) {
	    
	    $payment_gateways = self::payment_gateways();
	    
	    $gateway_title = $gateway_key;
	    
	    if( isset( $payment_gateways[ $gateway_key ]['title'] ) )
	    	$gateway_title = $payment_gateways[ $gateway_key ]['title'];
	    
        return apply_filters( 'wpsight_get_payment_gateway_title', $gateway_title );

    }
    
    /**
	 *	payment_types()
	 *
     *	Central array to define payment types.
     *	
     *	@access	public
     *	@return	array
     */
    public static function payment_types() {	    
        return apply_filters( 'wpsight_dashboard_payment_types', array( 'package' ) );
    }
    
    /**
	 *	prepare_payment()
	 *
     *	Prepare payment data.
     *	
     *	@access	public
     *	@param	array	$payment_data
     *	@param	string	$payment_type
     *	@param	integer	$object_id
     *	@uses	get_post()
     *	@uses	WPSight_Dashboard_Packages::get_package_price()
     *	@return array
     *
     *	@since	1.1.0
     */
    public static function prepare_payment( $payment_data, $payment_type, $object_id ) {

        if ( $payment_type == 'package' ) {
            $post = get_post( $object_id );
            $payment_data['price'] = WPSight_Dashboard_Packages::get_package_price( $object_id );
            $payment_data['action_title'] = __( 'Purchase package', 'wpcasa-dashboard' );
            $payment_data['description'] = sprintf( __( 'Upgrade package to %s', 'wpcasa-dashboard' ), $post->post_title );
        }

        return $payment_data;

    }

    /**
	 *	payment_form_before()
	 *
     *	Renders data before payment form with
     *	information about the purchase item.
     *	
     *	@access	public
     *	@param	string	$payment_type
     *	@param	integer	$object_id
     *	@param	string	$payment_gateway
     *	@uses	wpsight_get_template()
     *	@return	mixed
     *
     *	@sinde	1.1.0
     */
    public static function payment_form_before( $payment_type, $object_id, $payment_gateway ) {

        $args = array(
            'payment_type'      => $payment_type,
            'object_id'         => $object_id,
            'payment_gateway'   => $payment_gateway,
        );

		wpsight_get_template( 'payment-form-before.php', $args, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );

    }

    /**
	 *	payment_form_fields()
	 *
     *	Displays additional fields in the payment form.
     *	
     *	@access	public
     *	@param	string	$payment_type
     *	@param	integer	$object_id
     *	@param	string	$payment_gateway
     *	@uses	wpsight_get_template()
     *	@return	mixed
     *
     *	@since	1.1.0
     */
    public static function payment_form_fields( $payment_type, $object_id, $payment_gateway ) {

        $args = array(
            'payment_type'      => $payment_type,
            'object_id'         => $object_id,
            'payment_gateway'   => $payment_gateway,
        );

        wpsight_get_template( 'payment-form-fields.php', $args, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );

    }
    
    /**
	 *	catch_payment()
	 *
     *	Handles payment and sets package for user.
     *	
     *	@param	bool	$success
     *	@param	string	$payment_type
     *	@param	integer	$object_id
     *	@param	float	$price
     *	@param	string	$currency_code
     *	@param	integer	$user_id
     *	@uses	WPSight_Dashboard_Packages::set_package_for_user()
     *
     *	@since	1.1.0
     */
    public static function catch_payment( $success, $gateway, $payment_type, $payment_id, $object_id, $price, $currency_code, $user_id, $billing_details ) {

        if ( $success && $payment_type == 'package' ) {
            WPSight_Dashboard_Packages::set_package_for_user( $user_id, $object_id );
            $_SESSION['messages'][] = array( 'success', __( 'Package has been upgraded.', 'wpcasa-dashboard' ) );
        }

    }
    
    /**
	 *	payment_price_value()
	 *
     *	Get the price value for a payment object.
     *	
     *	@access	public
     *	@param	float	$price
     *	@param	string	$payment_type
     *	@param	integer	$object_id
     *	@uses	WPSight_Dashboard_Packages::get_package_price()
     *	@return	float
     *
     *	@since	1.1.0
     */
    public static function payment_price_value( $price, $payment_type, $object_id ) {

        if ( 'package' == $payment_type && ! empty( $object_id ) )
            return WPSight_Dashboard_Packages::get_package_price( $object_id );

        return $price;

    }
    
}

WPSight_Dashboard_Payments::init();
