<?php
/**
 * Landkit Admin Class
 *
 * @package  storefront
 * @since    2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Landkit_Admin' ) ) :
	/**
	 * The Landkit admin class
	 */
	class Landkit_Admin {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'admin_init', [ $this, 'run_once' ] );
		}

		/**
		 * Setup class.
		 */
		public function run_once() {
			if ( get_option( 'landkit_admin_run_once_completed', false ) ) {
				return;
			}

			update_option( 'job_manager_enable_categories', '1' );

			do_action( 'landkit_admin_run_once' );

			update_option( 'landkit_admin_run_once_completed', true );
		}
	}

endif;

return new Landkit_Admin();
