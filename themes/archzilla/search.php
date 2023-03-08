<?php 
/**
 * Search Page
 * 
 * @since 1.0.0
 */

get_header();

/**
 * Before Archive Print
 */
do_action( 'n-theme/archive/print/before_content' );

// Display Filter
if ( apply_filters( 'archzilla', true ) ) :

/**
 * Arguments
 * 
 * Modify the query with
 * different arguments properties.
 */
$args = array_merge(
    $wp_query->query_vars, 
    array( 'post_type' => [ 'post', 'page' ] )
);

$query = new WP_Query( $args );
?>
<?php if ( ! $query->have_posts() ) : ?>
    <?php get_template_part( 'template-parts/content', 'search' ); ?>
<?php endif; ?>

<?php if ( $query->have_posts() ) : ?>
    <main id="primary" class="main">
        <?php do_action( 'n-theme/open/container', 'n-container' ); ?>

       <div class="n-blog-archive n-blog-archive--single">
       
            <h2 class="n-blog-archive__title n-blog-archive__title--search"><?php echo esc_html__( 'Search for: ', 'archzilla' ); ?><?php the_search_query(); ?></h2>

            <div class="n-blog-archive__posts">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <article <?php post_class( 'n-blog-archive__post' ); ?> id="id-<?php the_ID(); ?>" data-id="<?php the_ID(); ?>"> 
                        <?php get_template_part( 'template-parts/content', 'blog' ); ?>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php archzilla_pagination(); ?>

        </div>

        <?php do_action( 'n-theme/close/container' ); ?>
    </main>
<?php 
endif; wp_reset_postdata();

endif; // Display Filter Ending

get_footer();