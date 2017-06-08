<?php

add_action( 'after_setup_theme', function () {
    // load custom translation file for the parent theme
    load_theme_textdomain( 'wpcasa-madrid', get_stylesheet_directory() . '/languages/wpcasa-madrid' );
	load_theme_textdomain( 'wpcasa-listings-map', get_stylesheet_directory() . '/languages/wpcasa-madrid' );
    
    // load translation file for the child theme
    //load_child_theme_textdomain( 'my-child-theme', get_stylesheet_directory() . '/languages' );
} );

add_action( 'wp_enqueue_scripts', function () {

    $parent_style = 'wpcasa-madrid';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'investim-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
} );

?>