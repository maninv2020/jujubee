<?php
/**
 * Template functions for Single Posts
 *
 * @package landkit
 */

if ( ! function_exists( 'landkit_single_post_thumbnail' ) ) {
	/**
	 * Displays Single Post Thumbnail
	 */
	function landkit_single_post_thumbnail() {

		if ( ! has_post_thumbnail() ) {
			return;
		}

		?><div data-jarallax data-speed=".8" class="py-12 py-md-15 bg-cover jarallax" style="background-image: url(<?php echo esc_url( get_the_post_thumbnail_url() ); ?>);"></div>
		<?php
	}
}

if ( ! function_exists( 'landkit_single_post_cover' ) ) {
	/**
	 * Displays Single Post Cover
	 */
	function landkit_single_post_cover() {
		if ( ! landkit_has_post_cover() && ! has_post_thumbnail() ) {
			return;
		}

		$cover_url = landkit_get_the_post_cover_url();

		if ( ! $cover_url ) {
			$cover_url = get_the_post_thumbnail_url();
		}

		?>
		<div data-jarallax data-speed=".8" class="py-12 py-md-15 bg-cover jarallax" style="background-image: url(<?php echo esc_url( $cover_url ); ?>);"></div>
		<?php
	}
}

if ( ! function_exists( 'landkit_single_post_header' ) ) {
	/**
	 * Displays Single Post Header
	 */
	function landkit_single_post_header() {
		?>
		<section class="pt-8 pt-md-11">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-12 col-md-10 col-lg-9 col-xl-8">

						<?php if ( ! empty( trim( get_the_title() ) ) ) : ?>
						<h1 class="display-
							<?php
							if ( is_singular( 'jetpack-portfolio' ) ) :
								?>
							3 font-weight-bold
								<?php
else :
	?>
							4<?php endif; ?> text-center text-break
							<?php
							if ( ! has_excerpt() ) :
								?>
	mb-7<?php endif; ?>"><?php the_title(); ?></h1>
						<?php endif; ?>

						<?php if ( has_excerpt() ) : ?>
						<p class="lead mb-7 text-center text-muted"><?php echo wp_kses_post( get_the_excerpt() ); ?></p>
						<?php endif; ?>

						<?php landkit_single_post_meta(); ?>

					</div>
				</div> <!-- / .row -->
			</div> <!-- / .container -->
		</section>
		<?php
	}
}

if ( ! function_exists( 'landkit_single_post_content' ) ) {
	/**
	 * Displays Single Post Content
	 *
	 * @return void
	 */
	function landkit_single_post_content() {
		$post_type = get_post_type();
		?>
		<section class="pt-6 pt-md-8 
		<?php
		if ( is_singular( 'jetpack-portfolio' ) ) :
			?>
pb-6 pb-md-8
			<?php
else :
	?>
					pb-8 pb-md-11<?php endif; ?>">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-12 col-md-10 col-lg-9 col-xl-8">
						<?php do_action( "landkit_single_{$post_type}_content_before" ); ?>
						<div class="post__content single_post__content 
						<?php
						if ( ! is_singular( 'jetpack-portfolio' ) ) :
							?>
mb-7<?php endif; ?> clearfix">
							<?php
							do_action( "landkit_single_{$post_type}_the_content_before" );

							the_content();

							do_action( "landkit_single_{$post_type}_the_content_after" );
							?>
						</div>
						<?php do_action( "landkit_single_{$post_type}_content_after" ); ?>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}

if ( ! function_exists( 'landkit_link_pages' ) ) {
	/**
	 * Page links
	 */
	function landkit_link_pages() {
		$link_pages = wp_link_pages(
			array(
				'before'      => '<div class="page-links mt-4"><span class="d-block text-secondary mb-3">' . esc_html__( 'Pages:', 'landkit' ) . '</span><nav class="pagination pagination-sm mb-0">',
				'after'       => '</nav></div>',
				'link_before' => '<span class="page-link">',
				'link_after'  => '</span>',
				'echo'        => 0,
			)
		);

		$link_pages = str_replace( 'post-page-numbers', 'post-page-numbers page-item', $link_pages );
		$link_pages = str_replace( 'current', 'current active', $link_pages );
		echo wp_kses_post( $link_pages );
	}
}

if ( ! function_exists( 'landkit_single_post_meta' ) ) {
	/**
	 * Displays single post meta
	 *
	 * @return void
	 */
	function landkit_single_post_meta() {
		?>
		<div class="row align-items-center py-5 border-top border-bottom">
			<div class="col-auto">

				<!-- Avatar -->
				<div class="avatar avatar-lg">
					<?php echo get_avatar( get_the_author_meta( 'user_email' ), 36, '', '', array( 'class' => 'avatar-img rounded-circle' ) ); ?>
				</div>

			</div>

			<div class="col ml-n5">

				<!-- Name -->
				<h6 class="text-uppercase mb-0"><?php the_author(); ?></h6>
				<?php

				// Posted on.
				$time_string = '<time class="post__date published updated" datetime="%1$s">%2$s</time>';
				/* translators: %$s - theme name */
				$output_string = esc_html__( 'Posted on %s', 'landkit' );

				if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
					/*
					Translators: %1$s - date
					%2$s - date
					%3$s - modified date
					%4$s - modified date
					*/
					$time_string = '<time class="post__date published sr-only" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
					/* translators: %$s - theme name */
					$output_string = esc_html__( 'Updated on %s', 'landkit' );
				}

				$time_string = sprintf(
					$time_string,
					esc_attr( get_the_date( 'c' ) ),
					esc_html( get_the_date() ),
					esc_attr( get_the_modified_date( 'c' ) ),
					esc_html( get_the_modified_date() )
				);

				?>

				<!-- Date -->
				<div class="font-size-sm text-muted">
					<?php printf( $output_string, $time_string ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>

			</div>

			<div class="col-auto">
			<?php

				do_action( 'landkit_share' );

			?>
			</div>
		</div>
		<?php
	}
}


if ( ! function_exists( 'landkit_single_post_nav' ) ) {
	/**
	 * Displays Single Post Nav
	 */
	function landkit_single_post_nav() {

		$prev_post = get_previous_post();
		$next_post = get_next_post();

		?>
		<div class="row align-items-center py-5 border-top border-bottom">
			<?php if ( $prev_post ) : ?>
			<div class="col-12 col-md-6">
				<div class="d-flex position-relative align-items-center">
					<div class="icon">
						<i class="fe fe-arrow-left"></i>
					</div>
					<div class="ml-4 text-truncate">
						<h6 class="text-uppercase mb-0">
						<?php
								/* translators: previous post in post navigation */
							echo esc_html_x( 'Previous post', 'front-end', 'landkit' );
						?>
							</h6>
						<a title="<?php echo esc_attr( get_the_title( $prev_post ) ); ?>" href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" class="font-size-sm text-muted text-decoration-none stretched-link">
							<?php echo get_the_title( $prev_post ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<?php if ( $next_post ) : ?>
			<div class="col-12 col-md-6 ml-auto mt-4 mt-md-0">
				<div class="d-flex position-relative align-items-center justify-content-end">
					<div class="mr-4 text-truncate">
						<h6 class="text-uppercase mb-0">
						<?php
								/* translators: next post in post navigation */
							echo esc_html_x( 'Next post', 'front-end', 'landkit' );
						?>
							</h6>
						<a title="<?php echo esc_attr( get_the_title( $next_post ) ); ?>" href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="font-size-sm text-muted text-decoration-none stretched-link">
							<?php echo get_the_title( $next_post ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> 
						</a>
					</div>
					<div class="icon">
						<i class="fe fe-arrow-right"></i>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'landkit_single_post_footer' ) ) {
	/**
	 * Single post footer
	 */
	function landkit_single_post_footer() {
		$before_footer_content = get_theme_mod( 'single_post_before_footer_content' );

		if ( $before_footer_content ) {
			print( landkit_render_content( $before_footer_content, false ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

if ( ! function_exists( 'landkit_comments' ) ) {
	/**
	 * Post comment
	 */
	function landkit_comments() {
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;
	}
}

if ( ! function_exists( 'landkit_comment_form_default_fields' ) ) {
	/**
	 * Comment form default fields
	 *
	 * @param array $fields The comment form fields.
	 */
	function landkit_comment_form_default_fields( $fields ) {
		$commenter = wp_get_current_commenter();
		$is_req    = (bool) get_option( 'require_name_email', 1 );

		// Remove url field.
		unset( $fields['url'] );

		// Update other fields.
		$fields['author'] = sprintf(
			'<div class="form-group comment-form-author mb-5">
                <label class="input-label" for="author">%1$s%4$s</label>
                <input type="text" name="author" id="author" class="form-control" value="%2$s" maxlength="245" %3$s>
            </div>',
			/* translators: comment author name */
			esc_html__( 'Your name', 'landkit' ),
			esc_attr( $commenter['comment_author'] ),
			$is_req ? 'required' : '',
			$is_req ? '<span class="text-danger">*</span>' : ''
		);

		$fields['email'] = sprintf(
			'<div class="form-group comment-form-email mb-5">
                <label class="input-label" for="email">%1$s%4$s</label>
                <input type="email" name="email" id="email" class="form-control" value="%2$s" maxlength="100" aria-describedby="email-notes" %3$s>
            </div>',
			/* translators: comment author e-mail */
			esc_html__( 'Your email', 'landkit' ),
			esc_attr( $commenter['comment_author_email'] ),
			$is_req ? 'required' : '',
			$is_req ? '<span class="text-danger">*</span>' : ''
		);

		if ( isset( $fields['cookies'] ) ) {
			$consent           = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
			$fields['cookies'] = sprintf(
				'<div class="custom-control custom-checkbox mb-5 comment-form-cookies-consent">
                    <input type="checkbox" id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" class="custom-control-input" value="yes"' . $consent . '>
                    <label class="custom-control-label input-label" for="wp-comment-cookies-consent">%s</label>
                </div>',
				esc_html__( 'Save my name and email in this browser for the next time I comment.', 'landkit' )
			);
		}

		return $fields;
	}
}

if ( ! function_exists( 'landkit_post_protected_password_form' ) ) :
	/**
	 * Protected password form
	 */
	function landkit_post_protected_password_form() {
		global $post;

		$label = 'pwbox-' . ( empty( $post->ID ) ? wp_rand() : $post->ID );
		?>

		<form class="p-6 bg-light bordered protected-post-form input-group landkit-protected-post-form" action="<?php echo esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ); ?>" method="post">
			<p><?php echo esc_html__( 'This content is password protected. To view it please enter your password below:', 'landkit' ); ?></p>
			<div class="d-flex align-items-center w-md-85">
				<label class="text-secondary mb-0 mr-3 d-none d-md-block" for="<?php echo esc_attr( $label ); ?>"><?php echo esc_html__( 'Password:', 'landkit' ); ?></label>
				<div class="d-flex flex-grow-1">
					<input class="input-text form-control" name="post_password" id="<?php echo esc_attr( $label ); ?>" type="password" style="border-top-right-radius: 0; border-bottom-right-radius: 0;"/>
					<input type="submit" name="Submit" class="btn btn-primary font-weight-medium" value="<?php echo esc_attr( 'Submit' ); ?>" style="border-top-left-radius: 0; border-bottom-left-radius: 0; transform: none;"/>
				</div>
			</div>
		</form>
		<?php
	}
endif;
