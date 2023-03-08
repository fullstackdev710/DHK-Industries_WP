<?php
/**
 * Page
 * 
 * @since 1.0.0
 */

get_header();
?>
    <main class="main">
        <?php do_action( 'n-theme/open/container', 'n-container' ) ?>

        <?php the_title( '<h1 class="entry-title">', '</h1>' ) ?>

        <?php get_template_part( 'template-parts/content', 'page' ) ?>

        <?php do_action( 'n-theme/close/container' ) ?>
    </main>
<?php
comments_template();

get_footer();