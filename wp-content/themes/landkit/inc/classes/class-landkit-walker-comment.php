<?php
/**
 * Customizing the comment HTML
 *
 * @package Landkit
 */
class Landkit_Comment_Walker extends Walker_Comment {
	/**
	 * Outputs a comment in the HTML5 format.
	 *
	 * @since 3.6.0
	 *
	 * @see wp_list_comments()
	 *
	 * @param WP_Comment $comment Comment to display.
	 * @param int        $depth   Depth of the current comment.
	 * @param array      $args    An array of arguments.
	 */
	protected function html5_comment( $comment, $depth, $args ) {
		global $post;

		$classes[] = $this->has_children ? 'parent' : '';

		if ( $depth > 1 && $depth < 7 ) {
			$classes[] = 'ml-6';
		}

		?>
		<<?php echo ( 'div' === $args['style'] ) ? 'div' : 'li'; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $classes, $comment ); ?>>
			<div class="comment-inner p-6 border bg-light mb-6
			<?php
			if ( $depth > 1 ) :
				?>
				pl-5 border-left" style="border-left-width:3px !important;<?php endif; ?>">
				<div class="media align-items-center mb-2">
					<?php
						$avatar = get_avatar( $comment, 50, '', '', array( 'class' => 'avatar-img rounded-circle' ) );

					if ( $avatar ) :
						?>
							<div class="avatar avatar-50 mr-4"><?php echo wp_kses_post( $avatar ); ?></div>
							<?php
						endif;
					?>
					<div class="media-body">
						<div class="d-flex justify-content-between align-items-center">
							<span class="h5 font-weight-bold mb-0 d-flex align-items-center">
								<?php echo esc_html( get_comment_author( $comment ) ); ?>
								<?php if ( $comment->user_id === $post->post_author ) : ?>
								<span class="badge badge-info badge-pill ml-2"><?php echo esc_html__( 'Author', 'landkit' ); ?></span>
								<?php endif; ?>
							</span>
							<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>" class="small text-muted comment-posted-on text-nowrap ml-4">
								<?php echo esc_html( get_comment_date( '', $comment ) ); ?>
							</a>
						</div>
					</div>
				</div>

				<div class="comment-text text-gray-700">
					<?php
					if ( '0' === $comment->comment_approved ) :
						$commenter = wp_get_current_commenter();
						if ( $commenter['comment_author_email'] ) {
							echo esc_html_x( 'Your comment is awaiting moderation.', 'front-end', 'landkit' );
						} else {
							echo esc_html_x(
								'Your comment is awaiting moderation. This is a preview, your comment will be visible after it has been approved.',
								'front-end',
								'landkit'
							);
						}
					else :
						comment_text();
					endif;
					?>
				</div>
				<?php

					echo wp_kses_post(
						str_replace(
							'comment-reply-link',
							'comment-reply-link mr-4',
							get_comment_reply_link(
								array_merge(
									$args,
									[
										'add_below' => 'comment-reply-target',
										'depth'     => $depth,
										'max_depth' => $args['max_depth'],
									]
								),
								$comment
							)
						)
					);

				if ( current_user_can( 'edit_comment', $comment->comment_ID ) ) :
					?>
						<a class="comment-edit-link" href="<?php echo esc_url( get_edit_comment_link( $comment ) ); ?>"><?php esc_html_e( 'Edit', 'landkit' ); ?></a>
						<?php
					endif;
				?>
			</div>
			<div id="comment-reply-target-<?php comment_ID(); ?>" class="comment-reply-target"></div>
		<?php
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @since 2.7.0
	 *
	 * @see Walker::end_el()
	 * @see wp_list_comments()
	 *
	 * @param string     $output  Used to append additional content. Passed by reference.
	 * @param WP_Comment $comment The current comment object. Default current comment.
	 * @param int        $depth   Optional. Depth of the current comment. Default 0.
	 * @param array      $args    Optional. An array of arguments. Default empty array.
	 */
	public function end_el( &$output, $comment, $depth = 0, $args = array() ) {
		if ( ! empty( $args['end-callback'] ) ) {
			ob_start();
			call_user_func( $args['end-callback'], $comment, $args, $depth );
			$output .= ob_get_clean();
			return;
		}

		$output .= '</div>'; // close  div.media > div.media-body.
	}
}
