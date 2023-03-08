<?php
/**
 * Single Page
 * 
 * @since 1.0.0
 */

get_header();

// Single Post Loop
if ( have_posts() ) : while ( have_posts() ) : the_post();
?>
<section id="primary" class="main">
    <?php do_action( 'n-theme/open/container' ) ?>

    <div class="n-blog-archive n-blog-archive--single">
        <main id="main">
            <?php get_template_part( 'template-parts/content', 'single' ) ?>

            <?php comments_template() ?>
        </main>
    </div>

    <?php do_action( 'n-theme/close/container' ) ?>
</section>

<?php 
endwhile; endif; // Loop Ending

get_footer();