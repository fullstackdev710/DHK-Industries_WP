<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Comment Reply Script.
if ( comments_open() && get_option( 'thread_comments' ) ) {
	wp_enqueue_script( 'comment-reply' );
}

if ( post_password_required() ) {
	return;
}

if ( have_comments() || comments_open() ) :
?>
<section id="comments" class="comments-area">
	<div class="container">
		<?php if ( have_comments() ) : ?>
			<h4 class="title-comments">
				<?php
				$comments_number = get_comments_number();
				if ( '1' === $comments_number ) {
					printf( esc_html_x( 'One Response', 'comments title', 'archzilla' ) );
				} else {
					printf(
						esc_html( /* translators: 1: number of comments */
							_nx(
								'%1$s Response',
								'%1$s Responses',
								$comments_number,
								'comments title',
								'archzilla'
							)
						),
						esc_html( number_format_i18n( $comments_number ) )
					);
				}
				?>
			</h4>

			<?php the_comments_navigation(); ?>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 72,
				)
			);
			?>
		</ol><!-- .comment-list -->

		<?php the_comments_navigation(); ?>

	<?php endif; // Check for have_comments(). ?>

	<?php
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'archzilla' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
			'title_reply_after'  => '</h2>',
		)
	);
	?>
	</div>

</section><!-- .comments-area -->
<?php endif; ?>