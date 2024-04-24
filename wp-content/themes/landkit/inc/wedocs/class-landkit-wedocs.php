<?php
/**
 * Landkit WeDocs Class
 *
 * @package  landkit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Landkit_WeDocs' ) ) :

	/**
	 * Landkit WeDocs Integration class
	 */
	class Landkit_WeDocs {

		/**
		 * Setup class.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->init_hooks();
		}

		/**
		 * Initialize Hooks
		 *
		 * @since 1.0.0
		 */
		private function init_hooks() {
			add_filter( 'wedocs_post_type', array( $this, 'post_type_args' ), 10 );
			add_filter( 'body_class', array( $this, 'set_bg_light' ), 10, 2 );
		}
		/**
		 * Add excerpt support
		 *
		 * @param array $args Argument.
		 */
		public function post_type_args( $args ) {
			$args['supports'][] = 'excerpt';
			return $args;
		}
		/**
		 * Adds custom classes to the array of body classes.
		 *
		 * @param array $classes Classes for the body element.
		 * @param array $class Class for the body element.
		 */
		public function set_bg_light( $classes, $class ) {
			if ( landkit_is_wedocs() ) {
				$classes[] = 'bg-light';
			}

			return $classes;
		}
	}

endif;

return new Landkit_WeDocs();
