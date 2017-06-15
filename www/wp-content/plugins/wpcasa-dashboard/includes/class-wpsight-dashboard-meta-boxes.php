<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Dashboard_Meta_Boxes class
 */
class WPSight_Dashboard_Meta_Boxes {

	/**
	 * Initialize class
	 */
	public static function init() {
		
		add_filter( 'wpsight_meta_boxes', array( __CLASS__, 'meta_boxes' ) );
		add_filter( 'wpsight_dashboard_meta_box_allowed', array( __CLASS__, 'meta_box_allowed' ), 10, 3 );
		
		add_filter( 'wpsight_meta_boxes', array( __CLASS__, 'meta_boxes_submission_field_defaults' ) );
		
		if( class_exists( 'WPSight_Admin_Map_UI' ) && ! is_admin() ) {
			
			include( WPSIGHT_ADMIN_MAP_UI_PLUGIN_DIR . '/includes/admin/class-wpsight-admin-map-ui-admin.php' );
			
			// Add custom meta box field types
			add_filter( 'cmb2_render_map', array( __CLASS__, 'render_map' ), 10, 5 );
			add_filter( 'cmb2_sanitize_map', array( 'WPSight_Admin_Map_UI_Admin', 'sanitize_map' ), 10, 4 );
		
			// Add map and map args field to meta box
			add_filter( 'wpsight_meta_box_listing_location_fields', array( 'WPSight_Admin_Map_UI_Admin', 'location_map_fields' ) );
			
			// Remove general WPCasa Google Maps API call
			
			if( isset( $_GET['step'] ) && 'listing_location' == $_GET['step'] ) {
				add_action( 'wp_print_scripts', array( __CLASS__, 'dequeue_scripts' ), 100 );
			}
		
		}

	}
	
	public static function dequeue_scripts() {
		wp_dequeue_script( 'wpsight-map-googleapi' );
	}
	
	/**
	 *	meta_box_allowed()
	 *	
	 *	Check if user is allowed to see dashboard meta box.
	 *
	 *	@access	public
	 *	@param	bool	$allowed
	 *	@param	array	$meta_box
	 *	@param	integer	$user_id
	 *	@return	bool	
	 *	
	 *	@since 1.1.0
	 */
	public static function meta_box_allowed( $allowed, $meta_box, $user_id ) {
		
		$allowed = apply_filters( 'wpsight_dashboard_allowed_meta_boxes', array(
			'listing_general',
			'listing_price',
			'listing_details',
			'listing_location',
			'listing_agent',
			'listing_images',
		) );
		
		// Only allowed meta boxes
		
		if( ! in_array( $meta_box['id'], $allowed ) )
			$allowed = false;

		return $allowed;

	}
	
	/**
	 *	meta_boxes_dashboard()
	 *	
	 *	Control all meta boxes in dashboard.
	 *
	 *	@access	public
	 *	@param	array	$meta_boxes
	 *	@uses	self::meta_box_listing_general()
	 *	@return	array
	 *	
	 *	@since 1.1.0
	 */
	public static function meta_boxes( $meta_boxes ) {
		
		unset( $meta_boxes['user'] );
		
		$meta_boxes_dashboard = array(
			'listing_general' => self::meta_box_listing_general()
		);

		return apply_filters( 'wpsight_dashboard_meta_boxes', $meta_boxes_dashboard + $meta_boxes );

	}
	
	/**
	 *	meta_box_field_ids()
	 *	
	 *	Get all WPSight meta box dashboard field IDs.
	 *
	 *	@access	public
	 *	@uses	wpsight_meta_boxes()
	 *	@return	array
	 *	
	 *	@since 1.1.0
	 */
	public static function meta_box_field_ids() {
		
		$meta_box_field_ids = array();
		
		$meta_boxes = wpsight_meta_boxes();
		
		foreach( $meta_boxes as $key => $meta_box ) {
			
			if( ! empty( $meta_box['fields'] ) && is_array( $meta_box['fields'] ) ) {
				
				foreach( $meta_box['fields'] as $key_field => $field ) {
					
					$meta_box_field_ids[] = $field['id'];
					
					if( strpos( $field['id'], 'image' ) !== false || strpos( $field['id'], 'logo' ) !== false )
						$meta_box_field_ids[] = $field['id'] . '_id';
					
				}
				
			}
			
		}

		return apply_filters( 'wpsight_dashboard_meta_box_field_ids', $meta_box_field_ids );

	}
	
	/**
	 *	meta_box_listing_general()
	 *	
	 *	Create listing general meta box to set some WordPress default values
	 *	(title, description, featured image, taxonomies) on the front end.
	 *
	 *	@access	public
	 *	@uses	wpsight_sort_array_by_priority()
	 *	@uses	wpsight_post_type()
	 *	@return	array	$meta_box	Meta box array with fields
	 *	@see	wpsight_meta_boxes()
	 *	
	 *	@since 1.0.0
	 */
	public static function meta_box_listing_general() {
		
		if( is_admin() )
			return false;

		// Set meta box fields

		$fields = array(
			'title' => array(
				'name'			=> __( 'Title', 'wpcasa-dashboard' ),
				'id'			=> '_listing_title',
				'type'			=> 'text',
				'attributes'	=> array(
					'required'    => 'required',
				),
				'priority'		=> 10
			),
			'description' => array(
				'name'			=> __( 'Description', 'wpcasa-dashboard' ),
				'id'			=> '_listing_description',
				'type'			=> 'textarea',
				'options'		=> array(
					'textarea_rows' => 10,
					'media_buttons' => false,
				),
				'priority'		=> 20
			),
			'featured_image' => array(
				'name'			=> __( 'Featured Image', 'wpcasa-dashboard' ),
				'id'			=> '_listing_featured_image',
				'type'			=> 'file',
				'priority'		=> 30
			),
			'taxonomy_location' => array(
				'name'			=> __( 'Location', 'wpcasa-dashboard' ),
				'id'			=> '_listing_taxonomy_location',
				'taxonomy'		=> 'location',
				'type'			=> 'taxonomy_select',
				'priority'		=> 40
			),
			'taxonomy_type' => array(
				'name'			=> __( 'Type', 'wpcasa-dashboard' ),
				'id'			=> '_listing_taxonomy_listing_type',
				'taxonomy'		=> 'listing-type',
				'type'			=> 'taxonomy_select',
				'priority'		=> 50
			),
			'taxonomy_feature' => array(
				'name'			=> __( 'Features', 'wpcasa-dashboard' ),
				'id'			=> '_listing_taxonomy_feature',
				'taxonomy'		=> 'feature',
				'type'			=> 'taxonomy_multicheck',
				'priority'		=> 60
			)
		);

		// Apply filter and order fields by priority
		$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_listing_general_fields', $fields ) );

		// Set meta box

		$meta_box = array(
			'id'       => 'listing_general',
			'title'    => __( 'Listing General', 'wpcasa' ),
			'object_types'	=> array( wpsight_post_type() ),
			'context'  => 'normal',
			'priority' => 'high',
			'fields'   => $fields
		);

		return apply_filters( 'wpsight_meta_box_listing_general', $meta_box );

	}
	
	/**
	 *	meta_boxes_submission_field_defaults()
	 *	
	 *	Check if we already have a submission session
	 *	and set values accordingly. Further check if
	 *	users can edit listing IDs.
	 *
	 *	@access	public
	 *	@param	array	$meta_boxes
	 *	@uses	call_user_func_array()
	 *	@uses	wpsight_user_can_edit_listing_id()
	 *	@uses	wpsight_get_option()
	 *	@return	array
	 *	
	 *	@since 1.1.0
	 */
	public static function meta_boxes_submission_field_defaults( $meta_boxes ) {
		
		foreach( $meta_boxes as $meta_box ) {
			
			$meta_box_id = isset( $meta_box['id'] ) ? $meta_box['id'] : false;
			
			if( false === $meta_box_id )
				continue;
			
			if( 'listing_agent' == $meta_box_id || ! $meta_box['fields'] )
				continue;
			
			foreach( $meta_box['fields'] as $key => $field ) {
				
				$submission_default = call_user_func_array( array( 'WPSight_Dashboard_Meta_Boxes', 'meta_boxes_submission_field_values' ), array( $meta_box_id, $field['id'] ) );
				
				if( ! empty( $submission_default ) )
					$meta_boxes[ $meta_box_id ]['fields'][ $key ]['default'] = $submission_default;
				
				if( '_listing_id' == $field['id'] && ! wpsight_user_can_edit_listing_id() && ! wpsight_get_option( 'dashboard_listing_id' ) ) {
					
					$object_id = isset( $_GET['id'] ) ? $_GET['id'] : false;
					
					if( ! $object_id )
						$meta_boxes[ $meta_box_id ]['fields'][ $key ]['type'] = 'hidden';
					
					$meta_boxes[ $meta_box_id ]['fields'][ $key ]['attributes'] = array(
						'readonly' => 'readonly',
						'disabled' => 'disabled'
					);
				}
				
				if( '_price' == $field['id'] )
					$meta_boxes[ $meta_box_id ]['fields'][ $key ]['attributes'] = array(
						'type' => 'number',
						'pattern' => '\d*',	
					);
			
			}
			
		}

		return $meta_boxes;

	}
	
	/**
	 *	meta_boxes_submission_field_values()
	 *
	 *	Callback function to get submission value
	 *	of a specific field from the submission session.
	 *	
	 *	@access	public
	 *	@param	string	$meta_box_id
	 *	@param	string	$field_id
	 *	@return	string
	 *
	 *	@since	1.1.0
	 */
	public static function meta_boxes_submission_field_values( $meta_box_id, $field_id ) {
		return WPSight_Dashboard_Submission::get_submission_field_value( $meta_box_id, $field_id );
	}
    
    /**
	 *	enqueue_scripts()
	 *	
	 *	Enqueues JS dependencies and passes map options to script
	 *	
	 *	@uses	wp_enqueue_script()
	 *	@uses	WPSight_Admin_Map_UI_Admin::get_location_data()
	 *	@uses	wp_localize_script()
	 *	
	 *	@since 1.0.0
	 */
	public static function enqueue_scripts() {
		
		if( class_exists( 'WPSight_Admin_Map_UI' ) && ! is_admin() ) {
		
			// Script debugging?
			$suffix = SCRIPT_DEBUG ? '' : '.min';
			
			// Enqueue scripts
			
			$api_key = wpsight_get_option( 'google_maps_api_key' );			
			$api_url = $api_key ? add_query_arg( array( 'libraries' => 'places', 'key' => $api_key ), '//maps.googleapis.com/maps/api/js' ) : add_query_arg( array( 'libraries' => 'places' ), '//maps.googleapis.com/maps/api/js' );
		
			wp_enqueue_script( 'cmb-google-maps', apply_filters( 'wpsight_dashboard_google_maps_endpoint', $api_url, $api_key ), null, WPSIGHT_DASHBOARD_VERSION );
			wp_enqueue_script( 'cmb-google-maps-script', WPSIGHT_ADMIN_MAP_UI_PLUGIN_URL . '/assets/js/map' . $suffix . '.js', array( 'jquery', 'cmb-google-maps', 'cmb2-scripts' ) );
			
			// Get map listing options
			
			$object_id = isset( $_GET['id'] ) ? $_GET['id'] : false;
			
			$map_options = array(
			    '_map_type' 			=> get_post_meta( $object_id, '_map_type', true ) ? get_post_meta( $object_id, '_map_type', true ) : 'ROADMAP',
			    '_map_zoom' 			=> get_post_meta( $object_id, '_map_zoom', true ) ? get_post_meta( $object_id, '_map_zoom', true ) : 14,
			    '_map_no_streetview' 	=> get_post_meta( $object_id, '_map_no_streetview', true ) ? get_post_meta( $object_id, '_map_no_streetview', true ) : 'false'
			);
			
			$geolocation = WPSight_Admin_Map_UI_Admin::get_location_data( $object_id );
			
			wp_localize_script( 'cmb-google-maps-script', 'CMBGmaps',
				apply_filters( 'wpsight_admin_map_ui_map_args', wp_parse_args( $map_options, array(
					'_map_no_streetview' => 'false',
					'_map_type'          => 'ROADMAP',
					'_map_zoom'          => 14,
					'control_nav'        => 'true',
					'control_type'       => 'true',
					'latitude'           => isset( $geolocation['lat'] ) ? $geolocation['lat'] : '36.510071',
					'longitude'          => isset( $geolocation['long'] ) ? $geolocation['long'] : '-4.882447400000046',
					'markerTitle'        => __( 'Drag to set the exact location', 'wpcasa-admin-map-ui' ),
					'scrollwheel'        => 'false'
				)
			) ) );
		
		}

	}
	
	/**
	 * 	render_map()
	 * 	
	 * 	Displays the map UI in the meta boxes of the listing
	 * 	
	 * 	@param	array	$field
	 * 	@param  array	$value
	 * 	@param  int		$object_id
	 * 	@param  string	$object_type
	 * 	@param  array	$field_type
	 * 	@uses	self::enqueue_scripts()
	 * 	@uses	wp_parse_args()
	 * 	@uses	$field_type->input()
	 * 	@uses	$field_type->_name()
	 * 	
	 * 	@since 1.0.0
	 */
	public static function render_map( $field, $value, $object_id, $object_type, $field_type ) {
		
		if( class_exists( 'WPSight_Admin_Map_UI' ) && ! is_admin() ) {

			self::enqueue_scripts();
			
			// Ensure all args used are set
			$value = wp_parse_args( $value, array( 'lat' => null, 'long' => null, 'elevation' => null ) ); ?>
			
			<div class="map" style="width: 100%; height: 400px; border: 1px solid #eee; margin-top: 8px;"></div>
			
			<?php echo $field_type->input( array(
					'name'  => $field_type->_name( '[lat]' ),
					'value' => $value['lat'],
					'type'  => 'hidden',
					'class' => '_map_geolocation_lat',
					'id'    => '_map_geolocation_lat'
				) ); ?>
			
        	<?php echo $field_type->input( array(
					'name'  => $field_type->_name( '[long]' ),
					'value' => $value['long'],
					'type'  => 'hidden',
					'class' => '_map_geolocation_long',
					'id'    => '_map_geolocation_long'
				) ); ?>
			
        	<?php echo $field_type->input( array(
					'name'  => $field_type->_name( '[elevation]' ),
					'value' => $value['elevation'],
					'type'  => 'hidden',
					'class' => '_map_geolocation_elevation',
					'id'    => '_map_geolocation_elevation'
				) ); ?>
			
			<?php
		
		}
	}

}

WPSight_Dashboard_Meta_Boxes::init();

/**
 *	Class WPSight_Dashboard_Field_Types_Unique_User_Email
 */
class WPSight_Dashboard_Field_Types_Unique_User_Email {

    /**
	 * Initialize class
	 */
    public static function init() {
        add_action( 'cmb2_render_text_unique_user_email', array( __CLASS__, 'render' ), 10, 5 );
        add_filter( 'cmb2_sanitize_text_unique_user_email', array( __CLASS__, 'sanitize' ), 10, 5 );
    }

    /**
	 *	render()
	 *
     *	Render input and set type to email.
     *	
     *	@access	public
     *	@param	$field
     *	@param	$value
     *	@param	$object_id
     *	@param	$object_tyoe
     *	@param	$field_type_object
	 *
	 *	@since	1.1.0
     */
    public static function render( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
        echo $field_type_object->input( array( 'type' => 'email' ) );
    }

    /**
	 *	sanitize()
	 *
     *	Sanitizes the input value.
     *	
     *	@access	public
     *	@param	$override_value
     *	@param	$value
     *	@param	$object_id
     *	@param	$field_args
     *	@return	mixed
     */
    public static function sanitize( $override_value, $value, $object_id, $field_args, $sanitizer_object ) {

        $old_value = $sanitizer_object->field->value;
        $object_type = $sanitizer_object->field->object_type;

        if( $object_type != 'user' ) {
            return $value;
        }

        // not an email?
        if ( ! is_email( $value ) ) {
            $_SESSION['messages'][] = array( 'danger', __( 'Invalid E-mail value.', 'wpcasa-dashboard' ) );
            return $old_value;
        }

        $user_with_email = email_exists( $value );
        if( $user_with_email && $user_with_email != $object_id ) {
            // message
            $_SESSION['messages'][] = array( 'danger', __( 'E-mail already exists.', 'wpcasa-dashboard' ) );
            return $old_value;
        }

        return $value;

    }

}

WPSight_Dashboard_Field_Types_Unique_User_Email::init();
