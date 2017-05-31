<?php
/**
 * One-Click Demo Importer
 *
 * @package WPSight Bootstrap
 *
 */
if ( ! class_exists( 'WPSight_Demo_Import' ) ) {

	/**
	 * Class WPSight_Demo_Import
	 */
	final class WPSight_Demo_Import {

		/**
		 *	Initialize class
		 */
		public static function init() {
			
			define( 'WPSIGHT_DEMO_IMPORT_FILES', 'import' );
			
			add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ) );
			
			add_action( 'tgmpa_register', array( __CLASS__, 'register_plugins' ) );
			add_action( 'admin_init', array( __CLASS__, 'process' ) );
		}

		/**
		 *	admin_menu()
		 *
		 *	Add demo importer menu item
		 *	
		 *	@access	public
		 *
		 *	@since	1.0.0
		 */
		public static function admin_menu() {
			add_submenu_page( 'themes.php', sprintf( __( '%s Demo Import', 'wpcasa-madrid'), WPSIGHT_NAME ), __( 'Demo Import', 'wpcasa-madrid' ), 'manage_options', 'wpsight-demo-import', array( __CLASS__, 'template' ) );
		}
		
		/**
		 *	admin_enqueue_scripts()
		 *
		 *	Load additional admin scripts
		 *	
		 *	@access	public
		 *
		 *	@since	1.0.0
		 */
		public static function admin_enqueue_scripts() {
			
			// Script debugging?
			$suffix = SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_style( 'demo-import', get_template_directory_uri() . '/assets/css/wpsight-demo-import' . $suffix . '.css', false, WPSIGHT_MADRID_VERSION, 'all'  );
			wp_enqueue_script( 'demo-import', get_template_directory_uri() . '/assets/js/wpsight-demo-import' . $suffix . '.js', false, WPSIGHT_MADRID_VERSION, true );

		}
		
		/**
		 *	required_plugins()
		 *
		 *	Get required plugins for importer
		 *	
		 *	@access	public
		 *	@return	array
		 *
		 *	@since	1.0.0
		 */
		public static function required_plugins() {
			
			$plugins = array(
		
				array (
					'name'			=> 'WordPress Importer',
					'slug'			=> 'wordpress-importer',
					'required'		=> false,
					'path'			=> 'wordpress-importer/wordpress-importer.php',
				),
				
				array (
					'name'			=> 'Customizer Export/Import',
					'slug'			=> 'customizer-export-import',
					'required'		=> false,
					'path'			=> 'customizer-export-import/customizer-export-import.php',
				),
				
				array (
					'name'			=> 'Widget Importer & Exporter',
					'slug'			=> 'widget-importer-exporter',
					'required'		=> false,
					'path'			=> 'widget-importer-exporter/widget-importer-exporter.php'
				),
				
				array (
					'name'			=> 'Display Widgets',
					'slug'			=> 'display-widgets',
					'required'		=> false,
					'path'			=> 'display-widgets/display-widgets.php'
				),
		
			);
			
			return apply_filters( 'wpsight_demo_import_required_plugins', $plugins );
		
		}
		
		/**
		 *	register_plugins()
		 *
		 *	Install plugins
		 *	
		 *	@access public
		 *
		 *	@since	1.0.0
		 */
		public static function register_plugins() {
			tgmpa( self::required_plugins() );
		}

		/**
		 *	get_import_step_file()
		 *
		 *	Get file of a specific import step
		 *	
		 *	@access	public
		 *	@param	string	$import_step
		 *	@return	string
		 *
		 *	@since	1.0.0
		 */
		public static function get_import_step_file( $import_step = false ) {
		
			if( ! $import_step )
				return false;
			
			$import_steps = self::get_import_steps();
			
			if( isset( $import_steps[ $import_step ]['file'] ) )
				return $import_steps[ $import_step ]['file'];
			
			return false;
		
		}
		
		/**
		 *	get_import_steps()
		 *
		 *	Get list of all import steps
		 *	
		 *	@access	public
		 *	@return	array
		 *
		 *	@since	1.0.0
		 */
		public static function get_import_steps() {
			
			$import_steps = array(

				'content' => array(
					'id'            => 'content',
					'file'          => get_template_directory() . '/' . WPSIGHT_DEMO_IMPORT_FILES . '/demo-content.xml',
					'title'         => __( 'WordPress Content', 'wpcasa-madrid' ),
					'description'   => __( '<strong>Please be patient!</strong> The attached images of the example listings, posts and pages are downloaded from our demo server.', 'wpcasa-madrid' ),
					'action'        => esc_url( wp_nonce_url( add_query_arg( array( 'wpsight-demo-import-step' => 'content', 'import' => 'true' ) ), 'wpsight_import_step_content_nonce', '_wpsight_import_step_content_nonce' ) ),
				),
				
				'theme-options' => array(
					'id'            => 'theme-options',
					'file'          => get_template_directory() . '/' . WPSIGHT_DEMO_IMPORT_FILES . '/demo-options.dat',
					'title'         => __( 'Theme Options', 'wpcasa-madrid'),
					'description'   => __( 'Here we import options and theme modificactions from the theme customizer. Change them to your needs later.', 'wpcasa-madrid' ) . '<br />',
					'action'        => esc_url( wp_nonce_url( add_query_arg( 'wpsight-demo-import-step', 'theme-options' ), 'wpsight_import_step_theme_options_nonce', '_wpsight_import_step_theme_options_nonce' ) ),
				),
				
				'widgets-content' => array(
					'id'            => 'widgets-content',
					'file'          => get_template_directory() . '/' . WPSIGHT_DEMO_IMPORT_FILES . '/demo-widgets.wie',
					'title'         => __( 'Widgets &amp; Settings', 'wpcasa-madrid'),
					'description'   => __( 'In this step widgets including all their settings are imported to the widget areas of this theme.', 'wpcasa-madrid' ),
					'action'        => esc_url( wp_nonce_url( add_query_arg( 'wpsight-demo-import-step', 'widgets-content' ), 'wpsight_import_step_widgets_content_nonce', '_wpsight_import_step_widgets_content_nonce' ) ),
				),
				
				/**
				
				'widget-logic' => array(
					'id'            => 'widget-logic',
					'file'          => get_template_directory() . '/' . WPSIGHT_DEMO_IMPORT_FILES . '/demo-widget-logic.json',
					'title'         => __( 'Widget Logic', 'wpcasa-madrid'),
					'description'   => __( 'Next we import conditional tags of the Widget Logic plugin for more flexibility.', 'wpcasa-madrid' ) . '<br />' . sprintf( __( 'Required plugin: %s', 'wpcasa-madrid' ), '<a href="https://wordpress.org/plugins/widget-logic/" target="_blank">Widget Logic</a>' ),
					'action'        => esc_url( wp_nonce_url( add_query_arg( 'wpsight-demo-import-step', 'widget-logic' ), 'wpsight_import_step_widget_logic_nonce', '_wpsight_import_step_widget_logic_nonce' ) ),
				),
				
				*/
				
				'custom-options' => array(
					'id'            => 'custom-options',
					'file'          => get_template_directory() . '/' . WPSIGHT_DEMO_IMPORT_FILES . '/demo-custom.json',
					'title'         => __( 'General Options', 'wpcasa-madrid'),
					'description'   => __( 'Finally we import some custom options with basic WordPress settings.', 'wpcasa-madrid' ),
					'action'        => esc_url( wp_nonce_url( add_query_arg( 'wpsight-demo-import-step', 'custom-options' ), 'wpsight_import_step_custom_options_nonce', '_wpsight_import_step_custom_options_nonce' ) ),
				),

			);
			
			return apply_filters( 'wpsight_demo_import_steps', $import_steps );

		}
		
		/**
		 *	template()
		 *
		 *	Display the demo importer template
		 *	
		 *	@access public
		 *
		 *	@since	1.0.0
		 */
		public static function template() {
		   
		   ?><div class="wrap">
				<h2><?php printf( __( '%s Demo Import', 'wpcasa-madrid'), WPSIGHT_NAME ); ?></h2>
			
				<div class="wpsight-demo-import-wrapper">
				
					<?php if ( false == self::exports_exists() ) : ?>
					
						<div class="wpsight-demo-import-notice notice notice-error">
						
							<p><strong><?php _e( 'Import Files Missing!', 'wpcasa-madrid' ); ?></strong>
							<br /><?php _e( 'Seems that the import files that are necessary for the import process are missing. Please check that or contact support.', 'wpcasa-madrid' ); ?></p>

						</div>

					<?php elseif ( true === self::is_import_complete() ) : ?>
					
						<div class="wpsight-demo-import-notice notice notice-success">
						
							<p><strong><?php _e( 'The import is complete!', 'wpcasa-madrid' ); ?></strong> <?php _e( 'All import steps have been processed sucessfully. Enjoy the theme!', 'wpcasa-madrid' ); ?></p>

						</div>

					<?php elseif ( 0 === count( self::get_missing_plugins() ) ) : ?>
					
						<div class="wpsight-demo-import-notice notice notice-info is-dismissible">					
							<p><?php _e( 'Please do not reload the browser window during the import process.', 'wpcasa-madrid' ); ?></p>						
						</div>
					
						<a class="wpcasa-demo-import-run button button-primary button-hero">
							<?php if ( true === self::are_steps_missing() ) : ?>
								<span><?php _e( 'Run Missing Steps', 'wpcasa-madrid' ); ?></span>
							<?php else: ?>
								<span><?php _e( 'Run Demo Import', 'wpcasa-madrid' ); ?></span>
							<?php endif; ?>
						</a>

					<?php else : ?>
					
						<div class="wpsight-demo-import-notice notice notice-error">
						
							<p><strong><?php _e( 'Plugins Missing!', 'wpcasa-madrid' ); ?></strong>
							<br /><?php _e( 'Please install and activate the plugins listed below. They are necessary to complete the import process.', 'wpcasa-madrid' ); ?></p>
							
							<?php $plugins = self::get_missing_plugins(); ?>
							<?php $plugins_count = count( $plugins ); ?>
							<?php $index = 0; ?>
			
							<?php if ( 0 !== $plugins_count ) : ?>
								<ul class="wpsight-demo-import-missing-plugins-list">
									<?php foreach( $plugins as $plugin ): ?>
										<li>
											<?php add_thickbox(); ?>
											<a class="thickbox onclick" href="<?php echo admin_url( sprintf( 'plugin-install.php?tab=plugin-information&plugin=%s&TB_iframe=true&width=772&height=772', esc_attr( $plugin['slug'] ) ) ); ?>" title="<?php echo esc_attr( $plugin['name'] ); ?>"><?php echo esc_attr( $plugin['name'] ); ?></a>
											<?php if ( $index + 1 != $plugins_count ) : ?>, <?php endif; ?>
										</li>
										<?php $index++; ?>
									<?php endforeach; ?>
								</ul><!-- /.wpsight-demo-import-missing-plugins-list -->
							<?php endif; ?>

						</div>

					<?php endif; ?>
			
					<?php $steps = self::get_import_steps(); ?>

					<?php if ( ! empty( $steps ) ) : ?>

						<ul class="wpsight-demo-import-steps">
							<?php $index = 1; foreach ( $steps as $step ) : ?>
								<?php if ( self::import_file_found( $step['file' ] ) ) : ?>
									<?php if ( '1' == get_option( 'wpsight_demo_import_step_' . $step['id'], false ) ) : ?>
										<li class="wpsight-demo-import-step completed">
									<?php else: ?>
										<li class="wpsight-demo-import-step" data-action="<?php echo esc_attr( $step['action'] ); ?>">
									<?php endif; ?>
									
										<div class="wpsight-demo-import-step-inner">
											<div class="wpsight-demo-import-step-counter"><?php echo esc_attr( $index ); ?>.</div>
											
											<div class="wpsight-demo-import-step-content">
												<div class="wpsight-demo-import-step-title"><?php echo $step['title']; ?></div>
											
												<div class="wpsight-demo-import-step-description"><?php echo $step['description']; ?></div><!-- /.wpsight-demo-import-step-description -->
											</div><!-- /.wpsight-demo-import-step-content -->
										</div>
									</li><!-- /.wpsight-demo-import-step -->
			
									<?php $index++; ?>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul><!-- /.wpsight-demo-import-steps -->

					<?php endif; ?>

				</div><!-- /.wpsight-demo-import-wrapper -->
			</div><!-- /.wrap --><?php
		}
		
		/**
		 *	process()
		 *
		 *	Process all import steps
		 *	
		 *	@access public
		 *
		 *	@since	1.0.0
		 */
		public static function process() {

			if ( ! empty( $_GET['wpsight-demo-import-step'] ) ) {

				switch ( $_GET['wpsight-demo-import-step'] ) {

					case 'content':
						self:: process_content();
						break;

					case 'theme-options':
						self::process_theme_options();
						break;

					case 'widgets-content':
						self::process_widgets_content();
						break;
	
					case 'widget-logic':
						self::process_widget_logic();
						break;

					case 'custom-options':
						self::process_custom_options();
						break;

					default:
						exit();
						break;

				}
				
				update_option( 'wpsight_demo_import_step_' . $_GET['wpsight-demo-import-step'], true );
				
				// Action hook
				do_action( 'wpsight_demo_import_process_step', $_GET['wpsight-demo-import-step'] );
				
				exit();

			}

		}
		
		/**
		 *	process_content()
		 *
		 *	Import main content with WordPress Importer
		 *	
		 *	@access public
		 *
		 *	@since	1.0.0
		 */
		public static function process_content() {
			
			// Action hook
			do_action( 'wpsight_demo_import_process_content_before' );
			
			$file_path = self::get_import_step_file( 'content' );

			$import = new WP_Import();
			$import->fetch_attachments = true;
			$import->import( $file_path );
			
			// Action hook
			do_action( 'wpsight_demo_import_process_content_after' );

		}
		
		/**
		 *	process_theme_options()
		 *
		 *	Import theme options
		 *	
		 *	@access public
		 *
		 *	@since	1.0.0
		 */
		public static function process_theme_options() {
			
			// Action hook
			do_action( 'wpsight_demo_import_process_theme_options_before' );
			
			$file_path = self::get_import_step_file( 'theme-options' );

			$data = unserialize( file_get_contents( $file_path ) );
			
			if ( isset( $data['options'] ) ) {
				foreach ( $data['options'] as $option_key => $option_value ) {
					update_option( $option_key, $option_value );
				}
			}
			
			if ( isset( $data['mods'] ) ) {
				foreach ( $data['mods'] as $key => $val ) {
					set_theme_mod( $key, $val );
				}
			}
			
			// Action hook
			do_action( 'wpsight_demo_import_process_theme_options_after' );

		}
		
		/**
		 *	process_widgets_content()
		 *
		 *	Import widget content
		 *	
		 *	@access public
		 *
		 *	@since	1.0.0
		 */
		public static function process_widgets_content() {
			
			// Action hook
			do_action( 'wpsight_demo_import_process_widgets_before' );
			
			$file_path = self::get_import_step_file( 'widgets-content' );
			
			// wie_process_import_file( $file_path );

			// Get file contents and decode			
			$data = json_decode( file_get_contents( $file_path ) );

			// Import the widget data
			wie_import_data( $data );
			
			// Action hook
			do_action( 'wpsight_demo_import_process_widgets_after' );

		}
		
		/**
		 *	process_widget_logic()
		 *
		 *	Import widget logic options
		 *	
		 *	@access public
		 *
		 *	@since	1.0.0
		 */
		public static function process_widget_logic() {
			
			// Action hook
			do_action( 'wpsight_demo_import_process_widget_logic_before' );
			
			$file_path = self::get_import_step_file( 'widgets-content' );

			$options = json_decode( file_get_contents( $file_path ), true );

			update_option( 'widget_logic', $options );
			
			// Action hook
			do_action( 'wpsight_demo_import_process_widget_logic_after' );

		}
		
		/**
		 *	process_custom_options()
		 *
		 *	Import custom options including
		 *	general WordPress settings and menus
		 *	
		 *	@access public
		 *
		 *	@since	1.0.0
		 */
		public static function process_custom_options() {
			
			// Action hook
			do_action( 'wpsight_demo_import_process_custom_options_before' );
			
			$file_path = self::get_import_step_file( 'custom-options' );

			$options = json_decode( file_get_contents( $file_path ), true );
			
			if( $options && is_array( $options ) ) {
			
				foreach ( $options as $key => $value ) {
				
					if ( 'locations' != $key ) {
						update_option( $key, $value );
					} else {
						
						$locations = array();
						
						foreach ( $value as $menu_location => $menu_name ) {
							$menu = wp_get_nav_menu_object( $menu_name );
							$locations[ $menu_location ] = $menu->term_id;
						}
					
						set_theme_mod( 'nav_menu_locations', $locations );
				
					}
				
				}
			
			}
			
			save_mod_rewrite_rules();
			flush_rewrite_rules( true );
			
			// Action hook
			do_action( 'wpsight_demo_import_process_custom_options_after' );

		}
		
		/**
		 *	import_file_found()
		 *
		 *	Helper function to check if an import file exists.
		 *	
		 *	@access	public
		 *	@param	$file	string
		 *	@return	bool
		 */
		public static function import_file_found( $file ) {			
			return file_exists( $file );
		}
		
		/**
		 *	get_missing_plugins()
		 *
		 *	Helper function that returns list of missing plugins
		 *	
		 *	@access public
		 *	@return bool|array
		 */
		public static function get_missing_plugins() {

			$plugins = self::required_plugins();
			$missing = array();
			
			foreach ( $plugins as $plugin ) {
				if ( ! is_plugin_active( $plugin['path'] ) ) {
					$missing[] = $plugin;
				}
			}
			
			return $missing;

		}
		
		/**
		 *	is_import_complete()
		 *
		 *	Checks if all steps has been processed and import is complete
		 *	
		 *	@access public
		 *	@return bool
		 *
		 *	@since	1.0.0
		 */
		public static function is_import_complete() {

			$steps = self::get_import_steps();
			
			foreach( $steps as $step ) {

				$step_complete = get_option( 'wpsight_demo_import_step_' . $step['id'], false );
				
				if ( self::import_file_found( $step['file'] ) && '1' !==  $step_complete ) {
					return false;
				}

			}
			
			// Action hook
			do_action( 'wpsight_demo_import_complete' );
		
			return true;

		}
		
		/**
		 *	are_steps_missing()
		 *
		 *	Checks if steps are missing when they are available
		 *	
		 *	@access public
		 *	@return bool
		 *
		 *	@since	1.0.0
		 */
		public static function are_steps_missing() {
			
			$steps = self::get_import_steps();
			$count_completed = 0;
			$count_available = 0;
			
			foreach( $steps as $step ) {

				$step_complete = get_option( 'wpsight_demo_import_step_' . $step['id'], false );
				
				if ( self::import_file_found( $step['file'] ) ) {

					$count_available++;
					
					if ( '1' !==  $step_complete ) {
						$count_completed++;
					}

				}

			}
			
			if ( 0 != $count_completed && $count_completed != $count_available )
				return true;
			
			return false;

		}
		
		/**
		 *	exports_exists()
		 *
		 *	Check if imports folder exists
		 *	
		 *	@access	public
		 *	@return	bool
		 *
		 *	@since	1.0.0
		 */
		public static function exports_exists() {
			return file_exists( get_template_directory() . '/' . WPSIGHT_DEMO_IMPORT_FILES );
		}

	}

	WPSight_Demo_Import::init();

}
