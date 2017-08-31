<?php

/*
Plugin Name: WPCasa Mail Alert - Investim
Plugin URI: https://www.devhouse.com.br/
Description: Allow Visitor to subscribe to a mail alert to receive a mail when a new property is added. Customized for Investim
Version: 1.1.6
Author: Sébastien Serre / Leonardo Lima de Vasconcellos
Author URI: http://www.devhouse.com.br
License: GPL2
Tested up to: 4.7.3
Text Domain: wpcasa-mail-alert
Domain Path: /languages
*/

class thfo_mail_alert {
	function __construct() {


		define( 'PLUGIN_VERSION', '1.1.6' );
		define('WPCASAMA_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ));
		define('WPCASAMA_PLUGIN_PATH', plugin_dir_url( __FILE__ ) );

		include_once plugin_dir_path( __FILE__ ) . '/inc/class/thfo_mailalert_load.php';
		include_once plugin_dir_path( __FILE__ ) . '/inc/class/thfo_mailalert_widget.php';
		include_once plugin_dir_path( __FILE__ ) . '/inc/class/thfo_mailalert_search.php';
		include_once plugin_dir_path( __FILE__ ) . '/inc/class/thfo_mailalert_admin_menu.php';
		include_once plugin_dir_path( __FILE__ ) . '/inc/class/thfo_mailalert_unsubscribe.php';

		if(!class_exists('WP_List_Table')){
			include_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		}
		include_once plugin_dir_path( __FILE__ ) . '/inc/class/thfo_investidores_table.php';

		new thfo_mailalert();
		new thfo_mailalert_widget();
		new thfo_mailalert_admin_menu();
		new thfo_mailalert_unsubscribe();

		add_action( 'plugins_loaded', array( $this, 'thfo_load_textdomain' ) );
		add_action( 'admin_init', array( $this, 'thfo_register_admin_style' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'thfo_register_style' ) );
		add_action('admin_notices', array($this, 'wpcasa_mailalert_check_wpcasa'));

		register_activation_hook( __FILE__, array( $this, 'wpcasama_pro_activation' ) );
		register_uninstall_hook( __FILE__, 'wpcasama_pro_deactivation' );


	}

	function wpcasama_pro_activation() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . 'wpcasama_mailalert';

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			usuario VARCHAR (255) NOT NULL,
			name VARCHAR (255),
			company VARCHAR (255),
			email VARCHAR (255) NOT NULL,
			skype varchar(255),
			tel VARCHAR (20),
			mobile varchar(20),
			country varchar(255),
			state varchar(255),
			city VARCHAR (255),
			max_price VARCHAR (10),
			min_price VARCHAR (10),
			third_party_capital tinyint(1),
			prefered_city varchar(255),
			sector varchar(255),
			description text,
			subscription datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			enable tinyint(1) NOT NULL DEFAULT '0',
			  PRIMARY KEY  (id)
			) $charset_collate;";


		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		do_action( 'wpcasama_pro_activation' );
	}

	function wpcasama_pro_deactivation() {
		global $wpdb;
		//$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wpcasama_mailalert;" );
		do_action( 'wpcasama_pro_deactivation' );
	}

	public function thfo_load_textdomain() {
		load_plugin_textdomain( 'wpcasa-mail-alert', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	public function thfo_register_admin_style() {
		wp_enqueue_style( 'thfo_mailalert_admin_style', plugins_url( 'assets/css/admin-styles.css', __FILE__ ) );
	}

	public function thfo_register_style() {
		wp_enqueue_style( 'thfo_mailalert_style', plugins_url( 'assets/css/styles.css', __FILE__ ) );
	}

	public function wpcasa_mailalert_check_wpcasa(){
		if ( ! class_exists('WPSight_Framework')){

			echo '<div class="notice notice-error"><p>' . __('WPCASA is not activated. WPCasa Mail-Alert need it to work properly. Please activate WPCasa.', 'wpcasa-mail-alert' ) . '</p></div>';

			deactivate_plugins(plugin_basename(__FILE__));

		}
	}


}

new thfo_mail_alert();