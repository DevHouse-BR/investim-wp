<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Class WPSight_Dashboard_Post_Type_Transaction
 */
class WPSight_Dashboard_Post_Type_Transaction {

	/**
	 *	Initialize class
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'definition' ) );
		add_filter( 'wpsight_meta_boxes', array( __CLASS__, 'meta_box_transaction' ) );
        add_filter( 'manage_edit-transaction_columns', array( __CLASS__, 'custom_columns' ) );
        add_action( 'manage_transaction_posts_custom_column', array( __CLASS__, 'custom_columns_manage' ) );
	}

	/**
	 *	definition()
	 *
     *	Define the custom post type.
     *	
     *	@access	public
	 *
	 *	@since	1.1.0
     */
	public static function definition() {

		$labels = array(
			'name'                  => __( 'Transactions', 'wpcasa-dashboard' ),
			'singular_name'         => __( 'Transaction', 'wpcasa-dashboard' ),
			'add_new'               => __( 'Add New', 'wpcasa-dashboard' ),
			'add_new_item'          => __( 'Add New Transaction', 'wpcasa-dashboard' ),
			'edit_item'             => __( 'Edit Transaction', 'wpcasa-dashboard' ),
			'new_item'              => __( 'New Transaction', 'wpcasa-dashboard' ),
			'all_items'             => __( 'Transactions', 'wpcasa-dashboard' ),
			'view_item'             => __( 'View Transaction', 'wpcasa-dashboard' ),
			'search_items'          => __( 'Search Transaction', 'wpcasa-dashboard' ),
			'not_found'             => __( 'No Transactions found', 'wpcasa-dashboard' ),
			'not_found_in_trash'    => __( 'No Transactions Found in Trash', 'wpcasa-dashboard' ),
			'parent_item_colon'     => '',
			'menu_name'             => __( 'Transactions', 'wpcasa-dashboard' ),
		);

		register_post_type( 'transaction',
			array(
				'labels'            => $labels,
				'show_in_menu'      => true,
				'menu_position'		=> 51,
				'menu_icon'			=> 'dashicons-arrow-right-alt2',
				'supports'          => array( null ),
				'public'            => false,
				'has_archive'       => false,
				'show_ui'           => 'packages' == wpsight_get_option( 'dashboard_payment_options' ) ? true : false,
				'categories'        => array(),
			)
		);

	}
	
	/**
	 *	meta_box_transaction()
	 *	
	 *	Create transaction meta box.
	 *	
	 *	@param	array	$meta_boxes
	 *	@uses	wpsight_sort_array_by_priority()
	 *	@uses	wpsight_post_type()
	 *	@return	array
	 *	@see	wpsight_meta_boxes()
	 *	
	 *	@since 1.0.0
	 */
	public static function meta_box_transaction( $meta_boxes ) {

		// Set meta box fields

		$fields = array(
			'transaction_payment_type' => array(
				'id'                => 'transaction_payment_type',
				'name'              => __( 'Payment type', 'wpcasa-dashboard' ),
				'type'              => 'text',
				'priority'			=> 10
			),
			'transaction_gateway' => array(
				'id'                => 'transaction_gateway',
				'name'              => __( 'Gateway', 'wpcasa-dashboard' ),
				'type'              => 'text',
				'priority'			=> 20
			),
			'transaction_object_id' => array(
				'id'                => 'transaction_object_id',
				'name'              => __( 'Object ID', 'wpcasa-dashboard' ),
				'type'              => 'text',
				'priority'			=> 30
			),
			'transaction_payment_id' => array(
				'id'                => 'transaction_payment_id',
				'name'              => __( 'Payment ID', 'wpcasa-dashboard' ),
				'type'              => 'text',
				'priority'			=> 40
			),
			'transaction_price' => array(
				'id'                => 'transaction_price',
				'name'              => __( 'Price', 'wpcasa-dashboard' ),
				'type'              => 'text_small',
				'priority'			=> 50
			),
			'transaction_currency' => array(
				'id'                => 'transaction_currency',
				'name'              => __( 'Currency', 'wpcasa-dashboard' ),
				'type'              => 'text_small',
				'priority'			=> 60
			),
			'transaction_success' => array(
				'id'                => 'transaction_success',
				'name'              => __( 'Success', 'wpcasa-dashboard' ),
				'label_cb'			=> __( 'Success', 'wpcasa' ),
				'desc'				=> __( 'Transaction was successful', 'wpcasa' ),
				'type'              => 'checkbox',
				'priority'			=> 70
			),
			'transaction_data' => array(
				'id'                => 'transaction_data',
				'name'              => __( 'Data', 'wpcasa-dashboard' ),
				'type'              => 'textarea',
				'priority'			=> 80
			)
		);

		// Apply filter and order fields by priority
		$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_transaction_fields', $fields ) );

		// Set meta box

		$meta_box = array(
			'id'			=> 'transaction_general',
			'title'			=> __( 'General', 'wpcasa-dashboard' ),
			'object_types'	=> array( 'transaction' ),
			'context'		=> 'normal',
			'priority'		=> 'high',
			'show_names'	=> true,
			'fields'		=> $fields
		);
		
		// Add meta box to general meta box array		
		$meta_boxes = array_merge( $meta_boxes, array( 'wpsight_transaction' => apply_filters( 'wpsight_meta_box_transaction', $meta_box ) ) );

		return $meta_boxes;

	}

    /**
	 *	custom_columns()
	 *
     *	Custom admin columns for transactions.
     *	
     *	@access	public
     *	@return	array
     *
     *	@since	1.1.0
     */
    public static function custom_columns() {

        $fields = array(
            'cb' 			=> '<input type="checkbox" />',
            'payment_id' 	=> __( 'Payment ID', 'wpcasa-dashboard' ),
            'payment_type' 	=> __( 'Payment type', 'wpcasa-dashboard' ),
            'gateway'		=> __( 'Gateway', 'wpcasa-dashboard' ),
            'price'			=> __( 'Price', 'wpcasa-dashboard' ),
            'object'		=> __( 'Object', 'wpcasa-dashboard' ),
            'success'		=> __( 'Success', 'wpcasa-dashboard' ),
            'author'		=> __( 'User', 'wpcasa-dashboard' ),
            'date'			=> __( 'Date', 'wpcasa-dashboard' ),
        );

        return $fields;

    }

    /**
	 *	custom_columns_manage()
	 *
     *	Apply custom admin columns.
     *	
     *	@access	public
     *	@param	string	$column
     *
     *	@since	1.1.0
     */
    public static function custom_columns_manage( $column ) {

        switch ( $column ) {

            case 'payment_id':
                $payment_id = get_post_meta( get_the_id(), 'transaction_payment_id', true );
                echo $payment_id;
                break;

            case 'price':

                $price = get_post_meta( get_the_id(), 'transaction_price', true );

                if ( empty( $price ) ) {
                    echo '-';
                } else {
	                echo WPSight_Dashboard_Price::format_price( $price );
                }
                break;

            case 'gateway':
                $gateway = get_post_meta( get_the_id(), 'transaction_gateway', true );
                echo WPSight_Dashboard_Payments::get_payment_gateway_title( wpsight_underscores( $gateway ) );
                break;

            case 'payment_type':
                $payment_type = get_post_meta( get_the_id(), 'transaction_payment_type', true );
                echo $payment_type;
                break;

            case 'object':
                $object_id = get_post_meta( get_the_id(), 'transaction_object_id', true );
                echo get_the_title( get_post( $object_id ) );
                break;

            case 'success':
                $is_successful = WPSight_Dashboard_Transactions::is_successful( get_the_id() );

                if ( $is_successful ) {
                    echo '<div class="dashicons-before dashicons-yes green"></div>';
                } else {
                    echo '<div class="dashicons-before dashicons-no red"></div>';
                }
                break;

        }
        
    }

}

WPSight_Dashboard_Post_Type_Transaction::init();
