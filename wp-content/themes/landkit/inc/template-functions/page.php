<?php
/**
 * Template functions used in Page
 *
 * @package landkit
 */

if ( ! function_exists( 'landkit_page_header' ) ) {
	/**
	 * Displays Page Header
	 */
	function landkit_page_header() {
		$title = get_the_title();
		$desc  = '';
		$thumb = get_the_post_thumbnail_url();
		landkit_content_header( $title, $desc, $thumb );
	}
}

if ( ! function_exists( 'landkit_page_content' ) ) {
	/**
	 * Display the post content
	 *
	 * @since 1.0.0
	 */
	function landkit_page_content() {
		global $post;

		if ( did_action( 'elementor/loaded' ) ) {
			$is_built_with_elementor = \Elementor\Plugin::$instance->documents->get( $post->ID )->is_built_with_elementor();
		} else {
			$is_built_with_elementor = false;
		}

		?><section class="pt-6 pt-md-11 pb-8 pb-md-11">
			<div class="
			<?php
			if ( $is_built_with_elementor ) :
				?>
				no-need-<?php endif; ?>container">
				<div class="post__content clearfix">
					<?php the_content(); ?>
				</div><!-- .page__content -->

				<?php landkit_link_pages(); ?>
				<?php landkit_display_comments(); ?>

			</div>
		</section>
		<?php
	}
}


if ( ! function_exists( 'landkit_display_comments' ) ) :
	/**
	 * Landkit display comments
	 *
	 * @since  1.0.0
	 */
	function landkit_display_comments() {
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || 0 !== intval( get_comments_number() ) ) :
			comments_template();
		endif;
	}
endif;
