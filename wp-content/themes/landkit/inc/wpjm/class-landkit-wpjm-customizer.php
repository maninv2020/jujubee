<?php
/**
 * Landkit Job Manager Customizer Class
 *
 * @package  landkit
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Landkit_WPJM_Customizer' ) ) :

	/**
	 * The Landkit Customizer class
	 */
	class Landkit_WPJM_Customizer extends Landkit_Customizer {
		/**
		 * Setup class.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __construct() {
			add_action( 'customize_register', array( $this, 'customize_job' ), 10 );
			add_action( 'customize_controls_print_scripts', array( $this, 'add_scripts' ), 30 );
		}


		/**
		 * Add postMessage support for site title and description for the Theme Customizer along with several other settings.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 * @since 2.4.0
		 */
		public function customize_job( $wp_customize ) {
			$wp_customize->add_section(
				'landkit_job',
				[
					'priority' => 50,
					'title'    => esc_html__( 'Jobs', 'landkit' ),
				]
			);

			$this->add_job_section( $wp_customize );
		}

		/**
		 * Scripts to improve our form.
		 */
		public function add_scripts() {
			$jobs_page_id = get_option( 'job_manager_jobs_page_id' );
			?>
			<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				wp.customize.section( 'landkit_job', function( section ) {
					section.expanded.bind( function( isExpanded ) {
						if ( isExpanded ) {
							wp.customize.previewer.previewUrl.set( '<?php echo esc_js( get_permalink( $jobs_page_id ) ); ?>' );
						}
					} );
				} );
			});
			</script>
			<?php
		}

		/**
		 * Job Section
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		private function add_job_section( $wp_customize ) {
			$this->static_contents = static_content_options();

			$wp_customize->add_setting(
				'enable_application_form',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_application_form',
				[
					'type'        => 'radio',
					'section'     => 'landkit_job',
					'label'       => esc_html__( 'Do you want to use a custom application form for the jobs?', 'landkit' ),
					'description' => esc_html__( 'This setting allows you to show or hide application form.', 'landkit' ),
					'choices'     => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_application_form',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'landkit_application_form',
				[
					'transport'         => 'postMessage',
					'sanitize_callback' => 'sanitize_textarea_field',
				]
			);

			$wp_customize->add_control(
				'landkit_application_form',
				[
					'type'            => 'textarea',
					'section'         => 'landkit_job',
					'label'           => esc_html__( 'Application Form', 'landkit' ),
					'description'     => esc_html__( 'Paste your application form HTML or shortcode here', 'landkit' ),
					'active_callback' => function () {
						return get_theme_mod( 'enable_application_form', 'yes' ) === 'yes';
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'landkit_application_form',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_single_job_custom_header',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_single_job_custom_header',
				[
					'type'        => 'radio',
					'section'     => 'landkit_job',
					'label'       => esc_html__( 'Enable Custom Header for Single Job', 'landkit' ),
					'description' => esc_html__( 'This setting allows you to show or hide custom header for Single Job', 'landkit' ),
					'choices'     => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_single_job_custom_header',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'single_job_header_skin',
				[
					'default'           => 'light',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'single_job_header_skin',
				[
					'type'            => 'select',
					'section'         => 'landkit_job',
					'label'           => esc_html__( 'Skin', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to choose your header skin.', 'landkit' ),
					'choices'         => [
						'light' => esc_html__( 'Light', 'landkit' ),
						'dark'  => esc_html__( 'Dark', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_single_job_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'single_job_header_skin',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_single_job_border_bottom',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_single_job_border_bottom',
				[
					'type'            => 'radio',
					'section'         => 'landkit_job',
					'label'           => esc_html__( 'Enable Border Bottom', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to show or hide border bottom', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_single_job_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_single_job_border_bottom',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_single_job_navbar_togglable',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_single_job_navbar_togglable',
				[
					'type'            => 'radio',
					'section'         => 'landkit_job',
					'label'           => esc_html__( 'Enable Navbar Togglable', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to enable or disable navbar toggle option', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],

					'active_callback' => function () {
						return in_array(
							get_theme_mod( 'single_job_header_skin' ),
							[ 'dark' ],
							true
						) && filter_var( get_theme_mod( 'enable_single_job_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_single_job_navbar_togglable',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_single_job_fixed_top',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_single_job_fixed_top',
				[
					'type'            => 'radio',
					'section'         => 'landkit_job',
					'label'           => esc_html__( 'Enable Fixed Top', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to enable or disable fixed top option', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_single_job_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_single_job_fixed_top',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'single_job_header_is_full_width',
				[
					'default'           => 'yes',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'single_job_header_is_full_width',
				[
					'type'            => 'radio',
					'section'         => 'landkit_job',
					'label'           => esc_html__( 'Make header full-width?', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to control header width. Default is boxed.', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_single_job_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'single_job_header_is_full_width',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'single_job_container_layout',
				[
					'default'           => 'container',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'single_job_container_layout',
				[
					'type'            => 'select',
					'section'         => 'landkit_job',
					'label'           => esc_html__( 'Container', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to choose your container layout', 'landkit' ),
					'choices'         => [
						'container'    => esc_html__( 'Default', 'landkit' ),
						'container-sm' => esc_html__( 'Container Small', 'landkit' ),
						'container-md' => esc_html__( 'Container medium ', 'landkit' ),
						'container-lg' => esc_html__( 'Container Large ', 'landkit' ),
						'container-xl' => esc_html__( 'Container Extra Large', 'landkit' ),
					],

					'active_callback' => function () {
						return ( 'no' === get_theme_mod( 'single_job_header_is_full_width', 'no' ) ) && filter_var( get_theme_mod( 'enable_single_job_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'single_job_container_layout',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_single_job_action_button',
				[
					'default'           => 'yes',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_single_job_action_button',
				[
					'type'            => 'radio',
					'section'         => 'landkit_job',
					'label'           => esc_html__( 'Enable Button', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to show or hide Buy Now button', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_single_job_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_single_job_action_button',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_single_job_custom_footer',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_single_job_custom_footer',
				[
					'type'        => 'radio',
					'section'     => 'landkit_job',
					'label'       => esc_html__( 'Enable Custom Footer for Single Job', 'landkit' ),
					'description' => esc_html__( 'This setting allows you to show or hide custom footer for Single Job', 'landkit' ),
					'choices'     => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_single_job_custom_footer',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'landkit_single_job_footer_background',
				[
					'default'           => 'bg-gray-200',
					'sanitize_callback' => 'sanitize_key',
				]
			);
			$wp_customize->add_control(
				'landkit_single_job_footer_background',
				[
					'type'            => 'select',
					'section'         => 'landkit_job',
					'label'           => esc_html__( 'Footer Background', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to choose your footer background.', 'landkit' ),
					'choices'         => [
						'bg-gray-200' => esc_html__( 'Gray 200', 'landkit' ),
						'bg-white'    => esc_html__( 'White', 'landkit' ),
						'bg-dark'     => esc_html__( 'Dark', 'landkit' ),
						'bg-black'    => esc_html__( 'Black', 'landkit' ),
						'bg-light'    => esc_html__( 'Light', 'landkit' ),

					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_single_job_custom_footer' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);
			$wp_customize->selective_refresh->add_partial(
				'landkit_single_job_footer_background',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_single_job_footer_border_top',
				[
					'default'           => 'yes',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_single_job_footer_border_top',
				[
					'type'            => 'radio',
					'section'         => 'landkit_job',
					'label'           => esc_html__( 'Enable Border Top', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to show or hide border top in footer', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],

					'active_callback' => function () {
						return in_array(
							get_theme_mod( 'landkit_single_job_footer_background' ),
							[ 'bg-dark' ],
							true
						) && filter_var( get_theme_mod( 'enable_single_job_custom_footer' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_single_job_footer_border_top',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'single_job_before_footer_content',
				array(
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'absint',
				)
			);

			$wp_customize->add_control(
				'single_job_before_footer_content',
				array(
					'section'     => 'landkit_job',
					'label'       => esc_html__( 'Single Job Footer Before Content', 'landkit' ),
					'description' => esc_html__( 'Choose a static block that will be displayed before the footer in single job.', 'landkit' ),
					'type'        => 'select',
					'choices'     => $this->static_contents,
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'single_job_before_footer_content',
				[
					'fallback_refresh' => true,
				]
			);
		}

	}

endif;

return new Landkit_WPJM_Customizer();
