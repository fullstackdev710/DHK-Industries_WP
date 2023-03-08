<?php
/**
 * Blog Content
 * 
 * @since 1.0.0
 */
?>

<div class="n-blog-archive__post--inner">
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="n-blog-archive__thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail(); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="n-blog-archive__meta">
            <div class="n-blog-archive__tags">
                <span><?php the_time( get_option( 'date_format' ) ); ?></span>
                <?php if ( get_the_category() ) { 
                    $cat = get_the_category()[0];
                    ?>
                    <span><a href="<?php echo esc_url( get_category_link( $cat ) ) ?>"><?php echo esc_attr( $cat->cat_name ) ?></a></span>
                <?php } ?>
            </div>
        </div>

    <div class="n-blog-archive__post--text">
        
        <?php if ( get_the_title() ) : ?>
            <h2 class="n-blog-archive__title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
        <?php endif; ?>
        
        <?php 
        /**
         * Content
         */
        the_excerpt();

        /**
         * Read More
         * 
         * Show read more button
         * in case there is no title.
         */
        ?>

        <div class="a-read-more">
            <a href="<?php the_permalink(); ?>"><?php echo esc_attr__( 'Read More', 'archzilla' ); ?></a>
        </div>
    </div>
</div>


