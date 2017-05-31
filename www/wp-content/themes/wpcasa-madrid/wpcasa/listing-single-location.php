<?php
/**
 * Template: Single Listing Location
 *
 * When used in a widget, the variables
 * $widget_args and $widget_instance are available.
 */
global $listing;

$lat  = get_post_meta( $listing->ID, '_geolocation_lat', true );
$long = get_post_meta( $listing->ID, '_geolocation_long', true );

$hide = get_post_meta( $listing->ID, '_map_hide', true );

$map_height			= isset( $widget_instance['map_height'] ) ? $widget_instance['map_height'] : 400;
$map_type			= isset( $widget_instance['map_type'] ) ? $widget_instance['map_type'] : 'ROADMAP';
$map_zoom			= isset( $widget_instance['map_zoom'] ) ? $widget_instance['map_zoom'] : 14;
$map_no_streetview	= isset( $widget_instance['map_no_streetview'] ) && $widget_instance['map_no_streetview'] ? 'false' : 'true';
$map_control_type	= isset( $widget_instance['map_control_type'] ) && ! $widget_instance['map_control_type'] ? 'false' : 'true';
$map_control_nav	= isset( $widget_instance['map_control_nav'] ) && ! $widget_instance['map_control_nav'] ? 'false' : 'true';
$map_scrollwheel	= isset( $widget_instance['map_scrollwheel'] ) && $widget_instance['map_scrollwheel'] ? 'true' : 'false';
$display_note		= isset( $widget_instance['display_note'] ) && ! $widget_instance['display_note'] ? false : true;

if( $lat && $long && ! $hide ) { ?>

	<div class="wpsight-listing-section wpsight-listing-section-location">
		
		<style>
	      #map-canvas {
	        width: 100%;
	        height: <?php echo absint( $map_height ); ?>px;
	      }
	      #map-canvas img {
		      max-width: 0;
	      }
	    </style>
	    <?php if( ! version_compare( WPSIGHT_VERSION, '1.0.6', '>' ) ) : ?>
	    <script src="https://maps.googleapis.com/maps/api/js"></script>
	    <?php endif; ?>
	    <?php
		    
		    // Set map default options
		    
		    $map_defaults = array(
				'map_type' 	   	    => $map_type,
				'control_type' 	    => $map_control_type,
				'control_nav'  	    => $map_control_nav,
				'scrollwheel'  	    => $map_scrollwheel,
				'streetview'   	    => $map_no_streetview,
				'map_zoom'			=> $map_zoom
		    );
		    
		    // Get map listing options

		    $map_options = array(
			    '_map_type' 			=> get_post_meta( $listing->ID, '_map_type', true ),
			    '_map_zoom' 			=> get_post_meta( $listing->ID, '_map_zoom', true ),
			    '_map_no_streetview' 	=> get_post_meta( $listing->ID, '_map_no_streetview', true )
		    );
		    
		    $map_args = array(
			    'map_type' 		=> ! empty( $map_options['_map_type'] ) ? $map_options['_map_type'] : $map_defaults['map_type'],
			    'map_zoom' 		=> ! empty( $map_options['_map_zoom'] ) ? $map_options['_map_zoom'] : $map_defaults['map_zoom'],
			    'streetview' 	=> ! empty( $map_options['_map_no_streetview'] ) ? $map_options['_map_no_streetview'] : $map_defaults['streetview']
		    );

			// Parse map args and apply filter		    
		    $map_args = apply_filters( 'wpsight_listing_map_args', wp_parse_args( $map_args, $map_defaults ) );
		    
		?>
	    <script>
	      function initialize() {
			  var myLatlng = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $long; ?>);
			  var mapOptions = {
			    zoom: 				<?php echo $map_args['map_zoom']; ?>,
			    mapTypeId: 			google.maps.MapTypeId.<?php echo $map_args['map_type']; ?>,
			    mapTypeControl: 	<?php echo $map_args['control_type']; ?>,
			    navigationControl: 	<?php echo $map_args['control_nav']; ?>,
			    scrollwheel: 		<?php echo $map_args['scrollwheel']; ?>,
			    streetViewControl: 	<?php echo $map_args['streetview']; ?>,
			    center: myLatlng
			  }
			  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
			
			  var marker = new google.maps.Marker({
			      position: myLatlng,
			      map: map,
			      title: '<?php echo esc_attr( $listing->post_title ); ?>'
			  });
			}
			
			google.maps.event.addDomListener(window, 'load', initialize);
	    </script>
	    
	    <div itemprop="availableAtOrFrom" itemscope itemtype="http://schema.org/Place">
		
			<?php do_action( 'wpsight_listing_single_location_before', $listing->ID ); ?>
			
			<div class="wpsight-listing-location" itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">
			
				<div id="map-canvas"></div>
				
				<meta itemprop="latitude" content="<?php echo $lat; ?>" />
				<meta itemprop="longitude" content="<?php echo $long; ?>" />
				
				<?php if( ! empty( $listing->_map_note ) && $display_note ) : ?>
				<div class="wpsight-listing-location-note bs-callout bs-callout-primary bs-callout-small" role="alert">
					<?php echo wp_kses_post( $listing->_map_note ); ?>
				</div>
				<?php endif; ?>
				
			</div>
			
			<?php do_action( 'wpsight_listing_single_location_after', $listing->ID ); ?>
		
	    </div>
	
	</div><!-- .wpsight-listing-section -->

<?php } // endif $lat && $long ?>