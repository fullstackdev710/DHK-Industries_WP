<?php 
/**
 * Global Logo
 * 
 * Prints the custom logo uploaded
 * in Customizer > Site Identity.
 * 
 * @since 1.0.0
 */
?>
<div class="n-site-branding">
    <?php
    if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
        the_custom_logo();
    } else {
    ?> 
        <a href="<?php echo esc_url( home_url( '/' ) ) ?>"><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></a>
    <?php } ?>
</div>