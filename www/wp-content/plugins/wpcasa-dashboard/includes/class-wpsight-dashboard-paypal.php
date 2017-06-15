<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

require WPSIGHT_DASHBOARD_PLUGIN_DIR . '/includes/libraries/PayPal-PHP-SDK/vendor/autoload.php';

use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

/**
 * Class WPSight_Dashboard_PayPal
 */
class WPSight_Dashboard_PayPal {

    /**
	 *	Initialize class
	 */
	public static function init() {
        add_action( 'init', array( __CLASS__, 'process_payment' ), 9999 );
        add_action( 'init', array( __CLASS__, 'process_result' ), 9999 );
	    add_filter( 'wpsight_dashboard_payment_gateways', array( __CLASS__, 'payment_gateways' ) );
    }

	/**
	 *	payment_gateways()
	 *
	 *	Add PayPal to payments gateways using
	 *	wpsight_dashboard_payment_gateways filter.
	 *	
	 *	@access	public
	 *	@param	array	$gateways
	 *	@uses	self::is_active()
	 *	@uses	wpsight_get_template()
	 *	@uses	wpsight_get_option()
	 *	@return	array
	 *
	 *	@since	1.1.0
	 */
	public static function payment_gateways( $gateways ) {
		
		if (  self::is_active() ) {

			$gateways['paypal_account'] = array(
				'id'      => 'paypal-account',
				'title'   => __( 'PayPal Account', 'wpcasa-dashboard' ),
				'proceed' => true,
				'priority'=> 20
			);
			
			ob_start();
			wpsight_get_template( 'paypal-credit-card.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
			$content = ob_get_clean();
			
			if ( wpsight_get_option( 'dashboard_paypal_cc' ) ) {

				$gateways['paypal_credit_card'] = array(
					'id'      => 'paypal-credit-card',
					'title'   => __( 'PayPal Credit Card', 'wpcasa-dashboard' ),
					'proceed' => true,
					'content' => $content,
					'priority'=> 30
				);

			}

		}

		return $gateways;

	}

    /**
	 *	process_payment()
	 *
     *	Process PayPal payment form.
     *	
     *	@access	public
     *	@uses	WPSight_Dashboard_Billing::get_billing_details_from_context()
     *	@uses	wpsight_get_option()
     *	@uses	self::validate_card_number()
     *	@uses	self::validate_cvv()
     *	@uses	self::process_credit_card()
     *	@uses	get_current_user_id()
     *	@uses	self::get_paypal_url()
     *	@uses	wp_redirect()
     *	@uses	self::get_account_approval_url()
     *
     *	@since	1.1.0
     */
    public static function process_payment() {

        if ( ! isset( $_POST['process-payment'] ) )
            return;

        $gateway = ! empty( $_POST['payment_gateway'] ) ? $_POST['payment_gateway'] : null;

        $settings = array(
            'payment_type'  => ! empty( $_POST['payment_type'] ) ? $_POST['payment_type'] : '',
            'object_id'  	=> ! empty( $_POST['object_id'] ) ? $_POST['object_id'] : '',
            'first_name'    => ! empty( $_POST['first_name'] ) ? $_POST['first_name'] : '',
            'last_name'     => ! empty( $_POST['last_name'] ) ? $_POST['last_name'] : '',
            'card_number'   => ! empty( $_POST['card_number'] ) ? $_POST['card_number'] : '',
            'cvv'           => ! empty( $_POST['cvv'] ) ? $_POST['cvv'] : '',
            'expires_month' => ! empty( $_POST['expires_month'] ) ? $_POST['expires_month'] : '',
            'expires_year'  => ! empty( $_POST['expires_year'] ) ? $_POST['expires_year'] : '',
        );

        // billing details
        $settings['billing_details'] = WPSight_Dashboard_Billing::get_billing_details_from_context( $_POST );

        $terms = wpsight_get_option( 'dashboard_terms' );

        switch ( $gateway ) {

            case 'paypal-credit-card':
                if ( empty( $_POST['first_name']) ) {
                    $_SESSION['messages'][] = array( 'danger', __( 'First name is required.', 'wpcasa-dashboard' ) );
                    break;
                }

                if ( empty( $_POST['last_name']) ) {
                    $_SESSION['messages'][] = array( 'danger', __( 'Last name is required.', 'wpcasa-dashboard' ) );
                    break;
                }

                if ( empty( $_POST['card_number']) ) {
                    $_SESSION['messages'][] = array( 'danger', __( 'Card number is required.', 'wpcasa-dashboard' ) );
                    break;
                }

                if ( empty( $_POST['cvv']) ) {
                    $_SESSION['messages'][] = array( 'danger', __( 'CVV is required.', 'wpcasa-dashboard' ) );
                    break;
                }

                if ( empty( $_POST['expires_month']) ) {
                    $_SESSION['messages'][] = array( 'danger', __( 'Expires month is required.', 'wpcasa-dashboard' ) );
                    break;
                }

                if ( empty( $_POST['expires_year']) ) {
                    $_SESSION['messages'][] = array( 'danger', __( 'Expires year is required.', 'wpcasa-dashboard' ) );
                    break;
                }

                if ( ! self::validate_card_number( $_POST['card_number'] ) ){
                    $_SESSION['messages'][] = array( 'danger', __( 'Credit card number is not valid.', 'wpcasa-dashboard' ) );
                    break;
                }

                if ( ! self::validate_cvv( $_POST['cvv'] ) ){
                    $_SESSION['messages'][] = array( 'danger', __( 'CVV number is not valid.', 'wpcasa-dashboard' ) );
                    break;
                }

                if ( $terms && empty( $_POST['agree_terms'] ) ) {
                    $_SESSION['messages'][] = array( 'danger', __( 'You must agree terms &amp; conditions.', 'wpcasa-dashboard' ) );
                    break;
                }

                $payment = self::process_credit_card( $settings );

                if ( ! empty( $payment->state ) ) {
                    # possible states: ["created", "approved", "completed", "partially_completed", "failed", "canceled", "expired", "in_progress"]
                    if ( in_array( $payment->state, array( 'approved', 'completed' ) ) ) {
                        $url = self::get_paypal_url( true, $gateway, get_current_user_id(), $settings['payment_type'], $settings['object_id'], $settings['billing_details'], $payment->id );
                        wp_redirect( $url );
                        exit();
                    }

                    if ( in_array( $payment->state, array( 'failed', 'canceled', 'expired' ) ) ) {
                        $url = self::get_paypal_url( false, $gateway, get_current_user_id(), $settings['payment_type'], $settings['object_id'], $settings['billing_details'], $payment->id );
                        wp_redirect( $url );
                        exit();
                    }
                } else {
	                $payment_id = isset( $payment->id ) ? $payment->id : false;
                    $url = self::get_paypal_url( false, $gateway, get_current_user_id(), $settings['payment_type'], $settings['object_id'], $settings['billing_details'], $payment_id );
                    wp_redirect( $url );
                    exit();
                }

                break;

            case 'paypal-account':
                if ( $terms && empty( $_POST['agree_terms'] ) ) {
                    $_SESSION['messages'][] = array( 'danger', __( 'You must agree terms &amp; conditions.', 'wpcasa-dashboard' ) );
                    break;
                }

                $url = self::get_account_approval_url( $settings );
                if( ! empty( $url ) ) {
                    wp_redirect( $url );
                    exit();
                }

                break;

        }

    }

    /**
	 *	process_result()
	 *
     *	Process PayPal payment result.
     *	
     *	@access	public
     *	@uses	WPSight_Dashboard_Transactions::does_transaction_exist()
     *	@uses	self::is_paypal_payment_valid()
     *	@uses	self::execute_payment()
     *	@uses	WPSight_Dashboard_Transactions::create_transaction()
     *	@uses	WPSight_Dashboard_Billing::get_billing_details_from_context()
     *	@uses	WPSight_Dashboard_Payments::payment_types()
     *	@uses	wp_redirect()
     *	@uses	site_url()
     *
     *	@since	1.1.0
     */
    public static function process_result() {

        // check if all required params are set
        $transaction_params = array( 'gateway', 'success', 'user_id', 'payment_type', 'paymentId', 'object_id', 'price', 'currency_code' );

        foreach ( $transaction_params as $required_param ) {
            if ( empty( $_GET[ $required_param ] ) )
                return;
        }

        // cast string to bool
        $success = $_GET['success'] == 'true';

        // validate payment
        $is_valid = true;

        if ( $success ) {

            // if paypal param is missing, payment is not valid
            $paypal_params = $_GET['gateway'] == 'paypal-account' ? array( 'paymentId', 'token', 'PayerID' ) : array( 'paymentId' );

            foreach ( $paypal_params as $required_param ) {
                if ( empty( $_GET[ $required_param ] ) ) {
                    $is_valid = false;
                    break;
                }
            }

            // if params are present, validate them
            if ( $is_valid ) {
                $is_valid = ! WPSight_Dashboard_Transactions::does_transaction_exist( array( 'paypal-account', 'paypal-credit-card' ), $_GET['paymentId'] );
            }

            // if params are present, validate them
            if ( $is_valid ) {
                if ( $_GET['gateway'] == 'paypal-account' ) {
                    $is_valid = self::is_paypal_payment_valid( $_GET['gateway'], $_GET['paymentId'], $_GET['token'], $_GET['PayerID'] );
                } else {
                    $is_valid = self::is_paypal_payment_valid( $_GET['gateway'], $_GET['paymentId'] );
                }
            }

            if ( $_GET['gateway'] == 'paypal-account' ) {
                if ( ! self::execute_payment( $_GET['paymentId'], $_GET['PayerID'] ) ) {
                    $success = false;
                };
            }

            // if payment is invalid, it is not successful transaction
            if ( ! $is_valid ) {
                $success = false;
            }
        }

        // prepare data for transaction
        $params = $_GET;
        foreach ( array( 'success', 'gateway', 'payment_type', 'object_id', 'user_id' ) as $unset_key ) {
            unset( $params[ $unset_key ] );
        }

        // create transaction
        WPSight_Dashboard_Transactions::create_transaction( $_GET['gateway'], $success, $_GET['user_id'], $_GET['payment_type'], $_GET['paymentId'], $_GET['object_id'], $_GET['price'], $_GET['currency_code'], $params );

        // billing_details
        $billing_details = WPSight_Dashboard_Billing::get_billing_details_from_context( $_GET );

        // fire after payment hook
        do_action( 'wpsight_dashboard_payment_processed', $success, $_GET['gateway'], $_GET['payment_type'], $_GET['paymentId'], $_GET['object_id'], $_GET['price'], $_GET['currency_code'], $_GET['user_id'], $billing_details );
        
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
            if( ! $is_valid ) {
                $_SESSION['messages'][] = array( 'danger', __( 'Payment is invalid.', 'wpcasa-dashboard' ) );
            } elseif( ! in_array( $_GET['payment_type'], WPSight_Dashboard_Payments::payment_types() ) ) {
                $_SESSION['messages'][] = array( 'danger', __( 'Undefined payment type.', 'wpcasa-dashboard' ) );
            } else {
                $_SESSION['messages'][] = array( 'success', __( 'Payment has been successful.', 'wpcasa-dashboard' ) );
            }
        } else {
            $_SESSION['messages'][] = array( 'danger', __( 'Payment failed, canceled or is invalid.', 'wpcasa-dashboard' ) );
			$redirect_url = $profile_page ? get_permalink( $profile_page ) : $redirect_url;
        }

        wp_redirect( esc_url( $redirect_url ) );
        exit();
    }

    /**
	 *	execute_payment()
	 *
     *	Execute PayPal payment.
     *	
     *	@access	public
     *	@param	string	$payment_id
     *	@param	string	$payer_id
     *	@uses	self::get_paypal_context()
     *	@return	bool
     */
    public static function execute_payment( $payment_id, $payer_id ) {

        $api_context = self::get_paypal_context();

        // execution
        $execution = new PaymentExecution();
        $execution->setPayerId( $payer_id );

        // payment
        $payment = Payment::get( $payment_id, $api_context );

        // result
        try {
            $payment->execute( $execution, $api_context );
        } catch ( Exception $ex ) {
            // if execution fails, it is not successful transaction
            return false;
        }

        return true;

    }

    /**
	 *	is_paypal_payment_valid()
	 *
     *	Checks if a PayPal payment is valid.
     *	
     *	@access	public
     *	@param	string	$gateway
     *	@param	string	$payment_id
     *	@param	string	$token
     *	@param	string	$payer_id
     *	@uses	self::get_paypal_context()
     *	@return	bool
     *
     *	@since	1.1.0
     */
    public static function is_paypal_payment_valid( $gateway, $payment_id, $token = null, $payer_id = null ) {

        try {

            $api_context = self::get_paypal_context();
            $payment = Payment::get( $payment_id, $api_context );

            if ( $gateway == 'paypal-account' ) {

                $api_payer_id = $payment->payer->payer_info->payer_id;

                if ( $payer_id != $api_payer_id )
                    return false;

                $links = $payment->links;
                
                foreach ( $links as $url ) {
                    if ( $url->rel == 'approval_url' ) {
                        if (strpos( $url->href, 'token=' . $token ) === false) {
                            return false;
                        }
                    }
                }

            }

        } catch ( Exception $ex ) {

            return false;

        }

        return true;

    }

    /**
	 *	get_paypal_context()
	 *
     *	Gets PayPal context with client ID
     *	and secret key.
     *	
     *	@access	public
     *	@uses	wpsight_get_option()
     *	@return	Object|bool
     */
    public static function get_paypal_context() {

        $client_id = wpsight_get_option( 'dashboard_paypal_id' );
        $client_secret = wpsight_get_option( 'dashboard_paypal_secret' );

        if ( empty( $client_id ) || empty( $client_secret ) )
            return false;

        $apiContext = new ApiContext( new OAuthTokenCredential( $client_id, $client_secret ) );

	    $live = wpsight_get_option( 'dashboard_paypal_live' );

	    if ( $live == "1" ) {
		    $apiContext->setConfig( array( 'mode' => 'live' ) );
	    } else {
		    $apiContext->setConfig( array( 'mode' => 'sandbox' ) );
	    }

        return $apiContext;

    }

    /**
	 *	get_paypal_url()
	 *
     *	Gets return/success URL
     *	
     *	@access	public
     *	@param	bool	$success
     *	@param	integer	$user_id
     *	@param	string	$gateway
     *	@param	string	$payment_type
     *	@param	integer	$object_id
     *	@param	string	$payment_id
     *	@uses	self::get_data()
     *	@return string
     *
     *	@since	1.1.0
     */
    public static function get_paypal_url( $success, $gateway, $user_id, $payment_type, $object_id, $billing_details, $payment_id = null ) {

        $data = self::get_data( $payment_type, $object_id );
        
        // redirect to profile or transactions or homepage
        
		$profile_page	= wpsight_get_option( 'dashboard_profile' );
		$redirect_url	= $profile_page ? get_permalink( $profile_page ) : site_url();

        $success = $success ? 'true' : 'false';
        $url = sprintf( '%s?success=%s', $redirect_url, $success );

        $params = array(
            'gateway'           => $gateway,
            'payment_type'      => $payment_type,
            'object_id'         => $object_id,
            'user_id'           => $user_id,
            'price'             => $data['price'],
            'currency_code'     => $data['currency_code'],
            'currency_symbol'   => $data['currency_symbol'],
            'price_formatted'   => $data['price_formatted'],
        );

        // $params = array_merge( $params, $billing_details );

        foreach( $params as $param => $value ) {	        
            $url = sprintf( '%s&%s=%s', $url, $param, urlencode( $value ) );
        }

        if ( ! empty( $payment_id ) ) {
            $url = sprintf( '%s&%s=%s', $url, 'paymentId', $payment_id );
        }

        return $url;

    }

    /**
	 *	process_credit_card()
	 *
     *	Process PayPal credit card payment.
     *	
     *	@access	public
     *	@param	array	$settings
     *	@return	Exception|Payment
     *
     *	@since	1.1.0
     */
    public static function process_credit_card( array $settings ) {

        $data = self::get_data( $settings['payment_type'], $settings['object_id'] );

        $card = new CreditCard();
        $card->setType( self::get_credit_card_type( $settings['card_number'] ) )
            ->setNumber( $settings['card_number'] )
            ->setExpireMonth( $settings['expires_month'] )
            ->setExpireYear( $settings['expires_year'] )
            ->setCvv2( $settings['cvv'] )
            ->setFirstName( $settings['first_name'] )
            ->setLastName( $settings['last_name'] );

        $fi = new FundingInstrument();
        $fi->setCreditCard( $card );

        $payer = new Payer();
        $payer->setPaymentMethod( 'credit_card' )
            ->setFundingInstruments( array( $fi ) );

        $item = new Item();
        $item->setName( $data['title'] )
            ->setDescription( $data['description'] )
            ->setCurrency( $data['currency_code'] )
            ->setQuantity( 1 )
            ->setPrice( $data['price'] );

        $item_list = new ItemList();
        $item_list->setItems( array( $item, ) );

        $details = new Details();
        $details->setSubtotal( $data['price'] );

        $amount = new Amount();
        $amount->setCurrency( $data['currency_code'] )
            ->setTotal( $data['price'] )
            ->setDetails( $details );

        $transaction = new Transaction();
        $transaction->setAmount( $amount )
            ->setItemList($item_list)
            ->setDescription( $data['description'] )
            ->setInvoiceNumber( uniqid() );

        $payment = new Payment();
        $payment->setIntent( 'sale' )
            ->setPayer( $payer )
            ->setTransactions( array( $transaction ) );

        try {
            $api_context = self::get_paypal_context();
            $payment->create( $api_context );
            $_SESSION['messages'][] = array( 'success', __( 'Payment has been successful.', 'wpcasa-dashboard' ) );

            return $payment;
        } catch (Exception $ex) {
            $_SESSION['messages'][] = array( 'danger', __( 'Sorry, there was an error processing the payment.', 'wpcasa-dashboard' ) );            
            return $ex;
        }

    }

    /**
	 *	get_account_approval_url()
	 *
     *	Get link for account payment.
     *	
     *	@access	public
     *	@param	array	$settings
     *	@return	string
     *
     *	@since	1.1.0
     */
    public static function get_account_approval_url( array $settings ) {

        $payer = new Payer();
        $payer->setPaymentMethod( 'paypal' );

        $data = self::get_data( $settings['payment_type'], $settings['object_id'] );

        $item = new Item();
        $item->setName( $data['title'] )
            ->setDescription( $data['description'] )
            ->setCurrency( $data['currency_code'] )
            ->setQuantity( 1 )
            ->setPrice( $data['price'] );

        $item_list = new ItemList();
        $item_list->setItems( array($item, ) );

        $details = new Details();
        $details->setSubtotal( $data['price'] );

        $amount = new Amount();
        $amount->setCurrency( $data['currency_code'] )
            ->setTotal( $data['price'] )
            ->setDetails( $details );

        $transaction = new Transaction();
        $transaction->setAmount( $amount )
            ->setItemList( $item_list )
            ->setDescription( $data['description'])
            ->setInvoiceNumber( uniqid() );

        $redirectUrls = new RedirectUrls();
        $return_url = self::get_paypal_url( true, 'paypal-account', get_current_user_id(), $settings['payment_type'], $settings['object_id'], $settings['billing_details'] );
        $cancel_url = self::get_paypal_url( false, 'paypal-account', get_current_user_id(), $settings['payment_type'], $settings['object_id'], $settings['billing_details'] );
        $redirectUrls->setReturnUrl( $return_url )->setCancelUrl( $cancel_url );

        $payment = new Payment();
        $payment->setIntent( 'sale' )
            ->setPayer( $payer )
            ->setRedirectUrls( $redirectUrls )
            ->setTransactions( array( $transaction ) );

        try {
            $api_context = self::get_paypal_context();
            $payment->create( $api_context );
        } catch (Exception $ex) {
            var_dump($ex); die;
            return null;
        }

        return $payment->getApprovalLink();

    }

    /**
	 *	get_data()
	 *
     *	Prepare payment data.
     *	
     *	@access	public
     *	@param	$payment_type
     *	@param	$object_id
     *	@uses	WPSight_Dashboard_Payments::payment_types()
     *	@uses	wpsight_get_option()
     *	@uses	wpsight_get_currency_abbr()
     *	@uses	wpsight_get_currency()
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

        $data = array(
            'title'             => $payment_data['action_title'],
            'description'       => $payment_data['description'],
            'price'             => $payment_data['price'],
            'price_formatted'   => $payment_data['price'],
            'currency_code'     => wpsight_get_currency_abbr( wpsight_get_option( 'currency' ) ),
            'currency_symbol'   => wpsight_get_currency(),
        );

        return $data;

    }

    /**
	 *	is_active()
	 *
     *	Check if PayPal is active, necessary
     *	options are avaiable.
     *	
     *	@access	public
     *	@uses	wpsight_get_option()
     *	@return	bool
     *
     *	@since	1.1.0
     */
    public static function is_active() {

        $paypal_client_id = wpsight_get_option( 'dashboard_paypal_id' );
        $paypal_client_secret = wpsight_get_option( 'dashboard_paypal_secret' );

        return ( ! empty( $paypal_client_id ) && ! empty( $paypal_client_secret ) );

    }

    /**
	 *	validate_card_number()
	 *
     *	Validate credit card number.
     *	
     *	@access	public
     *	@param	$number
     *	@return	bool
     *
     *	@since	1.1.0
     */
    public static function validate_card_number( $number ) {

        // Strip any non-digits (useful for credit card numbers with spaces and hyphens)
        $number = preg_replace( '/\D/', '', $number );

        // Set the string length and parity
        $number_length = strlen( $number );
        $parity = $number_length % 2;

        // Loop through each digit and do the maths

        $total = 0;

        for ( $i = 0; $i < $number_length; $i++ ) {

            $digit = $number[$i];

            // Multiply alternate digits by two
            if ( $i % 2 == $parity ) {
                $digit *= 2;

                // If the sum is two digits, add them together (in effect)
                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            // Total up the digits
            $total += $digit;

        }

        return ( $total % 10 == 0 ) ? true : false;

    }

    /**
	 *	validate_cvv()
	 *
     *	Validate CVV.
     *	
     *	@access	public
     *	@param	$cvv
     *	@return	bool
     *
     *	@since	1.1.0
     */
    public static function validate_cvv( $cvv ) {
        return ( strlen( $cvv ) == 3 || strlen( $cvv ) == 4 );
    }

    /**
	 *	get_credit_card_type()
	 *
     *	Get credit card type.
     *	
     *	@access	public
     *	@param	$number
     *	@return	bool|int|string
     *
     *	@since	1.1.0
     */
    public static function get_credit_card_type( $number ) {

        if ( empty( $number ) )
            return false;

        $matchingPatterns = array(
            'visa'          => '/^4[0-9]{12}(?:[0-9]{3})?$/',
            'mastercard'    => '/^5[1-5][0-9]{14}$/',
            'amex'          => '/^3[47][0-9]{13}$/',
            'diners'        => '/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/',
            'discover'      => '/^6(?:011|5[0-9]{2})[0-9]{12}$/',
            'jcb'           => '/^(?:2131|1800|35\d{3})\d{11}$/',
        );

        foreach ( $matchingPatterns as $key => $pattern ) {
            if ( preg_match( $pattern, $number ) )
                return $key;
        }

        return false;

    }

    /**
	 *	get_supported_currencies()
	 *
     *	Returns supported currencies by PayPal listed here:
     *	
     *	@access	public
     *	@param	string	$payment
     *	@see	https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/
     *	@return	array
     *
     *	@since	1.1.0
     */
    public static function get_supported_currencies( $payment ) {

        if ( $payment == 'account' ) {
            return array( "AUD", "BRL", "CAD", "CZK", "DKK", "EUR", "HKD", "HUF", "ILS", "JPY", "MYR", "MXN", "TWD", "NZD", "NOK", "PHP", "PLN", "GBP", "SGD", "SEK", "CHF", "THB", "TRY", "USD" );
        }

        if ( $payment == 'card' ) {
            return array( "USD", "GBP", "CAD", "EUR", "JPY" );
        }

        return array();

    }

}

WPSight_Dashboard_PayPal::init();
