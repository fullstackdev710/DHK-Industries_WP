<?php
/**
 * Single Content
 * 
 * @since 1.0.0
 */
?>
<?php if ( has_post_thumbnail() ) : ?>
    <div class="n-blog-archive__thumbnail">
        <a href="<?php the_permalink() ?>">
            <?php the_post_thumbnail() ?>
        </a>
    </div>
<?php endif; ?>

<div class="n-blog-archive__content">
     <div class="n-blog-archive__meta">
        <div class="n-blog-archive__tags">
            <span><?php the_time( get_option( 'date_format' ) ) ?></span>
        </div>
    </div>

    <?php if ( get_the_title() ) : ?>
        <h2 class="n-blog-archive__title">
            <a href="<?php the_permalink() ?>"><?php the_title() ?></a>
        </h2>
    <?php endif; ?>

    <div class="n-blog-archive__content">
        <?php the_content() ?>
    </div>

    <?php if ( get_the_category() ) { ?>
        <ul class="n-blog-archive__taxonomies">
            <li class="n-blog-archive__taxonomies--title"><?php echo esc_attr__( 'Categories:', 'archzilla' ) . ' ' ?></li>
            <?php foreach ( get_the_category() as $category ) : ?>
                <li><a href="<?php echo esc_url( get_category_link( $category->term_id ) ) ?>"><?php echo esc_attr( $category->name ) ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php } ?>

    <?php if ( get_the_tags() ) { ?>
        <ul class="n-blog-archive__taxonomies n-blog-archive__tags--cloud">
            <li class="n-blog-archive__taxonomies--title"><?php echo esc_attr__( 'Tags:', 'archzilla' ) . ' ' ?></li>
            <?php foreach ( get_the_tags() as $tag ) : ?>
                <li><a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ) ?>"><?php echo esc_attr( $tag->name ) ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php } ?>

    <?php wp_link_pages( array( 'before' => '<div class="n-site-pagination n-site-pagination--pages"><span class="n-site-pagination__title">' . esc_attr__( 'Pages:', 'archzilla' ) . '</span><div class="n-site-pagination--pages__numbers">', 'after' => '</div></div>', 'link_before' => '<span>', 'link_after' => '</span>', 'next_or_number' => 'next_and_number', 'separator' => '', 'nextpagelink' => esc_attr__( '&raquo;', 'archzilla' ), 'previouspagelink' => esc_attr__( '&laquo;', 'archzilla' ), 'pagelink' => '%' ) ); ?>
    
    <?php paginate_links() ?> 
</div>




            
        