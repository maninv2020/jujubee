<?php
/**
 * General Template Functions
 */

if ( ! function_exists( 'landkit_breadcrumb' ) ) {

	/**
	 * Output the WooCommerce Breadcrumb.
	 *
	 * @param array $args Arguments.
	 */
	function landkit_breadcrumb( $args = array() ) {
		$args = wp_parse_args(
			$args,
			apply_filters(
				'landkit_breadcrumb_defaults',
				array(
					'delimiter'   => '',
					'wrap_before' => '<ol class="breadcrumb breadcrumb-scroll">',
					'wrap_after'  => '</ol>',
					'before'      => '<li class="breadcrumb-item">',
					'after'       => '</li>',
					'home'        => _x( 'Home', 'breadcrumb', 'landkit' ),
				)
			)
		);

		require_once get_template_directory() . '/inc/classes/class-landkit-breadcrumb.php';

		$breadcrumbs = new Landkit_Breadcrumb();

		if ( ! empty( $args['home'] ) ) {
			$breadcrumbs->add_crumb( $args['home'], apply_filters( 'landkit_breadcrumb_home_url', home_url() ) );
		}

		$args['breadcrumb'] = $breadcrumbs->generate();

		/**
		 * WooCommerce Breadcrumb hook
		 *
		 * @hooked WC_Structured_Data::generate_breadcrumblist_data() - 10
		 */
		do_action( 'landkit_breadcrumb', $breadcrumbs, $args );

		extract( $args );//phpcs:ignore

		if ( ! empty( $breadcrumb ) ) {

			echo wp_kses_post( $wrap_before );

			foreach ( $breadcrumb as $key => $crumb ) {

				if ( ! empty( $crumb[1] ) && count( $breadcrumb ) !== $key + 1 ) {
					echo sprintf(
						'%s<a href="%s" class="text-gray-700">%s</a>%s',
						wp_kses_post( $before ),
						esc_url( $crumb[1] ),
						esc_html( $crumb[0] ),
						wp_kses_post( $after )
					);
				} else {
					echo '<li class="breadcrumb-item active">' . esc_html( $crumb[0] ) . '</li>';
				}
			}

			echo wp_kses_post( $wrap_after );

		}
	}
}

if ( ! function_exists( 'landkit_before_site_content' ) ) {
	/**
	 * Before site content
	 */
	function landkit_before_site_content() {
		$before_page_site_control = get_theme_mod( 'before_site_control' );

		if ( $before_page_site_control ) {
			print( landkit_render_content( $before_page_site_control, false ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}


if ( ! function_exists( 'landkit_single_post_type_after' ) ) {
	/**
	 * After single post
	 */
	function landkit_single_post_type_after() {
		if ( is_single() ) {
			$post_type = get_post_type();
			do_action( "landkit_single_{$post_type}_after" );
		}
	}
}
