<?php
/**
 * Landkit WeDocs Customizer Class
 *
 * @package  landkit
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Landkit_WeDocs_Customizer' ) ) :
	/**
	 * The Landkit WeDocs Customizer class
	 */
	class Landkit_WeDocs_Customizer extends Landkit_Customizer {

		/**
		 * Setup class.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __construct() {
			add_action( 'customize_register', array( $this, 'customize_helpcenter' ), 10 );
			add_action( 'customize_controls_print_scripts', array( $this, 'add_scripts' ), 30 );
		}


		/**
		 * Add postMessage support for site title and description for the Theme Customizer along with several other settings.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 * @since 2.4.0
		 */
		public function customize_helpcenter( $wp_customize ) {
			/**
			 * Helpcenter
			 */
			$wp_customize->add_section(
				'landkit_helpcenter',
				[
					'priority'    => 60,
					'title'       => esc_html__( 'Helpcenter', 'landkit' ),
					'description' => esc_html__( 'This section contains settings related to single article', 'landkit' ),
				]
			);

			$this->add_helpcenter_section( $wp_customize );
		}

		/**
		 * Scripts to improve our form.
		 */
		public function add_scripts() {
			$docs_home = wedocs_get_option( 'docs_home', 'wedocs_settings' ); ?>
			<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				wp.customize.section( 'landkit_helpcenter', function( section ) {
					section.expanded.bind( function( isExpanded ) {
						if ( isExpanded ) {
							wp.customize.previewer.previewUrl.set( '<?php echo esc_js( get_permalink( $docs_home ) ); ?>' );
						}
					} );
				} );
			});
			</script>
			<?php
		}

		/**
		 * Helpcenter Section
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		private function add_helpcenter_section( $wp_customize ) {
			$this->static_contents = static_content_options();

			$wp_customize->add_setting(
				'enable_docs_custom_header',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_docs_custom_header',
				[
					'type'        => 'radio',
					'section'     => 'landkit_helpcenter',
					'label'       => esc_html__( 'Enable Custom Header', 'landkit' ),
					'description' => esc_html__( 'This setting allows you to show or hide custom header for Single Article', 'landkit' ),
					'choices'     => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_docs_custom_header',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'docs_header_skin',
				[
					'default'           => 'light',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'docs_header_skin',
				[
					'type'            => 'select',
					'section'         => 'landkit_helpcenter',
					'label'           => esc_html__( 'Skin', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to choose your header skin.', 'landkit' ),
					'choices'         => [
						'light' => esc_html__( 'Light', 'landkit' ),
						'dark'  => esc_html__( 'Dark', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_docs_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'docs_header_skin',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_docs_border_bottom',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_docs_border_bottom',
				[
					'type'            => 'radio',
					'section'         => 'landkit_helpcenter',
					'label'           => esc_html__( 'Enable Border Bottom', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to show or hide border bottom', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_docs_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_docs_border_bottom',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_docs_navbar_togglable',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_docs_navbar_togglable',
				[
					'type'            => 'radio',
					'section'         => 'landkit_helpcenter',
					'label'           => esc_html__( 'Enable Navbar Togglable', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to enable or disable navbar toggle option', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return in_array(
							get_theme_mod( 'docs_header_skin' ),
							[ 'dark' ],
							true
						) && filter_var( get_theme_mod( 'enable_docs_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_docs_navbar_togglable',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_docs_fixed_top',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_docs_fixed_top',
				[
					'type'            => 'radio',
					'section'         => 'landkit_helpcenter',
					'label'           => esc_html__( 'Enable Fixed Top', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to enable or disable fixed top option', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_docs_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_docs_fixed_top',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'docs_header_is_full_width',
				[
					'default'           => 'yes',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'docs_header_is_full_width',
				[
					'type'            => 'radio',
					'section'         => 'landkit_helpcenter',
					'label'           => esc_html__( 'Make header full-width?', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to control header width. Default is boxed.', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_docs_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'docs_header_is_full_width',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'docs_container_layout',
				[
					'default'           => 'container',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'docs_container_layout',
				[
					'type'            => 'select',
					'section'         => 'landkit_helpcenter',
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
						return ( 'no' === get_theme_mod( 'docs_header_is_full_width', 'no' ) ) && filter_var( get_theme_mod( 'enable_docs_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'docs_container_layout',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_docs_action_button',
				[
					'default'           => 'yes',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_docs_action_button',
				[
					'type'            => 'radio',
					'section'         => 'landkit_helpcenter',
					'label'           => esc_html__( 'Enable Button', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to show or hide Buy Now button', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_docs_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_docs_action_button',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_docs_custom_footer',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_docs_custom_footer',
				[
					'type'        => 'radio',
					'section'     => 'landkit_helpcenter',
					'label'       => esc_html__( 'Enable Custom Footer', 'landkit' ),
					'description' => esc_html__( 'This setting allows you to show or hide custom footer for Single Article', 'landkit' ),
					'choices'     => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_docs_custom_footer',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'landkit_docs_footer_background',
				[
					'default'           => 'bg-gray-200',
					'sanitize_callback' => 'sanitize_key',
				]
			);
			$wp_customize->add_control(
				'landkit_docs_footer_background',
				[
					'type'            => 'select',
					'section'         => 'landkit_helpcenter',
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
						return filter_var( get_theme_mod( 'enable_docs_custom_footer' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);
			$wp_customize->selective_refresh->add_partial(
				'landkit_docs_footer_background',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_docs_footer_border_top',
				[
					'default'           => 'yes',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_docs_footer_border_top',
				[
					'type'            => 'radio',
					'section'         => 'landkit_helpcenter',
					'label'           => esc_html__( 'Enable Border Top', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to show or hide border top in footer', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],

					'active_callback' => function () {
						return in_array(
							get_theme_mod( 'landkit_docs_footer_background' ),
							[ 'bg-dark' ],
							true
						) && filter_var( get_theme_mod( 'enable_docs_custom_footer' ), FILTER_VALIDATE_BOOLEAN );
					},

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_docs_footer_border_top',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'article_before_footer_content',
				array(
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'absint',
				)
			);

			$wp_customize->add_control(
				'article_before_footer_content',
				array(
					'section'     => 'landkit_helpcenter',
					'label'       => esc_html__( 'Footer Before Content', 'landkit' ),
					'description' => esc_html__( 'Choose a static content that will be displayed before the footer in single article page.', 'landkit' ),
					'type'        => 'select',
					'choices'     => $this->static_contents,
				)
			);

			$wp_customize->add_setting(
				'enable_related_articles',
				[
					'default'           => 'yes',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_related_articles',
				[
					'type'        => 'radio',
					'section'     => 'landkit_helpcenter',
					'label'       => esc_html__( 'Enable Related Articles', 'landkit' ),
					'description' => esc_html__( 'This setting allows you to show or hide related articles', 'landkit' ),
					'choices'     => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_related_articles',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'related_article_title',
				[
					'default'           => 'Related Help Center Categories',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				]
			);
			$wp_customize->add_control(
				'related_article_title',
				[
					'section'         => 'landkit_helpcenter',
					'type'            => 'text',
					'label'           => esc_html__( 'Related Articles Title', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to set related article title', 'landkit' ),
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_related_articles' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);
			$wp_customize->selective_refresh->add_partial(
				'related_article_title',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'related_article_description',
				[
					'default'           => 'If you didn’t find what you needed, these could help!',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				]
			);
			$wp_customize->add_control(
				'related_article_description',
				[
					'section'         => 'landkit_helpcenter',
					'type'            => 'textarea',
					'label'           => esc_html__( 'Related Articles Description', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to set related article description', 'landkit' ),
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_related_articles' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);
			$wp_customize->selective_refresh->add_partial(
				'related_article_description',
				[
					'fallback_refresh' => true,
				]
			);

		}

	}

endif;

return new Landkit_WeDocs_Customizer();
