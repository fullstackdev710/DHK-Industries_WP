<?php
/**
 * Theme functions and definitions.
 */
function archzilla_child_enqueue_styles() {

wp_enqueue_style( 'archzilla-style' , get_template_directory_uri() . '/style.css' );

    wp_enqueue_style( 'archzilla-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'archzilla-style' ),
        wp_get_theme()->get('Version')
    );
}

add_action(  'wp_enqueue_scripts', 'archzilla_child_enqueue_styles' );