<?php
/**
 * Template Name: Home
 *
 * This is the home page template.
 *
 * @package WPCasa Madrid
 */
global $post;

get_header();

// Set default set of home page elements
$home_elements = array( 'search', 'carousel', 'cta-1', 'services', 'listings', 'cta-2' );

// Create sorted set of elements
$home_sorted = array();

$index = 0;

foreach( $home_elements as $element ) {
	
	// Get priority from post meta
	$element_priority = get_post_meta( get_the_id(), '_priority_' . wpsight_underscores( $element ), true );
	
	// Set element priority or default index
	$priority = ! empty( $element_priority ) ? absint( $element_priority ) : $index;
	
	// Create array with priority
	$home_sorted[ $element ] = array(
		'priority' => $priority
	);
	
	$index = $index + 10;
	
}

// Finally sort elements by priority
$home_sorted = wpsight_sort_array_by_priority( $home_sorted ); ?>

	<?php get_template_part( 'template-parts/header', 'home' ); ?>
	
	<?php get_template_part( 'template-parts/home', 'content' ); ?>
	
	<?php foreach( $home_sorted as $key => $home_element ) : ?>	
		<?php get_template_part( 'template-parts/home', $key ); ?>	
	<?php endforeach; ?>

<?php get_footer(); ?>