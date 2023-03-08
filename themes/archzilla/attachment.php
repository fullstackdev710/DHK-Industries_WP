<?php 
/**
 * Attachment Page
 * 
 * @since 1.0.0
 */

get_header();

if ( have_posts() ) : while ( have_posts()) : the_post();
?>
    <div <?php post_class() ?> id="id-<?php the_ID() ?>" data-id="<?php the_ID() ?>"> 
        <?php do_action( 'n-theme/open/container' ) ?>

        <a href="<?php echo wp_get_attachment_url(get_the_ID()) ?>">
            <?php echo wp_get_attachment_image(get_the_ID(), 'full' ) ?>
        </a>
        
        <div><?php echo esc_url( wp_get_attachment_url( get_the_ID() ) ) ?></div>

        <?php do_action( 'n-theme/close/container' ) ?>
    </div>
<?php 
endwhile; endif; wp_reset_postdata();

get_footer();
