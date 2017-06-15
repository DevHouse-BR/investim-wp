<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Dashboard_General class
 */
class WPSight_Dashboard_General {

	/**
	 * Initialize class
	 */
	public static function init() {
		add_action( 'wp', array( __CLASS__, 'check' ) );
		add_action( 'wp_footer', array( __CLASS__, 'notifications' ) );
	}
	
	/**
	 *	notifications()
	 *
	 *	Display user notifications.
	 *	
	 *	@access	public
	 *
	 *	@since	1.1.0
	 */
	public static function notifications() {
		wpsight_get_template( 'messages.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
	}
	
	/**
	 *	get_listings_by_user_query()
	 *
	 *	Get listings by user.
	 *	
	 *	@access	public
	 *	@param	int	$user_id
	 *	@uses	wpsight_post_type()
	 *	@uses	wpsight_statuses()
	 *	@return	WP_Query
	 *
	 *	@since	1.1.0
	 */
	public static function get_listings_by_user_query( $user_id ) {
		
		$listings = new WP_Query( array(
			'author'            => $user_id,
			'post_type'         => array( wpsight_post_type() ),
			'posts_per_page'    => -1,
			'post_status'       => array_keys( wpsight_statuses() ),
		) );
		
		return apply_filters( 'wpsight_dashboard_get_listings_by_user_query', $listings );

	}

    /**
	 *	unpublish()
	 *
     *	Unpublish listings when no longer valid.
     *	
     *	@access	public
     *	@param	array	$listings
     *	@uses	$wpdb->get_results()
     *
     *	@since	1.1.0
     */
    public static function unpublish( $listings ) {

        $items_to_unpublish = array();

        foreach ( $listings as $item ) {
            if ( $item->post_status != 'publish' ) {
                continue;
            }

            $items_to_unpublish[] += $item->ID;
        }

        if ( count( $items_to_unpublish ) > 0 ) {
            global $wpdb;

            $sql = 'UPDATE ' . $wpdb->prefix . 'posts SET post_status = \'pending_payment\' WHERE ID IN (' . implode( ",", $items_to_unpublish ) . ');';

            $wpdb->get_results( $sql );
        }

    }

    /**
	 *	publish()
	 *
     *	Publish listings when valid.
     *	
     *	@access	public
     *	@param	array	$listings
	 *	@uses	$wpdb->get_results()
	 *
	 *	@since	1.1.0
     */
    public static function publish( $listings ) {

        $review_before_publish = wpsight_get_option( 'dashboard_approval' );

        $items_to_publish = array();

        foreach ( $listings as $item ) {	        
            if ( $item->post_status == 'publish' ) {
                continue;
            }

            if ( $review_before_publish && $item->post_status == 'pending_payment' || ! $review_before_publish ) {
                $items_to_publish[] += $item->ID;
            }
        }

        if ( count( $items_to_publish ) > 0 ) {
            global $wpdb;

            $sql = 'UPDATE ' . $wpdb->prefix . 'posts SET post_status = \'publish\' WHERE ID IN (' . implode( ",", $items_to_publish ) . ');';

            $wpdb->get_results( $sql );
        }

    }

    /**
	 *	check()
	 *
     *	Check if listings are valide and set
     *	status depending on user packages.
     *	
     *	@access	public
     *	@uses	wpsight_get_option()
     *	@uses	get_users()
     *	@uses	self::get_listings_by_user_query()
     *	@uses	WPSight_Dashboard_Packages::is_package_valid_for_user()
     *	@uses	WPSight_Dashboard_Packages::get_remaining_listings_count_for_user()
     *	@uses	self::publish()
     *	@uses	self::unpublish()
     *
     *	@since	1.1.0
     */
    public static function check() {

        if ( 'packages' != wpsight_get_option( 'dashboard_payment_options' ) )
            return;

        $options = array();
        $users = get_users( $options );

        foreach ( $users as $user ) {

            $query = self::get_listings_by_user_query( $user->ID );
            
            $items = $query->posts;

            if ( count( $items ) == 0 )
                continue;

            // Check if package is valid
            $is_package_valid = WPSight_Dashboard_Packages::is_package_valid_for_user( $user->ID );

            if ( ! $is_package_valid ) {
                // Unpublish all listings
                self::unpublish( $items );
            } else {
                // Get remaining posts available to create
                $remaining = WPSight_Dashboard_Packages::get_remaining_listings_count_for_user( $user->ID );

                if ( 'unlimited' == $remaining || $remaining >= 0 ) {	                
                    // Publish all listings
                    self::publish( $items );
                } else {	                
                    // Publish newest available listings
                    self::publish( array_slice( $items, 0, count( $items ) - abs( $remaining ) ) );

                    // Unpublish oldest unavailable listings
                    self::unpublish( array_slice( $items, count( $items ) - abs( $remaining ), count( $items ) ) );
                }
            }
        }
    }
    
    /**
	 *	get_metabox_key()
	 *
	 *	Returns metabox key from its id by
	 *	removing listing and post type prefixes.
	 *	
	 *	@access	public
	 *	@param	string $metabox_id
	 *	@param	string $post_type
	 *	@return	string
	 *
	 *	@since	1.1.0
	 */
	public static function get_metabox_key( $metabox_id, $post_type ) {

		$parts = explode( '_', $metabox_id );

		if ( strpos( $metabox_id, $post_type ) === strlen( $parts[0] ) + 1 ) {

			unset( $parts[0] ); // unset listing
			unset( $parts[1] ); // unset post type

			$metabox_key = implode( '_', $parts ); // the rest is metabox key

			return $metabox_key;
		}

		return $metabox_id;

	}

	/**
	 *	array_unique_multidimensional()
	 *
	 *	Helper function to make multi dimensional array
	 *	
	 *	@access	public
	 *	@param	$input	array
	 *	@return	array
	 *
	 *	@since	1.1.0
	 */
	public static function array_unique_multidimensional( $input ) {
		$serialized = array_map( 'serialize', $input );
		$unique = array_unique( $serialized );
		return array_intersect_key( $input, $unique );
	}
	
	/**
	 *	get_current_url()
	 *
	 *	Helper function to get current URL of any page.
	 *	
	 *	@access	public
	 *	@uses	add_query_arg()
	 *	@uses	home_url()
	 *	@return	string
	 *
	 *	@since	1.1.0
	 */
	public static function get_current_url() {
		global $wp;

		return home_url( add_query_arg( array(),$wp->request ) );

	}

}

WPSight_Dashboard_General::init();
