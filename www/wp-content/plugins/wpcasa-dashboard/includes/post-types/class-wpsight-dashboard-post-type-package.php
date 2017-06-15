<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Class WPSight_Dashboard_Post_Type_Package
 */
class WPSight_Dashboard_Post_Type_Package {

    /**
	 *	Initialize class
	 */
	public static function init() {
        add_action( 'init', array( __CLASS__, 'definition' ) );
        add_filter( 'wpsight_meta_boxes', array( __CLASS__, 'meta_box_package' ) );
        add_filter( 'wpsight_meta_boxes', array( __CLASS__, 'meta_box_package_permissions' ), 9 );
        add_filter( 'wpsight_meta_box_package_fields', array( __CLASS__, 'meta_box_package_private' ) );
        add_filter( 'manage_edit-package_columns', array( __CLASS__, 'custom_columns' ) );
        add_action( 'manage_package_posts_custom_column', array( __CLASS__, 'custom_columns_manage' ) );
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
            'name'                  => __( 'Packages', 'wpcasa-dashboard' ),
            'singular_name'         => __( 'Package', 'wpcasa-dashboard' ),
            'add_new'               => __( 'Add New', 'wpcasa-dashboard' ),
            'add_new_item'          => __( 'Add New Package', 'wpcasa-dashboard' ),
            'edit_item'             => __( 'Edit Package', 'wpcasa-dashboard' ),
            'new_item'              => __( 'New Package', 'wpcasa-dashboard' ),
            'all_items'             => __( 'Packages', 'wpcasa-dashboard' ),
            'view_item'             => __( 'View Package', 'wpcasa-dashboard' ),
            'search_items'          => __( 'Search Package', 'wpcasa-dashboard' ),
            'not_found'             => __( 'No Packages found', 'wpcasa-dashboard' ),
            'not_found_in_trash'    => __( 'No Items Found in Trash', 'wpcasa-dashboard' ),
            'parent_item_colon'     => '',
            'menu_name'             => __( 'Packages', 'wpcasa-dashboard' ),
        );

        register_post_type( 'package',
            array(
                'labels'            => $labels,
                'show_in_menu'      => true,
				'menu_position'		=> 51,
				'menu_icon'			=> 'dashicons-arrow-right-alt2',
                'supports'          => array( 'title' ),
                'public'            => false,
                'has_archive'       => false,
                'show_ui'           => 'packages' == wpsight_get_option( 'dashboard_payment_options' ) ? true : false,
                'categories'        => array(),
            )
        );

    }
	
	/**
	 *	meta_box_package()
	 *	
	 *	Create package meta box.
	 *	
	 *	@param	array	$meta_boxes
	 *	@uses	wpsight_sort_array_by_priority()
	 *	@uses	wpsight_post_type()
	 *	@return	array
	 *	@see wpsight_meta_boxes()
	 *	
	 *	@since 1.1.0
	 */
	public static function meta_box_package( $meta_boxes ) {

		// Set meta box fields

		$fields = array(
			'package_price' => array(
				'id'                => 'package_price',
				'name'              => __( 'Price', 'wpcasa-dashboard' ),
				'type'              => 'text_money',
				'before_field'      => wpsight_get_currency(),
				'description'       => sprintf( __( 'In %s.', 'wpcasa-dashboard' ), wpsight_get_currency_abbr() ),
				'sanitization_cb'   => false,
				'attributes'		=> array(
				    'type'				=>	'number',
				    'step'				=> 	'any',
				    'min'				=> 	0,
				    'pattern'			=> 	'\d*(\.\d*)?',
				),
				'priority'			=> 10
			),
			'package_duration'	=> array(
				'id'				=> 'package_duration',
				'name'				=> __( 'Duration', 'wpcasa-dashboard' ),
				'type'				=> 'select',
				'options'			=> WPSight_Dashboard_Packages::get_package_durations_choices( true ),
				'priority'			=> 20
			),
			'package_max_listings'	=> array(
				'id'                => 'package_max_listings',
				'name'              => __( 'Max Listings', 'wpcasa-dashboard' ),
				'type'              => 'text_small',
				'description'       => __( 'Use -1 for unlimited amount of listings.', 'wpcasa-dashboard' ),
				'attributes'		=> array(
				    'type'				=>	'number',
				    'min'				=> 	-1,
				    'pattern'			=> 	'\d*',
				),
				'priority'			=> 30
			),
		);

		// Apply filter and order fields by priority
		$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_package_fields', $fields ) );

		// Set meta box

		$meta_box = array(
			'id'			=> 'package_general',
            'title'			=> __( 'General', 'wpcasa-dashboard' ),
            'object_types'	=> array( 'package' ),
            'context'		=> 'normal',
            'priority'		=> 'high',
            'show_names'	=> true,
			'fields'		=> $fields
		);
		
		// Add meta box to general meta box array		
		$meta_boxes = array_merge( $meta_boxes, array( 'wpsight_package' => apply_filters( 'wpsight_meta_box_package', $meta_box ) ) );

		return $meta_boxes;

	}
	
	/**
	 *	meta_box_package_private()
	 *	
	 *	Add private option to package using
	 *	the wpsight_meta_box_package_fields filter.
	 *	
	 *	@param	array	$fields
	 *	@uses	WPSight_Dashboard_Packages::get_packages_choices()
	 *	@return	array
	 *	
	 *	@since 1.1.0
	 */
	public static function meta_box_package_private( $fields ) {
		
		if( class_exists( 'WPSight_Pricing_Tables' ) ) {
		
			$fields['package_private'] = array(
				'id'                => 'package_private',
				'name'              => __( 'Private', 'wpcasa-dashboard' ),
				'type'              => 'checkbox',
				'label_cb'			=> __( 'Private package', 'wpcasa-dashboard' ),
				'description'       => __( 'If checked, package won\'t be visible in pricing options.', 'wpcasa-dashboard' ),
				'priority'			=> 40
			);
		
		}
		
		return $fields;
		
	}
	
	/**
	 *	meta_box_package_permissions()
	 *	
	 *	Create package meta box permissions
	 *	
	 *	@param	array	$meta_boxes
	 *	@uses	wpsight_sort_array_by_priority()
	 *	@uses	wpsight_post_type()
	 *	@return	array
	 *	@see	wpsight_meta_boxes()
	 *	
	 *	@since 1.0.0
	 */
	public static function meta_box_package_permissions( $meta_boxes ) {

		// Set meta box fields

		$fields = array(
			'listing_images_allowed'	=> array(
				'id'                => 'listing_images_allowed',
				'name'              => '',
				'type'              => isset( $meta_boxes['listing_images'] ) ? 'checkbox' : 'hidden',
				'label_cb'			=> __( 'Images', 'wpcasa-dashboard' ),
				'description'       => __( 'If checked, users can add an image gallery to their listings.', 'wpcasa-dashboard' ),
				'priority'			=> 10
			),
			'listing_images_nr_allowed'	=> array(
				'id'                => 'listing_images_nr_allowed',
				'name'              => __( 'Max Images', 'wpcasa-dashboard' ),
				'type'              => isset( $meta_boxes['listing_images'] ) ? 'text_small' : 'hidden',
				'attributes' => array(
					'type'		=> isset( $meta_boxes['listing_images'] ) ? 'number' : 'hidden',
					'pattern'	=> '\d*',
					'min'		=> 1,
				),
				'description'       => __( 'Enter the maximum of images displayed on listing pages.', 'wpcasa-dashboard' ),
				'attributes' => array(
					'data-conditional-id' 		=> 'listing_images_allowed',
					'data-conditional-value'	=> '1',
				),
				'default'			=> '10',
				'priority'			=> 20
			),
			'listing_location_allowed'	=> array(
				'id'                => 'listing_location_allowed',
				'name'              => '',
				'type'              => 'checkbox',
				'label_cb'			=> __( 'Location', 'wpcasa-dashboard' ),
				'description'       => __( 'If checked, users can add a map to their listings.', 'wpcasa-dashboard' ),
				'priority'			=> 30
			),
		);

		// Apply filter and order fields by priority
		$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_package_permissions_fields', $fields ) );

		// Set meta box

		$meta_box = array(
			'id'			=> 'package_permissions',
            'title'			=> __( 'Permissions', 'wpcasa-dashboard' ),
            'object_types'	=> array( 'package' ),
            'context'		=> 'normal',
            'priority'		=> 'default',
            'show_names'	=> true,
			'fields'		=> $fields
		);
		
		// Add meta box to general meta box array		
		$meta_boxes = array_merge( $meta_boxes, array( 'wpsight_package_permissions' => apply_filters( 'wpsight_meta_box_package_permissions', $meta_box ) ) );

		return $meta_boxes;

	}

    /**
	 *	custom_columns()
	 *
     *	Custom admin columns for packages.
     *	
     *	@access	public
     *	@return	array
     *
     *	@since	1.1.0
     */
    public static function custom_columns() {

        $fields = array(
            'cb' 				=> '<input type="checkbox" />',
            'title' 			=> __( 'Title', 'wpcasa-dashboard' ),
            'price' 			=> __( 'Price', 'wpcasa-dashboard' ),
            'duration' 		    => __( 'Duration', 'wpcasa-dashboard' ),
            'max_listings' 		=> __( 'Max Listings', 'wpcasa-dashboard' ),
            'type' 			    => __( 'Type', 'wpcasa-dashboard' ),
            'private'    		=> __( 'Private', 'wpcasa-dashboard' ),
            'date' 			    => __( 'Date', 'wpcasa-dashboard' ),
        );
        
        // Don't show private option when pricing table add-on is not active
        
        if( ! class_exists( 'WPSight_Pricing_Tables' ) )
        	unset( $fields['private'] );

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

            case 'price':
                $price = get_post_meta( get_the_id(), 'package_price', true );

                if ( ! empty( $price ) ) {
                    $price_formatted = WPSight_Dashboard_Packages::get_package_formatted_price( get_the_id() );
                    echo $price_formatted;
                } else {
                    echo '-';
                }
                break;

            case 'duration':
                $duration = WPSight_Dashboard_Packages::get_package_duration( get_the_id(), true );

                if ( ! empty( $duration ) ) {
                    echo $duration;
                } else {
                    echo '-';
                }
                break;

            case 'max_listings':
                $max_listings = get_post_meta( get_the_id(), 'package_max_listings', true );

                if ( ! empty( $max_listings ) ) {
                    echo $max_listings;
                } else {
                    echo '-';
                }
                break;

            case 'private':
                $is_private = get_post_meta( get_the_id(), 'package_private', true );
                echo $is_private ? '<span class="dashicons dashicons-yes" title="' . esc_attr__( 'Yes', 'wpcasa-dashboard' ) . '"></span>' : '&ndash;';
                break;

            case 'type':
                if ( WPSight_Dashboard_Packages::is_package_free( get_the_id() ) ) {
                    _ex( 'Free', 'package type', 'wpcasa-dashboard' );
                } elseif ( WPSight_Dashboard_Packages::is_package_regular( get_the_id() ) ) {
	                _ex( 'Regular', 'package type', 'wpcasa-dashboard' );
                } elseif ( WPSight_Dashboard_Packages::is_package_simple( get_the_id() ) ) {
	                _ex( 'Simple (one-time)', 'package type', 'wpcasa-dashboard' );
                } elseif ( WPSight_Dashboard_Packages::is_package_trial( get_the_id() ) ) {
	                _ex( 'Trial', 'package type', 'wpcasa-dashboard' );
                }
                break;

        }

    }

}

WPSight_Dashboard_Post_Type_Package::init();
