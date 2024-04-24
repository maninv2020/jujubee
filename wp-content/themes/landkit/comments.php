<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Landkit
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

?>
<div id="comments" class="mt-9">

	<?php if ( have_comments() ) : ?>
		<div class="mb-6">
			<h3 class="font-weight-bold">
				<?php
				// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
				printf(
					/* translators: 1: number of comments, 2: post title */
					esc_html( _nx( '%1$s Comment', '%1$s Comments ', get_comments_number(), 'comments title', 'landkit' ) ),
					number_format_i18n( get_comments_number() ),
					get_the_title()
				);
				// phpcs:enable
				?>
			</h3>
		</div>

		<div class="list-unstyled comment-list">
			<?php
			wp_list_comments(
				[
					/* translators: comment reply text */
					'reply_text'  => esc_html__( 'Reply', 'landkit' ),
					'format'      => 'html5',
					'avatar_size' => 50,
					'walker'      => new Landkit_Comment_Walker(),
					'style'       => 'div',
				]
			);
			?>
		</div><!-- .comment-list -->	

		<?php landkit_comments_navigation(); ?>

		<?php if ( ! comments_open() ) : ?>
			<div class="alert alert-warning mt-4 mb-0" role="alert">
				<span class="no-comments"><?php echo esc_html__( 'Comments are closed.', 'landkit' ); ?></span>
			</div>
		<?php endif; ?>

		<?php
	endif;

	comment_form(
		apply_filters(
			'landkit_comment_form_args',
			[
				'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title mb-4 font-weight-bold">',
				'title_reply_after'    => '</h3>',
				'submit_field'         => '<div class="form-group form-submit d-flex justify-content-center mb-0">%1$s%2$s</div>',
				'submit_button'        => '<button type="submit" name="%1$s" id="%2$s" class="%3$s">%4$s</button>',
				'class_submit'         => 'btn btn-primary btn-wide transition-3d-hover',
				'comment_notes_after'  => '',
				'comment_notes_before' => sprintf(
					'<p class="font-size-sm text-muted">%s %s <span class="text-danger">*</span></p>',
					esc_html__( 'Your email address will not be published.', 'landkit' ),
					/* translators: related to comment form; phrase follows by red mark*/
					esc_html__( 'Required fields are marked', 'landkit' )
				),
				'comment_field'        => sprintf(
					'<div class="form-group comment-form-comment mb-5">
				<label class="input-label" for="comment">%s</label>
				<textarea id="comment" name="comment" class="form-control" rows="8" maxlength="65525" required></textarea>
			</div>',
					/* translators: label for textarea in comment form */
					esc_html__( 'Comment', 'landkit' )
				),
				'class_container'      => 'comment-respond bg-light border p-6',
			]
		)
	);
	?>
</div>
<?php
