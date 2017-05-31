<?php
/**
 *	Custom template tags and filters
 *	
 *	@package WPCasa Madrid
 */
	
/**
 *	Custom excerpt and more link
 *	
 *	@uses	get_post_type()
 *	@uses	get_the_ID()
 *	@uses	__()
 *	@uses	get_permalink()
 *	@uses	apply_filters()
 *	@return	string	$excerpt_more	Read more link
 *	
 *	@since 1.0.0
 */
add_filter( 'excerpt_more', 'wpsight_madrid_excerpt_more' );

function wpsight_madrid_excerpt_more() {

	$more_text = get_post_type( get_the_ID() ) == 'post' ? __( 'Read more', 'wpcasa-madrid' ) : __( 'More info', 'wpcasa-madrid' );

	$excerpt_more = ' <span class="moretag"><a href="'. get_permalink( get_the_ID() ) . '" class="btn btn-sm btn-primary">' . apply_filters( 'wpsight_madrid_more_text', $more_text ) . '</a></span>';

	return apply_filters( 'wpsight_madrid_excerpt_more', $excerpt_more );
	
}

/**
 *	Custom excerpt and more link
 *	
 *	@uses	get_post_type()
 *	@uses	get_the_ID()
 *	@uses	__()
 *	@uses	get_permalink()
 *	@uses	apply_filters()
 *	@return	string	$excerpt_more	Read more link
 *	
 *	@since 1.0.0
 */
add_filter( 'wpsight_listing_archive_image_before', 'wpsight_madrid_listing_image_more' );

function wpsight_madrid_listing_image_more() {
	echo wpsight_madrid_excerpt_more();	
}

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_madrid_the_excerpt' ) ) :
/**
 *	Echo custom excerpt
 *	
 *	@param	integer	$post_id	Post ID
 *	@param	bool	$excerpt	Show WordPress excerpt or not
 *	@param	integer	$length		Length of the excerpt
 *	@uses	get_the_ID()
 *	@uses	get_post()
 *	@uses	get_the_content()
 *	@uses	preg_match()
 *	@uses	wpsight_madrid_excerpt_more()
 *	@uses	apply_filters()
 *	@uses	the_excerpt()
 *	@uses	the_content()
 *	
 *	@since 1.0.0
 */
function wpsight_madrid_the_excerpt( $post_id = '', $excerpt = false, $length = '' ) {	
	global $post, $more;

	$more = false;

	if( empty( $post_id ) )
	    $post_id = get_the_ID();
	
	// Get post object	
	$post = get_post( $post_id );
	
	/**
	 * If length parameter provided,
	 * create custom excerpt.
	 */
	if( ! empty( $length ) ) {
	
		// Clean up excerpt
		$output = trim( strip_tags( strip_shortcodes( $post->post_content ) ) );
		
		// Respect post excerpt
		if( ! empty( $post->post_excerpt ) )
			$output = trim( strip_tags( strip_shortcodes( $post->post_excerpt ) ) );
		
		// Stop if no content
		if( empty( $output ) )
			return;
		
		// Get post word count	
		$count = count( explode( ' ', $output ) );
		
		// Respect more tag
		
		if( strpos( $post->post_content, '<!--more-->' ) ) {
			$output = get_the_content( '', true );
		} else {		
			// Get excerpt depening on $length		
			preg_match( '/^\s*+(?:\S++\s*+){1,' . $length . '}/', $output, $matches );	  
			$output = $matches[0] . '&hellip;';
		}
		
		// If content longer than excerpt, display more	
		if( $length <= $count )
			$output .= wpsight_madrid_excerpt_more();
		
		// Respect the_excerpt filter	
		$output = apply_filters( 'the_excerpt', $output );
		
		// Finally display custom excerpt
		echo $output;
		
		return;
	
	}
	
	/**
	 * Check if only the_excerpt or
	 * the_content with more.
	 */
	if( $excerpt == true || ! empty( $post->post_excerpt ) ) {
		the_excerpt();
	} else {	
		the_content( wpsight_madrid_excerpt_more() );	
	}

}

endif;

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_madrid_posted_on' ) ) :
/**
 *	Prints HTML with meta information for the current post-date/time and author.
 *	
 *	@since	1.0.0
 */
function wpsight_madrid_posted_on( $_categories = true, $_date = true, $_author = true ) {
	
	if( $_categories ) {

		$categories_list = get_the_category_list( ', ' );
		if ( $categories_list && wpsight_madrid_categorized_blog() ) {
			printf( '<span class="cat-links"><i class="fa fa-fw fa-archive" aria-hidden="true"></i> ' . esc_html( '%s' ) . '</span> ', $categories_list );
		}
	
	}
	
	if( $_date ) {

		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) )
			$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';
		
		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);
		
		printf( __( '<span class="posted-on"><i class="fa fa-fw fa-calendar" aria-hidden="true"></i> %1$s</span>', 'wpcasa-madrid' ), $time_string );
	
	}
	
	if( $_author ) {
		
		printf( __( '<span class="byline"><i class="fa fa-fw fa-user" aria-hidden="true"></i> %1$s</span>', 'wpcasa-madrid' ),
			sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_html( get_the_author() )
			)
		);
		
	}

}

endif;

/**
 *	Returns true if a blog has more than 1 category.
 *	
 *	@return bool
 *
 *	@since	1.0.0
 */
function wpsight_madrid_categorized_blog() {

	if ( false === ( $all_the_cool_cats = get_transient( 'wpsight_madrid_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'wpsight_madrid_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so wpsight_madrid_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so wpsight_madrid_categorized_blog should return false.
		return false;
	}

}

/**
 * Flush out the transients used in wpsight_madrid_categorized_blog.
 */
add_action( 'edit_category', 'wpsight_madrid_category_transient_flusher' );
add_action( 'save_post',     'wpsight_madrid_category_transient_flusher' );

function wpsight_madrid_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'wpsight_madrid_categories' );
}

/**
 *	Filter archive title on some pages
 *
 *	@param	string	$title
 *	@uses	is_author()
 *	@uses	wpsight_is_listings_archive()
 *	@uses	is_404()
 *	@return	string
 *
 *	@since	1.0.0
 */
add_filter( 'get_the_archive_title', 'wpsight_madrid_get_the_archive_title' );

function wpsight_madrid_get_the_archive_title( $title ) {
	
	if( is_author() && wpsight_is_listings_archive() )
		$title = str_replace( __( 'Author' ), __( 'Agent', 'wpcasa-madrid' ), $title );
	
	if( is_404() )
		$title = __( 'Page not found', 'wpcasa-madrid' );
	
	if( is_home() )
		$title = get_the_title( get_option( 'page_for_posts' ) );

	return $title;

}

/**
 * Hide email from Spam Bots using a shortcode.
 *
 * @param array  $atts    Shortcode attributes. Not used.
 * @param string $content The shortcode content. Should be an email address.
 *
 * @return string The obfuscated email address. 
 */
add_shortcode( 'email', 'wpsight_madrid_email_shortcode' );

function wpsight_madrid_email_shortcode( $atts , $content = null ) {
	if ( ! is_email( $content ) ) {
		return;
	}

	return '<a href="mailto:' . antispambot( $content ) . '">' . antispambot( $content ) . '</a>';
}
