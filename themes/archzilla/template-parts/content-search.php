<?php
/**
 * Search Content Nothing found
 * 
 * @since 1.0.0
 */
?>
<main id="primary" class="main">
    <?php do_action( 'n-theme/open/container' ) ?>

    <div class="n-blog-archive">
        <div class="n-blog-archive__search">
            <h1><?php echo wp_kses_post( __( 'Nothing Found', 'archzilla' ) ) ?></h1>
            <p><?php echo wp_kses_post( __( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'archzilla' ) ) ?></p>
            <?php get_search_form() ?>
        </div>
    </div>



    <?php do_action( 'n-theme/close/container' ) ?>
</main>