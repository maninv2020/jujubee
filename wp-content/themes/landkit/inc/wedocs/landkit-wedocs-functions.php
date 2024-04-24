<?php
/**
 * Landkit WeDocs Functions
 */

if ( ! function_exists( 'landkit_is_wedocs' ) ) {
	/**
	 * Check Wedocs is activated
	 */
	function landkit_is_wedocs() {
		return apply_filters( 'is_wedocs', ( landkit_is_wedocs_page() || landkit_has_wedocs_shortcode() || landkit_is_wedocs_docs() || landkit_is_wedocs_taxonomy() ) );
	}
}


/**
 * Checks if the visitor is currently on a WPJM page.
 *
 * @since 1.30.0
 *
 * @return bool
 */
function landkit_is_wedocs_page() {
	$is_wedocs_page = is_post_type_archive( 'docs' );

	if ( ! $is_wedocs_page ) {
		$wedocs_page_ids = array_filter(
			[
				wedocs_get_option( 'docs_home', 'wedocs_settings' ),
			]
		);

		/**
		 * Filters a list of all page IDs related to WPJM.
		 *
		 * @since 1.30.0
		 *
		 * @param int[] $wedocs_page_ids
		 */
		$wedocs_page_ids = array_unique( apply_filters( 'wedocs_page_ids', $wedocs_page_ids ) );

		if ( ! empty( $wedocs_page_ids ) ) {
			$is_wedocs_page = is_page( $wedocs_page_ids );
		}
	}

	/**
	 * Filter the result of is_wedocs_page()
	 *
	 * @since 1.30.0
	 *
	 * @param bool $is_wedocs_page
	 */
	return apply_filters( 'landkit_is_wedocs_page', $is_wedocs_page );
}

/**
 * Checks if the provided content or the current single page or post has a WPJM shortcode.
 *
 * @param string|null       $content   Content to check. If not provided, it uses the current post content.
 * @param string|array|null $tag Check specifically for one or more shortcodes. If not provided, checks for any WPJM shortcode.
 *
 * @return bool
 */
function landkit_has_wedocs_shortcode( $content = null, $tag = null ) {
	global $post;

	$has_wedocs_shortcode = false;

	if ( null === $content && is_singular() && is_a( $post, 'WP_Post' ) ) {
		$content = $post->post_content;
	}

	if ( ! empty( $content ) ) {
		$wedocs_shortcodes = [ 'wedocs' ];
		/**
		 * Filters a list of all shortcodes associated with WPJM.
		 *
		 * @since 1.30.0
		 *
		 * @param string[] $wedocs_shortcodes
		 */
		$wedocs_shortcodes = array_unique( apply_filters( 'wedocs_shortcodes', $wedocs_shortcodes ) );

		if ( null !== $tag ) {
			if ( ! is_array( $tag ) ) {
				$tag = [ $tag ];
			}
			$wedocs_shortcodes = array_intersect( $wedocs_shortcodes, $tag );
		}

		foreach ( $wedocs_shortcodes as $shortcode ) {
			if ( has_shortcode( $content, $shortcode ) ) {
				$has_wedocs_shortcode = true;
				break;
			}
		}
	}

	/**
	 * Filter the result of has_wedocs_shortcode()
	 *
	 * @since 1.30.0
	 *
	 * @param bool $has_wedocs_shortcode
	 */
	return apply_filters( 'landkit_has_wedocs_shortcode', $has_wedocs_shortcode );
}

/**
 * Checks if the current page is a docs
 *
 * @since 1.30.0
 *
 * @return bool
 */
function landkit_is_wedocs_docs() {
	return is_singular( [ 'docs' ] );
}

/**
 * Checks if the visitor is on a page for a WPJM taxonomy.
 *
 * @since 1.30.0
 *
 * @return bool
 */
function landkit_is_wedocs_taxonomy() {
	return is_tax( get_object_taxonomies( 'docs' ) );
}
