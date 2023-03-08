<?php 
/**
 * Searchform
 * 
 * @since 1.0.0
 */
?>
<form action="<?php echo esc_url( home_url( '/' ) ) ?>" method="get" class="n-site-searchform">
    <input placeholder="<?php echo esc_attr__( 'Search...', 'archzilla' ) ?>" type="search" name="s" id="search" />
</form>
