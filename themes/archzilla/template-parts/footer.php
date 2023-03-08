<?php
/**
 * Footer Part
 */

$archzilla_footer_copyright = sprintf(
    '%s %s %s. %s',
    '©',
    date( 'Y' ),
    get_bloginfo( 'name' ),
    esc_html__( 'All rights reserved.', 'archzilla' )
);
?>
<footer class="n-site-footer">
    <?php do_action( 'n-theme/open/container', 'container' ) ?>
    
    <div class="n-site-footer__copyright">
        <?php echo wpautop( wp_kses_post( $archzilla_footer_copyright ) ) ?>
    </div>

    <?php do_action( 'n-theme/close/container' ) ?>
</footer>

<?php get_template_part( 'template-parts/overlay-search' ) ?>

