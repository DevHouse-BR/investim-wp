<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Class WPSight_Dashboard_Submission
 */
class WPSight_Dashboard_Submission {

    /**
	 *	Initialize class
	 */
	public static function init() {
        add_action( 'init', array( __CLASS__, 'process_remove_form' ), 9999 );
        add_action( 'init', array( __CLASS__, 'process_availability_toggle' ), 9999 );
        add_action( 'cmb2_init', array( __CLASS__, 'process_submission' ), 9999 );
        add_action( 'init', array( __CLASS__, 'create_general_metabox_fields' ), 9999 );
        add_action( 'transition_post_status', array( __CLASS__, 'update_general_metabox_fields' ), 10, 3 );
		add_action( 'wpsight_listing_single_before', array( __CLASS__, 'edit_listing_link' ) );
		add_filter( 'wpsight_dashboard_meta_box_allowed', array( __CLASS__, 'submission_meta_box_allowed' ), 10, 3 );
    }

    /**
	 *	is_user_allowed_to_add_submission()
	 *
     *	Check if user is allowed to add a submission.
     *	
     *	@access	public
     *	@param	integer	$user_id
     *	@return	bool
     *
     *	@since	1.1.0
     */
    public static function is_user_allowed_to_add_submission( $user_id ) {
	    
	    $can = true;
	    
	    if( ! user_can( $user_id, 'edit_listings' ) )
	    	$can = false;
	    
        return apply_filters( 'wpsight_dashboard_is_user_allowed_to_add_submission', $can, $user_id );

    }
    
    /**
	 *	is_user_allowed_to_edit_submission()
	 *
     *	Check if user is allowed to edit a specific submission.
     *	
     *	@access	public
     *	@param	integer	$user_id
     *	@param	integer	$submission_id
     *	@return	bool
	 *
	 *	@since	1.1.0
     */
    public static function is_user_allowed_to_edit_submission( $user_id, $submission_id ) {
	    
	    $can = true;
	    
	    if( ! user_can( $user_id, 'edit_listing', $submission_id ) ) {
		    
		    $can = false;
	    
	    	if( self::is_submission_active( $submission_id ) && wpsight_get_option( 'dashboard_edit_active' ) )
	    		$can = true;
	    
	    }
	    
        return apply_filters( 'wpsight_dashboard_is_user_allowed_to_edit_submission', $can, $user_id, $submission_id );

    }
	
	/**
	 *	is_user_allowed_to_remove_submission()
	 *
	 *	Check if user allowed to delete a specific listing.
	 *	
	 *	@access	public
	 *	@param	integer	$user_id
	 *	@param	integer	$submission_id
	 *	@return bool
	 *
	 *	@since	1.1.0
	 */
	public static function is_user_allowed_to_remove_submission( $user_id, $submission_id ) {
		
		$can = true;
		
		$submission = get_post( $submission_id );
		
		if( $user_id != $submission->post_author || ! user_can( $user_id, 'delete_listings' ) )
			$can = false;

		return apply_filters( 'wpsight_dashboard_is_user_allowed_to_remove_submission', $can, $user_id, $submission_id );

	}

    /**
	 *	get_submission_steps()
	 *
     *	Get the list of all steps needed to create a new submission.
     *	
     *	@access	public
     *	@param	$post_type
     *	@return	array
     *
     *	@since	1.1.0
     */
    public static function get_submission_steps( $post_type = false ) {

        if ( $post_type == false )
            return array();
        
        $meta_boxes = wpsight_meta_boxes();
        $steps = array();

        if ( ! empty( $meta_boxes ) ) {
            foreach ( $meta_boxes as $meta_box ) {
	            
	            if ( $meta_box && apply_filters( 'wpsight_dashboard_meta_box_allowed', true, $meta_box, get_current_user_id() ) && in_array( $post_type, $meta_box['object_types'] ) ) {
				    $steps[] = array(
				        'id'    => $meta_box['id'],
				        'title' => $meta_box['title'],
				    );
				}

            }
        }

        return $steps;

    }

    /**
	 *	get_submission_step_title()
	 *
     *	Get the title of a specific submission step.
     *	
     *	@access	public
     *	@param	string	$step_title
     *	@param	integer	$step_id
     *	@param	integer	$step_index
     *	@param	bool	$short
     *	@return	string
     *
     *	@since	1.1.0
     */
    public static function get_submission_step_title( $step_title, $step_id, $step_index, $short = false ) {
	    
	    // Set default title
	    $title = $step_title;
        
        $step_titles = self::submission_step_titles();
        
        if( isset( $step_titles[ $step_id ] ) ) {
	        
        	$title = esc_attr( $step_titles[ $step_id ]['default'] );

			if( true === $short && isset( $step_titles[ $step_id ]['short'] ) )
				$title = esc_attr( $step_titles[ $step_id ]['short'] );

        }
        
        // Add index to title
        $title = '<span class="step-title-index">' . $step_index . '.</span> ' . $title;

        return apply_filters( 'wpsight_dashboard_get_submission_step_title', $title, $step_title, $step_id, $step_index );

    }
    
    /**
	 *	get_current_submission_step_title()
	 *
     * 	Get the title of the current submission step.
     * 	
     * 	@access	public
     *	@param	array	$steps
     *	@param	string	$current_step
     * 	@return string
     *
     *	@since	1.1.0
     */
    public static function get_current_submission_step_title( $steps, $current_step ) {
	    
	    $index = 1;
		$title = '';
		
		foreach( $steps as $step ) {				
			if ( $step['id'] == $current_step )
				$title = WPSight_Dashboard_Submission::get_submission_step_title( $step['title'], $step['id'], $index );
			$index++;
		}
		
		return apply_filters( 'get_current_submission_step_title', $title, $steps, $current_step );
    }
    
    /**
	 *	submission_step_titles()
	 *
     * 	List of submission step titles to
     *	replace the default meta box titles.
     * 	
     * 	@access	public
     * 	@return	array
     */
    public static function submission_step_titles() {
        
        $step_titles = array(
			'listing_general'	=> array(
				'default'	=> _x( 'General Information', 'submission step title', 'wpcasa-dashboard' ),
				'short'		=> _x( 'General', 'submission step title', 'wpcasa-dashboard' )
			),
			'listing_price'	=> array(
				'default'	=> _x( 'Price Information', 'submission step title', 'wpcasa-dashboard' ),
				'short'		=> _x( 'Price', 'submission step title', 'wpcasa-dashboard' )
			),
			'listing_details'	=> array(
				'default'	=> _x( 'Property Details', 'submission step title', 'wpcasa-dashboard' ),
				'short'		=> _x( 'Details', 'submission step title', 'wpcasa-dashboard' )
			),
			'listing_images'	=> array(
				'default'	=> _x( 'Property Images', 'submission step title', 'wpcasa-dashboard' ),
				'short'		=> _x( 'Images', 'submission step title', 'wpcasa-dashboard' )
			),
			'listing_location'	=> array(
				'default'	=> _x( 'Property Location', 'submission step title', 'wpcasa-dashboard' ),
				'short'		=> _x( 'Location', 'submission step title', 'wpcasa-dashboard' )
			),
			'listing_agent'	=> array(
				'default'	=> _x( 'Agent Information', 'submission step title', 'wpcasa-dashboard' ),
				'short'		=> _x( 'Agent', 'submission step title', 'wpcasa-dashboard' )
			)
        );

        return apply_filters( 'wpsight_dashboard_submission_step_titles', $step_titles );

    }

    /**
	 *	get_next_step()
	 *
     * 	Get next submission step.
     * 	
     * 	@access	public
     * 	@param	$post_type
     * 	@param	$current_step
     * 	@return	string|bool
     */
    public static function get_next_step( $post_type, $current_step ) {

        $steps = self::get_submission_steps( $post_type );
        $index = 0;

        foreach ( $steps as $step ) {
            if ( $step['id'] == $current_step ) {
                if ( array_key_exists( $index + 1, $steps ) ) {	                
                    return $steps[ $index + 1 ]['id'];
                }
            }

            $index++;
        }

        return false;

    }

    /**
	 *	process_submission()
	 *
     * 	Process the submission form.
     * 	
     * 	@access	public
     *
     *	@since	1.1.0
     */
    public static function process_submission() {
	    
	    $post_type = ! empty( $_GET['type'] ) ? $_GET['type'] : wpsight_post_type();
	    
        if ( $post_type ) {
            $steps = self::get_submission_steps( $post_type );            
            $step = ! empty( $_GET['step'] ) ? $_GET['step'] : $steps[0]['id'];
        }

        if ( empty( $step ) )
            return;

        self::process_submission_step( $step, $_POST );

        if ( ! empty( $post_type ) && ! empty( $_GET['action'] ) && $_GET['action'] == 'save' ) {

            $post_id = self::process_submission_save( $post_type );

            $url = home_url();

            if ( wpsight_get_option( 'dashboard_approval' ) && ! user_can( get_current_user_id(), 'publish_listings' ) ) {
	            
                // if listing needs to be approved, redirect to dashboard page (if exists)
                $submission_list_page = wpsight_get_option( 'dashboard_page' );
                if ( ! empty( $submission_list_page ) ) {
                    $url = get_permalink( $submission_list_page );
                }

            } else {

                // else redirect to post detail
                $url = get_permalink( $post_id );

            }

            wp_redirect( $url );
            exit();
        }

        if ( ! empty( $_POST ) && ! empty( $_POST['submit-submission'] ) ) {

            $next_step = self::get_next_step( $post_type, $step );

            if ( false === $next_step ) {
                if ( ! empty( $_GET['id'] ) ) {
                    $url = sprintf( '?type=%s&action=%s&id=%s', $post_type, 'save', $_GET['id'] );
                } else {
                    $url = sprintf( '?type=%s&action=%s', $post_type, 'save' );
                }
            } else {
                if ( ! empty( $_GET['id'] ) ) {
                    $url = sprintf( '?type=%s&step=%s&id=%s', $post_type, $next_step, $_GET['id'] );
                } else {
                    $url = sprintf( '?type=%s&step=%s', $post_type, $next_step );
                }
            }

            wp_redirect( $url );
            exit();

        }

    }

	/**
	 * process_submission_save()
	 *
	 * 	Finally save a submission.
	 * 	
	 * 	@access	public
	 * 	@param	$post_type
	 * 	@return	integer|WP_Error
	 *
	 *	@since	1.1.0
	 */
    public static function process_submission_save( $post_type ) {

        $post_id       = ! empty( $_GET['id'] ) ? $_GET['id'] : false;        
        $post_status   = 'publish';
        
        // If approval is required and user cannot publish, set post_status pending (approval)

        if ( wpsight_get_option( 'dashboard_approval' ) && ! user_can( get_current_user_id(), 'publish_listings' ) && get_post_status( $post_id ) != 'publish' )
            $post_status = 'pending';

        // If we are updating a listing, get the old one to properly set the
        // post_date because just modified post will be at the top in archive pages.

        if ( ! empty( $post_id ) ) {
            $old_post  = get_post( $post_id );
            $post_date = $old_post->post_date;
            $comment_status = $old_post->comment_status;
        } else {
            $post_date = '';
            $comment_status = '';
        }

        $data = array(
            'post_title'        => sanitize_text_field( self::get_submission_field_value( 'listing_general', '_listing_title' ) ),
            'post_author'       => get_current_user_id(),
            'post_status'       => $post_status,
            'post_type'         => $post_type,
            'post_date'         => $post_date,
            'post_content'      => wp_kses( self::get_submission_field_value( 'listing_general', '_listing_description' ), wp_kses_allowed_html( 'post' ) ),
            'comment_status'    => $comment_status
        );

        if ( ! empty( $post_id ) ) {
            $new_post = false;
            $data['ID'] = $post_id;
        } else {
            $new_post = true;
        }

        $post_id = wp_insert_post( $data, true );

        if ( ! is_wp_error( $post_id ) ) {

            if ( ! empty( $_SESSION['submission'] ) ) {
                foreach ( $_SESSION['submission'] as $key => $value ) {
                    $cmb = cmb2_get_metabox( $key, $post_id );
                    $cmb->save_fields( $post_id, $cmb->object_type(), $_SESSION['submission'][ $key ] );
                }

                // Create featured image
                $featured_image_id = self::get_submission_field_value( 'listing_general', '_listing_featured_image_id' );

                if ( ! empty( $featured_image_id ) ) {
                    set_post_thumbnail( $post_id, $featured_image_id );
                } else {
                    update_post_meta( $post_id, '_listing_featured_image', null );
                    update_post_meta( $post_id, '_listing_featured_image_id', null );
                    delete_post_thumbnail( $post_id );
                }
                
                // Set default listing ID
                $listing_id = self::get_submission_field_value( 'listing_details', '_listing_id' );
                
                if( ! empty( $listing_id ) ) {
	                update_post_meta( $post_id, '_listing_id', $listing_id );
                } else {
	                update_post_meta( $post_id, '_listing_id', wpsight_get_option( 'listing_id' ) . $post_id );
                }

                unset( $_SESSION['submission'] );
            }

            if ( $new_post ) {
                $_SESSION['messages'][] = array( 'success', __( 'New submission has been successfully created.', 'wpcasa-dashboard' ) );
            } else {
                $_SESSION['messages'][] = array( 'success', __( 'Submission has been successfully updated.', 'wpcasa-dashboard' ) );
            }

        }

        return $post_id;

    }

    /**
	 *	process_submission_step()
	 *
     *	Process a specific submission step and save data into session.
     *	
     *	@access	public
     *	@param	string	$step
     *	@param	array	$raw
     *
     *	@since	1.1.0
     */
    public static function process_submission_step( $step, $raw ) {

        $data = array();

        foreach( $raw as $key => $value ) {

            if ( in_array( $key, WPSight_Dashboard_Meta_Boxes::meta_box_field_ids() ) ) {
	            
                if ( ! empty( $value ) ) {
                    $data[ $key ] = $value;
                }

            }
        }

        if ( is_array( $data ) && count( $data ) > 0 )
            $_SESSION['submission'][ $step ] = $data;

    }

    /**
	 *	get_submission_field_value()
	 *
     *	Get default field value for front end submission forms.
     *	Default value is set if value has not been stored before.
     *	
     *	@param	string	$meta_box_id
     *	@param	string	$field_id
     *	@return null|string
     *
     *	@since	1.1.0
     */
    public static function get_submission_field_value( $meta_box_id, $field_id ) {

        if ( ! is_admin() ) {	        
            if ( ! empty( $_SESSION['submission'] ) && ! empty( $_SESSION['submission'][ $meta_box_id ] ) && ! empty( $_SESSION['submission'][ $meta_box_id ][ $field_id ] ) ) {
                return $_SESSION['submission'][ $meta_box_id ][ $field_id ];
            } elseif ( ! empty( $_GET['id'] ) ) {
                return get_post_meta( $_GET['id'], $field_id, true );
            }
        }

        return null;

    }

    /**
	 *	create_general_metabox_fields()
	 *
     *	For the front end form we need to make sure that
     *	some basic information is available. Therefore we
     *	save title, content and featured image in separate
     *	custom fields one time for all listings.
     *	
     *	@access	public
     *
     *	@since	1.1.0
     */
    public static function create_general_metabox_fields() {
	    
	    // If we did it before, stop here

	    if( '1' == get_option( 'wpsight_dashbaord_fields_general' ) )
	    	return;

        $args = array(
	        'posts_per_page'	=> -1,
			'post_type'			=> wpsight_post_type(),
			'post_status'		=> 'any',
        );
        
        $all_listings = get_posts( $args );
        
        // Loop through all listings
        
        foreach( $all_listings as $post ) {
        	add_post_meta( $post->ID, '_listing_title', get_the_title( $post->ID ), true );
        	add_post_meta( $post->ID, '_listing_description', $post->post_content, true );
        	add_post_meta( $post->ID, '_listing_featured_image', wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ), true );
        	add_post_meta( $post->ID, '_listing_featured_image_id', get_post_thumbnail_id( $post->ID ), true );        
        }
        
        // Set flag to run only once
        add_option( 'wpsight_dashbaord_fields_general', '1' );

    }

    /**
	 *	update_general_metabox_fields()
	 *
     *	General meta box uses native WordPress fields.
     *	It is necessary to update CMB2 fields every time listing is updated in WP admin.
     *	This function is fired on transition_post_status action hook.
     *	
     *	@access	public
     *	@param	$new_status
     *	@param	$old_status
     *	@param	$post
     *
     *	@since	1.1.0
     */
    public static function update_general_metabox_fields( $new_status, $old_status, $post ) {

        if ( ! is_admin() )
            return;

        $post_type = get_post_type( $post );
        $listing_post_types = array( wpsight_post_type() );

        if ( ! in_array( $post_type, $listing_post_types ) )
            return;

        update_post_meta( $post->ID, '_listing_title', get_the_title( $post->ID ) );
        update_post_meta( $post->ID, '_listing_description', $post->post_content );
        update_post_meta( $post->ID, '_listing_featured_image', wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ) );

    }

    /**
	 *	process_availability_toggle()
	 *
     *	Mark items available or unavailable.
     *	
     *	@access	public
     *
	 *	@since	1.1.0
     */
    public static function process_availability_toggle() {
	    
	    $action = ! empty( $_GET['action'] ) ? $_GET['action'] : false;
	    
	    if( ! $action || ( 'available' != $action && 'unavailable' != $action ) )
	    	return;

        $object_id = ! empty( $_GET['id'] ) ? $_GET['id'] : false;
        
        if( ! empty( $object_id ) && ! current_user_can( 'edit_listings' ) ) {
	        $_SESSION['messages'][] = array( 'danger', __( 'This listing cannot be edited at the moment.', 'wpcasa-dashboard' ) );
	        return;
        }
        
        $post = get_post( $object_id );

        if ( 'on' == $post->_listing_not_available && 'available' == $action ) {
	        update_post_meta( $object_id, '_listing_not_available', '0' );
			$_SESSION['messages'][] = array( 'success', sprintf( __( '<em>%s</em> has been marked available.', 'wpcasa-dashboard' ), $post->post_title ) );
			do_action( 'wpsight_dashboard_listing_was_marked_available', $post );
		} elseif ( '0' == $post->_listing_not_available && 'unavailable' == $action ) {
			update_post_meta( $object_id, '_listing_not_available', 'on' );
			$_SESSION['messages'][] = array( 'success', sprintf( __( '<em>%s</em> has been marked unavailable.', 'wpcasa-dashboard' ), $post->post_title ) );
			do_action( 'wpsight_dashboard_listing_was_marked_unavailable', $post );
		}

    }
    
    /**
	 *	process_remove_form()
	 *
     *	Process remove listing form.
     *	
     *	@access	public
     *
	 *	@since	1.1.0
     */
    public static function process_remove_form() {

        if ( ! isset( $_POST['remove_listing_form'] ) || empty( $_POST['listing_id'] ) )
            return;

        if ( wp_delete_post( $_POST['listing_id'] ) ) {
			$_SESSION['messages'][] = array( 'success', __( 'Item has been successfully removed.', 'wpcasa-dashboard' ) );
		} else {
			$_SESSION['messages'][] = array( 'danger', __( 'An error occurred when removing an item.', 'wpcasa-dashboard' ) );
		}

    }
    
    /**
	 *	submission_types()
	 *
     *	Define list of submission types.
     *	
     *	@access	public
     *	@return	array
     *
     *	@since	1.1.0
     */
    public static function submission_types() {
        
        $types = array(
			'free'		=> _x( 'Free', 'submission type', 'wpcasa-dashboard' ),
			'packages'	=> _x( 'Packages', 'submission type', 'wpcasa-dashboard' )
        );

        return apply_filters( 'wpsight_dashboard_submission_types', $types );

    }
	
	/**
	 *	edit_listing_link()
	 *
	 *	Show edit listing link to those who are allowed to edit.
	 *	
	 *	@access	public
	 *	@param	integer		$listing_id
	 *
	 *	@since	1.1.0
	 */
	public static function edit_listing_link( $listing_id ) {
		
		if ( ! self::is_user_allowed_to_edit_submission( get_current_user_id(), $listing_id ) )
            return;
        
        wpsight_get_template( 'submission-listing-edit.php', array(
	        'listing_id' => $listing_id
        ), WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
		
	}
    
    /**
	 *	submission_meta_box_allowed()
	 *
     *	Decide whether a user is allowed to see
     *	a specific submission meta box or not.
     *	
     *	@access	public
     *	@param	bool	$allowed
     *	@param	string	$metabox_key
     *	@param	integer	$user_id
     *	@return bool
     *
     *	@since	1.1.0
     */
    public static function submission_meta_box_allowed( $allowed, $meta_box, $user_id ) {
	    
	    if( wpsight_get_option( 'dashboard_payment_options' ) == 'packages' ) {

        	$package_valid = WPSight_Dashboard_Packages::is_package_valid_for_user( $user_id );
        	$permissions_metabox = CMB2_Boxes::get( 'package_permissions' );
        	$fields = $permissions_metabox->meta_box['fields'];
			
        	$field_id = $meta_box['id'] . '_allowed';
			
        	if ( array_key_exists( $field_id, $fields ) ) {
        	    $package = WPSight_Dashboard_Packages::get_package_for_user( $user_id );
        	    if ( empty( $package ) ) {
        	        return false;
        	    }
        	    $allowed_by_package = get_post_meta( $package->ID, $field_id, true );
        	    return $allowed_by_package && $package_valid;
        	}
        
        }

        return $allowed;

    }
    
    /**
	 *	is_submission_active()
	 *
     *	Checks if a submission is active (published)
     *	
     *	@access	public
     *	@param	integer	$submission_id (post ID)
     *	@return	bool
     *
     *	@since	1.1.0
     */
    public static function is_submission_active( $submission_id ) {
        
        // Set default post ID

		if ( ! $submission_id )
			$submission_id = get_the_id();

		// Get custom post meta and set result
		$result = 'publish' == get_post_status( $submission_id ) ? true : false;

		return apply_filters( 'wpsight_dashboard_is_submission_active', $result, $submission_id );
        
    }
    
    /**
	 *	is_submission_pending()
	 *
     *	Checks if a submission is pending or pending payment.
     *	
     *	@access	public
     *	@param	integer	$submission_id (post ID)
     *	@return	bool
     *
     *	@since	1.1.0
     */
    public static function is_submission_pending( $submission_id ) {
        
        // Set default post ID

		if ( ! $submission_id )
			$submission_id = get_the_id();

		// Get custom post meta and set result
		$result = in_array( get_post_status( $submission_id ), array( 'pending', 'pending_payment' ) ) ? true : false;

		return apply_filters( 'wpsight_dashboard_is_submission_pending', $result, $submission_id );
        
    }

}

WPSight_Dashboard_Submission::init();
