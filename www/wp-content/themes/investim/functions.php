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

    wp_enqueue_script( 'fixed-menu', 
        get_stylesheet_directory_uri() . '/js/fixed-menu.js', 
        array( 'jquery' ), 
        '1.0', 
        true 
    );

    wp_enqueue_script( 'waypoints', 
        get_stylesheet_directory_uri() . '/js/jquery.waypoints.min.js', 
        array( 'jquery' ), 
        '4.0.1', 
        true 
    );

    wp_enqueue_script( 'animate-on-scroll', 
        get_stylesheet_directory_uri() . '/js/animate-on-scroll.js', 
        array( 'waypoints' ), 
        '1.0', 
        true 
    );

    wp_enqueue_script( 'jquery-masks', 
        get_stylesheet_directory_uri() . '/js/jquery.mask.min.js', 
        array( 'jquery' ), 
        '1.0', 
        true 
    );

    wp_enqueue_script( 'masks', 
        get_stylesheet_directory_uri() . '/js/masks.js', 
        array( 'jquery-masks' ), 
        '1.0', 
        true 
    );

});
?>