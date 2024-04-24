<?php
/**
 * Landkit functions.
 *
 * @package landkit
 */

use Elementor\Plugin;

/**
 * Get the post types enabled for Landkit Elementor.
 *
 * @return array
 */
function landkit_option_enabled_post_types() {
	$post_types = [ 'post', 'page', 'docs', 'jetpack-portfolio', 'job_listing' ];
	return apply_filters( 'landkit_option_enabled_post_types', $post_types );
}

/**
 * Gets Footer Classes
 */
function landkit_get_footer_classes() {
	global $post;
	$footer_classes  = array( ' py-8', 'py-md-11' );
	$lk_page_options = array();

	if ( $post && is_singular( landkit_option_enabled_post_types() ) ) {
		$clean_meta_data  = get_post_meta( $post->ID, '_lk_page_options', true );
		$_lk_page_options = maybe_unserialize( $clean_meta_data );

		if ( is_array( $_lk_page_options ) ) {
			$lk_page_options = $_lk_page_options;
		}
	}

	if ( isset( $lk_page_options['footer'] ) && isset( $lk_page_options['footer']['enable_custom_footer'] ) && 'yes' === $lk_page_options['footer']['enable_custom_footer'] ) {
		$background        = isset( $lk_page_options['footer']['background'] ) ? $lk_page_options['footer']['background'] : 'bg-gray-200';
		$enable_border_top = isset( $lk_page_options['footer']['enable_border_top'] ) && 'yes' === $lk_page_options['footer']['enable_border_top'] ? true : false;

		if ( $enable_border_top ) {
			$footer_classes[] = 'border-top';

			if ( ! in_array( $background, [ 'bg-gray-200', 'bg-white', 'bg-light' ], true ) ) {
				$footer_classes[] = 'border-gray-800-50';
			}
		}

		$footer_classes[] = $background;
	} elseif ( is_singular( 'post' ) && filter_var( get_theme_mod( 'enable_single_post_custom_footer', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) {
		$footer_classes[] = get_theme_mod( 'landkit_single_post_footer_background', 'bg-gray-200' );

		if ( 'yes' === get_theme_mod( 'enable_single_post_footer_border_top', 'no' ) ) {
			$footer_classes[] = 'border-top border-gray-800-50';
		}
	} elseif ( is_singular( 'job_listing' ) && filter_var( get_theme_mod( 'enable_single_job_custom_footer', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) {
		$footer_classes[] = get_theme_mod( 'landkit_single_job_footer_background', 'bg-gray-200' );

		if ( 'yes' === get_theme_mod( 'enable_single_job_footer_border_top', 'no' ) ) {
			$footer_classes[] = 'border-top border-gray-800-50';
		}
	} elseif ( is_singular( [ 'docs' ] ) && filter_var( get_theme_mod( 'enable_docs_custom_footer', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) {
		$footer_classes[] = get_theme_mod( 'landkit_docs_footer_background', 'bg-gray-200' );

		if ( 'yes' === get_theme_mod( 'enable_docs_footer_border_top', 'no' ) ) {
			$footer_classes[] = 'border-top border-gray-800-50';
		}
	} elseif ( is_singular( 'jetpack-portfolio' ) && filter_var( get_theme_mod( 'enable_portfolio_custom_footer', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) {
		$footer_classes[] = get_theme_mod( 'landkit_portfolio_footer_background', 'bg-gray-200' );

		if ( 'yes' === get_theme_mod( 'enable_portfolio_footer_border_top', 'no' ) ) {
			$footer_classes[] = 'border-top border-gray-800-50';
		}
	} else {
		if ( 'yes' === get_theme_mod( 'enable_border_top', 'no' ) ) {
			$footer_classes[] = 'border-top border-gray-800-50';
		}

		/**
		 * Footer Background
		 */
		$footer_classes[] = get_theme_mod( 'landkit_footer_background', 'bg-gray-200' );
	}

	return implode( ' ', $footer_classes );
}

/**
 * Get Navbar and container classes.
 *
 * @return array
 */
function landkit_get_navbar_classes() {
	global $post;

	$navbar          = '';
	$container       = '';
	$lk_page_options = array();

	if ( $post && is_singular( landkit_option_enabled_post_types() ) ) {
		$clean_meta_data  = get_post_meta( $post->ID, '_lk_page_options', true );
		$_lk_page_options = maybe_unserialize( $clean_meta_data );

		if ( is_array( $_lk_page_options ) ) {
			$lk_page_options = $_lk_page_options;
		}
	}

	if ( isset( $lk_page_options['header'] ) && isset( $lk_page_options['header']['enable_custom_header'] ) && 'yes' === $lk_page_options['header']['enable_custom_header'] ) {

		$header_skin             = isset( $lk_page_options['header']['skin'] ) ? $lk_page_options['header']['skin'] : 'light';
		$enable_border_bottom    = isset( $lk_page_options['header']['enable_border_bottom'] ) && 'yes' === $lk_page_options['header']['enable_border_bottom'] ? true : false;
		$enable_bg_transparent   = isset( $lk_page_options['header']['enable_bg_transparent'] ) && 'yes' === $lk_page_options['header']['enable_bg_transparent'] ? true : false;
		$enable_navbar_togglable = isset( $lk_page_options['header']['enable_navbar_togglable'] ) && 'yes' === $lk_page_options['header']['enable_navbar_togglable'] ? true : false;
		$enable_fixed_top        = isset( $lk_page_options['header']['enable_fixed_top'] ) && 'yes' === $lk_page_options['header']['enable_fixed_top'] ? true : false;

		$enable_header_fullwidth = isset( $lk_page_options['header']['enable_header_fullwidth'] ) && 'yes' === $lk_page_options['header']['enable_header_fullwidth'] ? true : false;
		$container_layout        = isset( $lk_page_options['header']['container_layout'] ) ? $lk_page_options['header']['container_layout'] : 'container';

		$navbar = 'light' === $header_skin ? 'navbar-light' : 'navbar-dark';

		if ( ! $enable_bg_transparent ) {
			if ( 'light' === $header_skin ) {
				$navbar .= ' bg-white';
			}

			if ( 'dark' === $header_skin ) {
				$navbar .= ' bg-dark';
			}
		}

		if ( 'light' === $header_skin && $enable_border_bottom ) {
			$navbar .= ' border-bottom';
		}

		if ( 'dark' === $header_skin && $enable_navbar_togglable ) {
			$navbar .= ' navbar-togglable';
		}

		if ( $enable_fixed_top ) {
			$navbar .= ' fixed-top';
		}

		if ( $enable_header_fullwidth ) {
			$container = 'container-fluid';
		} else {
			$container = $container_layout;
		}
	} elseif ( is_singular( 'post' ) && filter_var( get_theme_mod( 'enable_single_post_custom_header', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) {

		$header_skin = get_theme_mod( 'single_post_header_skin', 'light' );

		if ( 'light' === $header_skin ) {
			$navbar = 'navbar-light bg-white';
		} else {
			$navbar = 'navbar-dark bg-dark';
		}

		if ( apply_filters( 'landkit_enable_single_post_border_bottom', filter_var( get_theme_mod( 'enable_single_post_border_bottom', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) ) :
			$navbar .= ' border-bottom';
		endif;

		if ( apply_filters( 'landkit_enable_single_post_fixed_top', filter_var( get_theme_mod( 'enable_single_post_fixed_top', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) ) :
			$navbar .= ' fixed-top';
		endif;

		if ( apply_filters( 'landkit_enable_single_post_navbar_togglable', filter_var( get_theme_mod( 'enable_single_post_navbar_togglable', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) ) :
			$navbar .= ' navbar-togglable';
		endif;

		$header_container = get_theme_mod( 'single_post_container_layout', 'container' );

		if ( 'yes' === get_theme_mod( 'single_post_header_is_full_width', 'yes' ) ) {
			$container = 'container-fluid';
		} else {
			$container = $header_container;
		}
	} elseif ( is_singular( 'job_listing' ) && filter_var( get_theme_mod( 'enable_single_job_custom_header', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) {

		$header_skin = get_theme_mod( 'single_job_header_skin', 'light' );

		if ( 'light' === $header_skin ) {
			$navbar = 'navbar-light bg-white';
		} else {
			$navbar = 'navbar-dark bg-dark';
		}

		if ( apply_filters( 'landkit_enable_single_job_border_bottom', filter_var( get_theme_mod( 'enable_single_job_border_bottom', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) ) :
			$navbar .= ' border-bottom';
		endif;

		if ( apply_filters( 'landkit_enable_single_job_fixed_top', filter_var( get_theme_mod( 'enable_single_job_fixed_top', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) ) :
			$navbar .= ' fixed-top';
		endif;

		if ( apply_filters( 'landkit_enable_single_job_navbar_togglable', filter_var( get_theme_mod( 'enable_single_job_navbar_togglable', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) ) :
			$navbar .= ' navbar-togglable';
		endif;

		$header_container = get_theme_mod( 'single_job_container_layout', 'container' );

		if ( 'yes' === get_theme_mod( 'single_job_header_is_full_width', 'yes' ) ) {
			$container = 'container-fluid';
		} else {
			$container = $header_container;
		}
	} elseif ( is_singular( [ 'docs' ] ) && filter_var( get_theme_mod( 'enable_docs_custom_header', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) {

		$header_skin = get_theme_mod( 'docs_header_skin', 'light' );

		if ( 'light' === $header_skin ) {
			$navbar = 'navbar-light bg-white';
		} else {
			$navbar = 'navbar-dark bg-dark';
		}

		if ( apply_filters( 'landkit_enable_docs_border_bottom', filter_var( get_theme_mod( 'enable_docs_border_bottom', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) ) :
			$navbar .= ' border-bottom';
		endif;

		if ( apply_filters( 'landkit_enable_docs_fixed_top', filter_var( get_theme_mod( 'enable_docs_fixed_top', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) ) :
			$navbar .= ' fixed-top';
		endif;

		if ( apply_filters( 'landkit_enable_docs_navbar_togglable', filter_var( get_theme_mod( 'enable_docs_navbar_togglable', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) ) :
			$navbar .= ' navbar-togglable';
		endif;

		$header_container = get_theme_mod( 'docs_container_layout', 'container' );

		if ( 'yes' === get_theme_mod( 'docs_header_is_full_width', 'yes' ) ) {
			$container = 'container-fluid';
		} else {
			$container = $header_container;
		}
	} elseif ( is_singular( 'jetpack-portfolio' ) && filter_var( get_theme_mod( 'enable_portfolio_custom_header', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) {

		$header_skin = get_theme_mod( 'portfolio_header_skin', 'light' );

		if ( 'light' === $header_skin ) {
			$navbar = 'navbar-light bg-white';
		} else {
			$navbar = 'navbar-dark bg-dark';
		}

		if ( apply_filters( 'landkit_enable_portfolio_border_bottom', filter_var( get_theme_mod( 'enable_portfolio_border_bottom', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) ) :
			$navbar .= ' border-bottom';
		endif;

		if ( apply_filters( 'landkit_enable_portfolio_fixed_top', filter_var( get_theme_mod( 'enable_portfolio_fixed_top', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) ) :
			$navbar .= ' fixed-top';
		endif;

		if ( apply_filters( 'landkit_enable_portfolio_navbar_togglable', filter_var( get_theme_mod( 'enable_portfolio_navbar_togglable', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) ) :
			$navbar .= ' navbar-togglable';
		endif;

		$header_container = get_theme_mod( 'portfolio_container_layout', 'container' );

		if ( 'yes' === get_theme_mod( 'portfolio_header_is_full_width', 'yes' ) ) {
			$container = 'container-fluid';
		} else {
			$container = $header_container;
		}
	} else {

		$header_skin = function_exists( 'landkit_header_skin' ) ? landkit_header_skin() : 'light';

		$navbar = 'light' === $header_skin ? 'navbar-light bg-white' : 'navbar-dark bg-dark';

		if ( apply_filters( 'landkit_enable_border_bottom', filter_var( get_theme_mod( 'enable_border_bottom', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) ) :
			$navbar .= ' border-bottom';
			endif;

		if ( apply_filters( 'landkit_enable_fixed_top', filter_var( get_theme_mod( 'enable_fixed_top', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) ) :
			$navbar .= ' fixed-top';
			endif;

		if ( apply_filters( 'landkit_enable_navbar_togglable', filter_var( get_theme_mod( 'enable_navbar_togglable', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) ) :
			$navbar .= ' navbar-togglable';
			endif;

		$header_container = function_exists( 'landkit_container_layout' ) ? landkit_container_layout() : 'container';

		if ( 'yes' === get_theme_mod( 'header_is_full_width', 'yes' ) ) {
			$container = 'container-fluid';
		} else {
			$container = $header_container;
		}
	}

	return array(
		'navbar'    => $navbar,
		'container' => $container,
	);
}

/**
 * Get unique ID.
 *
 * This is a PHP implementation of Underscore's uniqueId method. A static variable
 * contains an integer that is incremented with each call. This number is returned
 * with the optional prefix. As such the returned value is not universally unique,
 * but it is unique across the life of the PHP process.
 *
 * @see wp_unique_id() Themes requiring WordPress 5.0.3 and greater should use this instead.
 *
 * @staticvar int $id_counter
 *
 * @param string $prefix Prefix for the returned ID.
 * @return string Unique ID.
 */
function landkit_unique_id( $prefix = '' ) {
	static $id_counter = 0;
	if ( function_exists( 'wp_unique_id' ) ) {
		return wp_unique_id( $prefix );
	}
	return $prefix . (string) ++$id_counter;
}

/**
 * Get blog post classes.
 *
 * @return array
 */
function landkit_get_blog_post_classes() {
	if ( is_sticky() && is_home() && ! is_paged() ) {
		$classes = array( 'col-12' );
	} else {
		$classes = array(
			'col-12',
			'col-lg-4',
			'd-flex',
		);
	}
	return $classes;
}

/**
 * Pagination
 *
 * @param WP_Query|null $wp_query WP_Query instance.
 * @param bool          $echo Should return or echo.
 * @param string        $ul_class Additional class to <ul> tag.
 * @param string        $a_class Additional class to <a> tag.
 *
 * @return string
 * Accepts a WP_Query instance to build pagination (for custom wp_query()),
 * or nothing to use the current global $wp_query (eg: taxonomy term page)
 * - Tested on WP 4.9.5
 * - Tested with Bootstrap 4.1
 * - Tested on Sage 9
 *
 * USAGE:
 *     <?php echo landkit_bootstrap_pagination(); ?> //uses global $wp_query
 * or with custom WP_Query():
 *     <?php
 *      $query = new \WP_Query($args);
 *       ... while(have_posts()), $query->posts stuff ...
 *       echo landkit_bootstrap_pagination($query);
 *     ?>
 */
function landkit_bootstrap_pagination( \WP_Query $wp_query = null, $echo = true, $ul_class = '', $a_class = '' ) {

	if ( null === $wp_query ) {
		global $wp_query;
	}

	$pages = paginate_links(
		[
			'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
			'format'       => '?paged=%#%',
			'current'      => max( 1, get_query_var( 'paged' ) ),
			'total'        => $wp_query->max_num_pages,
			'type'         => 'array',
			'show_all'     => false,
			'end_size'     => 3,
			'mid_size'     => 1,
			'prev_next'    => true,
			'prev_text'    => esc_html__( '&laquo; Prev', 'landkit' ),
			'next_text'    => esc_html__( 'Next &raquo;', 'landkit' ),
			'add_args'     => false,
			'add_fragment' => '',
		]
	);

	if ( is_array( $pages ) ) {

		if ( ! empty( $ul_class ) ) {
			$ul_class = ' ' . $ul_class;
		}

		$pagination = '<nav aria-label="' . esc_attr__( 'Page navigation', 'landkit' ) . '"><ul class="pagination flex-wrap mb-n4' . esc_attr( $ul_class ) . '">';

		foreach ( $pages as $page ) {
			$t           = ( strpos( $page, 'current' ) === false ) ? $a_class : '';
			$t          .= ' page-link mb-4';
			$pagination .= '<li class="page-item' . ( strpos( $page, 'current' ) !== false ? ' active' : '' ) . '">' . str_replace( 'page-numbers', $t, $page ) . '</li>';
		}

		$pagination .= '</ul></nav>';

		if ( $echo ) {
			echo wp_kses_post( $pagination );
		} else {
			return $pagination;
		}
	}

	return null;
}

if ( ! function_exists( 'landkit_is_woocommerce_activated' ) ) {
	/**
	 * Query WooCommerce activation
	 */
	function landkit_is_woocommerce_activated() {
		return class_exists( 'WooCommerce' ) ? true : false;
	}
}

if ( ! function_exists( 'landkit_is_ocdi_activated' ) ) {
	/**
	 * Check if One Click Demo Import is activated
	 */
	function landkit_is_ocdi_activated() {
		return class_exists( 'OCDI_Plugin' ) ? true : false;
	}
}

if ( ! function_exists( 'landkit_is_wp_job_manager_activated' ) ) {
	/**
	 * Query WP Job Mananger activation
	 */
	function landkit_is_wp_job_manager_activated() {
		return class_exists( 'WP_Job_Manager' ) ? true : false;
	}
}

if ( ! function_exists( 'landkit_is_wedocs_activated' ) ) {
	/**
	 * Query weDocs Activation
	 */
	function landkit_is_wedocs_activated() {
		return class_exists( 'WeDocs' ) ? true : false;
	}
}

if ( ! function_exists( 'landkit_is_mas_static_content_activated' ) ) {
	/**
	 * Query MAS Static Content activation
	 */
	function landkit_is_mas_static_content_activated() {
		return class_exists( 'Mas_Static_Content' ) ? true : false;
	}
}


if ( ! function_exists( 'landkit_content_header' ) ) {
	/**
	 * Displays Header for Pages, Posts, etc
	 *
	 * @param string $title Title of the section.
	 * @param string $description Description of the section.
	 * @param string $background Background URL for the section.
	 */
	function landkit_content_header( $title, $description = '', $background = '' ) {

		$has_background = ! empty( $background );

		if ( $has_background ) {
			$atts['data-jarallax'] = true;
			$atts['data-speed']    = '.8';
			$atts['style']         = 'background-image:url(' . $background . ');';
			$atts['class']         = 'py-10 py-md-14 overlay overlay-black overlay-60 jarallax bg-cover';
			$title_class           = 'display-2 font-weight-bold text-white';
			$desc_class            = 'lead mb-0 text-white-75';
			$col_class             = 'col-md-10 col-lg-8 text-center';
		} else {
			$atts['class'] = 'bg-dark py-9';
			$title_class   = 'font-weight-bold text-white mb-2';
			$desc_class    = 'font-size-lg text-white-75 mb-0';
			$col_class     = '';
		}

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				if ( true === $value ) {
					$attributes .= ' ' . $attr;
				} else {
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}
		}

		?><!-- WELCOME
		================================================== -->
		<section<?php printf( $attributes ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-12 <?php echo esc_attr( $col_class ); ?>">

						<h1 class="content__title <?php echo esc_attr( $title_class ); ?>"><?php echo wp_kses_post( $title ); ?></h1>

						<?php if ( ! empty( $description ) ) : ?>
						<div class="content__desc <?php echo esc_attr( $desc_class ); ?>">
							<?php echo wp_kses_post( $description ); ?>
						</div>
						<?php endif; ?>

					</div>
				</div> <!-- / .row -->
			</div> <!-- / .container -->
		</section>
		<?php if ( $has_background ) : ?>
		<div class="position-relative">
			<div class="shape shape-bottom shape-fluid-x svg-shim text-light">
				<?php require get_theme_file_path( 'assets/img/shapes/curves/curve-1.svg' ); ?>
			</div>
		</div>
			<?php
		endif;
	}
}

/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @param string $template_name Template to fetch.
 * @param array  $args (default: array()) Arguments passed to the template.
 * @param string $template_path (default: '') Template path.
 * @param string $default_path (default: '') Default template path.
 * @return void
 */
function landkit_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( $args && is_array( $args ) ) {
		extract( $args ); //phpcs:ignore WordPress.PHP.DontExtract.extract_extract
	}

	$located = landkit_locate_template( $template_name, $template_path, $default_path );

	if ( ! file_exists( $located ) ) {
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '2.1' ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return;
	}

	// Allow 3rd party plugin filter template file from their plugin.
	$located = apply_filters( 'landkit_get_template', $located, $template_name, $args, $template_path, $default_path );

	do_action( 'landkit_before_template_part', $template_name, $template_path, $located, $args );

	include $located;
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *      yourtheme       /   $template_path  /   $template_name
 *      yourtheme       /   $template_name
 *      $default_path   /   $template_name
 *
 * @param string $template_name Template slug.
 * @param string $template_path (default: '') Path of the template.
 * @param string $default_path (default: '') Default path of the template.
 * @return string
 */
function landkit_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = 'templates/';
	}

	if ( ! $default_path ) {
		$default_path = 'templates/';
	}

	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name,
		)
	);

	// Get default template.
	if ( ! $template || LANDKIT_TEMPLATE_DEBUG_MODE ) {
		$template = $default_path . $template_name;
	}

	// Return what we found.
	return apply_filters( 'landkit_locate_template', $template, $template_name, $template_path );
}

if ( ! function_exists( 'landkit_clean' ) ) {
	/**
	 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
	 * Non-scalar values are ignored.
	 *
	 * @param string|array $var Data to sanitize.
	 * @return string|array
	 */
	function landkit_clean( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'landkit_clean', $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}
}

if ( ! function_exists( 'landkit_sass_yiq' ) ) {
	/**
	 * SASS YIQ
	 *
	 * @param string $hex Hex value.
	 * @return string
	 */
	function landkit_sass_yiq( $hex ) {
		$hex    = sanitize_hex_color( $hex );
		$length = strlen( $hex );
		if ( $length < 5 ) {
			$hex = ltrim( $hex, '#' );
			$hex = '#' . $hex . $hex;
		}

		preg_match( '/^#?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i', $hex, $matches );

		for ( $i = 1; $i <= 3; $i++ ) {
			$matches[ $i ] = hexdec( $matches[ $i ] );
		}
		$yiq = ( ( $matches[1] * 299 ) + ( $matches[2] * 587 ) + ( $matches[3] * 114 ) ) / 1000;
		return ( $yiq >= 128 ) ? '#000' : '#fff';
	}
}

/**
 * Adjust a hex color brightness
 * Allows us to create hover styles for custom link colors
 *
 * @param  strong  $hex   hex color e.g. #111111.
 * @param  integer $steps factor by which to brighten/darken ranging from -255 (darken) to 255 (brighten).
 * @return string        brightened/darkened hex color
 * @since  1.0.0
 */
function landkit_adjust_color_brightness( $hex, $steps ) {
	// Steps should be between -255 and 255. Negative = darker, positive = lighter.
	$steps = max( -255, min( 255, $steps ) );

	// Format the hex color string.
	$hex = str_replace( '#', '', $hex );

	if ( 3 === strlen( $hex ) ) {
		$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
	}

	// Get decimal values.
	$r = hexdec( substr( $hex, 0, 2 ) );
	$g = hexdec( substr( $hex, 2, 2 ) );
	$b = hexdec( substr( $hex, 4, 2 ) );

	// Adjust number of steps and keep it inside 0 to 255.
	$r = max( 0, min( 255, $r + $steps ) );
	$g = max( 0, min( 255, $g + $steps ) );
	$b = max( 0, min( 255, $b + $steps ) );

	$r_hex = str_pad( dechex( $r ), 2, '0', STR_PAD_LEFT );
	$g_hex = str_pad( dechex( $g ), 2, '0', STR_PAD_LEFT );
	$b_hex = str_pad( dechex( $b ), 2, '0', STR_PAD_LEFT );

	return '#' . $r_hex . $g_hex . $b_hex;
}

if ( ! function_exists( 'landkit_has_post_cover' ) ) {
	/**
	 * Returns if a cover image is set or not
	 *
	 * @param int|WP_Post|null $post  Post ID or post object. null, false, 0 and other PHP falsey values return the current global post inside the loop. A numerically valid post ID that points to a non-existent post returns null. Defaults to global $post.
	 */
	function landkit_has_post_cover( $post = null ) {
		$cover_id  = landkit_get_post_cover_id( $post );
		$has_cover = (bool) $cover_id;
		return (bool) apply_filters( 'has_cover', $has_cover, $post, $cover_id );
	}
}

if ( ! function_exists( 'landkit_get_the_post_cover_url' ) ) {
	/**
	 * Returns URL of Post Cover
	 *
	 * @param int|WP_Post|null $post  Post ID or post object. null, false, 0 and other PHP falsey values return the current global post inside the loop. A numerically valid post ID that points to a non-existent post returns null. Defaults to global $post.
	 * @param string           $size Cover URL image size.
	 */
	function landkit_get_the_post_cover_url( $post = null, $size = 'full' ) {
		$post_cover_id = landkit_get_post_cover_id( $post );

		if ( ! $post_cover_id ) {
			return false;
		}

		return wp_get_attachment_image_url( $post_cover_id, $size );
	}
}

if ( ! function_exists( 'landkit_get_post_cover_id' ) ) {
	/**
	 * Gets Cover Image
	 *
	 * @param int|WP_Post|null $post  Post ID or post object. null, false, 0 and other PHP falsey values return the current global post inside the loop. A numerically valid post ID that points to a non-existent post returns null. Defaults to global $post.
	 */
	function landkit_get_post_cover_id( $post = null ) {
		$post = get_post( $post );

		if ( ! $post ) {
			return false;
		}

		$meta_data = get_post_meta( $post->ID, '_lk_cover_image', true );
		$cover     = maybe_unserialize( $meta_data );

		return $cover;
	}
}

if ( ! function_exists( 'landkit_header_skin' ) ) {
	/**
	 * Get the header skin from theme mod.
	 */
	function landkit_header_skin() {
		$skin = get_theme_mod( 'header_skin', 'light' );
		return sanitize_key( apply_filters( 'landkit_header_skin', $skin ) );
	}
}


if ( ! function_exists( 'landkit_container_layout' ) ) {
	/**
	 * Get the container layout from theme mod.
	 */
	function landkit_container_layout() {
		$container_layout = get_theme_mod( 'container_layout', 'container' );
		return apply_filters( 'landkit_container_layout', $container_layout );
	}
}

/**
 * Render content.
 *
 * @param int  $post_id Post ID.
 * @param bool $echo Should return or render content.
 * @return void|string
 */
function landkit_render_content( $post_id, $echo = false ) {
	if ( did_action( 'elementor/loaded' ) ) {
		$content = Plugin::instance()->frontend->get_builder_content_for_display( $post_id );
	} else {
		$content = get_the_content( null, false, $post_id );
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
	}

	if ( $echo ) {
		echo wp_kses_post( $content );
	} else {
		return $content;
	}
}

/**
 * Get time difference in humanized form.
 *
 * @param int $from Timestamp from.
 * @param int $to Timestamp to.
 * @return string
 */
function landkit_human_time_diff( $from, $to = 0 ) {
	if ( empty( $to ) ) {
		$to = time();
	}

	$diff = (int) abs( $to - $from );
	//phpcs:disable

	if ( $diff < MINUTE_IN_SECONDS ) {
		$secs = $diff;
		if ( $secs <= 1 ) {
			$secs = 1;
		}
		/* translators: Time difference between two dates, in seconds. %s: Number of seconds. */
		$since = sprintf( _n( '%s second ago', '%s seconds ago', $secs, 'landkit' ), $secs );
	} elseif ( $diff < HOUR_IN_SECONDS && $diff >= MINUTE_IN_SECONDS ) {
		$mins = round( $diff / MINUTE_IN_SECONDS );
		if ( $mins <= 1 ) {
			$mins = 1;
		}
		/* translators: Time difference between two dates, in minutes (min=minute). %s: Number of minutes. */
		$since = sprintf( _n( '%s min ago', '%s mins ago', $mins, 'landkit' ), $mins );
	} elseif ( $diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS ) {
		$hours = round( $diff / HOUR_IN_SECONDS );
		if ( $hours <= 1 ) {
			$hours = 1;
		}
		/* translators: Time difference between two dates, in hours. %s: Number of hours. */
		$since = sprintf( _n( '%s hour', '%s hours ago', $hours, 'landkit' ), $hours );
	} elseif ( $diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS ) {
		$days = round( $diff / DAY_IN_SECONDS );
		if ( $days <= 1 ) {
			$days = 1;
		}
		/* translators: Time difference between two dates, in days. %s: Number of days. */
		$since = sprintf( _n( 'yesterday', '%s days ago', $days, 'landkit' ), $days );
	} elseif ( $diff < MONTH_IN_SECONDS && $diff >= WEEK_IN_SECONDS ) {
		$weeks = round( $diff / WEEK_IN_SECONDS );
		if ( $weeks <= 1 ) {
			$weeks = 1;
		}
		/* translators: Time difference between two dates, in weeks. %s: Number of weeks. */
		$since = sprintf( _n( 'last week', '%s weeks ago', $weeks, 'landkit' ), $weeks );
	} elseif ( $diff < YEAR_IN_SECONDS && $diff >= MONTH_IN_SECONDS ) {
		$months = round( $diff / MONTH_IN_SECONDS );
		if ( $months <= 1 ) {
			$months = 1;
		}
		/* translators: Time difference between two dates, in months. %s: Number of months. */
		$since = sprintf( _n( 'last month', '%s months ago', $months, 'landkit' ), $months );
	} elseif ( $diff >= YEAR_IN_SECONDS ) {
		$years = round( $diff / YEAR_IN_SECONDS );
		if ( $years <= 1 ) {
			$years = 1;
		}
		/* translators: Time difference between two dates, in years. %s: Number of years. */
		$since = sprintf( _n( 'last year', '%s years ago', $years, 'landkit' ), $years );
	}
	//phpcs:enable

	/**
	 * Filters the human readable difference between two timestamps.
	 *
	 * @since 4.0.0
	 *
	 * @param string $since The difference in human readable text.
	 * @param int    $diff  The difference in seconds.
	 * @param int    $from  Unix timestamp from which the difference begins.
	 * @param int    $to    Unix timestamp to end the time difference.
	 */
	return apply_filters( 'human_time_diff', $since, $diff, $from, $to );
}

if ( ! function_exists( 'landkit_form_errors' ) ) {
	/**
	 * Landkit Form errors.
	 *
	 * @return WP_Error
	 */
	function landkit_form_errors() {
		static $wp_error; // Will hold global variable safely.
		if ( ! isset( $wp_error ) ) {
			$wp_error = new WP_Error( null, null, null );
		}
		return $wp_error;

	}
}

if ( ! function_exists( 'landkit_show_error_messages' ) ) {
	/**
	 * Show Error messages.
	 */
	function landkit_show_error_messages() {
		$codes = landkit_form_errors()->get_error_codes();
		if ( $codes ) {
			echo '<div class="notification alert alert-danger">';
				// Loop error codes and display errors.
			foreach ( $codes as $code ) {
				$message = landkit_form_errors()->get_error_message( $code );
				echo '<span>' . esc_html( $message ) . '</span><br/>';

			}
			echo '</div>';
		}
	}
}

/**
 * Get data if set, otherwise return a default value or null. Prevents notices when data is not set.
 *
 * @param  mixed  $var     Variable.
 * @param  string $default Default value.
 * @return mixed
 */
function landkit_get_var( &$var, $default = null ) {
	return isset( $var ) ? $var : $default;
}

/**
 * Landkit Form Success.
 *
 * @return WP_Error
 */
function landkit_form_success() {
	static $wp_error; // Will hold global variable safely.
	if ( ! isset( $wp_error ) ) {
		$wp_error = new WP_Error( null, null, null );
	}
	return $wp_error;
}

/**
 * Landkit show success messages.
 */
function landkit_show_success_messages() {
	$codes = landkit_form_success()->get_error_codes();
	if ( $codes ) {
		echo '<div class="notification alert alert-success">';
			// Loop success codes and display success.
		foreach ( $codes as $code ) {
			$message = landkit_form_success()->get_error_message( $code );
			echo '<span>' . esc_html( $message ) . '</span><br/>';
		}
		echo '</div>';
	}
}

if ( ! function_exists( 'landkit_login_member' ) ) {
	/**
	 * Logs a member in after submitting a form.
	 */
	function landkit_login_member() {
		$nonce_value = landkit_get_var( $_REQUEST['landkit_login_nonce'], landkit_get_var( $_REQUEST['_wpnonce'], '' ) ); //phpcs:ignore 
		if ( isset( $_POST['landkit_login_check'], $_POST['username'], $_POST['password'] ) && wp_verify_nonce( $nonce_value, 'landkit-login-nonce' ) ) {
			$username = trim( wp_unslash( $_POST['username'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			// this returns the user ID and other info from the user name.
			if ( is_email( $username ) ) {
				$user = get_user_by( 'email', $username );
			} else {
				$user = get_user_by( 'login', $username );
			}

			if ( ! $user ) {
				// if the user name doesn't exist.
				landkit_form_errors()->add( 'empty_username', esc_html__( 'Invalid username or email address', 'landkit' ) );
			}

			do_action( 'landkit_wp_login_form_custom_field_validation' );

			if ( ! empty( $user ) ) {
				$password = $_POST['password']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash

				if ( ! isset( $password ) || '' === $password ) {
					// if no password was entered.
					landkit_form_errors()->add( 'empty_password', esc_html__( 'Please enter a password', 'landkit' ) );
				}

				if ( isset( $password ) && ! empty( $password ) ) {
					// check the user's login with their password.
					if ( ! wp_check_password( $password, $user->user_pass, $user->ID ) ) {
						// if the password is incorrect for the specified user.
						landkit_form_errors()->add( 'empty_password', esc_html__( 'Incorrect password', 'landkit' ) );
					}
				}

				// retrieve all error messages.
				$errors = landkit_form_errors()->get_error_messages();

				// only log the user in if there are no errors.
				if ( empty( $errors ) ) {

					$creds                  = array();
					$creds['user_login']    = $user->user_login;
					$creds['user_password'] = $password;
					$creds['remember']      = true;

					$user = wp_signon( $creds, false );
					// send the newly created user to the home page after logging them in.
					if ( is_wp_error( $user ) ) {
						echo wp_kses_post( $user->get_error_message() );
					} else {
						$o_user = get_user_by( 'login', $creds['user_login'] );
						$a_user = get_object_vars( $o_user );
						$s_role = $a_user['roles'][0];

						if ( isset( $_POST['redirect_to'] ) && ! empty( $_POST['redirect_to'] ) ) {
							$redirect_url = wp_sanitize_redirect( wp_unslash( $_POST['redirect_to'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
						} else {
							$redirect_url = home_url( '/' );
						}

						wp_safe_redirect( wp_validate_redirect( apply_filters( 'landkit_redirect_login_url', $redirect_url ) ) );
					}
					exit;
				}
			}
		}
	}
}

add_action( 'wp_loaded', 'landkit_login_member' );

if ( ! function_exists( 'landkit_add_new_member' ) ) {
	/**
	 * Register a new user.
	 */
	function landkit_add_new_member() {
		$nonce_value = landkit_get_var( $_REQUEST['landkit_register_nonce'], landkit_get_var( $_REQUEST['_wpnonce'], '' ) ); //phpcs:ignore
		if ( isset( $_POST['landkit_register_check'], $_POST['email'] ) && wp_verify_nonce( $nonce_value, 'landkit-register-nonce' ) ) {
			$register_user_name_enabled = apply_filters( 'landkit_register_user_name_enabled', true );
			$default_role               = 'subscriber';
			$available_roles            = array( 'subscriber' );

			if ( function_exists( 'landkit_is_wp_job_manager_activated' ) && landkit_is_wp_job_manager_activated() ) {
				$available_roles[] = 'employer';
			}

			$user_email = wp_unslash( $_POST['email'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$user_role  = ! empty( $_POST['landkit_user_role'] ) && in_array( $_POST['landkit_user_role'], $available_roles, true ) ? sanitize_text_field( wp_unslash( $_POST['landkit_user_role'] ) ) : $default_role;

			if ( ! empty( $_POST['username'] ) ) {
				$user_login = wp_unslash( $_POST['username'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			} else {
				$user_login = sanitize_user( current( explode( '@', $user_email ) ), true );

				// Ensure username is unique.
				$append       = 1;
				$o_user_login = $user_login;

				while ( username_exists( $user_login ) ) {
					$user_login = $o_user_login . $append;
					$append++;
				}
			}

			if ( username_exists( $user_login ) && $register_user_name_enabled ) {
				// Username already registered.
				landkit_form_errors()->add( 'username_unavailable', esc_html__( 'Username already taken', 'landkit' ) );
			}
			if ( ! validate_username( $user_login ) && $register_user_name_enabled ) {
				// invalid username.
				landkit_form_errors()->add( 'username_invalid', esc_html__( 'Invalid username', 'landkit' ) );
			}
			if ( '' === $user_login && $register_user_name_enabled ) {
				// empty username.
				landkit_form_errors()->add( 'username_empty', esc_html__( 'Please enter a username', 'landkit' ) );
			}
			if ( ! is_email( $user_email ) ) {
				// invalid email.
				landkit_form_errors()->add( 'email_invalid', esc_html__( 'Invalid email', 'landkit' ) );
			}
			if ( email_exists( $user_email ) ) {
				// Email address already registered.
				landkit_form_errors()->add( 'email_used', esc_html__( 'Email already registered', 'landkit' ) );
			}

			$password           = wp_generate_password();
			$password_generated = true;

			if ( apply_filters( 'landkit_register_password_enabled', true ) && ! empty( $_POST['password'] ) && ! empty( $_POST['confirmPassword'] ) ) {
				$password           = $_POST['password']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
				$password_generated = false;
			}

			if ( $_POST['password'] !== $_POST['confirmPassword'] ) {
				// Mismatched Password.
				landkit_form_errors()->add( 'wrong_password', esc_html__( 'Password you entered is mismatched', 'landkit' ) );
			}

			do_action( 'landkit_wp_register_form_custom_field_validation' );

			$errors = landkit_form_errors()->get_error_messages();

			// only create the user in if there are no errors.
			if ( empty( $errors ) ) {

				$new_user_data = array(
					'user_login' => $user_login,
					'user_pass'  => $password,
					'user_email' => $user_email,
					'role'       => $user_role,
				);

				$new_user_id = wp_insert_user( $new_user_data );

				if ( $new_user_id ) {
					// send an email to the admin alerting them of the registration.
					if ( apply_filters( 'landkit_new_user_notification', false ) ) {
						wc()->mailer()->customer_new_account( $new_user_id, $new_user_data, $password_generated );
					} else {
						wp_new_user_notification( $new_user_id, null, 'both' );
					}

					// log the new user in.
					$creds                  = array();
					$creds['user_login']    = $user_login;
					$creds['user_password'] = $password;
					$creds['remember']      = true;
					if ( $password_generated ) {
						landkit_form_success()->add( 'verify_user', esc_html__( 'Account created successfully. Please check your email to create your account password', 'landkit' ) );
					} else {
						$user = wp_signon( $creds, false );
						// send the newly created user to the home page after logging them in.
						if ( is_wp_error( $user ) ) {
							echo wp_kses_post( $user->get_error_message() );
						} else {
							$o_user = get_user_by( 'login', $creds['user_login'] );
							$a_user = get_object_vars( $o_user );
							$s_role = $a_user['roles'][0];

							if ( get_option( 'woocommerce_myaccount_page_id' ) ) {
								$account_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
							} else {
								$account_url = home_url( '/' );
							}

							if ( get_option( 'job_manager_job_dashboard_page_id' ) ) {
								$job_url = get_permalink( get_option( 'job_manager_job_dashboard_page_id' ) );
							} else {
								$job_url = home_url( '/' );
							}

							switch ( $s_role ) {
								case 'subscriber':
									$redirect_url = $account_url;
									break;
								case 'employer':
									$redirect_url = $job_url;
									break;

								default:
									$redirect_url = home_url( '/' );
									break;
							}

							wp_safe_redirect( apply_filters( 'landkit_redirect_register_url', $redirect_url, $user ) );
						}
						exit;
					}
				}
			}
		}
	}
}
add_action( 'wp_loaded', 'landkit_add_new_member' );

if ( ! function_exists( 'landkit_lost_password' ) ) {
	/**
	 * Landkit Lost Password function.
	 */
	function landkit_lost_password() {
		$nonce_value = landkit_get_var( $_REQUEST['landkit_lost_password_nonce'], landkit_get_var( $_REQUEST['_wpnonce'], '' ) ); //phpcs:ignore
		if ( isset( $_POST['landkit_lost_password_check'] ) && wp_verify_nonce( $nonce_value, 'landkit-lost-password-nonce' ) ) {
			$login     = isset( $_POST['user_login'] ) ? sanitize_user( wp_unslash( $_POST['user_login'] ) ) : '';
			$user_data = get_user_by( 'login', $login );

			if ( empty( $login ) ) {
				landkit_form_errors()->add( 'empty_user_login', esc_html__( 'Enter a username or email address', 'landkit' ) );

			} else {
				// Check on username first, as customers can use emails as usernames.
				$user_data = get_user_by( 'login', $login );
			}
			// If no user found, check if it login is email and lookup user based on email.
			if ( ! $user_data && is_email( $login ) ) {
				$user_data = get_user_by( 'email', $login );
			}

			do_action( 'lostpassword_post' );

			if ( ! $user_data ) {
				// if the user name doesn't exist.
				landkit_form_errors()->add( 'empty_user_login', esc_html__( 'There is no account with that username or email address.', 'landkit' ) );
			}

			if ( is_multisite() && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
				landkit_form_errors()->add( 'empty_user_login', esc_html__( 'Invalid username or email address.', 'landkit' ) );

				return false;
			}

			$errors = landkit_form_errors()->get_error_messages();

			// only create the user in if there are no errors.
			if ( empty( $errors ) ) {
				landkit_form_success()->add( 'verify_user', esc_html__( 'Passord has been sent to your email', 'landkit' ) );

			}
		}

	}
}
add_action( 'wp_loaded', 'landkit_lost_password' );

if ( ! function_exists( 'landkit_share_display' ) ) {
	/**
	 * Landkit Share display services.
	 */
	function landkit_share_display() {

		if ( ! class_exists( 'Landkit_SocialShare' ) ) {
			return;
		}

		$services = Landkit_SocialShare::get_share_services();

		?>
		<span class="h6 text-uppercase text-muted d-none d-md-inline mr-4"><?php echo esc_html__( 'Share:', 'landkit' ); ?></span>
		<ul class="d-inline list-unstyled list-inline list-social">

		<?php
		foreach ( $services as $service ) :
			if ( ! isset( $service['share'] ) ) {
				continue; }
			?>
			<li class="list-inline-item list-social-item mr-3">
				<a href="<?php echo esc_url( $service['share'] ); ?>" class="text-decoration-none text-muted font-size-xl" target="_blank" rel="noopener noreferrer">
					<?php if ( isset( $service['icon'] ) ) : ?>
					<i class="list-social-icon <?php echo esc_attr( $service['icon'] ); ?>"></i>
					<?php endif; ?>

					<?php if ( isset( $service['name'] ) ) : ?>
					<span class="sr-only">
						<?php
							/* translators: %s - Name of the share service */
							echo esc_html( sprintf( esc_html__( 'Share this on %s', 'landkit' ), $service['name'] ) );
						?>
					</span>
					<?php endif; ?>
				</a>
			</li>

		<?php endforeach; ?>

		</ul>
		<?php
	}
}

if ( ! function_exists( 'landkit_default_colors' ) ) :
	/**
	 * Landkit default colors.
	 *
	 * @return array
	 */
	function landkit_default_colors() {
		$landkit_colors = [
			[
				'_id'   => 'primary',
				'title' => esc_html__( 'Primary', 'landkit' ),
				'color' => '#335EEA',
			],
			[
				'_id'   => 'secondary',
				'title' => esc_html__( 'Secondary', 'landkit' ),
				'color' => '#506690',
			],
			[
				'_id'   => 'primary-desat',
				'title' => esc_html__( 'Primary Desat', 'landkit' ),
				'color' => '#6C8AEC',
			],
			[
				'_id'   => 'success',
				'title' => esc_html__( 'Success', 'landkit' ),
				'color' => '#42BA96',
			],
			[
				'_id'   => 'info',
				'title' => esc_html__( 'Info', 'landkit' ),
				'color' => '#7C69EF',
			],
			[
				'_id'   => 'warning',
				'title' => esc_html__( 'Warning', 'landkit' ),
				'color' => '#FAD776',
			],
			[
				'_id'   => 'danger',
				'title' => esc_html__( 'Danger', 'landkit' ),
				'color' => '#DF4759',
			],
			[
				'_id'   => 'light',
				'title' => esc_html__( 'Light', 'landkit' ),
				'color' => '#F9FBFD',
			],
			[
				'_id'   => 'dark',
				'title' => esc_html__( 'Dark', 'landkit' ),
				'color' => '#1B2A4E',
			],
			[
				'_id'   => 'white',
				'title' => esc_html__( 'White', 'landkit' ),
				'color' => '#FFFFFF',
			],
			[
				'_id'   => 'black',
				'title' => esc_html__( 'Black', 'landkit' ),
				'color' => '#161C2D',
			],
			[
				'_id'   => 'text',
				'title' => esc_html__( 'Text', 'landkit' ),
				'color' => '#161C2D',
			],
			[
				'_id'   => 'textmuted',
				'title' => esc_html__( 'Text Muted', 'landkit' ),
				'color' => '#869ab8',
			],
			[
				'_id'   => 'gray100',
				'title' => esc_html__( 'Gray 100', 'landkit' ),
				'color' => '#F9FBFD',
			],
			[
				'_id'   => 'gray200',
				'title' => esc_html__( 'Gray 200', 'landkit' ),
				'color' => '#F1F4F8',
			],
			[
				'_id'   => 'gray300',
				'title' => esc_html__( 'Gray 300', 'landkit' ),
				'color' => '#D9E2EF',
			],
			[
				'_id'   => 'gray400',
				'title' => esc_html__( 'Gray 400', 'landkit' ),
				'color' => '#C6D3E6',
			],
			[
				'_id'   => 'gray500',
				'title' => esc_html__( 'Gray 500', 'landkit' ),
				'color' => '#ABBCD5',
			],
			[
				'_id'   => 'gray600',
				'title' => esc_html__( 'Gray 600', 'landkit' ),
				'color' => '#869AB8',
			],
			[
				'_id'   => 'gray700',
				'title' => esc_html__( 'Gray 700', 'landkit' ),
				'color' => '#506690',
			],
			[
				'_id'   => 'gray800',
				'title' => esc_html__( 'Gray 800', 'landkit' ),
				'color' => '#384C74',
			],
			[
				'_id'   => 'gray900',
				'title' => esc_html__( 'Gray 900', 'landkit' ),
				'color' => '#1B2A4E',
			],
			[
				'_id'   => 'accent',
				'title' => esc_html__( 'Accent', 'landkit' ),
				'color' => '#335EEA',
			],
		];

		return apply_filters( 'landkit_default_colors', $landkit_colors );
	}
endif;
