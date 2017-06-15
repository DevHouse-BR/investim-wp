<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Dashboard_General class
 */
class WPSight_Dashboard_Billing {

	/**
	 *	Initialize class
	 */
	public static function init() {
		add_filter( 'wpsight_meta_boxes', array( __CLASS__, 'meta_box_agent_billing' ) );
		add_action( 'wpsight_dashboard_payment_processed', array( __CLASS__, 'payment_billing_details' ), 10, 9 );
	}
	
	/**
	 *	billing_fields()
	 *
     *	Returns all billing fields with their key and title
     *	
     *	@access public
     *	@return array
     *
     *	@since 1.1.0
     */
    public static function billing_fields() {

        $fields = array(
            'billing_name'                  => _x( 'Name', 'billing fields', 'wpcasa-dashboard' ),
            'billing_registration_number'   => _x( 'Reg. No.', 'billing fields', 'wpcasa-dashboard' ),
            'billing_vat_number'            => _x( 'VAT No.', 'billing fields', 'wpcasa-dashboard' ),
            'billing_street_and_number'     => _x( 'Street and number', 'billing fields', 'wpcasa-dashboard' ),
            'billing_country'               => _x( 'Country', 'billing fields', 'wpcasa-dashboard' ),
            'billing_county'                => _x( 'State / County', 'billing fields', 'wpcasa-dashboard' ),
            'billing_city'                  => _x( 'City', 'billing fields', 'wpcasa-dashboard' ),
            'billing_postal_code'           => _x( 'Postal code', 'billing fields', 'wpcasa-dashboard' ),
        );

        return apply_filters( 'wpsight_dashboard_billing_fields', $fields );

    }
    
    /**
	 *	get_billing_details_from_context()
	 *
     *	Searches for billing details in given context
     *	
     *	@access	public
     *	@param	array $context
     *	@uses	self::billing_fields()
     *	@return	array
     *
     *	@since 1.1.0
     */
    public static function get_billing_details_from_context( $context ) {

        $billing_details_keys = array_keys( self::billing_fields() );

        $billing_details = array();

        foreach( $billing_details_keys as $key ) {
            $billing_details[ $key ] = ! empty( $context[ $key ] ) ? $context[ $key ] : null;
        }

        return $billing_details;

    }
	
	/**
	 *	payment_billing_details()
	 *
     *	Update user billing details after a payment has been made.
     *	
     *	@access public
     *	@param	string	$success
     *	@param	string	$gateway
     *	@param	string	$payment_type
     *	@param	integer	$payment_id
     *	@param	integer	$object_id
     *	@param	string	$price
     *	@param	string	$currency_code
     *	@param	integer	$user_id
     *	@param	array	$billing_details
     *
     *	@since 1.1.0
     */
    public static function payment_billing_details( $success, $gateway, $payment_type, $payment_id, $object_id, $price, $currency_code, $user_id, $billing_details ) {
	    
	    $billing_details_keys = array_keys( self::billing_fields() );

        foreach( $billing_details_keys as $key ) {
	        
	        if( isset( $billing_details[ $key ] ) )
	        	update_user_meta( $user_id, $key, $billing_details[ $key ] );

        }

    }
    
    /**
	 *	meta_box_agent_billing()
	 *	
	 *	Create user billing meta box.
	 *	
	 *	@param	array	$meta_boxes
	 *	@uses	wpsight_get_option()
	 *	@uses	self::billing_fields()
	 *	@uses	wpsight_sort_array_by_priority()
	 *	@uses	wpsight_post_type()
	 *	@return	array	$meta_box	Meta box array with fields
	 *	
	 *	@since 1.0.0
	 */
	public static function meta_box_agent_billing( $meta_boxes ) {
		
		if ( 'packages' == wpsight_get_option( 'dashboard_payment_options' ) ) {
		
			$i = 1;
			
			$fields = array(
				'billing_title' => array(
					'id'        => 'billing_title',
					'name'      => __( 'Billing Information', 'wpcasa-dashboard' ),
					'type'      => 'title',
					'show_on_cb'=> array( 'WPSight_Dashboard_Meta_Boxes', 'meta_box_field_only_admin' ),
					'priority'  => $i . 0
				)
			);
			
			$billing_fields = self::billing_fields();
			
			foreach( $billing_fields as $key => $label ) {
				
				$i++;
				
				$fields[ $key ] = array(
					'id'        => $key,
					'name'      => $label,
					'type'      => 'text',
					'show_on_cb'=> array( 'WPSight_Dashboard_Meta_Boxes', 'meta_box_field_only_admin' ),
					'priority'  => $i . 0
				);
				
			}
			
			// Apply filter and order fields by priority
			$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_agent_billing_fields', $fields ) );
			
			// Set meta box
			
			$meta_box = array(
				'id'            => 'wpsight_agent_billing',
				'title'			=> __( 'Billing', 'wpcasa-dashboard' ),
				'object_types'  => array( 'user' ),
				'context'       => 'normal',
				'priority'      => 'high',
				'show_names'    => true,
				'fields'		=> $fields
			);
			
			// Add meta box to general meta box array		
			$meta_boxes = array_merge( $meta_boxes, array( 'wpsight_agent_billing' => apply_filters( 'wpsight_meta_box_agent_billing', $meta_box ) ) );
		
		}

		return $meta_boxes;

	}

}

WPSight_Dashboard_Billing::init();
