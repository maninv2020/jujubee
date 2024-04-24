<?php
/**
 * Landkit Job Manager Class
 *
 * @package  front
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Landkit_WPJM' ) ) :

	/**
	 * The main Landkit class
	 */
	class Landkit_WPJM {
		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'widgets_init', array( $this, 'widgets_init' ) );
			add_filter( 'body_class', array( $this, 'set_bg_light' ), 10, 2 );
			add_filter( 'job_manager_enqueue_frontend_style', '__return_false', 30 );
			add_action( 'init', array( $this, 'init' ) );
			add_filter( 'job_manager_page_ids', array( $this, 'check_empty_wpjm_pages' ) );
		}
		/**
		 * Initialise.
		 */
		public function init() {
			if ( apply_filters( 'landkit_wpjm_use_custom_form_application', filter_var( get_theme_mod( 'enable_application_form', 'yes' ), FILTER_VALIDATE_BOOLEAN ) ) ) {

				global $job_manager;

				if ( ! is_admin() ) {
					if ( get_option( 'job_application_form_for_email_method', '1' ) ) {
						add_action( 'job_manager_application_details_email', [ $this, 'application_form' ], 20 );

						// Unhook job manager apply details.
						remove_action( 'job_manager_application_details_email', [ $job_manager->post_types, 'application_details_email' ] );
					}
					if ( get_option( 'job_application_form_for_url_method', '1' ) ) {
						add_action( 'job_manager_application_details_url', [ $this, 'application_form' ], 20 );

						// Unhook job manager apply details.
						remove_action( 'job_manager_application_details_url', [ $job_manager->post_types, 'application_details_url' ] );
					}
				}
			}
		}
		/**
		 * Check wpjm empty pages.
		 *
		 * @param int $wpjm_page_ids Page id.
		 */
		public function check_empty_wpjm_pages( $wpjm_page_ids ) {
			if ( empty( $wpjm_page_ids ) ) {
				$wpjm_page_ids = array( false );
			}

			return $wpjm_page_ids;
		}
		/**
		 * Application form shortcode.
		 */
		public function application_form() {
			$application_form = apply_filters( 'landkit_wpjm_application_form_shortcode', get_theme_mod( 'landkit_application_form' ) );

			if ( ! empty( $application_form ) ) {
				echo do_shortcode( $application_form );
			}
		}
		/**
		 * Adds custom classes to the array of body classes.
		 *
		 * @param array $classes Classes for the body element.
		 * @param array $class Class for the body element.
		 */
		public function set_bg_light( $classes, $class ) {
			if ( is_wpjm() ) {
				$classes[] = 'bg-light';
			}

			return $classes;
		}
		/**
		 * Register widget area.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
		 */
		public function widgets_init() {
			register_sidebar(
				array(
					'name'          => esc_html__( 'Single Job Listing Sidebar', 'landkit' ),
					'id'            => 'sidebar-single-job-listing',
					'description'   => esc_html__( 'Widgets in this area will be shown under your single job listing, before comments.', 'landkit' ),
					'before_widget' => '<div class="card shadow-light-lg mb-5"><div class="card-body">',
					'after_widget'  => '</div></div>',
					'before_title'  => '<h4>',
					'after_title'   => '</h4>',
				)
			);
		}
	}

endif;

new Landkit_WPJM();
