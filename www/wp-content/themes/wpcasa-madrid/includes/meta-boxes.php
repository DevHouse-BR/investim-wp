<?php
/**
 * WPCasa Bootstrap custom meta boxes.
 *
 * @package WPCasa Madrid
 */

/**
 *	wpsight_madrid_admin_meta_box_css()
 *
 *	Enqueue meta box CSS on corresponding admin pages.
 *
 *	@uses	get_current_screen()
 *	@uses	wp_enqueue_style()
 *	@uses	get_template_directory_uri()
 *	@uses	get_post_meta()
 *	@uses	get_the_id()
 *
 *	@since 1.0.0
 */
add_action( 'admin_enqueue_scripts', 'wpsight_madrid_admin_meta_box_css', 20 );

function wpsight_madrid_admin_meta_box_css() {
	
	// Script debugging?
	$suffix = SCRIPT_DEBUG ? '' : '.min';
	
	$screen		= get_current_screen();
	$template	= get_post_meta( get_the_id(), '_wp_page_template', true );

	if ( in_array( $screen->id, array( 'edit-page', 'page', 'edit-post', 'post' ) ) )
		wp_enqueue_style( 'wpsight-madrid-meta-boxes', get_template_directory_uri() . '/assets/css/wpsight-meta-boxes' . $suffix . '.css' );

}

/**
 * Enqueue scripts and styles.
 */
add_action( 'admin_enqueue_scripts', 'wpsight_madrid_scripts_admin' );

function wpsight_madrid_scripts_admin() {
	
	// Script debugging?
	$suffix = SCRIPT_DEBUG ? '' : '.min';
	
	// Enqueue CMB2 Conditionals if desired
	
	if( apply_filters( 'wpsight_madrid_cmb2_conditionals', true ) )
		wp_enqueue_script( 'cmb2-conditionals', get_template_directory_uri() . '/vendor/jcchavezs/cmb2-conditionals/cmb2-conditionals.js', false, '1.0.4', true );

}

/**
 * WPCasa Bootstrap listing gallery
 */
add_filter( 'wpsight_meta_boxes', 'wpsight_madrid_meta_boxes_listing_images', 8 );

function wpsight_madrid_meta_boxes_listing_images( $meta_boxes ) {
	
	$meta_boxes['listing_images'] = wpsight_meta_box_listing_images();
	
	return $meta_boxes;
	
}

/**
 *	wpsight_madrid_meta_boxes()
 *
 *	Combine Madrid meta boxes and add
 *	to general WPCasa meta boxes array.
 *
 *	@uses	wpsight_madrid_meta_boxes_home_slider()
 *	@uses	wpsight_madrid_meta_boxes_home_cta_1()
 *	@uses	wpsight_madrid_meta_boxes_home_cta_2()
 *	@uses	wpsight_madrid_meta_boxes_home_carousel()
 *	@return	array	$meta_boxes	Array of meta boxes
 *
 *	@since 1.0.0
 */
add_filter( 'wpsight_meta_boxes', 'wpsight_madrid_meta_boxes' );

function wpsight_madrid_meta_boxes( $meta_boxes ) {
	
	// Home page template
	
	$meta_boxes['home_search']		= wpsight_madrid_meta_boxes_home_search();
	$meta_boxes['home_carousel']	= wpsight_madrid_meta_boxes_home_carousel();
	$meta_boxes['home_cta_1']		= wpsight_madrid_meta_boxes_home_cta_1();
	$meta_boxes['home_services']	= wpsight_madrid_meta_boxes_home_services();
	$meta_boxes['home_listings']	= wpsight_madrid_meta_boxes_home_listings();
	$meta_boxes['home_cta_2']		= wpsight_madrid_meta_boxes_home_cta_2();
	
	// Header settings
	
	$meta_boxes['header_general']	= wpsight_madrid_meta_boxes_header_general();
	$meta_boxes['header_listings']	= wpsight_madrid_meta_boxes_header_listings();
	
	// Listing queries
	
	$meta_boxes['listings_query']	= wpsight_madrid_meta_boxes_listings_query();
	
	return $meta_boxes;
	
}

/**
 *	wpsight_madrid_meta_boxes_home_search()
 *
 *	Set up home search meta box.
 *
 *	@uses	wpsight_sort_array_by_priority()
 *	@return	array	$meta_box	Array of meta box
 *
 *	@since 1.0.0
 */
function wpsight_madrid_meta_boxes_home_search() {
	
	// Set meta box fields

	$fields = array(
		'display' => array(
			'name'      => '',
			'id'        => '_search_display',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Display', 'wpcasa-madrid' ),
			'desc'      => __( 'Display search form on the front page', 'wpcasa-madrid' ),
			'priority'  => 10
		),
		'orientation' => array(
			'name'      => __( 'Orientation', 'wpcasa-madrid' ),
			'id'        => '_search_orientation',
			'type'      => 'select',
			'options'	=> array(
				'horizontal'	=> _x( 'horizontal', 'listing widget', 'wpcasa-madrid' ),
				'vertical'		=> _x( 'vertical', 'listing widget', 'wpcasa-madrid' ),
			),
			'default'	=> 'horizontal',
			'priority'  => 20
		),
		'priority' => array(
			'name'      => __( 'Priority', 'wpcasa-madrid' ),
			'id'        => '_priority_search',
			'type'      => 'text_small',
			'attributes' => array(
				'type' => 'number',
				'pattern' => '\d*',
			),
			'desc'      => __( 'Set priority to order home page elements. Lowest are displayed first.', 'wpcasa-madrid' ),
			'default'	=> 10,
			'priority'  => 20
		),
	);

	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_madrid_meta_boxes_home_search_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'home_search',
		'title'			=> __( 'Home Search Form', 'wpcasa-madrid' ),
		'object_types'	=> array( 'page' ),
		'show_on'		=> array( 'key' => 'page-template', 'value' => 'page-tpl-home.php' ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_madrid_meta_boxes_home_search', $meta_box );
	
}

/**
 *	wpsight_madrid_meta_boxes_home_carousel()
 *
 *	Set up home carousel meta box.
 *
 *	@uses	wpsight_offers()
 *	@uses	wpsight_post_type()
 *	@uses	get_object_taxonomies()
 *	@uses	get_terms()
 *	@uses	wpsight_madrid_checkbox_default()
 *	@uses	wpsight_sort_array_by_priority()
 *	@return	array	$meta_box	Array of meta box
 *
 *	@since 1.0.0
 */
function wpsight_madrid_meta_boxes_home_carousel() {
	
	// Prepare offers
	$offers = wpsight_offers();
	
	// Prepare taxonomies
	$taxonomies = wpsight_taxonomies();
	
	// Set meta box fields

	$fields = array(
		'display' => array(
			'name'      => '',
			'id'        => '_carousel_display',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Display', 'wpcasa-madrid' ),
			'desc'      => __( 'Display carousel on the front page', 'wpcasa-madrid' ),
			'priority'  => 10
		),
		'nr' => array(
			'name'      => __( 'Listings', 'wpcasa-madrid' ),
			'id'        => '_carousel_nr',
			'type'      => 'text',
			'desc'      => __( 'Please enter the number of listings to be displayed', 'wpcasa-madrid' ),
			'default'	=> 6,
			'priority'  => 20
		),
		'offer' => array(
			'name'      => __( 'Filter by', 'wpcasa-madrid' ) . ': ' . __( 'Offer', 'wpcasa-madrid' ),
			'id'        => '_carousel_offer',
			'type'      => 'select',
			'desc'      => '',
			'options'	=> array_merge( array( '' => __( 'None', 'wpcasa-madrid' ) ), $offers ),
			'priority'  => 30
		),
		'items' => array(
			'name'      => __( 'Items', 'wpcasa-madrid' ),
			'id'        => '_carousel_items',
			'type'      => 'text',
			'desc'      => __( 'Please enter the number of listings to be visible', 'wpcasa-madrid' ),
			'default'	=> 3,
			'priority'  => 40
		),
		'slide_by' => array(
			'name'      => __( 'Slide by', 'wpcasa-madrid' ),
			'id'        => '_carousel_slide_by',
			'type'      => 'text',
			'desc'      => __( 'Please enter the number of listings to move in one slide', 'wpcasa-madrid' ),
			'default'	=> 3,
			'priority'  => 50
		),
		'margin' => array(
			'name'      => __( 'Margin', 'wpcasa-madrid' ),
			'id'        => '_carousel_margin',
			'type'      => 'text',
			'desc'      => __( 'Please enter the space between two listings in px', 'wpcasa-madrid' ),
			'default'	=> 40,
			'priority'  => 60
		),
		'loop' => array(
			'name'      => '',
			'id'        => '_carousel_loop',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Loop', 'wpcasa-madrid' ),
			'desc'      => __( 'Loop slider to be infinite', 'wpcasa-madrid' ),
			'default'	=> wpsight_madrid_checkbox_default( true ),
			'priority'  => 70
		),
		'nav' => array(
			'name'      => '',
			'id'        => '_carousel_nav',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Carousel nav', 'wpcasa-madrid' ),
			'desc'      => __( 'Show prev/next carousel navigation', 'wpcasa-madrid' ),
			'default'	=> wpsight_madrid_checkbox_default( true ),
			'priority'  => 80
		),
		'dots' => array(
			'name'      => '',
			'id'        => '_carousel_dots',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Carousel dots', 'wpcasa-madrid' ),
			'desc'      => __( 'Show <em>dots</em> carousel navigation', 'wpcasa-madrid' ),
			'priority'  => 90
		),
		'autoplay' => array(
			'name'      => '',
			'id'        => '_carousel_autoplay',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Carousel autoplay', 'wpcasa-madrid' ),
			'desc'      => __( 'Slide through items automatically', 'wpcasa-madrid' ),
			'priority'  => 100
		),
		'autoplay_time' => array(
			'name'      => 'Autoplay interval',
			'id'        => '_carousel_autoplay_time',
			'type'      => 'select',
			'options'	=> array(
				'2000'	=> '2 ' . _x( 'seconds', 'listing widget', 'wpcasa-madrid' ),
				'4000'	=> '4 ' . _x( 'seconds', 'listing widget', 'wpcasa-madrid' ),
				'6000'	=> '6 ' . _x( 'seconds', 'listing widget', 'wpcasa-madrid' ),
				'8000'	=> '8 ' . _x( 'seconds', 'listing widget', 'wpcasa-madrid' ),
				'10000'	=> '10 ' . _x( 'seconds', 'listing widget', 'wpcasa-madrid' ),
			),
			'desc'		=> __( 'Please select the autoplay interval timeout', 'wpcasa-madrid' ),
			'default'	=> 6000,
			'priority'  => 110
		),
		'priority' => array(
			'name'      => __( 'Priority', 'wpcasa-madrid' ),
			'id'        => '_priority_carousel',
			'type'      => 'text_small',
			'attributes' => array(
				'type' => 'number',
				'pattern' => '\d*',
			),
			'desc'      => __( 'Set priority to order home page elements. Lowest are displayed first.', 'wpcasa-madrid' ),
			'default'	=> 20,
			'priority'  => 120
		),
	);
	
	// Add taxonomy filters
	
	foreach( $taxonomies as $taxonomy ) {
		
		$get_terms = get_terms( $taxonomy->name, array( 'hide_empty' => false ) );
		
		$terms = array(
			'' => __( 'None', 'wpcasa-madrid' )
		);
		
		foreach ( $get_terms as $term ) {
			$terms[ $term->slug ] = $term->name;
		}
		
		$fields[ $taxonomy->name ] = array(
			'name'		=> __( 'Filter by', 'wpcasa-madrid' ) . ': ' . $taxonomy->labels->singular_name,
			'desc'		=> '',
			'id'		=> '_carousel_taxonomy_' . $taxonomy->name,
			'options'	=> $terms,
			'type'		=> 'select',
			'priority'  => 40
		);

	}

	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_madrid_meta_boxes_home_carousel_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'home_carousel',
		'title'			=> __( 'Home Carousel', 'wpcasa-madrid' ),
		'object_types'	=> array( 'page' ),
		'show_on'		=> array( 'key' => 'page-template', 'value' => 'page-tpl-home.php' ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_madrid_meta_boxes_home_carousel', $meta_box );
	
}

/**
 *	wpsight_madrid_meta_boxes_home_cta_1()
 *
 *	Set up home call to action #1 meta box.
 *
 *	@uses	wpsight_sort_array_by_priority()
 *	@return	array	$meta_box	Array of meta box
 *
 *	@since 1.0.0
 */
function wpsight_madrid_meta_boxes_home_cta_1() {
	
	// Set meta box fields

	$fields = array(
		'display' => array(
			'name'      => '',
			'id'        => '_cta_1_display',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Display', 'wpcasa-madrid' ),
			'desc'      => __( 'Display call to action on the front page', 'wpcasa-madrid' ),
			'priority'  => 10
		),
		'title' => array(
			'name'      => __( 'Title', 'wpcasa-madrid' ),
			'id'        => '_cta_1_title',
			'type'      => 'text',
			'priority'  => 20
		),
		'description' => array(
			'name'      => __( 'Description', 'wpcasa-madrid' ),
			'id'        => '_cta_1_description',
			'type'      => 'textarea_small',
			'priority'  => 30
		),
		'button_label' => array(
			'name'      => __( 'Button Label', 'wpcasa-madrid' ),
			'id'        => '_cta_1_button_label',
			'type'      => 'text',
			'priority'  => 40
		),
		'button_url' => array(
			'name'      => __( 'Button URL', 'wpcasa-madrid' ),
			'id'        => '_cta_1_button_url',
			'type'      => 'text_url',
			'priority'  => 50
		),
		'button_target' => array(
			'name'      => '',
			'id'        => '_cta_1_button_target',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Button Target', 'wpcasa-madrid' ),
			'desc'      => __( 'Open link in new tab or window', 'wpcasa-madrid' ),
			'priority'  => 60
		),
		'orientation' => array(
			'name'      => __( 'Orientation', 'wpcasa-madrid' ),
			'id'        => '_cta_1_orientation',
			'type'      => 'select',
			'options'	=> array(
				'horizontal'	=> _x( 'horizontal', 'listing widget', 'wpcasa-madrid' ),
				'vertical'		=> _x( 'vertical', 'listing widget', 'wpcasa-madrid' ),
			),
			'default'	=> 'vertical',
			'priority'  => 70
		),
		'priority' => array(
			'name'      => __( 'Priority', 'wpcasa-madrid' ),
			'id'        => '_priority_cta_1',
			'type'      => 'text_small',
			'attributes' => array(
				'type' => 'number',
				'pattern' => '\d*',
			),
			'desc'      => __( 'Set priority to order home page elements. Lowest are displayed first.', 'wpcasa-madrid' ),
			'default'	=> 30,
			'priority'  => 80
		),
	);

	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_madrid_meta_boxes_home_cta_1_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'home_cta_1',
		'title'			=> __( 'Home Call to Action #1', 'wpcasa-madrid' ),
		'object_types'	=> array( 'page' ),
		'show_on'		=> array( 'key' => 'page-template', 'value' => 'page-tpl-home.php' ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_madrid_meta_boxes_home_cta_1', $meta_box );
	
}

/**
 *	wpsight_madrid_meta_boxes_home_services()
 *
 *	Set up home services meta box.
 *
 *	@uses	wpsight_sort_array_by_priority()
 *	@return	array	$meta_box	Array of meta box
 *
 *	@since 1.0.0
 */
function wpsight_madrid_meta_boxes_home_services() {
	
	// Set meta box fields

	$fields = array(
		'display' => array(
			'name'      => '',
			'id'        => '_services_display',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Display', 'wpcasa-madrid' ),
			'desc'      => __( 'Display services on the front page', 'wpcasa-madrid' ),
			'priority'  => 10
		),
		'display_features' => array(
			'name'      => '',
			'id'        => '_services_display_features',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Feature Boxes', 'wpcasa-madrid' ),
			'desc'      => __( 'Display services as feature boxes', 'wpcasa-madrid' ),
			'priority'  => 15
		),
		'title'	=> array(
			'name'      => __( 'Section Title', 'wpcasa-madrid' ),
			'id'        => '_services_title',
			'type'      => 'text',
			'priority'  => 20
		),
		'description'	=> array(
			'name'      => __( 'Section Description', 'wpcasa-madrid' ),
			'id'        => '_services_description',
			'type'      => 'textarea_small',
			'priority'  => 30
		),
		'services' => array(
			'name'      	=> __( 'Services', 'wpcasa-madrid' ),
			'id'        	=> '_services',
			'type'      	=> 'group',
			'group_fields'	=> array(
				'service_label' => array(
					'name'		=> __( 'Label', 'wpcasa-madrid' ),
					'id'		=> '_service_label',
					'type'		=> 'text',
				),
				'service_icon' => array(
					'name'		=> __( 'Icon Class', 'wpcasa-madrid' ),
					'id'		=> '_service_icon',
					'type'		=> 'text',
					'desc'		=> __( 'For example <a href="https://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a>', 'wpcasa-madrid' ),
					'attributes'	=> array(
						'placeholder'	=> 'fa fa-check'
					)
				),
				'service_description' => array(
					'name'		=> __( 'Description', 'wpcasa-madrid' ),
					'id'		=> '_service_desc',
					'type'		=> 'textarea_small',
				),
				'service_url' => array(
					'name'      => __( 'URL', 'wpcasa-madrid' ),
					'id'        => '_service_url',
					'type'      => 'text_url',
				),
				'service_button' => array(
					'name'      => __( 'Button', 'wpcasa-madrid' ),
					'id'        => '_service_button',
					'type'      => 'text',
				),
				'service_target' => array(
					'name'      => '',
					'id'        => '_service_target',
					'type'      => 'checkbox',
					'label_cb'	=> __( 'Target', 'wpcasa-madrid' ),
					'desc'      => __( 'Open link in new tab or window', 'wpcasa-madrid' ),
				),
			),
			'description' 	=> __( 'Display different services you offer', 'wpcasa-madrid' ),
			'repeatable'  	=> true,
			'options'     	=> array(
			    'group_title'   => __( 'Service {#}', 'wpcasa-madrid' ),
			    'add_button'    => __( 'Add Service', 'wpcasa-madrid' ),
			    'remove_button' => __( 'Remove', 'wpcasa-madrid' ),
			    'sortable'      => true,
			    'closed'		=> true
			),
			'priority'	=> 40
		),
		'priority' => array(
			'name'      => __( 'Priority', 'wpcasa-madrid' ),
			'id'        => '_priority_services',
			'type'      => 'text_small',
			'attributes' => array(
				'type' => 'number',
				'pattern' => '\d*',
			),
			'desc'      => __( 'Set priority to order home page elements. Lowest are displayed first.', 'wpcasa-madrid' ),
			'default'	=> 40,
			'priority'  => 50
		),
	);
	
	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_madrid_meta_boxes_home_services_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'home_services',
		'title'			=> __( 'Home Services', 'wpcasa-madrid' ),
		'object_types'	=> array( 'page' ),
		'show_on'		=> array( 'key' => 'page-template', 'value' => 'page-tpl-home.php' ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_madrid_meta_boxes_home_services', $meta_box );

}

/**
 *	wpsight_madrid_meta_boxes_home_listings()
 *
 *	Set up home listings meta box.
 *
 *	@uses	wpsight_offers()
 *	@uses	wpsight_post_type()
 *	@uses	get_object_taxonomies()
 *	@uses	get_terms()
 *	@uses	wpsight_sort_array_by_priority()
 *
 *	@since 1.0.0
 */
function wpsight_madrid_meta_boxes_home_listings() {
	
	// Prepare offers
	$offers = wpsight_offers();
	
	// Prepare taxonomies
	$taxonomies = wpsight_taxonomies();
	
	// Set meta box fields

	$fields = array(
		'display' => array(
			'name'      => '',
			'id'        => '_listings_display',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Display', 'wpcasa-madrid' ),
			'desc'      => __( 'Display listings on the front page', 'wpcasa-madrid' ),
			'priority'  => 10
		),
		'queries' => array(
			'name'      	=> __( 'Listings Query', 'wpcasa-madrid' ),
			'id'        	=> '_listings',
			'type'      	=> 'group',
			'group_fields'	=> array(
				'title'	=> array(
					'name'      => __( 'Section Title', 'wpcasa-madrid' ),
					'id'        => '_listings_title',
					'type'      => 'text',
				),
				'label' => array(
					'name'      => __( 'Button Label', 'wpcasa-madrid' ),
					'id'        => '_listings_button_label',
					'type'      => 'text',
					'default'   => __( 'View All', 'wpcasa-madrid' )
				),
				'url' => array(
					'name'      => __( 'Button URL', 'wpcasa-madrid' ),
					'id'        => '_listings_button_url',
					'type'      => 'text_url',
				),
				'nr' => array(
					'name'      => __( 'Listings', 'wpcasa-madrid' ),
					'id'        => '_listings_nr',
					'type'      => 'text',
					'desc'      => __( 'Please enter the number of listings to be displayed', 'wpcasa-madrid' ),
					'default'	=> 6
				),
				'offer' => array(
					'name'      => __( 'Filter by', 'wpcasa-madrid' ) . ' ' . __( 'Offer', 'wpcasa-madrid' ),
					'id'        => '_listings_offer',
					'type'      => 'select',
					'options'	=> array_merge( array( '' => __( 'None', 'wpcasa-madrid' ) ), $offers )
				)
			),
			'description' 	=> __( 'Create listing queries for the home page', 'wpcasa-madrid' ),
			'repeatable'  	=> true,
			'options'     	=> array(
			    'group_title'   => __( 'Listings Query {#}', 'wpcasa-madrid' ),
			    'add_button'    => __( 'Add Query', 'wpcasa-madrid' ),
			    'remove_button' => __( 'Remove', 'wpcasa-madrid' ),
			    'sortable'      => true,
			    'closed'		=> true
			),
			'priority'  => 20
		),
		'priority' => array(
			'name'      => __( 'Priority', 'wpcasa-madrid' ),
			'id'        => '_priority_listings',
			'type'      => 'text_small',
			'attributes' => array(
				'type' => 'number',
				'pattern' => '\d*',
			),
			'desc'      => __( 'Set priority to order home page elements. Lowest are displayed first.', 'wpcasa-madrid' ),
			'default'	=> 50,
			'priority'  => 30
		),
	);
	
	// Add taxonomy filters
	
	foreach( $taxonomies as $taxonomy ) {
		
		if( 'language' == $taxonomy->name || 'post_translations' == $taxonomy->name )
			continue;
		
		$get_terms = get_terms( $taxonomy->name, array( 'hide_empty' => false ) );
		
		$terms = array(
			'' => __( 'None', 'wpcasa-madrid' )
		);
		
		foreach ( $get_terms as $term ) {
			$terms[ $term->slug ] = $term->name;
		}
		
		$fields['queries']['group_fields'][ $taxonomy->name ] = array(
			'name'		=> __( 'Filter by', 'wpcasa-madrid' ) . ': ' . $taxonomy->labels->singular_name,
			'desc'		=> '',
			'id'		=> '_taxonomy_filter_' . $taxonomy->name,
			'options'	=> $terms,
			'type'		=> 'select'
		);

	}

	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_madrid_meta_boxes_home_listings_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'home_listings',
		'title'			=> __( 'Home Listings', 'wpcasa-madrid' ),
		'object_types'	=> array( 'page' ),
		'show_on'		=> array( 'key' => 'page-template', 'value' => 'page-tpl-home.php' ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_madrid_meta_boxes_home_listings', $meta_box );

}

/**
 *	wpsight_madrid_meta_boxes_home_cta_2()
 *
 *	Set up home call to action #2 meta box.
 *
 *	@uses	wpsight_sort_array_by_priority()
 *	@return	array	$meta_box	Array of meta box
 *
 *	@since 1.0.0
 */
function wpsight_madrid_meta_boxes_home_cta_2() {
	
	// Set meta box fields

	$fields = array(
		'display' => array(
			'name'      => '',
			'id'        => '_cta_2_display',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Display', 'wpcasa-madrid' ),
			'desc'      => __( 'Display call to action on the front page', 'wpcasa-madrid' ),
			'priority'  => 10
		),
		'title' => array(
			'name'      => __( 'Title', 'wpcasa-madrid' ),
			'id'        => '_cta_2_title',
			'type'      => 'text',
			'priority'  => 20
		),
		'description' => array(
			'name'      => __( 'Description', 'wpcasa-madrid' ),
			'id'        => '_cta_2_description',
			'type'      => 'textarea_small',
			'priority'  => 30
		),
		'button_label' => array(
			'name'      => __( 'Button Label', 'wpcasa-madrid' ),
			'id'        => '_cta_2_button_label',
			'type'      => 'text',
			'priority'  => 40
		),
		'button_url' => array(
			'name'      => __( 'Button URL', 'wpcasa-madrid' ),
			'id'        => '_cta_2_button_url',
			'type'      => 'text_url',
			'priority'  => 50
		),
		'button_target' => array(
			'name'      => '',
			'id'        => '_cta_2_button_target',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Button Target', 'wpcasa-madrid' ),
			'desc'      => __( 'Open link in new tab or window', 'wpcasa-madrid' ),
			'priority'  => 60
		),
		'orientation' => array(
			'name'      => __( 'Orientation', 'wpcasa-madrid' ),
			'id'        => '_cta_2_orientation',
			'type'      => 'select',
			'options'	=> array(
				'horizontal'	=> _x( 'horizontal', 'listing widget', 'wpcasa-madrid' ),
				'vertical'		=> _x( 'vertical', 'listing widget', 'wpcasa-madrid' ),
			),
			'default'	=> 'horizontal',
			'priority'  => 60
		),
		'priority' => array(
			'name'      => __( 'Priority', 'wpcasa-madrid' ),
			'id'        => '_priority_cta_2',
			'type'      => 'text_small',
			'attributes' => array(
				'type' => 'number',
				'pattern' => '\d*',
			),
			'desc'      => __( 'Set priority to order home page elements. Lowest are displayed first.', 'wpcasa-madrid' ),
			'default'	=> 60,
			'priority'  => 70
		),
	);

	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_madrid_meta_boxes_home_cta_2_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'home_cta_2',
		'title'			=> __( 'Home Call to Action #2', 'wpcasa-madrid' ),
		'object_types'	=> array( 'page' ),
		'show_on'		=> array( 'key' => 'page-template', 'value' => 'page-tpl-home.php' ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_madrid_meta_boxes_home_cta_2', $meta_box );	
	
}

/**
 *	wpsight_madrid_meta_boxes_header_general()
 *
 *	Set up header settings meta boxes.
 *
 *	@uses	wpsight_sort_array_by_priority()
 *	@return	array	$meta_box	Array of meta box
 *
 *	@since 1.0.0
 */
function wpsight_madrid_meta_boxes_header_general() {
	
	// Prepare offers
	$offers = wpsight_offers();
	
	// Prepare taxonomies
	$taxonomies = wpsight_taxonomies();
	
	// Set meta box fields

	$fields = array(
		'display' => array(
			'name'      => __( 'Display', 'wpcasa-madrid' ),
			'id'        => '_header_display',
			'type'      => 'select',
			'options'	=> array(
				''					=> __( 'Do not display', 'wpcasa-madrid' ),
				'page_title'		=> __( 'Page title', 'wpcasa-madrid' ),
				'tagline'			=> __( 'Tagline & background', 'wpcasa-madrid' ),
				'featured_image'	=> __( 'Featured image & title', 'wpcasa-madrid' ),
				'image_slider'		=> __( 'Image slider', 'wpcasa-madrid' ),
				'listings_slider'	=> __( 'Listings slider', 'wpcasa-madrid' ),
			),
			'desc'      => __( 'Display header area on this page', 'wpcasa-madrid' ),
			'priority'  => 10
		),
		'title_right' => array(
			'name'      => __( 'Page Title Right', 'wpcasa-madrid' ),
			'id'        => '_title_right',
			'type'      => 'textarea_small',
			'desc'      => __( 'Add custom content on the right side of the page title section', 'wpcasa-madrid' ),
			'attributes' => array(
				'data-conditional-id' 		=> '_header_display',
				'data-conditional-value'	=> 'page_title',
			),
			'priority'  => 20
		),
		'tagline_bg' => array(
			'name'      => __( 'Tagline Background', 'wpcasa-madrid' ),
			'id'        => '_tagline_bg',
			'type'      => 'file',
			'desc'      => __( 'Add an image to be displayed as tagline background', 'wpcasa-madrid' ),
			'attributes' => array(
				'data-conditional-id' 		=> '_header_display',
				'data-conditional-value'	=> 'tagline',
			),
			'priority'  => 20
		),
		'tagline_text' => array(
			'name'      => __( 'Tagline Text', 'wpcasa-madrid' ),
			'id'        => '_tagline_text',
			'type'      => 'textarea_small',
			'desc'      => __( 'Please enter the text for the tagline', 'wpcasa-madrid' ),
			'attributes' => array(
				'data-conditional-id' 		=> '_header_display',
				'data-conditional-value'	=> 'tagline',
			),
			'priority'  => 30
		),
		'image_slider' => array(
			'name'       => __( 'Images', 'wpcasa' ),
			'id'         => '_gallery',
			'type'       => 'file_list',
			'preview_size' => array( 150, 150 ),
			'sortable'   => true,
			'desc'       => false,
			'attributes' => array(
				'data-conditional-id' 		=> '_header_display',
				'data-conditional-value'	=> 'image_slider',
			),
			'priority'  => 20
		),
		'nr' => array(
			'name'      => __( 'Listings', 'wpcasa-madrid' ),
			'id'        => '_listings_nr',
			'type'      => 'text',
			'desc'      => __( 'Please enter the number of listings to be displayed', 'wpcasa-madrid' ),
			'attributes' => array(
				'data-conditional-id' 		=> '_header_display',
				'data-conditional-value'	=> json_encode( array( 'listings_slider', 'listings_map' ) ),
			),
			'default'	=> 6,
			'priority'  => 20
		),
		'offer' => array(
			'name'      => __( 'Filter by', 'wpcasa-madrid' ) . ' ' . __( 'Offer', 'wpcasa-madrid' ),
			'id'        => '_listings_offer',
			'type'      => 'select',
			'desc'      => '',
			'attributes' => array(
				'data-conditional-id' 		=> '_header_display',
				'data-conditional-value'	=> json_encode( array( 'listings_slider', 'listings_map' ) ),
			),
			'options'	=> array_merge( array( '' => __( 'None', 'wpcasa-madrid' ) ), $offers ),
			'priority'  => 25
		),
		'animation' => array(
			'name'      => __( 'Animation', 'wpcasa-madrid' ),
			'id'        => '_listings_animation',
			'type'      => 'select',
			'options'	=> array(
				'slide'	=> __( 'slide', 'wpcasa-madrid' ),
				'fade'	=> __( 'fade', 'wpcasa-madrid' ),
			),
			'default'	=> 'slide',
			'attributes' => array(
				'data-conditional-id' 		=> '_header_display',
				'data-conditional-value'	=> 'listings_slider',
			),
			'priority'  => 30
		),
		'loop' => array(
			'name'      => '',
			'id'        => '_listings_loop',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Loop', 'wpcasa-madrid' ),
			'desc'      => __( 'Loop slider to be infinite', 'wpcasa-madrid' ),
			'attributes' => array(
				'data-conditional-id' 		=> '_header_display',
				'data-conditional-value'	=> 'listings_slider',
			),
			'default'	=> wpsight_madrid_checkbox_default( true ),
			'priority'  => 40
		),
		'nav' => array(
			'name'      => '',
			'id'        => '_listings_nav',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Slider nav', 'wpcasa-madrid' ),
			'desc'      => __( 'Show prev/next slider navigation', 'wpcasa-madrid' ),
			'attributes' => array(
				'data-conditional-id' 		=> '_header_display',
				'data-conditional-value'	=> 'listings_slider',
			),
			'default'	=> wpsight_madrid_checkbox_default( true ),
			'priority'  => 50
		),
		'dots' => array(
			'name'      => '',
			'id'        => '_listings_dots',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Slider dots', 'wpcasa-madrid' ),
			'desc'      => __( 'Show <em>dots</em> slider navigation', 'wpcasa-madrid' ),
			'attributes' => array(
				'data-conditional-id' 		=> '_header_display',
				'data-conditional-value'	=> 'listings_slider',
			),
			'priority'  => 60
		),
		'dots_thumbs' => array(
			'name'      => '',
			'id'        => '_listings_dots_thumbs',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Slider thumbs', 'wpcasa-madrid' ),
			'desc'      => __( 'Show thumbnails instead of <em>dots</em>', 'wpcasa-madrid' ),
			'attributes' => array(
				'data-conditional-id' 		=> '_header_display',
				'data-conditional-value'	=> 'listings_slider',
			),
			'priority'  => 65
		),
		'autoplay' => array(
			'name'      => '',
			'id'        => '_listings_autoplay',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Slider autoplay', 'wpcasa-madrid' ),
			'desc'      => __( 'Slide through items automatically', 'wpcasa-madrid' ),
			'attributes' => array(
				'data-conditional-id' 		=> '_header_display',
				'data-conditional-value'	=> 'listings_slider',
			),
			'priority'  => 70
		),
		'autoplay_time' => array(
			'name'      => 'Autoplay interval',
			'id'        => '_listings_autoplay_time',
			'type'      => 'select',
			'options'	=> array(
				'2000'	=> '2 ' . _x( 'seconds', 'listing widget', 'wpcasa-madrid' ),
				'4000'	=> '4 ' . _x( 'seconds', 'listing widget', 'wpcasa-madrid' ),
				'6000'	=> '6 ' . _x( 'seconds', 'listing widget', 'wpcasa-madrid' ),
				'8000'	=> '8 ' . _x( 'seconds', 'listing widget', 'wpcasa-madrid' ),
				'10000'	=> '10 ' . _x( 'seconds', 'listing widget', 'wpcasa-madrid' ),
			),
			'desc'		=> __( 'Please select the autoplay interval timeout', 'wpcasa-madrid' ),
			'attributes' => array(
				'data-conditional-id' 		=> '_header_display',
				'data-conditional-value'	=> 'listings_slider',
			),
			'default'	=> 6000,
			'priority'  => 80
		),
		'featured_image' => array(
			'name'      => '',
			'id'        => '_featured_image_remove',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Featured Image', 'wpcasa-madrid' ),
			'desc'      => __( 'Remove featured image from content area', 'wpcasa-madrid' ),
			'priority'  => 150
		),
		'page_title' => array(
			'name'      => '',
			'id'        => '_page_title_remove',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Page Title', 'wpcasa-madrid' ),
			'desc'      => __( 'Remove page title from content area', 'wpcasa-madrid' ),
			'priority'  => 160
		),
	);
	
	if( class_exists( 'WPSight_Listings_Map' ) )		
		$fields['display']['options']['listings_map'] = __( 'Listings map', 'wpcasa-madrid' );
	
	if( ! get_theme_mod( 'deactivate_header_overlay' ) ) {
		
		$fields['header_filter'] = array(
			'name'      => '',
			'id'        => '_header_filter',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Header Filter', 'wpcasa-madrid' ),
			'desc'      => __( 'Remove filter effect from header images', 'wpcasa-madrid' ),
			'attributes' => array(
				'data-conditional-id'		=> '_header_display',
				'data-conditional-value'	=> json_encode( array( 'featured_image', 'tagline', 'image_slider' ) )
			),
			'priority'  => 40
		);
		
	}
	
	if( get_theme_mod( 'wpcasa_logo_alt' ) ) {

		$fields['logo_alt'] = array(
			'name'      => '',
			'id'        => '_logo_alt',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Alt Logo', 'wpcasa-madrid' ),
			'desc'      => __( 'Use alternative logo on this page', 'wpcasa-madrid' ),
			'priority'  => 70
		);
	
	}
	
	// Add taxonomy filters
	
	foreach( $taxonomies as $taxonomy ) {
		
		$get_terms = get_terms( $taxonomy->name, array( 'hide_empty' => false ) );
		
		$terms = array(
			'' => __( 'None', 'wpcasa-madrid' )
		);
		
		foreach ( $get_terms as $term ) {
			$terms[ $term->slug ] = $term->name;
		}
		
		$fields[ $taxonomy->name ] = array(
			'name'		=> __( 'Filter by', 'wpcasa-madrid' ) . ': ' . $taxonomy->labels->singular_name,
			'desc'		=> '',
			'attributes' => array(
				'data-conditional-id' 		=> '_header_display',
				'data-conditional-value'	=> json_encode( array( 'listings_slider', 'listings_map' ) ),
			),
			'id'		=> '_listings_taxonomy_' . $taxonomy->name,
			'options'	=> $terms,
			'type'		=> 'select',
			'priority'  => 25
		);

	}

	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_madrid_meta_boxes_header_general_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'header',
		'title'			=> __( 'Header Settings', 'wpcasa-madrid' ),
		'object_types'	=> array( 'page', 'post' ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_madrid_meta_boxes_header_general', $meta_box );
	
}

/**
 *	wpsight_madrid_meta_boxes_header_listings()
 *
 *	Set up header settings meta boxes.
 *
 *	@uses	wpsight_sort_array_by_priority()
 *	@return	array	$meta_box	Array of meta box
 *
 *	@since 1.0.0
 */
function wpsight_madrid_meta_boxes_header_listings() {
	
	// Set meta box fields

	$fields = array(
		'display' => array(
			'name'      => __( 'Display', 'wpcasa-madrid' ),
			'id'        => '_header_display',
			'type'      => 'select',
			'options'	=> array(
				''					=> __( 'Do not display', 'wpcasa-madrid' ),
				'tagline'			=> __( 'Tagline & background', 'wpcasa-madrid' ),
				'featured_image'	=> __( 'Featured image & title', 'wpcasa-madrid' ),
				'image_slider'		=> __( 'Image slider', 'wpcasa-madrid' ),
			),
			'desc'      => __( 'Display header area on this page', 'wpcasa-madrid' ),
			'priority'  => 10
		),
		'tagline_bg' => array(
			'name'      => __( 'Tagline Background', 'wpcasa-madrid' ),
			'id'        => '_tagline_bg',
			'type'      => 'file',
			'desc'      => __( 'Add an image to be displayed as tagline background', 'wpcasa-madrid' ),
			'attributes' => array(
				'data-conditional-id' 		=> '_header_display',
				'data-conditional-value'	=> 'tagline',
			),
			'priority'  => 20
		),
		'tagline_text' => array(
			'name'      => __( 'Tagline Text', 'wpcasa-madrid' ),
			'id'        => '_tagline_text',
			'type'      => 'textarea_small',
			'desc'      => __( 'Please enter the text for the tagline', 'wpcasa-madrid' ),
			'attributes' => array(
				'data-conditional-id' 		=> '_header_display',
				'data-conditional-value'	=> 'tagline',
			),
			'priority'  => 30
		),
	);
	
	if( ! get_theme_mod( 'deactivate_header_overlay' ) ) {
		
		$fields['header_filter'] = array(
			'name'      => '',
			'id'        => '_header_filter',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Header Filter', 'wpcasa-madrid' ),
			'desc'      => __( 'Remove filter effect from header images', 'wpcasa-madrid' ),
			'attributes' => array(
				'data-conditional-id'		=> '_header_display',
				'data-conditional-value'	=> json_encode( array( 'featured_image', 'tagline', 'image_slider' ) )
			),
			'priority'  => 40
		);
		
	}
	
	if( get_theme_mod( 'wpcasa_logo_alt' ) ) {

		$fields['logo_alt'] = array(
			'name'      => '',
			'id'        => '_logo_alt',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Alt Logo', 'wpcasa-madrid' ),
			'desc'      => __( 'Use alternative logo on this page', 'wpcasa-madrid' ),
			'priority'  => 50
		);
	
	}

	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_madrid_meta_boxes_header_listings_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'header_listings',
		'title'			=> __( 'Header Settings', 'wpcasa-madrid' ),
		'object_types'	=> array( wpsight_post_type() ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_madrid_meta_boxes_header_listings', $meta_box );
	
}

/**
 *	wpsight_madrid_meta_boxes_gallery()
 *
 *	Set up page/post gallery meta boxes.
 *
 *	@uses	wpsight_sort_array_by_priority()
 *	@return	array	$meta_box	Array of meta box
 *
 *	@since 1.0.0
 */
function wpsight_madrid_meta_boxes_gallery() {
	
	// Set meta box fields

	$fields = array(
		'gallery' => array(
			'name'       => __( 'Images', 'wpcasa' ),
			'id'         => '_gallery',
			'type'       => 'file_list',
			'preview_size' => array( 150, 150 ),
			'sortable'   => true,
			'desc'       => false
		)
	);

	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_madrid_meta_boxes_gallery_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'gallery',
		'title'			=> __( 'Gallery', 'wpcasa-madrid' ),
		'object_types'	=> array( 'page', 'post' ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_madrid_meta_boxes_gallery', $meta_box );
	
}

/**
 *	wpsight_madrid_meta_boxes_listings_query()
 *
 *	Set up listings query meta box.
 *
 *	@uses	wpsight_offers()
 *	@uses	wpsight_post_type()
 *	@uses	get_object_taxonomies()
 *	@uses	get_terms()
 *	@uses	wpsight_madrid_checkbox_default()
 *	@uses	wpsight_sort_array_by_priority()
 *	@return	array	$meta_box	Array of meta box
 *
 *	@since 1.0.0
 */
function wpsight_madrid_meta_boxes_listings_query() {
	
	// Prepare offers
	$offers = wpsight_offers();
	
	// Prepare taxonomies
	$taxonomies = wpsight_taxonomies();
	
	// Set meta box fields

	$fields = array(
		'nr' => array(
			'name'      => __( 'Listings', 'wpcasa-madrid' ),
			'id'        => '_listings_query_nr',
			'type'      => 'text',
			'desc'      => __( 'Please enter the number of listings to be displayed', 'wpcasa-madrid' ),
			'default'	=> 6,
			'priority'  => 10
		),
		'offer' => array(
			'name'      => __( 'Filter by', 'wpcasa-madrid' ) . ' ' . __( 'Offer', 'wpcasa-madrid' ),
			'id'        => '_listings_query_offer',
			'type'      => 'select',
			'desc'      => '',
			'options'	=> array_merge( array( '' => __( 'None', 'wpcasa-madrid' ) ), $offers ),
			'priority'  => 20
		),
		'panel' => array(
			'name'      => '',
			'id'        => '_listings_query_panel',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Show Panel', 'wpcasa-madrid' ),
			'desc'		=> __( 'Check this box to show the listings panel before the query', 'wpcasa-madrid' ),
			'default'	=> wpsight_madrid_checkbox_default( true ),
			'priority'  => 100
		),
		'paging' => array(
			'name'      => '',
			'id'        => '_listings_query_paging',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Show Pagination', 'wpcasa-madrid' ),
			'desc'		=> __( 'Check this box to show pagination options after the query', 'wpcasa-madrid' ),
			'default'	=> wpsight_madrid_checkbox_default( true ),
			'priority'  => 110
		),
	);
	
	// Add taxonomy filters
	
	foreach( $taxonomies as $taxonomy ) {
		
		$get_terms = get_terms( $taxonomy->name, array( 'hide_empty' => false ) );
		
		$terms = array(
			'' => __( 'None', 'wpcasa-madrid' )
		);
		
		foreach ( $get_terms as $term ) {
			$terms[ $term->slug ] = $term->name;
		}
		
		$fields[ $taxonomy->name ] = array(
			'name'		=> __( 'Filter by', 'wpcasa-madrid' ) . ': ' . $taxonomy->labels->singular_name,
			'desc'		=> '',
			'id'		=> '_listings_query_taxonomy_' . $taxonomy->name,
			'options'	=> $terms,
			'type'		=> 'select',
			'priority'  => 30
		);

	}

	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_madrid_meta_boxes_listings_query_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'listings_query',
		'title'			=> __( 'Listings Query', 'wpcasa-madrid' ),
		'object_types'	=> array( 'page' ),
		'show_on'		=> array( 'key' => 'page-template', 'value' => array( 'page-tpl-listings-query.php', 'page-tpl-listings-query-full.php' ) ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_madrid_meta_boxes_listings_query', $meta_box );
	
}

/**
 *	wpsight_madrid_checkbox_default()
 *	
 *	Helper function to set check box defaults.
 *	Only return default value if we don't
 *	have a post ID (in the 'post' query variable).
 *
 *	@param	bool	$default On/Off (true/false)
 *	@return	mixed	Returns true or '', the blank default
 *
 *	@since 	1.0.0
 */
function wpsight_madrid_checkbox_default( $default ) {
    return isset( $_GET['post'] ) ? '' : ( $default ? (string) $default : '' );
}

add_filter( 'wp_import_post_meta', 'themo_import_post_meta', 10, 3 );

function themo_import_post_meta( $post_meta, $post_id, $post ) {
    return maybe_unserialize( str_replace( array("\r\n", "\r", "\n"), "\r\n", $post_meta ) );    
}

/**
 *	Modify CMB2 Default Form Output
 *
 *	We add some Boostrap classes to the
 *	default CMB2 front end output here.
 *	
 *	@since	1.0.0
 */

/** Input and Textarea */

add_filter( 'cmb2_input_attributes', 'wpsight_madrid_cmb2_input_attributes', 10, 2 );
add_filter( 'cmb2_textarea_attributes', 'wpsight_madrid_cmb2_input_attributes', 10, 2 );

function wpsight_madrid_cmb2_input_attributes( $args, $defaults ) {
	
	$args['class'] = isset( $args['class'] ) ? $args['class'] . ' form-control' : 'form-control';
		
	return $args;
	
}

/** Select */

add_filter( 'cmb2_select_attributes', 'wpsight_madrid_cmb2_select_attributes', 10, 4 );

function wpsight_madrid_cmb2_select_attributes( $args, $defaults, $field, $object ) {
	
	$args['class'] = isset( $args['class'] ) ? $args['class'] . ' selectpicker form-control' : 'selectpicker form-control';
	
	// Let's order the terms hierarchically the hacky way :(
	
	if( isset( $field->args['taxonomy'] ) && is_taxonomy_hierarchical( $field->args['taxonomy'] ) ) {
		
		$names      = $object->get_object_terms();
		$saved_term = is_wp_error( $names ) || empty( $names ) ? $field->args( 'default' ) : $names[key( $names )]->slug;
		
		$terms = get_terms( array(
		    'taxonomy'		=> $field->args( 'taxonomy' ),
		    'hide_empty'	=> false,
		) );
		
		$options    = '';

		$option_none  = $field->args( 'show_option_none' );
		if ( ! empty( $option_none ) ) {
			$option_none_value = apply_filters( 'cmb2_taxonomy_select_default_value', '' );
			$option_none_value = apply_filters( "cmb2_taxonomy_select_{$object->_id()}_default_value", $option_none_value );

			$options .= $object->select_option( array(
				'label'   => $option_none,
				'value'   => $option_none_value,
				'checked' => $saved_term == $option_none_value,
			) );
		}
		
		$terms_hierarchical = array();
		
		wpsight_madrid_sort_terms_hierarchically( $terms, $terms_hierarchical );

		foreach ( $terms_hierarchical as $term ) {

			$options .= $object->select_option( array(
				'label'   => str_repeat( '&ndash; ', count( get_ancestors( $term->term_id, $field->args( 'taxonomy' ) ) ) ) . $term->name,
				'value'   => $term->slug,
				'checked' => $saved_term == $term->slug,
			) );
			
			if( isset( $term->children ) && is_array( $term->children ) ) {
				
				foreach ( $term->children as $term_child ) {
					$options .= $object->select_option( array(
						'label'   => str_repeat( '&ndash; ', count( get_ancestors( $term_child->term_id, $field->args( 'taxonomy' ) ) ) ) . $term_child->name,
						'value'   => $term_child->slug,
						'checked' => $saved_term == $term_child->slug,
					) );
					
					if( isset( $term_child->children ) && is_array( $term_child->children ) ) {
					
						foreach ( $term_child->children as $term_child_2 ) {
							$options .= $object->select_option( array(
								'label'   => str_repeat( '&ndash; ', count( get_ancestors( $term_child_2->term_id, $field->args( 'taxonomy' ) ) ) ) . $term_child_2->name,
								'value'   => $term_child_2->slug,
								'checked' => $saved_term == $term_child_2->slug,
							) );
							
							if( isset( $term_child_2->children ) && is_array( $term_child_2->children ) ) {
							
								foreach ( $term_child_2->children as $term_child_3 ) {
									$options .= $object->select_option( array(
										'label'   => str_repeat( '&ndash; ', count( get_ancestors( $term_child_3->term_id, $field->args( 'taxonomy' ) ) ) ) . $term_child_3->name,
										'value'   => $term_child_3->slug,
										'checked' => $saved_term == $term_child_3->slug,
									) );
									
									if( isset( $term_child_3->children ) && is_array( $term_child_3->children ) ) {
							
										foreach ( $term_child_3->children as $term_child_4 ) {
											$options .= $object->select_option( array(
												'label'   => str_repeat( '&ndash; ', count( get_ancestors( $term_child_4->term_id, $field->args( 'taxonomy' ) ) ) ) . $term_child_4->name,
												'value'   => $term_child_4->slug,
												'checked' => $saved_term == $term_child_4->slug,
											) );
										}

									} // $term_child_3->children

								}

							} // $term_child_2->children

						}

					} // $term_child->children

				}

			} // $term->children

		}
		
		$args['options'] = $options;
		
	}
		
	return $args;
	
}

/**
 *	Helper function to sort terms hierarchically
 *
 *	@credit	http://wordpress.stackexchange.com/a/99516
 */
function wpsight_madrid_sort_terms_hierarchically( array &$cats, array &$into, $parentId = 0 ) {
	
    foreach ($cats as $i => $cat) {
        if ($cat->parent == $parentId) {
            $into[$cat->term_id] = $cat;
            unset($cats[$i]);
        }
    }

    foreach ($into as $topCat) {
        $topCat->children = array();
        wpsight_madrid_sort_terms_hierarchically($cats, $topCat->children, $topCat->term_id);
    }
}

/** Taxonomy Multicheck */

add_filter( 'cmb2_taxonomy_multicheck_attributes', 'wpsight_madrid_cmb2_taxonomy_multicheck_attributes', 10, 2 );

function wpsight_madrid_cmb2_taxonomy_multicheck_attributes( $args, $defaults ) {
	
	$args['class'] = isset( $args['class'] ) ? $args['class'] . ' checkbox checkbox-primary' : 'checkbox checkbox-primary';
		
	return $args;
	
}

/** Radio */

add_filter( 'cmb2_radio_attributes', 'wpsight_madrid_cmb2_radio_attributes', 10, 2 );

function wpsight_madrid_cmb2_radio_attributes( $args, $defaults ) {
	
	$args['class'] = isset( $args['class'] ) ? $args['class'] . ' radio radio-primary' : 'radio radio-primary';
		
	return $args;
	
}

/** Submit Button */

add_filter( 'cmb2_get_metabox_form_format', 'wpsight_madrid_cmb2_get_metabox_form_format', 10, 3 );

function wpsight_madrid_cmb2_get_metabox_form_format( $form_format, $object_id, $cmb ) {
	
	if( ! is_admin() )
		$form_format = str_replace( 'button', 'button btn btn-primary btn-lg', $form_format );
	
	return $form_format;
	
}

/** Checkbox Row Classes */

add_filter( 'cmb2_row_classes', 'wpsight_madrid_row_classes', 10, 2 );

function wpsight_madrid_row_classes( $classes, $field ) {
	
	$classes = str_replace( 'cmb-type-checkbox', 'cmb-type-checkbox checkbox checkbox-primary', $classes );
	
	return $classes;
	
}

/** Hide Duplicate Label for Single Checkboxes */

add_filter( 'wpsight_meta_box_listing_location_fields', 'wpsight_meta_box_listing_location_fields' );

function wpsight_meta_box_listing_location_fields( $fields ) {
	
	$fields['hide']['name'] = false;
	
	return $fields;
	
}
