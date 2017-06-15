<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Class WPSight_Dashboard_Packages
 */
class WPSight_Dashboard_Packages {

    /**
	 *	Initialize class
	 */
	public static function init() {
		
		add_action( 'user_register', array( __CLASS__, 'set_default_package_for_user' ), 10, 1 );
		add_filter( 'wpsight_dashboard_is_user_allowed_to_add_submission', array( __CLASS__, 'can_user_create_submission' ), 10, 2 );		
		add_filter( 'wpsight_dashboard_meta_boxes', array( __CLASS__, 'listing_images_note' ) );		
		add_filter( 'get_post_metadata', array( __CLASS__, 'listing_images_gallery_limit' ), 10, 4 );
		
    }

    /**
	 *	get_package()
	 *
     *	Get a specific (active) package by ID.
     *	
     *	@access	public
     *	@param	interger	$package_id
     *	@uses	get_post()
     *	@return	object
     *
     *	@since	1.1.0
     */
    public static function get_package( $package_id ) {

        $post = get_post( $package_id );

        if( $post->post_type != 'package' || $post->post_status != 'publish' )
            return false;

        return $post;
    }

    /**
	 *	package_exists()
	 *		    
     *	Check if a specific package exists by ID.
     *	
     *	@access	public
     *	@param	integer	$package_id
     *	@return bool
     */
    public static function package_exists( $package_id = false ) {

        $package = self::get_package( $package_id );

        return is_object( $package );
    }
    
    /**
	 *	get_package_duration()
	 *
     *	Get the duration of a specific package by ID.
     *	
     *	@access	public
     *	@param	integer	$package_id
     *	@param	bool	$as_string
     *	@uses	self::get_package_durations()
     *	@return	array|string
     *
     *	@since	1.1.0
     */
    public static function get_package_duration( $package_id, $as_string = false ) {

        $duration = get_post_meta( $package_id, 'package_duration', true );

        if ( empty( $duration ) )
            return $as_string ? '' : false;

        $durations = self::get_package_durations_choices();

        $duration_formatted = ! empty ( $durations[ $duration ] ) ? $durations[ $duration ] : '';

        return $as_string ? $duration_formatted : $duration;

    }
	
	/**
	 *	get_package_durations()
	 *
     *	Get package durations.
     *	
     *	@access	public
     *	@param	bool	$show_none
     *	@return	array
     */
    public static function get_package_durations() {
	    
	    $package_durations = array(
		    '1day'	=> array(
		    	'key'		=> 'day',
		    	'display'	=> sprintf( _n( '%s day', '%s days', '1', 'wpcasa-dashboard' ), '1' ),
		    	'length'	=> '+1 day'
		    ),
		    '3days'	=> array(
		    	'key'		=> '3days',
		    	'display'	=> sprintf( _n( '%s day', '%s days', '3', 'wpcasa-dashboard' ), '3' ),
		    	'length'	=> '+3 day'
		    ),
		    '1week'	=> array(
		    	'key'		=> '1week',
		    	'display'	=> sprintf( _n( '%s week', '%s weeks', '1', 'wpcasa-dashboard' ), '1' ),
		    	'length'	=> '+1 week'
		    ),
		    '2weeks'	=> array(
		    	'key'		=> '2weeks',
		    	'display'	=> sprintf( _n( '%s week', '%s weeks', '2', 'wpcasa-dashboard' ), '2' ),
		    	'length'	=> '+2 week'
		    ),
		    'month' => array(
		    	'key'		=> 'month',
		    	'display'	=> sprintf( _n( '%s month', '%s months', '1', 'wpcasa-dashboard' ), '1' ),
		    	'length'	=> '+1 month'
		    ),
		    '2months' => array(
		    	'key'		=> '2months',
		    	'display'	=> sprintf( _n( '%s month', '%s months', '2', 'wpcasa-dashboard' ), '2' ),
		    	'length'	=> '+2 month'
		    ),
		    '3months' => array(
		    	'key'		=> '3months',
		    	'display'	=> sprintf( _n( '%s month', '%s months', '3', 'wpcasa-dashboard' ), '3' ),
		    	'length'	=> '+3 month'
		    ),
		    '6months' => array(
		    	'key'		=> '6months',
		    	'display'	=> sprintf( _n( '%s month', '%s months', '6', 'wpcasa-dashboard' ), '6' ),
		    	'length'	=> '+6 month'
		    ),
		    'year'	=> array(
		    	'key'		=> 'year',
		    	'display'	=> sprintf( _n( '%s year', '%s years', '1', 'wpcasa-dashboard' ), '1' ),
		    	'length'	=> '+1 year'
		    ),
	    );

        return apply_filters( 'wpsight_dashboard_package_durations', $package_durations );

    }
    
    /**
	 *	get_package_durations_choices()
	 *
     *	Get package durations choices
     *	array( [key] => [display] ).
     *	
     *	@access	public
     *	@param	bool	$show_none
     *	@return	array
     */
    public static function get_package_durations_choices( $show_none = false ) {
	    
	    $package_durations = self::get_package_durations();

        $durations = array();

        if ( $show_none )
            $durations[] = __( 'None', 'wpcasa-dashboard' );

        foreach( $package_durations as $key => $duration ) {
            $durations = array_merge( $durations, array(
                $duration['key'] => $duration['display'],
            ) );
        }

        return $durations;

    }
    
    /**
	 *	get_package_price()
	 *
     *	Get the price of a specific package by ID.
     *	
     *	@access	public
     *	@param	integer	$package_id
     *	@uses	get_post_meta()
     *	@return	bool|float
     *
     *	@since	1.1.0
     */
    public static function get_package_price( $package_id ) {

        $price = get_post_meta( $package_id, 'package_price', true );

        if ( ! isset( $price ) || ! is_numeric( $price ) ) {
            return false;
        }

        return $price;

    }

    /**
	 *	get_package_formatted_price()
	 *
     *	Get the formatted price of a specific package by ID.
     *	
     *	@access	public
     *	@param	integer	$package_id
     *	@uses	self::get_package_price()
     *	@uses	WPSight_Dashboard_Price::format_price()
     *	@return	bool|string
     *
     *	@since	1.1.0
     */
    public static function get_package_formatted_price( $package_id ) {
        $price = self::get_package_price( $package_id );
        return WPSight_Dashboard_Price::format_price( $price );
    }
    
    /**
	 *	get_package_title()
	 *
     *	Get package title optionally including
     *	the duration and package price.
     *	
     *	@acces	public
     *	@param	integer	$package_id
     *	@param	bool	$include_details
     *	@uses	self::get_package()
     *	@uses	self::get_package_formatted_price()
     *	@uses	self::get_package_duration()
     *	@return	string
     *
     *	@since	1.1.0
     */
    public static function get_package_title( $package_id, $include_details = false ) {

        $package = self::get_package( $package_id );

        if( ! $include_details )
            return $package->post_title;

        $price_formatted = self::get_package_formatted_price( $package_id );
        $duration_formatted = self::get_package_duration( $package_id, true );

        $price_and_duration = sprintf( ' (%s/%s)', $price_formatted, $duration_formatted );

        // free package
        $price_and_duration = str_replace( ' (/)', '', $price_and_duration );

        // package without duration
        $price_and_duration = str_replace( '/)', ')', $price_and_duration );

        return trim( sprintf( '%s%s', $package->post_title, $price_and_duration ) );

    }

    /**
	 *	get_package_duration_length()
	 *
     *	Get the length of a specific package duration by ID.
     *	
     *	@access	public
     *	@param	integer	$package_id
     *	@uses	self::get_package_duration()
     *	@return	string|null
     *
     *	@since	1.1.0
     */
    public static function get_package_duration_length( $package_id ) {

        $duration = self::get_package_duration( $package_id );

        if ( empty( $duration ) )
            return null;

        $package_durations = self::get_package_durations();

        foreach( $package_durations as $key => $definition ) {
            if ( $definition['key'] == $duration ) {
                return $definition['length'];
            }
        }

        return null;
    }
    
    /**
	 *	get_packages_choices()
	 *
     *	Return a list of package IDs and titles.
     *	
     *	@access	public
     *	@param	bool	$show_none
     *	@param	bool	$show_trial
     *	@param	bool	$show_free
     *	@param	bool	$show_private
     *	@uses	self::get_packages()
     *	@return array
     *
     *	@since	1.1.0
     */
    public static function get_packages_choices( $show_none = false, $show_trial = false, $show_free = true, $show_private = false ) {

        $packages = self::get_packages( $show_trial, $show_free, $show_private );

        $choices = array();

        if ( $show_none )
            $choices[] = __( 'None', 'wpcasa-dashboard' );

        foreach ( $packages as $package )
            $choices[ $package->ID ] = $package->post_title;

        return $choices;

    }

    /**
	 *	get_packages()
	 *
     *	Return a list of packages.
     *	
     *	@access	public
     *	@param	bool	$include_trial
     *	@param	bool	$include_free
     *	@param	bool	$include_private
     *	@uses	self::get_package_duration()
     *	@uses	self::get_package_price()
     *	@uses	get_post_meta()
     *	@return array
     *
     *	@since	1.1.0
     */
    public static function get_packages( $include_trial = false, $include_free = true, $include_private = false ) {

        $packages_query = new WP_Query( array(
            'post_type'         => 'package',
            'posts_per_page'    => -1,
            'post_status'       => 'publish',
        ) );

        $packages = array();

        foreach ( $packages_query->posts as $package ) {

            $duration = self::get_package_duration( $package->ID );
            $price = self::get_package_price( $package->ID );

            $is_regular = ! empty ( $price ) && $price > 0 && ! empty( $duration );
            $is_simple = ! empty ( $price ) && $price > 0 && empty( $duration );
            $is_trial = ( empty ( $price ) || $price == 0 ) && ! empty( $duration );
            $is_free = ( empty ( $price ) || $price == 0 ) && empty( $duration );
            $is_private = get_post_meta( $package->ID, 'package_private', true );

            if ( $is_regular || $is_simple || $is_trial && $include_trial || $is_free && $include_free ) {
                if ( ! ( $is_private && ! $include_private ) ) {
                    $packages[] = $package;
                }
            }

        }

        return $packages;

    }
    
    /**
	 *	is_package_free()
	 *
     *	Check if a specific package is free.
     *	
     *	@access	public
     *	@param	integer	$package_id
     *	@uses	self::get_package()
     *	@uses	self::get_package_duration()
     *	@uses	self::get_package_price()
     *	@return bool
     *
     *	@since	1.1.0
     */
    public static function is_package_free( $package_id ) {

        $package = self::get_package( $package_id );

        if ( ! $package )
            return false;

        $duration = self::get_package_duration( $package->ID );
        $price = self::get_package_price( $package->ID );

        $is_free = ( empty ( $price ) || $price == 0 ) && empty( $duration );

        return $is_free;

    }

    /**
	 *	is_package_trial()
	 *
     *	Check if a specific package is a trial.
     *	
     *	@access	public
     *	@param	integer	$package_id
     *	@uses	self::get_package()
     *	@uses	self::get_package_duration()
     *	@uses	self::get_package_price()
     *	@return bool
     *
     *	@since	1.1.0
     */
    public static function is_package_trial( $package_id ) {

        $package = self::get_package( $package_id );

        if ( ! $package )
            return false;

        $duration = self::get_package_duration( $package->ID );
        $price = self::get_package_price( $package->ID );

        $is_trial = ( empty ( $price ) || $price == 0 ) && ! empty( $duration );

        return $is_trial;

    }

    /**
	 *	is_package_simple()
	 *
     *	Check if a specific package is meant for one-time submissions.
     *	
     *	@access	public
     *	@param	integer	$package_id
     *	@uses	self::get_package()
     *	@uses	self::get_package_duration()
     *	@uses	self::get_package_price()
     *	@return bool
     *
     *	@since	1.1.0
     */
    public static function is_package_simple( $package_id ) {

        $package = self::get_package( $package_id );

        if ( ! $package )
            return false;

        $duration = self::get_package_duration( $package->ID );
        $price = self::get_package_price( $package->ID );

        $is_simple = ! empty ( $price ) && $price > 0 && empty( $duration );

        return $is_simple;

    }

    /**
	 *	is_package_regular()
	 *
     *	Check if a specific package is regular (has price and expires).
     *	
     *	@access	public
     *	@param	integer	$package_id
     *	@uses	self::get_package()
     *	@uses	self::get_package_duration()
     *	@uses	self::get_package_price()
     *	@return bool
     *
     *	@since	1.1.0
     */
    public static function is_package_regular( $package_id ) {

        $package = self::get_package( $package_id );

        if ( ! $package )
            return false;

        $duration = self::get_package_duration( $package->ID );
        $price = self::get_package_price( $package->ID );

        $is_regular = ! empty ( $price ) && $price > 0 && ! empty( $duration );

        return $is_regular;

    }
    
    /**
	 *	get_package_for_user()
	 *
     *	Get the package of a specific user.
     *	
     *	@access	public
     *	@param	integer	$user_id
     *	@uses	get_user_meta()
     *	@uses	get_post()
     *	@return	bool|WP_Post
     *
     *	@since	1.1.0
     */
    public static function get_package_for_user( $user_id ) {

        $package_id = get_user_meta( $user_id, 'package', true );

        if ( empty( $package_id ) )
            return false;

        return get_post( $package_id );

    }
    
    /**
	 *	set_default_package_for_user()
	 *
     *	Set the default package for user (if set in options).
     *	
     *	@access	public
     *	@param	integer	$user_id
     *	@uses	wpsight_get_option()
     *	@uses	self::set_package_for_user()
     *
     *	@since	1.1.0
     */
    public static function set_default_package_for_user( $user_id ) {

        $default_package = wpsight_get_option( 'dashboard_default_package' );

        if ( $default_package )
            self::set_package_for_user( $user_id, $default_package );

    }

    /**
	 *	get_package_valid_date_for_user()
	 *
     *	Get the expiry date of the package of a specific user.
     *	
     *	@access	public
     *	@param	integer	$user_id
     *	@param	bool	$formatted
     *	@uses	get_user_meta()
     *	@uses	get_option()
     *	@return bool|string
     *
     *	@since	1.1.0
     */
    public static function get_package_valid_date_for_user( $user_id, $formatted = true ) {

        $valid = get_user_meta( $user_id, 'package_valid', true );

        if ( $valid == -1 )
            return false;

        if ( ! empty( $valid ) ) {
            if ( ! $formatted ) {
                return $valid;
            } else {
                $date_format = get_option( 'date_format' );
                return date( $date_format, $valid );
            }
        }

        return false;

    }

    /**
	 *	is_package_valid_for_user()
	 *
     *	Check if the package of a specific user is valid.
     *	
     *	@access	public
     *	@param	integer	$user_id
     *	@uses	get_user_meta()
     *	@uses	strtotime()
     *	@return bool
     *
     *	@since	1.1.0
     */
    public static function is_package_valid_for_user( $user_id ) {

        $valid = get_user_meta( $user_id, 'package_valid', true );
        $today = strtotime( 'today' );

        if ( empty( $valid ) )
            return false;

        if ( $valid == -1 )
            return true;

        if ( $today > $valid )
            return false;

        return true;

    }

    /**
	 *	get_remaining_listings_count_for_user()
	 *
     *	Get the remaining listings for a specific
     *	user depending on his package.
     *	
     *	@access	public
     *	@param	integer	$user_id
     *	@uses	self::get_package_for_user()
     *	@uses	self::is_package_valid_for_user()
     *	@uses	get_post_meta()
     *	@uses	wpsight_post_type()
     *	@uses	wpsight_get_user_posts_by_type()
     *	@uses	count()
     *	@return	int|mixed|string
     *
     *	@since	1.1.0
     */
    public static function get_remaining_listings_count_for_user( $user_id ) {

        $package = self::get_package_for_user( $user_id );

        if ( $package && self::is_package_valid_for_user( $user_id ) ) {

            $max_listings = get_post_meta( $package->ID, 'package_max_listings', true );

            if ( '-1' == $max_listings )
                return 'unlimited';
            
            $query = WPSight_Dashboard_General::get_listings_by_user_query( $user_id );            
            $items = $query->posts;

            return $max_listings - count( $items );

        }

        return 0;
    }
    
    /**
	 *	set_package_for_user()
	 *
     *	Set a specific package for a specific user.
     *	
     *	@access	public
     *	@param	integer	$user_id
     *	@param	integer	$package_id
     *	@uses	self::package_exists()
     *	@uses	self::get_package_duration_length()
     *	@uses	strtotime()
     *	@uses	update_user_meta()
     *	@return	bool
     *
     *	@since	1.1.0
     */
    public static function set_package_for_user( $user_id, $package_id ) {

        if ( empty( $user_id ) || empty( $package_id ) )
            return false;

        if ( ! self::package_exists( $package_id ) )
            return false;

        $duration_length = self::get_package_duration_length( $package_id );
        $package_valid = $duration_length ? strtotime( $duration_length ) : -1;

        update_user_meta( $user_id, 'package_valid', $package_valid );
        update_user_meta( $user_id, 'package', $package_id );

        do_action( 'wpsight_dashboard_user_package_was_set', $user_id, $package_id );

        return true;

    }

    /**
	 *	can_user_create_submission()
	 *
     *	Checks if a user is allowed to add
     *	a submission when packages are active.
     *	Function is called by wpsight_dashboard_is_user_allowed_to_add_submission filter.
     *	
     *	@access	public
     *	@param	bool	$can
     *	@param	integer	$user_id
     *	@uses	wpsight_get_option()
     *	@uses	self::is_package_valid_for_user()
     *	@uses	self::get_remaining_listings_count_for_user()
     *	@return bool
     *
     *	@since	1.1.0
     */
    public static function can_user_create_submission( $can, $user_id ) {

        if ( 'packages' == wpsight_get_option( 'dashboard_payment_options' ) ) {

            if ( self::is_package_valid_for_user( $user_id ) && ( self::get_remaining_listings_count_for_user( $user_id ) > 0 || self::get_remaining_listings_count_for_user( $user_id ) === 'unlimited' ) ) {
                return $can;
            }

            return false;
        }

        return $can;
    }
    
    /**
	 *	process_free_package_selection()
	 *
     *	Process payment form when a free package is selected.
     *	
     *	@access	public
     *	@uses	get_current_user_id()
     *	@uses	wp_redirect()
     *	@uses	site_url()
     *	@uses	self::set_package_for_user()
     *
     *	@since	1.1.0
     */
    public static function process_free_package_selection() {

        $action			= ! empty( $_POST['action'] ) ? $_POST['action'] : '';
        $payment_type	= ! empty( $_POST['payment_type'] ) ? $_POST['payment_type'] : '';
        $object_id		= ! empty( $_POST['object_id'] ) ? $_POST['object_id'] : '';
        $price			= ! empty( $_POST['price'] ) ? $_POST['price'] : '';
        $user_id		= get_current_user_id();

        if( $action != 'set_free_package' || empty( $payment_type ) || empty( $object_id ) || empty( $user_id ) )
            return;

        if ( $price != 0 || ! empty( $price ) || $payment_type != 'package' )
            return;

        if ( ! self::is_package_free( $object_id ) ) {
            $_SESSION['messages'][] = array( 'danger', __( 'Package is not free.', 'wpcasa-dashboard' ) );
            wp_redirect( site_url() );
            exit();
        }

        if ( self::set_package_for_user( $user_id, $object_id ) ) {
            $_SESSION['messages'][] = array( 'success', __( 'Package has been set.', 'wpcasa-dashboard' ) );
        } else {
            $_SESSION['messages'][] = array( 'danger', __( 'Could not set package for user.', 'wpcasa-dashboard' ) );
        }

        wp_redirect( site_url() );
        exit();

    }
    
    /**
	 *	listing_images_gallery_limit()
	 *
     *	Filter post meta output to limit listings images
     *	to maximun number allow for corresponding package.
     *	
     *	@access	public
     *	@param	mixed		$meta_value
     *	@param	interger	$object_id
     *	@param	string		$meta_key
     *	@param	bool		$single
     *	@uses	wpsight_get_option()
     *	@uses	get_post()
     *	@uses	get_post_custom()
     *	@uses	maybe_unserialize()
     *	@uses	self::get_package_for_user()
     *	@uses	array_slice()
     *	@return	array
     *
     *	@since	1.1.0
     */
    public static function listing_images_gallery_limit( $meta_value, $object_id, $meta_key, $single ) {
	    
	    if ( 'packages' == wpsight_get_option( 'dashboard_payment_options' ) && '_gallery' == $meta_key && is_singular( wpsight_post_type() ) ) {
	    
	    	$post = get_post( $object_id );
	    	
	    	$custom = get_post_custom( $object_id );
	    	
	    	if( ! empty( $custom['_gallery'][0] ) ) {

	    		$gallery = maybe_unserialize( $custom['_gallery'][0] );
	    		
	    		$package = self::get_package_for_user( $post->post_author );
	    		
	    		if( is_array( $gallery ) && count( $gallery ) > $package->listing_images_nr_allowed ) {
				
	    			$gallery = array_slice( $gallery, 0, $package->listing_images_nr_allowed, true );
	    			
					return array( $gallery );
	    		
	    		}
	    	
	    	}
	    
	    }
	    
    }
    
    /**
	 *	listing_images_note()
	 *
     *	Filter meta box to add note callback.
     *	
     *	@access	public
     *	@param	array	$meta_boxes
     *	@return	array
     *
     *	@since	1.1.0
     */
    public static function listing_images_note( $meta_boxes ) {
	    
	    if ( 'packages' == wpsight_get_option( 'dashboard_payment_options' ) && isset( $meta_boxes['listing_images'] ) )
	    	$meta_boxes['listing_images']['fields']['images']['after_row'] = array( 'WPSight_Dashboard_Packages', 'cb_listing_images_note' );
	    
	    return $meta_boxes;
	    
    }
    
    /**
	 *	cb_listing_images_note()
	 *
     *	Callback function to output not about
     *	image limit in corresponding meta box.
     *	
     *	@access	public
     *	@param	array	$args
     *	@param	object	$field
     *	@uses	get_post()
     *	@uses	self::get_package_for_user()
     *
     *	@since	1.1.0
     */
    public static function cb_listing_images_note( $args, $field ) {
	    
	    $post = get_post( $field->object_id );
	    
	    $package = self::get_package_for_user( $post->post_author );
	    
	    echo '<div class="wpsight-alert bs-callout bs-callout-primary bs-callout-small"><p>' . sprintf( __( 'The first %s will be visible in your listing.', 'wpcasa-dashboard' ), sprintf( _n( '%s image', '%s images', absint( $package->listing_images_nr_allowed ), 'wpcasa-dashboard' ), absint( $package->listing_images_nr_allowed ) ) ) . '</p></div>';
	    
    }
    
}

WPSight_Dashboard_Packages::init();
