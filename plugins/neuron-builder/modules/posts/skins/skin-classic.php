<?php
/**
 * Skin Classic
 * 
 * Used in Posts for
 * a classic style of
 * posts.
 * 
 * @since 1.0.0
 */

$post_items = [
    'thumbnail' => $this->get_post_thumbnail(),
    'title' => $this->get_post_title(),
    'meta-data' => $this->get_post_meta_data(),
    'excerpt' => $this->get_post_excerpt(),
    'read-more' => $this->get_read_more_button(),
];

$content_order_img = $settings['image_position'] == 'left' || $settings['image_position'] == 'right';
$content_order = $content_order_img ? $settings['content_order_image'] : $settings['content_order'];

?>
<div class="m-neuron-post__inner m-neuron-post__inner--classic">
    <?php if ( $settings['allow_content_order'] == 'yes' ) { ?>
    
        <?php if ( $content_order_img ) { ?>
            <?php echo $this->get_post_thumbnail() ?>
            <div class="m-neuron-post__text">
        <?php }

        foreach ( $content_order as $key => $value ) {
            echo $post_items[$value['type']];
        } 

        if ( $content_order_img ) { ?>
            </div>
        <?php } ?>
        
    <?php } else { ?>
        <?php echo $this->get_post_thumbnail() ?>
        <div class="m-neuron-post__text">
            <?php echo $this->get_post_title() ?>
            <?php echo $this->get_post_meta_data() ?>
            <?php echo $this->get_post_excerpt() ?>
            <?php echo $this->get_read_more_button() ?>
        </div>
    <?php } ?>
</div>