<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Class WPSight_Dashboard_Transactions
 */
class WPSight_Dashboard_Transactions {

    /**
	 *	Initialize class
	 */
	public static function init() {
    }

    /**
	 *	is_successful()
	 *
     *	Check if transaction was successful.
     *	
     *	@access	public
     *	@param	integer	$transaction_id
     *	@return bool
     *
     *	@since	1.1.0
     */
    public static function is_successful( $transaction_id ) {

        $success = get_post_meta( $transaction_id, 'transaction_success', true );

        if ( ! empty( $success ) )
            return $success;

        $data = get_post_meta( $transaction_id, 'transaction_data', true );

        if ( empty( $data ) )
            return false;

        $data = unserialize( $data );

        return empty( $data['success'] ) ? false : $data['success'];

    }

    /**
	 *	create_transaction()
	 *
     *	Create a transaction.
     *	
     *	@access	public
     *	@param	string	$gateway
     *	@param	bool	$success
     *	@param	integer	$user_id
     *	@param	string	$payment_type
     *	@param	integer	$object_id
     *	@param	float	$price
     *	@param	string	$currency_code
     *	@param	array	$params
     *	@return integer	$transaction_id
     *
     *	@since	1.1.0
     */
    public static function create_transaction( $gateway, $success, $user_id, $payment_type, $payment_id, $object_id, $price, $currency_code, $params = array() ) {

        $transaction_id = wp_insert_post( array(
            'post_type'     => 'transaction',
            'post_title'    => date( get_option( 'date_format' ), strtotime( 'today' ) ),
            'post_status'   => 'publish',
            'post_author'   => $user_id,
        ) );

        $data = array(
            'success' => $success,
        );

        foreach ( $params as $key => $value ) {
            $data[ $key ] = $value;
        }

        update_post_meta( $transaction_id, 'transaction_success', $success );
        update_post_meta( $transaction_id, 'transaction_price', $price );
        update_post_meta( $transaction_id, 'transaction_currency', $currency_code );
        update_post_meta( $transaction_id, 'transaction_data', serialize( $data ) );
        update_post_meta( $transaction_id, 'transaction_object_id', $object_id );
        update_post_meta( $transaction_id, 'transaction_payment_type', $payment_type );
        update_post_meta( $transaction_id, 'transaction_payment_id', $payment_id );
        update_post_meta( $transaction_id, 'transaction_gateway', $gateway );

        return $transaction_id;

    }

    /**
	 *	does_transaction_exist()
	 *
     *	Check if specific transaction exists by gateway and payment ID.
     *	
     *	@access	public
     *	@param	array	$gateways
     *	@param	string	$payment_id
     *	@return	bool
     */
    public static function does_transaction_exist( $gateways, $payment_id ) {

        $query = new WP_Query( array(
            'post_type'         => 'transaction',
            'posts_per_page'    => -1,
            'post_status'       => 'any',
            'meta_query' => array(
                array(
                    'key'     => 'transaction_gateway',
                    'value'   => $gateways,
                    'compare' => 'IN',
                ),
                array(
                    'key'     => 'transaction_payment_id',
                    'value'   => $payment_id,
                )
            )
        ) );

        return count( $query->posts ) > 0;

    }
    
}

WPSight_Dashboard_Transactions::init();
