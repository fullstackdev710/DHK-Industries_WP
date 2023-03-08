<?php
/**
 * Skin Cards
 * 
 * Used in Posts for
 * a classic style of
 * posts.
 * 
 * @since 1.0.0
 */
?>
<div class="m-neuron-post__inner m-neuron-post__inner--card">
    <?php echo $this->get_post_thumbnail() ?>
    <?php echo $this->get_post_badge() ?>
    <?php echo $this->get_post_avatar() ?>
    <div class="m-neuron-post__text">
        <?php echo $this->get_post_title() ?>
        <?php echo $this->get_post_excerpt() ?>
        <?php echo $this->get_read_more_button() ?>
    </div>
    <?php echo $this->get_post_meta_data() ?>
</div>