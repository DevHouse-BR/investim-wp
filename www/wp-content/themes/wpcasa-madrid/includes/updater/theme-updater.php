<?php
/**
 * Easy Digital Downloads Theme Updater
 *
 * @package WPCasa Theme
 */

// Includes the files needed for the theme updater
if ( !class_exists( 'EDD_Theme_Updater_Admin' ) ) {
	include( dirname( __FILE__ ) . '/theme-updater-admin.php' );
}

// Loads the updater classes
$updater = new EDD_Theme_Updater_Admin(

	// Config settings
	$config = array(
		'remote_api_url'	=> WPSIGHT_SHOP_URL, // Site where EDD is hosted
		'item_name'			=> WPSIGHT_MADRID_NAME, // Name of theme
		'theme_slug'		=> WPSIGHT_MADRID_DOMAIN, // Theme slug
		'version'			=> WPSIGHT_MADRID_VERSION, // The current version of this theme
		'author'			=> WPSIGHT_AUTHOR, // The author of this theme
		'download_id'		=> '', // Optional, used for generating a license renewal link
		'renew_url'			=> '' // Optional, allows for a custom license renewal link
	),

	// Strings
	$strings = array(
		'theme-license' 			=> __( 'Theme License', 'wpcasa-madrid' ),
		'enter-key' 				=> __( 'Enter your theme license key.', 'wpcasa-madrid' ),
		'license-key'				=> __( 'License Key', 'wpcasa-madrid' ),
		'license-action'			=> __( 'License Action', 'wpcasa-madrid' ),
		'deactivate-license'		=> __( 'Deactivate License', 'wpcasa-madrid' ),
		'activate-license'			=> __( 'Activate License', 'wpcasa-madrid' ),
		'status-unknown'			=> __( 'License status is unknown.', 'wpcasa-madrid' ),
		'renew'						=> __( 'Renew?', 'wpcasa-madrid' ),
		'unlimited'					=> __( 'unlimited', 'wpcasa-madrid' ),
		'license-key-is-active'		=> __( 'License key is active.', 'wpcasa-madrid' ),
		'expires%s'					=> __( 'Expires %s.', 'wpcasa-madrid' ),
		'%1$s/%2$-sites'			=> __( 'You have %1$s / %2$s sites activated.', 'wpcasa-madrid' ),
		'license-key-expired-%s'	=> __( 'License key expired %s.', 'wpcasa-madrid' ),
		'license-key-expired'		=> __( 'License key has expired.', 'wpcasa-madrid' ),
		'license-keys-do-not-match'	=> __( 'License keys do not match.', 'wpcasa-madrid' ),
		'license-is-inactive'		=> __( 'License is inactive.', 'wpcasa-madrid' ),
		'license-key-is-disabled'	=> __( 'License key is disabled.', 'wpcasa-madrid' ),
		'site-is-inactive'			=> __( 'License key for this site is inactive.', 'wpcasa-madrid' ),
		'license-status-unknown'	=> __( 'License status is unknown.', 'wpcasa-madrid' ),
		'update-notice'				=> __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'wpcasa-madrid' ),
		'update-available'			=> __('<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'wpcasa-madrid' )
	)

);