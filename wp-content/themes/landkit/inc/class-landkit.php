<?php
/**
 * Landkit Class
 *
 * @since    1.0.0
 * @package  landkit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Landkit' ) ) :

	/**
	 * The main Landkit class
	 */
	class Landkit {

		/**
		 * Setup Class
		 */
		public function __construct() {
			add_action( 'after_setup_theme', array( $this, 'setup' ) );
			add_action( 'after_setup_theme', array( $this, 'wpforms_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ), 20 );
			add_action( 'admin_menu', array( $this, 'admin_pages' ) );
			add_action( 'widgets_init', array( $this, 'widgets_init' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 90 );
			add_action( 'wp_enqueue_scripts', array( $this, 'child_scripts' ), 95 ); // After WooCommerce.
			add_action( 'enqueue_block_assets', array( $this, 'block_assets' ) );
			add_filter( 'body_class', array( $this, 'body_classes' ) );
		}

		/**
		 * Sets up theme defaults and registers support for various WordPress features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 */
		public function setup() {
			/*
			 * Load Localisation files.
			 *
			 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
			 */

			// Loads wp-content/languages/themes/landkit-it_IT.mo.
			load_theme_textdomain( 'landkit', trailingslashit( WP_LANG_DIR ) . 'themes' );

			// Loads wp-content/themes/child-theme-name/languages/it_IT.mo.
			load_theme_textdomain( 'landkit', get_stylesheet_directory() . '/languages' );

			// Loads wp-content/themes/landkit/languages/it_IT.mo.
			load_theme_textdomain( 'landkit', get_template_directory() . '/languages' );

			/**
			 * Add default posts and comments RSS feed links to head.
			 */
			add_theme_support( 'automatic-feed-links' );

			/*
			 * Enable support for Post Thumbnails on posts and pages.
			 *
			 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#Post_Thumbnails
			 */
			add_theme_support( 'post-thumbnails' );

			/**
			 * Add Excerpt Support for Pages
			 */
			add_post_type_support( 'page', 'excerpt' );

			/**
			 * Enable support for site logo.
			 */
			add_theme_support(
				'custom-logo',
				apply_filters(
					'landkit_custom_logo_args',
					array(
						'width'       => 86,
						'height'      => 19,
						'flex-width'  => true,
						'flex-height' => true,
					)
				)
			);

			/**
			 * Register menu locations.
			 */
			register_nav_menus(
				apply_filters(
					'landkit_register_nav_menus',
					array(
						'navbar_nav'   => esc_html_x( 'Navbar Nav', 'menu location', 'landkit' ),
						'social_media' => esc_html_x( 'Social Media', 'menu location', 'landkit' ),
					)
				)
			);

			/*
			 * Switch default core markup for search form, comment form, comments, galleries, captions and widgets
			 * to output valid HTML5.
			 */
			add_theme_support(
				'html5',
				apply_filters(
					'landkit_html5_args',
					array(
						'search-form',
						'comment-form',
						'comment-list',
						'gallery',
						'caption',
						'widgets',
					)
				)
			);

			/**
			 * Add support for Block Styles.
			 */
			add_theme_support( 'wp-block-styles' );

			/*
			 * Add support for full and wide align images.
			 */
			add_theme_support( 'align-wide' );

			/**
			 * Add support for editor styles.
			 */
			add_theme_support( 'editor-styles' );

			/**
			 * Declare support for title theme feature.
			 */
			add_theme_support( 'title-tag' );

			/**
			 * Add support for responsive embedded content.
			 */
			add_theme_support( 'responsive-embeds' );

			/**
			 * Enqueue editor styles.
			 */

			$editor_styles = [
				is_rtl() ? 'assets/css/gutenberg-editor-rtl.css' : 'assets/css/gutenberg-editor.css',
			];

			add_editor_style( $editor_styles );

			/*
			 * Remove support for widget block editor.
			 */
			remove_theme_support( 'widgets-block-editor' );

		}

		/**
		 * Enqueue assets on admin side
		 *
		 * @since 1.0.0
		 */
		public function admin_assets() {
			global $landkit_version;
			wp_enqueue_style( 'landkit-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), $landkit_version, 'screen' );
		}

		/**
		 * Add custom pages in "Appearance"
		 *
		 * @since 1.0.0
		 */
		public function admin_pages() {
			add_theme_page(
				esc_html__( 'Duatone Icons', 'landkit' ),
				esc_html__( 'Duatone Icons', 'landkit' ),
				'edit_theme_options',
				'landkit-duatone-icons',
				function() {
					require get_theme_file_path( 'templates/admin/icons.php' );
				}
			);
		}

		/**
		 * Register widget area.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
		 */
		public function widgets_init() {

			$rows    = intval( apply_filters( 'landkit_footer_widget_rows', 1 ) );
			$regions = intval( apply_filters( 'landkit_footer_widget_columns', 5 ) );

			if ( ! class_exists( 'Landkit_Extensions' ) ) {
				$regions = 3;
			}

			for ( $row = 1; $row <= $rows; $row++ ) {
				for ( $region = 1; $region <= $regions; $region++ ) {
					$footer_n = $region + $regions * ( $row - 1 ); // Defines footer sidebar ID.
					$footer   = sprintf( 'footer_%d', $footer_n );

					if ( 1 === $rows ) {
						/* translators: 1: column number */
						$footer_region_name = sprintf( __( 'Footer Column %1$d', 'landkit' ), $region );

						/* translators: 1: column number */
						$footer_region_description = sprintf( __( 'Widgets added here will appear in column %1$d of the footer.', 'landkit' ), $region );
					} else {
						/* translators: 1: row number, 2: column number */
						$footer_region_name = sprintf( __( 'Footer Row %1$d - Column %2$d', 'landkit' ), $row, $region );

						/* translators: 1: column number, 2: row number */
						$footer_region_description = sprintf( __( 'Widgets added here will appear in column %1$d of footer row %2$d.', 'landkit' ), $region, $row );
					}

					$sidebar_args[ $footer ] = array(
						'name'        => $footer_region_name,
						'id'          => sprintf( 'footer-%d', $footer_n ),
						'description' => $footer_region_description,
					);
				}
			}

			$sidebar_args = apply_filters( 'landkit_sidebar_args', $sidebar_args );

			foreach ( $sidebar_args as $sidebar => $args ) {
				$widget_tags = array(
					'before_widget' => '<div id="%1$s" class="widget text-muted widget__footer mb-6 mb-md-8 %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h6 class="widget__title font-weight-bold text-uppercase text-gray-700">',
					'after_title'   => '</h6>',
				);

				/**
				 * Dynamically generated filter hooks. Allow changing widget wrapper and title tags. See the list below.
				 *
				 * 'landkit_footer_1_widget_tags'
				 * 'landkit_footer_2_widget_tags'
				 * 'landkit_footer_3_widget_tags'
				 * 'landkit_footer_4_widget_tags'
				 */
				$filter_hook = sprintf( 'landkit_%s_widget_tags', $sidebar );
				$widget_tags = apply_filters( $filter_hook, $widget_tags );

				if ( is_array( $widget_tags ) ) {
					register_sidebar( $args + $widget_tags );
				}
			}
		}

		/**
		 * Enqueue scripts and styles.
		 *
		 * @since  1.0.0
		 */
		public function scripts() {
			global $landkit_version;

			/**
			 * Styles
			 */
			$css_files = $this->get_css_libraries();

			foreach ( $css_files as $handle => $css_file ) {
				wp_enqueue_style( $handle, $css_file['src'], $css_file['dep'], $css_file['ver'] );
			}

			wp_enqueue_style( 'landkit-style', get_template_directory_uri() . '/style.css', '', $landkit_version );
			wp_style_add_data( 'landkit-style', 'rtl', 'replace' );

			/**
			 * Scripts
			 */
			$js_files = $this->get_js_libraries();
			foreach ( $js_files as $handle => $js_file ) {
				wp_enqueue_script( $handle, $js_file['src'], $js_file['dep'], $js_file['ver'], true );
			}

			wp_enqueue_script( 'landkit-js', get_template_directory_uri() . '/assets/js/theme.min.js', array( 'jquery', 'bootstrap-bundle' ), $landkit_version, true );

			wp_enqueue_script( 'landkit-scripts', get_template_directory_uri() . '/assets/js/landkit.js', array( 'jquery', 'bootstrap-bundle' ), $landkit_version, true );

			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}

			if ( apply_filters( 'landkit_use_predefined_colors', true ) && filter_var( get_theme_mod( 'landkit_enable_custom_color', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) {
				wp_enqueue_style( 'landkit-color', get_template_directory_uri() . '/assets/css/colors/color.css', '', $landkit_version );

			}

		}

		/**
		 * Get CSS Libraries used by Landkit
		 *
		 * @since 1.0.0
		 */
		private function get_css_libraries() {
			global $landkit_version;

			return apply_filters(
				'landkit_css_vendors',
				array(
					'feather'             => array(
						'src' => get_template_directory_uri() . '/assets/fonts/Feather/feather.css',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'fontawesome'         => array(
						'src' => get_template_directory_uri() . '/assets/libs/fontawesome/css/all.min.css',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'icomoon'             => array(
						'src' => get_template_directory_uri() . '/assets/fonts/icomoon/icomoon.css',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'jquery-fancybox-css' => array(
						'src' => get_template_directory_uri() . '/assets/libs/@fancyapps/fancybox/dist/jquery.fancybox.min.css',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'aos'                 => array(
						'src' => get_template_directory_uri() . '/assets/libs/aos/dist/aos.css',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'choices.js'          => array(
						'src' => get_template_directory_uri() . '/assets/libs/choices.js/public/assets/styles/choices.min.css',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'flickity-fade'       => array(
						'src' => get_template_directory_uri() . '/assets/libs/flickity-fade/flickity-fade.css',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'flickity'            => array(
						'src' => get_template_directory_uri() . '/assets/libs/flickity/dist/flickity.min.css',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'highlightjs'         => array(
						'src' => get_template_directory_uri() . '/assets/libs/highlightjs/styles/vs2015.css',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'jarallax'            => array(
						'src' => get_template_directory_uri() . '/assets/libs/jarallax/dist/jarallax.css',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'mapbox-gl-css'       => array(
						'src' => get_template_directory_uri() . '/assets/libs/mapbox-gl/dist/mapbox-gl.css',
						'dep' => array(),
						'ver' => $landkit_version,
					),
				)
			);
		}

		/**
		 * Get JS Libraries used by Landkit
		 */
		private function get_js_libraries() {
			global $landkit_version;

			return apply_filters(
				'landkit_js_vendors',
				array(
					'bootstrap-bundle' => array(
						'src' => get_template_directory_uri() . '/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js',
						'dep' => array( 'jquery' ),
						'ver' => $landkit_version,
					),
					'jquery-fancybox'  => array(
						'src' => get_template_directory_uri() . '/assets/libs/@fancyapps/fancybox/dist/jquery.fancybox.min.js',
						'dep' => array( 'jquery' ),
						'ver' => $landkit_version,
					),
					'aos'              => array(
						'src' => get_template_directory_uri() . '/assets/libs/aos/dist/aos.js',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'choices-js'       => array(
						'src' => get_template_directory_uri() . '/assets/libs/choices.js/public/assets/scripts/choices.min.js',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'countup-js'       => array(
						'src' => get_template_directory_uri() . '/assets/libs/countup.js/dist/countUp.min.js',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'dropzone'         => array(
						'src' => get_template_directory_uri() . '/assets/libs/dropzone/dist/min/dropzone.min.js',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'flickity'         => array(
						'src' => get_template_directory_uri() . '/assets/libs/flickity/dist/flickity.pkgd.min.js',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'flickity-fade'    => array(
						'src' => get_template_directory_uri() . '/assets/libs/flickity-fade/flickity-fade.js',
						'dep' => array( 'flickity' ),
						'ver' => $landkit_version,
					),
					'highlightjs'      => array(
						'src' => get_template_directory_uri() . '/assets/libs/highlightjs/highlight.pack.min.js',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'imagesloaded'     => array(
						'src' => get_template_directory_uri() . '/assets/libs/imagesloaded/imagesloaded.pkgd.min.js',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'isotope-layout'   => array(
						'src' => get_template_directory_uri() . '/assets/libs/isotope-layout/dist/isotope.pkgd.min.js',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'jarallax'         => array(
						'src' => get_template_directory_uri() . '/assets/libs/jarallax/dist/jarallax.min.js',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'jarallax-video'   => array(
						'src' => get_template_directory_uri() . '/assets/libs/jarallax/dist/jarallax-video.min.js',
						'dep' => array( 'jarallax' ),
						'ver' => $landkit_version,
					),
					'jarallax-element' => array(
						'src' => get_template_directory_uri() . '/assets/libs/jarallax/dist/jarallax-element.min.js',
						'dep' => array( 'jarallax' ),
						'ver' => $landkit_version,
					),
					'smooth-scroll'    => array(
						'src' => get_template_directory_uri() . '/assets/libs/smooth-scroll/dist/smooth-scroll.min.js',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'typed-js'         => array(
						'src' => get_template_directory_uri() . '/assets/libs/typed.js/lib/typed.min.js',
						'dep' => array(),
						'ver' => $landkit_version,
					),
					'mapbox-gl-js'     => array(
						'src' => get_template_directory_uri() . '/assets/libs/mapbox-gl/dist/mapbox-gl.js',
						'dep' => array(),
						'ver' => $landkit_version,
					),
				)
			);
		}

		/**
		 * Enqueue child theme stylesheet.
		 * A separate function is required as the child theme css needs to be enqueued _after_ the parent theme
		 * primary css and the separate WooCommerce css.
		 *
		 * @since  1.0.0
		 */
		public function child_scripts() {
			if ( is_child_theme() ) {
				$child_theme = wp_get_theme( get_stylesheet() );
				wp_enqueue_style( 'landkit-child-style', get_stylesheet_uri(), array(), $child_theme->get( 'Version' ) );
			}
		}

		/**
		 * Enqueue WPForm scripts.
		 */
		public function wpforms_scripts() {
			if ( function_exists( 'wpforms' ) ) {
				$settings                = get_option( 'wpforms_settings', array() );
				$settings['disable-css'] = 2;
			}
		}

		/**
		 * Adds custom classes to the array of body classes.
		 *
		 * @param array $classes Classes for the body element.
		 * @return array
		 */
		public function body_classes( $classes ) {

			$classes[] = 'text-break';

			if ( is_archive() || is_home() ) {
				$classes[] = 'bg-light';
			}

			if ( function_exists( 'landkit_option_enabled_post_types' ) && is_singular( landkit_option_enabled_post_types() ) ) {
				$clean_meta_data = get_post_meta( get_the_ID(), '_lk_page_options', true );
				$lk_page_options = maybe_unserialize( $clean_meta_data );

				if ( isset( $lk_page_options['general'] ) && isset( $lk_page_options['general']['body_classes'] ) && ! empty( $lk_page_options['general']['body_classes'] ) ) {
					$classes[] = $lk_page_options['general']['body_classes'];
				}
			}

			return $classes;
		}

		/**
		 * Enqueue block assets.
		 *
		 * @since 2.5.0
		 */
		public function block_assets() {
			global $landkit_version;

			// Styles.
			wp_enqueue_style( 'landkit-gutenberg-blocks', get_template_directory_uri() . '/assets/css/gutenberg-blocks.css', '', $landkit_version );
			wp_style_add_data( 'landkit-gutenberg-blocks', 'rtl', 'replace' );
		}
	}
endif;

return new Landkit();
