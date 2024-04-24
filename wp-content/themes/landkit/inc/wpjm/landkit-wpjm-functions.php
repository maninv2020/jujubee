<?php
/**
 * Functions related to WPJM
 *
 * @param array $args Arguments.
 */
function job_manager_get_filtered_links( $args = [] ) {
	$job_categories = [];
	$types          = get_job_listing_types();

	// Convert to slugs.
	if ( $args['search_categories'] ) {
		foreach ( $args['search_categories'] as $category ) {
			if ( is_numeric( $category ) ) {
				$category_object = get_term_by( 'id', $category, 'job_listing_category' );
				if ( ! is_wp_error( $category_object ) ) {
					$job_categories[] = $category_object->slug;
				}
			} else {
				$job_categories[] = $category;
			}
		}
	}

	$links = apply_filters(
		'job_manager_job_filters_showing_jobs_links',
		[
			'reset'    => [
				'name' => esc_html__( 'Reset', 'landkit' ),
				'url'  => '#',
			],
			'rss_link' => [
				'name' => esc_html__( 'RSS', 'landkit' ),
				'url'  => get_job_listing_rss_link(
					apply_filters(
						'job_manager_get_listings_custom_filter_rss_args',
						[
							'job_types'       => isset( $args['filter_job_types'] ) ? implode( ',', $args['filter_job_types'] ) : '',
							'search_location' => $args['search_location'],
							'job_categories'  => implode( ',', $job_categories ),
							'search_keywords' => $args['search_keywords'],
						]
					)
				),
			],
		],
		$args
	);

	if (
		count( (array) $args['filter_job_types'] ) === count( $types )
		&& empty( $args['search_keywords'] )
		&& empty( $args['search_location'] )
		&& empty( $args['search_categories'] )
		&& ! apply_filters( 'job_manager_get_listings_custom_filter', false )
	) {
		unset( $links['reset'] );
	}

	$return = '<span>';

	foreach ( $links as $key => $link ) {
		$return .= '<a href="' . esc_url( $link['url'] ) . '" class="ml-2 btn btn-sm btn-outline-gray-300 d-none d-md-inline ' . esc_attr( $key ) . '">' . wp_kses_post( $link['name'] ) . '</a>';
	}

	$return .= '</span>';

	return $return;
}

if ( ! function_exists( 'landkit_wpjm_has_single_job_sidebar' ) ) {
	/**
	 * Check sidebar in single job.
	 */
	function landkit_wpjm_has_single_job_sidebar() {
		return get_the_company_name() || is_active_sidebar( 'sidebar-single-job-listing' );
	}
}

/**
 * Get job team.
 *
 * @param array $post Post type.
 */
function landkit_get_the_job_team( $post = null ) {
	$post = get_post( $post );
	if ( ! $post || 'job_listing' !== $post->post_type ) {
		return null;
	}

	return apply_filters( 'the_job_team', $post->_team, $post );
}
