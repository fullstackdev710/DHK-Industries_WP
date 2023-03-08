<?php 
/**
 * Index Page
 * 
 * @since 1.0.0
 */

get_header();

// Before Index Print
do_action( 'n-theme/archive/print/before_content' );

// Display Filter
if ( apply_filters( 'archzilla_display_archive', true ) ) {

    get_template_part( 'template-parts/content', 'archive' );

} // Display Filter Ending

get_footer();