<?php
/**
 * Landkit OCDI Class
 *
 * @package  landkit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Landkit_OCDI' ) ) :
	/**
	 * The one click demo import class.
	 */
	class Landkit_OCDI {
		/**
		 * Stores the assets URL.
		 *
		 * @var string
		 */
		public $assets_url;

		/**
		 * Stores the demo URL.
		 *
		 * @var string
		 */
		public $demo_url;

		/**
		 * Instantiate the class.
		 */
		public function __construct() {
			$this->assets_url = 'https://transvelo.github.io/landkit/assets/';
			$this->demo_url   = 'https://landkit.madrasthemes.com/demo/';

			add_filter( 'pt-ocdi/confirmation_dialog_options', [ $this, 'confirmation_dialog_options' ], 10, 1 );

			add_action( 'pt-ocdi/import_files', [ $this, 'import_files' ] );
			add_action( 'pt-ocdi/after_import', [ $this, 'import_wpforms' ] );
			add_action( 'pt-ocdi/after_import', [ $this, 'set_nav_menus' ] );
			add_action( 'pt-ocdi/after_import', [ $this, 'set_site_options' ] );
			add_action( 'pt-ocdi/after_import', [ $this, 'replace_uploads_dir' ] );

			add_action( 'pt-ocdi/enable_wp_customize_save_hooks', '__return_true' );
			add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );
		}

		/**
		 * Confirmation dialog box options.
		 *
		 * @param array $options The dialog options.
		 * @return array
		 */
		public function confirmation_dialog_options( $options ) {
			return array_merge(
				$options,
				array(
					'width' => 410,
				)
			);
		}

		/**
		 * Trigger after import
		 */
		public function trigger_ocdi_after_import() {
			$import_files    = $this->import_files();
			$selected_import = end( $import_files );
			do_action( 'pt-ocdi/after_import', $selected_import );//phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		}
		/**
		 * Replace uploads Dir.
		 *
		 * @param array $selected_import The import that just ran.
		 */
		public function replace_uploads_dir( $selected_import ) {
			if ( isset( $selected_import['uploads_dir'] ) ) {
				$from = $selected_import['uploads_dir'] . '/';
			} else {
				$from = 'https://landkit.madrasthemes.com/demo/wp-content/uploads/';
			}

			$site_upload_dir = wp_get_upload_dir();
			$to              = $site_upload_dir['baseurl'] . '/';
			\Elementor\Utils::replace_urls( $from, $to );
		}

		/**
		 * Clear default widgets.
		 */
		public function clear_default_widgets() {
			$sidebars_widgets = get_option( 'sidebars_widgets' );
			$all_widgets      = array();

			array_walk_recursive(
				$sidebars_widgets,
				function ( $item, $key ) use ( &$all_widgets ) {
					if ( ! isset( $all_widgets[ $key ] ) ) {
						$all_widgets[ $key ] = $item;
					} else {
						$all_widgets[] = $item;
					}
				}
			);

			if ( isset( $all_widgets['array_version'] ) ) {
				$array_version = $all_widgets['array_version'];
				unset( $all_widgets['array_version'] );
			}

			$new_sidebars_widgets = array_fill_keys( array_keys( $sidebars_widgets ), array() );

			$new_sidebars_widgets['wp_inactive_widgets'] = $all_widgets;
			if ( isset( $array_version ) ) {
				$new_sidebars_widgets['array_version'] = $array_version;
			}

			update_option( 'sidebars_widgets', $new_sidebars_widgets );
		}

		/**
		 * Set site options.
		 *
		 * @param array $selected_import The import that just ran.
		 */
		public function set_site_options( $selected_import ) {
			if ( isset( $selected_import['set_pages'] ) && $selected_import['set_pages'] ) {
				$front_page_title = isset( $selected_import['front_page_title'] ) ? $selected_import['front_page_title'] : 'Basic';
				$front_page_id    = get_page_by_title( $front_page_title );

				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $front_page_id->ID );
			}

			update_option( 'posts_per_page', 9 );
		}

		/**
		 * Set the nav menus.
		 *
		 * @param array $selected_import The import that just ran.
		 */
		public function set_nav_menus( $selected_import ) {
			if ( isset( $selected_import['set_nav_menus'] ) && $selected_import['set_nav_menus'] ) {
				$main_menu   = get_term_by( 'name', 'Main Menu', 'nav_menu' );
				$social_menu = get_term_by( 'name', 'Social Icons', 'nav_menu' );
				$locations   = [
					'navbar_nav'   => $main_menu->term_id,
					'social_media' => $social_menu->term_id,
				];

				set_theme_mod( 'nav_menu_locations', $locations );
			}
		}

		/**
		 * Import WPForms.
		 */
		public function import_wpforms() {

			$assets_url = get_template_directory_uri() . '/assets/demo/';

			if ( ! function_exists( 'wpforms' ) || get_option( 'landkit_wpforms_imported', false ) ) {
				return;
			}

			$wpform_file_url = $assets_url . 'wpforms/all.json';
			$response        = wp_remote_get( $wpform_file_url );

			if ( is_wp_error( $response ) || 200 !== $response['response']['code'] ) {
				return;
			}

			$form_json = wp_remote_retrieve_body( $response );

			if ( is_wp_error( $form_json ) ) {
				return;
			}

			$forms = json_decode( $form_json, true );

			foreach ( $forms as $form_data ) {
				$form_title = $form_data['settings']['form_title'];

				if ( ! empty( $form_data['id'] ) ) {
					$form_content = array(
						'field_id' => '0',
						'settings' => array(
							'form_title' => sanitize_text_field( $form_title ),
							'form_desc'  => '',
						),
					);

					// Merge args and create the form.
					$form = array(
						'import_id'    => (int) $form_data['id'],
						'post_title'   => esc_html( $form_title ),
						'post_status'  => 'publish',
						'post_type'    => 'wpforms',
						'post_content' => wpforms_encode( $form_content ),
					);

					$form_id = wp_insert_post( $form );
				} else {
					// Create initial form to get the form ID.
					$form_id = wpforms()->form->add( $form_title );
				}

				if ( empty( $form_id ) ) {
					continue;
				}

				$form_data['id'] = $form_id;
				// Save the form data to the new form.
				wpforms()->form->update( $form_id, $form_data );
			}

			update_option( 'landkit_wpforms_imported', true );
		}

		/**
		 * Import Files from each demo.
		 */
		public function import_files() {
			$ocdi_host   = 'https://transvelo.github.io/landkit';
			$dd_url      = get_template_directory_uri() . '/assets/demo/';
			$preview_url = $ocdi_host . '/assets/img/screenshots/';
			/* translators: %1$s - The demo name. %2$s - Minutes. */
			$notice  = esc_html__( 'This demo will import %1$s. It may take %2$s', 'landkit' );
			$notice .= '<br><br>' . esc_html__( 'Menus, Widgets and Settings will not be imported. Content imported already will not be imported.', 'landkit' );
			$notice .= '<br><br>' . esc_html__( 'Alternatively, you can import this template directly into your page via Edit with Elementor.', 'landkit' );

			return apply_filters(
				'landkit_ocdi_files_args',
				array(
					array(
						'import_file_name'         => 'Basic',
						'categories'               => array( 'Landings - Web' ),
						'import_file_url'          => $dd_url . 'xml/basic.xml',
						'import_preview_image_url' => $preview_url . 'homepage.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '1 page & 12 images', 'landkit' ) . '</strong>', esc_html__( 'upto a minute', 'landkit' ) ),
						'preview_url'              => 'https://landkit.madrasthemes.com/demo/01-basic/',
						'plugin_profile'           => 'default',
						'uploads_dir'              => 'https://landkit.madrasthemes.com/demo/wp-content/uploads',
					),
					array(
						'import_file_name'         => 'Startup',
						'categories'               => array( 'Landings - Web' ),
						'import_file_url'          => $dd_url . 'xml/startup.xml',
						'import_preview_image_url' => $preview_url . 'startup.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '1 page & 20 images', 'landkit' ) . '</strong>', esc_html__( 'upto a minute', 'landkit' ) ),
						'preview_url'              => 'https://landkit.madrasthemes.com/demo/02-startup/',
						'plugin_profile'           => 'default',
					),
					array(
						'import_file_name'         => 'Enterprise',
						'categories'               => array( 'Landings - Web' ),
						'import_file_url'          => $dd_url . 'xml/enterprise.xml',
						'import_preview_image_url' => $preview_url . 'enterprise.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '1 page & 19 images', 'landkit' ) . '</strong>', esc_html__( 'upto a minute', 'landkit' ) ),
						'preview_url'              => 'https://landkit.madrasthemes.com/demo/03-enterprise/',
						'plugin_profile'           => 'default',
					),
					array(
						'import_file_name'         => 'Service',
						'categories'               => array( 'Landings - Web' ),
						'import_file_url'          => $dd_url . 'xml/service.xml',
						'import_preview_image_url' => $preview_url . 'service.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '1 page & 14 images', 'landkit' ) . '</strong>', esc_html__( 'upto a minute', 'landkit' ) ),
						'preview_url'              => 'https://landkit.madrasthemes.com/demo/04-service/',
						'plugin_profile'           => 'default',
					),
					array(
						'import_file_name'         => 'Cloud Hosting',
						'categories'               => array( 'Landings - Web' ),
						'import_file_url'          => $dd_url . 'xml/cloud.xml',
						'import_preview_image_url' => $preview_url . 'cloud.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '1 page & 5 images', 'landkit' ) . '</strong>', esc_html__( 'upto a minute', 'landkit' ) ),
						'preview_url'              => 'https://landkit.madrasthemes.com/demo/cloud-hosting/',
						'plugin_profile'           => 'default',
					),
					array(
						'import_file_name'         => 'Agency',
						'categories'               => array( 'Landings - Web' ),
						'import_file_url'          => $dd_url . 'xml/agency.xml',
						'import_preview_image_url' => $preview_url . 'agency.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '1 page & 17 images', 'landkit' ) . '</strong>', esc_html__( 'upto a minute', 'landkit' ) ),
						'preview_url'              => 'https://landkit.madrasthemes.com/demo/agency/',
						'plugin_profile'           => 'default',
					),
					/* Landings - Services - 7 */
					array(
						'import_file_name'         => 'Coworking',
						'categories'               => array( 'Landings - Services' ),
						'import_file_url'          => $dd_url . 'xml/coworking.xml',
						'import_preview_image_url' => $preview_url . 'coworking.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '1 page & 6 images', 'landkit' ) . '</strong>', esc_html__( 'upto a minute', 'landkit' ) ),
						'preview_url'              => 'https://landkit.madrasthemes.com/demo/coworking',
						'plugin_profile'           => 'default',
					),
					array(
						'import_file_name'         => 'Rental',
						'categories'               => array( 'Landings - Services' ),
						'import_file_url'          => $dd_url . 'xml/rental.xml',
						'import_preview_image_url' => $preview_url . 'rental.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '1 page, 3 blog posts & 10 images', 'landkit' ) . '</strong>', esc_html__( 'upto a minute', 'landkit' ) ),
						'preview_url'              => 'https://landkit.madrasthemes.com/demo/',
						'plugin_profile'           => 'default',
					),
					array(
						'import_file_name'         => 'Job Listing',
						'categories'               => array( 'Landings - Services' ),
						'import_file_url'          => $dd_url . 'xml/job.xml',
						'import_widget_file_url'   => $dd_url . 'widgets/job.wie',
						'import_preview_image_url' => $preview_url . 'jobs.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '1 page, 12 job listings & 15 images', 'landkit' ) . '</strong>', esc_html__( 'upto a minute', 'landkit' ) ),
						'preview_url'              => 'https://landkit.madrasthemes.com/demo/',
						'plugin_profile'           => 'jobs',
					),
					/* Landings - Apps - 10 */
					array(
						'import_file_name'         => 'Desktop App',
						'categories'               => array( 'Landings - App' ),
						'import_file_url'          => $dd_url . 'xml/desktop-app.xml',
						'import_preview_image_url' => $preview_url . 'desktop.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '1 page & 14 images', 'landkit' ) . '</strong>', esc_html__( 'upto a minute', 'landkit' ) ),
						'preview_url'              => 'https://landkit.madrasthemes.com/demo/',
						'plugin_profile'           => 'default',
					),
					array(
						'import_file_name'         => 'Mobile App',
						'categories'               => array( 'Landings - App' ),
						'import_file_url'          => $dd_url . 'xml/mobile-app.xml',
						'import_preview_image_url' => $preview_url . 'mobile.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '1 page & 10 images', 'landkit' ) . '</strong>', esc_html__( 'upto a minute', 'landkit' ) ),
						'preview_url'              => 'https://landkit.madrasthemes.com/demo/',
						'plugin_profile'           => 'default',
					),
					/** Secondary - 12 */
					array(
						'import_file_name'         => 'Career',
						'categories'               => array( 'Secondary' ),
						'import_file_url'          => $dd_url . 'xml/career.xml',
						'import_preview_image_url' => $preview_url . 'career.jpg',
						'import_widget_file_url'   => $dd_url . 'widgets/career.wie',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '1 page, 21 job listings & 6 images', 'landkit' ) . '</strong>', esc_html__( 'upto a minute', 'landkit' ) ),
						'preview_url'              => 'https://landkit.madrasthemes.com/demo/',
						'plugin_profile'           => 'jobs',
					),
					array(
						'import_file_name'         => 'Company Pages',
						'categories'               => array( 'Secondary' ),
						'import_file_url'          => $dd_url . 'xml/company.xml',
						'import_preview_image_url' => $preview_url . 'about.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '3 pages ( About, Pricing &amp; Terms ) & 21 images', 'landkit' ) . '</strong>', esc_html__( 'upto a minute', 'landkit' ) ),
						'preview_url'              => 'https://landkit.madrasthemes.com/demo/',
						'plugin_profile'           => 'default',
					),
					array(
						'import_file_name'         => 'Contact Pages',
						'categories'               => array( 'Secondary' ),
						'import_file_url'          => $dd_url . 'xml/contact.xml',
						'import_preview_image_url' => $preview_url . 'contact.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '2 pages & 3 images', 'landkit' ) . '</strong>', esc_html__( 'upto a minute', 'landkit' ) ),
						'preview_url'              => 'https://landkit.madrasthemes.com/demo/',
						'plugin_profile'           => 'default',
					),
					array(
						'import_file_name'         => 'Account Pages',
						'categories'               => array( 'Secondary' ),
						'import_file_url'          => $dd_url . 'xml/account.xml',
						'import_preview_image_url' => $preview_url . 'signin-cover.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '16 pages, 1 Static Content & 8 images', 'landkit' ) . '</strong>', esc_html__( 'upto a minute', 'landkit' ) ),
						'preview_url'              => 'https://landkit.madrasthemes.com/demo/',
						'plugin_profile'           => 'default',
					),
					array(
						'import_file_name'         => 'Help Center',
						'categories'               => array( 'Secondary', 'Demo' ),
						'import_file_url'          => $dd_url . 'xml/helpcenter.xml',
						'import_preview_image_url' => $preview_url . 'help-center.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '66 articles, 1 page, 1 static content & 1 image', 'landkit' ) . '</strong>', esc_html__( 'upto a minute', 'landkit' ) ),
						'preview_url'              => 'https://landkit.madrasthemes.com/demo/',
						'plugin_profile'           => 'docs',
					),
					array(
						'import_file_name'         => 'Blog',
						'categories'               => array( 'Secondary', 'Demo' ),
						'import_file_url'          => $dd_url . 'xml/blog.xml',
						'import_preview_image_url' => $preview_url . 'blog-search.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '25 blog posts, 2 pages, & 17 images', 'landkit' ) . '</strong>', esc_html__( '2-3 minutes', 'landkit' ) ),
						'preview_url'              => 'https://landkit.madrasthemes.com/demo/',
						'plugin_profile'           => 'default',
					),
					array(
						'import_file_name'         => 'Portfolio',
						'categories'               => array( 'Secondary', 'Demo' ),
						'import_file_url'          => $dd_url . 'xml/portfolio.xml',
						'import_preview_image_url' => $preview_url . 'portfolio-basic-grid.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '27 portfolio projects, 3 pages, & 42 images', 'landkit' ) . '</strong>', esc_html__( '2-3 minutes', 'landkit' ) ),
						'preview_url'              => 'https://landkit.madrasthemes.com/demo/',
						'plugin_profile'           => 'default',
					),
					array(
						'import_file_name'           => 'Full Demo',
						'categories'                 => array( 'Full Demo' ),
						'import_file_url'            => $dd_url . 'xml/full.xml',
						'import_widget_file_url'     => $dd_url . 'widgets/full.wie',
						'import_preview_image_url'   => $preview_url . 'homepage.jpg',
						'import_notice'              => esc_html__( 'It imports the entire demo. It may take upto 5 minutes', 'landkit' ),
						'preview_url'                => 'https://landkit.madrasthemes.com/demo/',
						'plugin_profile'             => 'all',
						'import_customizer_file_url' => $dd_url . 'customizer/full.dat',
						'set_nav_menus'              => true,
						'set_pages'                  => true,
						'front_page_title'           => 'Basic',
					),
					array(
						'import_file_name' => 'Menus',
						'categories'       => array( 'Misc' ),
						'import_file_url'  => $dd_url . 'xml/menus.xml',
						'import_notice'    => sprintf( $notice, '<strong>' . esc_html__( '3 Megamenus, 2 Menus & 5 images', 'landkit' ) . '</strong>', esc_html__( 'upto a minute', 'landkit' ) ),
						'preview_url'      => 'https://landkit.madrasthemes.com/demo/',
						'plugin_profile'   => 'default',
					),
				)
			);
		}
	}

endif;

return new Landkit_OCDI();
