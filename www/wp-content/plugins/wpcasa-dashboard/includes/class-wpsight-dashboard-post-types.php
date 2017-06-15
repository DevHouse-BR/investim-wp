<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Class WPSight_Dashboard_Post_Types
 */
class WPSight_Dashboard_Post_Types {

    /**
	 *	Initialize class
	 */
	public static function init() {
		self::includes();
    }

    /**
	 *	includes()
	 *
     *	Loads post types
     *	
     *	@access	public
     *	@uses	require_once()
     *
	 *	@since	1.1.0
     */
    public static function includes() {
        require_once WPSIGHT_DASHBOARD_PLUGIN_DIR . '/includes/post-types/class-wpsight-dashboard-post-type-package.php';
        require_once WPSIGHT_DASHBOARD_PLUGIN_DIR . '/includes/post-types/class-wpsight-dashboard-post-type-transaction.php';
        require_once WPSIGHT_DASHBOARD_PLUGIN_DIR . '/includes/post-types/class-wpsight-dashboard-post-type-user.php';
    }
    
}

WPSight_Dashboard_Post_Types::init();
