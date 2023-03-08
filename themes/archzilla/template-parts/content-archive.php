<?php 
/**
 * Blog Archive 
 * 
 * @since 1.0.0
 */

// Paged
if ( get_query_var( 'paged' ) ) {
    $paged = get_query_var( 'paged' );
} elseif ( get_query_var( 'page' ) ) {
    $paged = get_query_var( 'page' ) ;
} else {
    $paged = 1;
}

$args = array(
    'post_type' => 'post',
    'paged' => $paged
);

if ( is_home() ) {
    $query = new WP_Query( $args );
} else {
    $query = $wp_query;
}

$archzilla_wrapper = 'n-blog-archive--wrapper';

if ( ! is_active_sidebar( 'main-sidebar' ) ) {
    $archzilla_wrapper .= ' n-blog-archive--wrapper__no-sidebar';
}

if ( $query->have_posts() ) :
?>
    <main id="primary" class="main">
        <?php do_action( 'n-theme/open/container', 'n-container' ); ?>

        <?php  if ( is_archive() ) { ?>
            <div class="n-blog-archive__topbar">
                <div class="n-blog-archive__breadcrumb">
                    <span><a href="<?php echo esc_url( home_url( '/' ) ) ?>"><?php echo esc_html__( 'Home', 'archzilla' ); ?></a></span>
                    <span><?php single_term_title(); ?></span>
                </div>
                <h3 class="n-blog-archive__page-title n-blog-archive__page-title--search entry-title"><?php the_archive_title(); ?></h3>
            </div>
        <?php } ?>
        
        <div class="<?php echo esc_attr( $archzilla_wrapper ) ?>">
            <div class="n-blog-archive">

                <div class="n-blog-archive__posts">
                    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                        <article <?php post_class( 'n-blog-archive__post' ); ?> id="id-<?php the_ID(); ?>" data-id="<?php the_ID(); ?>"> 
                            <?php get_template_part( 'template-parts/content', 'blog' ); ?>
                        </article>
                    <?php endwhile; ?>
                </div>

                <?php archzilla_pagination(); ?>

            </div>

            <?php if ( is_active_sidebar( 'main-sidebar' ) ) :  ?>
                <div class="n-blog-archive__sidebar">
                    <?php get_sidebar() ?>
                </div>
            <?php endif; ?>
        </div>

        <?php do_action( 'n-theme/close/container' ); ?>
    </main>

   
<?php 
endif; wp_reset_postdata();