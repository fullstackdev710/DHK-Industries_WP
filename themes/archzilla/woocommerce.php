<?php
/**
 * WooCommerce
 * 
 * @since 1.0.0
 */

get_header();

/**
 * Before WooCommerce Print
 */
do_action( 'neuron/woocommerce/print/before_content' );
?>
<main id="primary" class="main">
    <?php 
        if ( have_posts() ) {
            woocommerce_content();
        }
    ?>
</main>
<?php get_footer(); ?>