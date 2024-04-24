<?php
/**
 * Landkit Plugin Install Class
 *
 * @package  landkit
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Landkit_Plugin_Install' ) ) :
	/**
	 * The Landkit plugin install class
	 */
	class Landkit_Plugin_Install {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'plugin_install_scripts' ) );
			add_action( 'tgmpa_register', [ $this, 'register_required_plugins' ] );
		}

		/**
		 * Wrapper around the core WP get_plugins function, making sure it's actually available.
		 *
		 * @since 2.5.0
		 *
		 * @param string $plugin_folder Optional. Relative path to single plugin folder.
		 * @return array Array of installed plugins with plugin information.
		 */
		public function get_plugins( $plugin_folder = '' ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			return get_plugins( $plugin_folder );
		}

		/**
		 * Helper function to extract the file path of the plugin file from the
		 * plugin slug, if the plugin is installed.
		 *
		 * @since 2.0.0
		 *
		 * @param string $slug Plugin slug (typically folder name) as provided by the developer.
		 * @return string Either file path for plugin if installed, or just the plugin slug.
		 */
		protected function get_plugin_basename_from_slug( $slug ) {
			$keys = array_keys( $this->get_plugins() );

			foreach ( $keys as $key ) {
				if ( preg_match( '|^' . $slug . '/|', $key ) ) {
					return $key;
				}
			}

			return $slug;
		}

		/**
		 * Check if all plugins profile are installed
		 *
		 * @param string $plugins Plugins.
		 */
		public function requires_install_plugins( $plugins ) {
			$requires = false;

			foreach ( $plugins as $plugin ) {
				$plugin['file_path']   = $this->get_plugin_basename_from_slug( $plugin['slug'] ); //phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
				$plugin['is_callable'] = '';

				if ( ! TGM_Plugin_Activation::is_active( $plugin ) ) {
					$requires = true;
					break;
				}
			}

			return $requires;
		}

		/**
		 * Load plugin install scripts
		 *
		 * @param string $hook_suffix the current page hook suffix.
		 * @return void
		 * @since  1.4.4
		 */
		public function plugin_install_scripts( $hook_suffix ) {
			global $landkit, $landkit_version;

			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '';

			wp_register_script( 'landkit-plugin-install', get_template_directory_uri() . '/assets/js/admin/plugin-install' . $suffix . '.js', array( 'jquery', 'updates' ), $landkit_version, 'all' );

			$params = [
				'tgmpa_url'   => admin_url( add_query_arg( 'page', 'tgmpa-install-plugins', 'themes.php' ) ),
				'txt_install' => esc_html__( 'Install Plugins', 'landkit' ),
				'profiles'    => $this->get_profile_params(),
			];

			if ( landkit_is_ocdi_activated() ) {
				$params['file_args'] = $landkit->ocdi->import_files();
			}
			wp_localize_script( 'landkit-plugin-install', 'ocdi_params', $params );
			wp_enqueue_script( 'landkit-plugin-install' );

			wp_enqueue_style( 'landkit-plugin-install', get_template_directory_uri() . '/assets/css/admin/plugin-install.css', array(), $landkit_version, 'all' );
		}
		/**
		 * Get profile params
		 */
		public function get_profile_params() {
			$profiles = $this->get_demo_profiles();
			$params   = [];
			foreach ( $profiles as $key => $profile ) {
				$plugins                            = $this->get_demo_plugins( $key );
				$params[ $key ]['requires_install'] = $this->requires_install_plugins( $plugins );
				if ( $params[ $key ]['requires_install'] ) {
					$params['all']['requires_install'] = true;
				}
			}
			return $params;
		}
		/**
		 * Get demo profiles
		 */
		public function get_demo_profiles() {
			return array(
				'default' => array(
					array(
						'name'     => 'Elementor',
						'slug'     => 'elementor',
						'required' => true,
					),
					array(
						'name'     => 'Landkit Elementor',
						'slug'     => 'landkit-elementor',
						'source'   => 'http://transvelo.github.io/landkit/assets/plugins/landkit-elementor.zip',
						'required' => true,
					),
					array(
						'name'     => 'Landkit Extensions',
						'slug'     => 'landkit-extensions',
						'source'   => 'http://transvelo.github.io/landkit/assets/plugins/landkit-extensions.zip',
						'required' => true,
					),
					array(
						'name'     => 'MAS Static Content',
						'slug'     => 'mas-static-content',
						'required' => true,
					),
					array(
						'name'     => 'One Click Demo Import',
						'slug'     => 'one-click-demo-import',
						'required' => false,
					),
					array(
						'name'     => 'WPForms Lite',
						'slug'     => 'wpforms-lite',
						'required' => false,
					),
					array(
						'name'        => 'Safe SVG',
						'slug'        => 'safe-svg',
						'required'    => false,
						'description' => esc_html__( 'Use this plugin to upload SVG files.', 'landkit' ),
					),
				),
				'jobs'    => array(
					array(
						'name'     => 'WP Job Manager',
						'slug'     => 'wp-job-manager',
						'required' => false,
					),
				),
				'docs'    => array(
					array(
						'name'     => 'weDocs',
						'slug'     => 'wedocs',
						'required' => false,
					),
				),
			);
		}
		/**
		 * Get demo plugins
		 *
		 * @param string $demo The demo profile.
		 */
		public function get_demo_plugins( $demo = '' ) {
			$profiles = $this->get_demo_profiles();
			$plugins  = [];

			foreach ( $profiles as $key => $profile ) {
				if ( 'all' === $demo || 'default' === $key || $key === $demo ) {
					$plugins = array_merge( $plugins, $profile );
				}
			}

			return $plugins;
		}

		/**
		 * Register the required plugins for this theme.
		 *
		 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
		 */
		public function register_required_plugins() {
			/*
			 * Array of plugin arrays. Required keys are name and slug.
			 * If the source is NOT from the .org repo, then source is also required.
			 */

			$profile = isset( $_GET['demo'] ) ? sanitize_text_field( wp_unslash( $_GET['demo'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			$plugins = $this->get_demo_plugins( $profile );

			$config = array(
				'id'           => 'landkit', // Unique ID for hashing notices for multiple instances of TGMPA.
				'default_path' => '',        // Default absolute path to bundled plugins.
				'menu'         => 'tgmpa-install-plugins', // Menu slug.
				'has_notices'  => true,      // Show admin notices or not.
				'dismissable'  => true,      // If false, a user cannot dismiss the nag message.
				'dismiss_msg'  => '',        // If 'dismissable' is false, this message will be output at top of nag.
				'is_automatic' => false,     // Automatically activate plugins after installation or not.
				'message'      => '',        // Message to output right before the plugins table.
			);

			tgmpa( $plugins, $config );
		}
	}

endif;

return new Landkit_Plugin_Install();
