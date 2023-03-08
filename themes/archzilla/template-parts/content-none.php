<?php
/**
 * Search Content Nothing found
 * 
 * @since 1.0.0
 */
?>
<main id="primary" class="main">
    <?php do_action( 'n-theme/open/container' ) ?>

    <div class="o-ntheme-blog">
        <div class="o-ntheme-blog__posts">
            <h1><?php echo wp_kses_post( __( 'Nothing Found', 'archzilla' ) ) ?></h1>
            <p><?php echo wp_kses_post( __( 'The post you were looking for couldn\'t be found. The post could be removed or you misspelled the word while searching for it.', 'archzilla' ) ) ?></p>
        </div>
    </div>

    <?php do_action( 'n-theme/close/container' ) ?>
</main>