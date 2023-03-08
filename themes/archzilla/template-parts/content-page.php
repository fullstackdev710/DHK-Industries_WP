<?php
/**
 * Page Content
 * 
 * @since 1.0.0
 */

if ( have_posts() ) {
    while ( have_posts() ) {

        the_post();

        the_content();

        wp_link_pages( array( 'before' => '<div class="n-site-pagination n-site-pagination--pages"><span class="n-site-pagination__title">' . esc_attr__( 'Pages:', 'archzilla' ) . '</span><div class="n-site-pagination--pages__numbers">', 'after' => '</div></div>', 'link_before' => '<span>', 'link_after' => '</span>', 'next_or_number' => 'next_and_number', 'separator' => '', 'nextpagelink' => esc_attr__( '&raquo;', 'archzilla'), 'previouspagelink' => esc_attr__('&laquo;', 'archzilla'), 'pagelink' => '%'));
        
        paginate_links();
    }
}