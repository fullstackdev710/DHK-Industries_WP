<?php
/**
 * Elementor Single
 * 
 * Hides the templates that
 * are assigned through Elementor.
 * 
 * @since 1.0.0
 */

add_filter( 'archzilla_display_header', '__return_false' );
add_filter( 'archzilla_display_footer', '__return_false' );
add_filter( 'archzilla_display_header_template', '__return_false' );

get_header();

if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();
        the_content();
    }
}

get_footer();