<?php
/**
 * Comments Template
 */
if ( post_password_required() ) {
	return;
}

$comments_args = array(
    'style'        => 'div',
	'callback'     => 'neuron_comments_open',
	'end-callback' => 'neuron_comments_close'
);

$comment_form =  array(
    'logged_in_as' => null,
    'comment_notes_before' => null,
    'title_reply_before' => '<h5 id="reply-title" class="o-comments__title d-flex align-items-center">',
    'title_reply_after' => '</h5>',
    'title_reply' => esc_attr__( 'Leave a Reply', 'neuron-builder' ),
    'submit_button' => '<div class="o-comments__form__submit d-flex"><div class="ml-auto"><input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" /></div></div>',
    'comment_field' => "<div class='o-comments__form__textarea row'><div class='col-12'><textarea placeholder=". esc_attr__( 'Comment', 'neuron-builder' ) ." type='text' name='comment' aria-required='true'/></textarea></div></div>",
    'fields' => apply_filters('comment_form_default_fields', array(
            'author' => "<div class='o-comments__form__inputs row'><div class='col-sm-4'><input placeholder=". esc_attr__( 'Name', 'neuron-builder' ) ." name='author' type='text' aria-required='true'/></div>",
        	'email' => "<div class='col-sm-4'><input placeholder=". esc_attr__( 'Email', 'neuron-builder' ) ." name='email' type='text' aria-required='true'/></div>",
        	'website' => "<div class='col-sm-4'><input placeholder=". esc_attr__( 'Website', 'neuron-builder' ) ." name='website' type='text'/></div></div>",
        )
    ),
);

if ( have_comments() || comments_open() ) :
?>
    <div class="o-comments-holder">
        <div class="container h-medium-bottom-padding clear-both">
            <div class="o-comments" id="comments">
                <div class="o-comments__area">
                    <h5 class="o-comments__title"><?php comments_number(esc_attr__( 'No Comments', 'neuron-builder' ), esc_attr__( 'One Comment', 'neuron-builder' ), esc_attr__( '% Comments', 'neuron-builder' )); ?></h5>
                    <div class="row">
                        <?php wp_list_comments($comments_args) ?>
                    </div>
                    <?php paginate_comments_links(); ?>
                </div>
                <?php if (comments_open()) : ?>
                    <div class="o-comments__form">
                        <?php comment_form($comment_form); ?>
                    </div>
                <?php elseif (!comments_open() && get_theme_mod('comments_notice', true) == true) : ?>
                    <div class="o-comments__closed">
                        <h5 class="o-comments__closed__title"><?php echo esc_attr__( 'Comments are closed!', 'neuron-builder' ) ?></h5>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php 
endif;