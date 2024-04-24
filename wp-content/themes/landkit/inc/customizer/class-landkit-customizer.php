<?php
/**
 * Landkit Customizer Class
 *
 * @package  landkit
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Landkit_Customizer' ) ) :

	/**
	 * The Landkit Customizer class
	 */
	class Landkit_Customizer {
		/**
		 * Static content
		 *
		 * @var array
		 */
		public $static_contents;

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			$this->includes();
			add_action( 'customize_register', array( $this, 'customize_general' ), 10 );
			add_action( 'customize_register', array( $this, 'customize_header' ), 10 );
			add_action( 'customize_register', array( $this, 'customize_footer' ), 10 );
			add_action( 'customize_register', array( $this, 'customize_blog' ), 10 );
			add_action( 'customize_register', array( $this, 'customize_portfolio' ), 10 );
			add_action( 'customize_register', array( $this, 'customize_404' ), 10 );
			add_action( 'wp_enqueue_scripts', array( $this, 'add_customizer_css' ), 130 );
			add_action( 'init', array( $this, 'default_theme_mod_values' ), 10 );
			add_action( 'customize_controls_print_scripts', array( $this, 'add_scripts' ), 30 );
			add_action( 'customize_register', array( $this, 'customize_customcolor' ), 10 );

		}

		/**
		 * Scripts to improve our form.
		 */
		public function add_scripts() {
			$args  = array(
				'fields'         => 'ids',
				'post_type'      => 'post',
				'posts_per_page' => 1,
			);
			$posts = get_posts( $args );

			$args     = array(
				'fields'         => 'ids',
				'post_type'      => 'jetpack-portfolio',
				'posts_per_page' => 1,
			);
			$projects = get_posts( $args );

			if ( isset( $posts[0] ) ) {
				$post_link = get_permalink( $posts[0] );
			} else {
				$post_link = get_permalink( get_option( 'page_for_posts' ) );
			}

			if ( isset( $projects[0] ) ) {
				$portfolio_link = get_permalink( $projects[0] );
			} else {
				$portfolio_link = get_post_type_archive_link( 'jetpack-portfolio' );
			}

			?>
			<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				wp.customize.section( 'landkit_blog', function( section ) {
					section.expanded.bind( function( isExpanded ) {
						if ( isExpanded ) {
							wp.customize.previewer.previewUrl.set( '<?php echo esc_js( $post_link ); ?>' );
						}
					} );
				} );

				wp.customize.section( 'landkit_portfolio', function( section ) {
					section.expanded.bind( function( isExpanded ) {
						if ( isExpanded ) {
							wp.customize.previewer.previewUrl.set( '<?php echo esc_js( $portfolio_link ); ?>' );
						}
					} );
				} );
			});
			</script>
			<?php
		}

		/**
		 * Customize site header
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_general( $wp_customize ) {

			$wp_customize->add_section(
				'landkit_general',
				[
					'title'       => esc_html__( 'General', 'landkit' ),
					'description' => esc_html__( 'This section contains general settings', 'landkit' ),
					'priority'    => 20,
				]
			);

			$this->add_general_section( $wp_customize );
		}
		/**
		 * Customize General Section
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		private function add_general_section( $wp_customize ) {
			$this->static_contents = static_content_options();

			$wp_customize->add_setting(
				'before_site_control',
				array(
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'absint',
				)
			);

			$wp_customize->add_control(
				'before_site_control',
				array(
					'section'     => 'landkit_general',
					'label'       => esc_html__( 'Before Site Content', 'landkit' ),
					'description' => esc_html__( 'Choose a static content that will be displayed before the site. This is useful if you\'d like to load modals', 'landkit' ),
					'type'        => 'select',
					'choices'     => $this->static_contents,
				)
			);

		}


		/**
		 * Customize site header
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_header( $wp_customize ) {
			$wp_customize->add_section(
				'landkit_header',
				[
					'title'       => esc_html__( 'Header', 'landkit' ),
					'description' => esc_html__( 'This section contains settings related to header.', 'landkit' ),
					'priority'    => 30,
				]
			);

			$this->add_header_section( $wp_customize );
		}
		/**
		 * Customize Header Section
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		private function add_header_section( $wp_customize ) {

			$wp_customize->add_setting(
				'header_skin',
				[
					'default'           => 'light',
					'sanitize_callback' => 'sanitize_key',
				]
			);
			$wp_customize->add_control(
				'header_skin',
				[
					'type'        => 'select',
					'section'     => 'landkit_header',
					'label'       => esc_html__( 'Skin', 'landkit' ),
					'description' => esc_html__( 'This setting allows you to choose your header skin.', 'landkit' ),
					'choices'     => [
						'light' => esc_html__( 'Light', 'landkit' ),
						'dark'  => esc_html__( 'Dark', 'landkit' ),
					],
				]
			);
			$wp_customize->selective_refresh->add_partial(
				'header_skin',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_border_bottom',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_border_bottom',
				[
					'type'            => 'radio',
					'section'         => 'landkit_header',
					'label'           => esc_html__( 'Enable Border Bottom', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to show or hide border bottom.', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return in_array(
							get_theme_mod( 'header_skin' ),
							[ 'light' ],
							true
						);
					},

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_border_bottom',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_navbar_togglable',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_navbar_togglable',
				[
					'type'            => 'radio',
					'section'         => 'landkit_header',
					'label'           => esc_html__( 'Enable Navbar Togglable', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to enable or disable navbar toggle option', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return in_array(
							get_theme_mod( 'header_skin' ),
							[ 'dark' ],
							true
						);
					},

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_navbar_togglable',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_fixed_top',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_fixed_top',
				[
					'type'        => 'radio',
					'section'     => 'landkit_header',
					'label'       => esc_html__( 'Enable Fixed Top', 'landkit' ),
					'description' => esc_html__( 'This setting allows you to enable or disable fixed top option', 'landkit' ),
					'choices'     => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_fixed_top',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'header_is_full_width',
				[
					'default'           => 'yes',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'header_is_full_width',
				[
					'type'        => 'radio',
					'section'     => 'landkit_header',
					'label'       => esc_html__( 'Make header full-width?', 'landkit' ),
					'description' => esc_html__( 'This setting allows you to control header width. Default is boxed.', 'landkit' ),
					'choices'     => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'header_is_full_width',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'container_layout',
				[
					'default'           => 'container',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'container_layout',
				[
					'type'            => 'select',
					'section'         => 'landkit_header',
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
						return ( 'no' === get_theme_mod( 'header_is_full_width', 'no' ) );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'container_layout',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_action_button',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_action_button',
				[
					'type'        => 'radio',
					'section'     => 'landkit_header',
					'label'       => esc_html__( 'Enable Buy Now Button', 'landkit' ),
					'description' => esc_html__( 'This setting allows you to show or hide Buy Now button', 'landkit' ),
					'choices'     => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_action_button',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'button_url',
				[
					'default'           => '#',
					'sanitize_callback' => 'esc_url_raw',
				]
			);

			$wp_customize->add_control(
				'button_url',
				[
					'type'            => 'url',
					'section'         => 'landkit_header',
					'label'           => esc_html__( 'Button Link', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to change the button link', 'landkit' ),
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_action_button' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'button_url',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'button_text',
				[
					'default'           => esc_html__( 'Buy now', 'landkit' ),
					'sanitize_callback' => 'wp_kses_post',
					'transport'         => 'postMessage',
				]
			);

			$wp_customize->add_control(
				'button_text',
				[
					'type'            => 'text',
					'section'         => 'landkit_header',
					'label'           => esc_html__( 'Button Text', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to change the button text', 'landkit' ),
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_action_button' ), FILTER_VALIDATE_BOOLEAN );
					},

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'button_text',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'button_size',
				[
					'default'           => 'btn-sm',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'button_size',
				[
					'type'            => 'select',
					'section'         => 'landkit_header',
					'label'           => esc_html__( 'Button Size', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to choose your button size', 'landkit' ),
					'choices'         => [
						''       => esc_html__( 'Default', 'landkit' ),
						'btn-xs' => esc_html__( 'Extra Small', 'landkit' ),
						'btn-sm' => esc_html__( 'Small', 'landkit' ),
						'btn-lg' => esc_html__( 'Large ', 'landkit' ),

					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_action_button' ), FILTER_VALIDATE_BOOLEAN );
					},

				]
			);
			$wp_customize->selective_refresh->add_partial(
				'button_size',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'button_css',
				[
					'default'           => '',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'button_css',
				[
					'type'            => 'text',
					'section'         => 'landkit_header',
					'label'           => esc_html__( 'Button CSS Class', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to add  button css', 'landkit' ),
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_action_button' ), FILTER_VALIDATE_BOOLEAN );
					},

				]
			);
			$wp_customize->selective_refresh->add_partial(
				'button_css',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'button_color',
				[
					'default'           => 'primary',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'button_color',
				[
					'type'            => 'select',
					'section'         => 'landkit_header',
					'label'           => esc_html__( 'Button Primary Color', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to choose your button color', 'landkit' ),
					'choices'         => [
						'primary' => esc_html__( 'Default', 'landkit' ),
						'custom'  => esc_html__( 'Custom', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_action_button' ), FILTER_VALIDATE_BOOLEAN );
					},

				]
			);
			$wp_customize->selective_refresh->add_partial(
				'button_color',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'landkit_header_button_background',
				array(
					'default'           => apply_filters( 'landkit_default_button_color', '#335EEA' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'landkit_header_button_background',
					array(
						'label'           => __( 'Button Background', 'landkit' ),
						'section'         => 'landkit_header',
						'settings'        => 'landkit_header_button_background',
						'active_callback' => function () {
							return filter_var( get_theme_mod( 'enable_action_button' ), FILTER_VALIDATE_BOOLEAN )
							&& get_theme_mod( 'button_color', 'custom' ) === 'custom';
						},
					)
				)
			);

		}
		/**
		 * Customize site footer
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_footer( $wp_customize ) {
			$wp_customize->add_section(
				'landkit_footer',
				[
					'title'       => esc_html__( 'Footer', 'landkit' ),
					'description' => esc_html__( 'This section contains settings related to footer.', 'landkit' ),
					'priority'    => 30,
				]
			);

			$this->add_footer_section( $wp_customize );
		}
		/**
		 * Customize Footer Section
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		private function add_footer_section( $wp_customize ) {

			$this->static_contents = static_content_options();

			$wp_customize->add_setting(
				'landkit_enable_footer',
				[
					'default'           => 'yes',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'landkit_enable_footer',
				[
					'type'        => 'radio',
					'section'     => 'landkit_footer',
					'label'       => esc_html__( 'Enable Footer', 'landkit' ),
					'description' => esc_html__( 'This setting allows you to show or hide footer', 'landkit' ),
					'choices'     => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'landkit_enable_footer',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'landkit_footer_background',
				[
					'default'           => 'bg-gray-200',
					'sanitize_callback' => 'sanitize_key',
				]
			);
			$wp_customize->add_control(
				'landkit_footer_background',
				[
					'type'            => 'select',
					'section'         => 'landkit_footer',
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
						return filter_var( get_theme_mod( 'landkit_enable_footer' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);
			$wp_customize->selective_refresh->add_partial(
				'landkit_footer_background',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_border_top',
				[
					'default'           => 'yes',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_border_top',
				[
					'type'            => 'radio',
					'section'         => 'landkit_footer',
					'label'           => esc_html__( 'Enable Border Top', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to show or hide border top in footer', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return in_array(
							get_theme_mod( 'landkit_footer_background' ),
							[ 'bg-dark' ],
							true
						);
					},

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_border_top',
				[
					'fallback_refresh' => true,
				]
			);
		}
		/**
		 * Customize blog
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_blog( $wp_customize ) {
			$wp_customize->add_section(
				'landkit_blog',
				[
					'title'       => esc_html__( 'Blog', 'landkit' ),
					'description' => esc_html__( 'This section contains settings related to posts listing archives and single post.', 'landkit' ),
					'priority'    => 30,
				]
			);

			$this->add_blog_section( $wp_customize );
		}

		/**
		 * Customizer Controls For Blog.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		private function add_blog_section( $wp_customize ) {
			$wp_customize->add_setting(
				'blog_title',
				[
					'default'           => '',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				]
			);
			$wp_customize->add_control(
				'blog_title',
				[
					'section'     => 'landkit_blog',
					'type'        => 'text',
					/* translators: label field of setting responsible for keeping the page title of blog in posts listing (no Static Front Page). */
					'label'       => esc_html__( 'Blog Title', 'landkit' ),
					'description' => esc_html__( 'This setting allows you to set blog title', 'landkit' ),
				]
			);
			$wp_customize->selective_refresh->add_partial(
				'blog_title',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'blog_subtitle',
				[
					'default'           => '',
					'sanitize_callback' => 'sanitize_textarea_field',
					'transport'         => 'postMessage',
				]
			);
			$wp_customize->add_control(
				'blog_subtitle',
				[
					'section'     => 'landkit_blog',
					'type'        => 'textarea',
					/* translators: label field of setting responsible for keeping the page title of blog in posts listing (no Static Front Page). */
					'label'       => esc_html__( 'Blog Subtitle', 'landkit' ),
					'description' => esc_html__( 'This setting allows you to set blog subtitle', 'landkit' ),
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'blog_subtitle',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'blog_cover_image',
				[
					'transport'         => 'postMessage',
					'sanitize_callback' => 'absint',
				]
			);

			$wp_customize->add_control(
				new WP_Customize_Media_Control(
					$wp_customize,
					'blog_cover_image',
					[
						'section'     => 'landkit_blog',
						/* translators: label field for setting in Customizer */
						'label'       => esc_html__( 'Blog Cover Image', 'landkit' ),
						/* translators: description field for "Payment Methods" setting in Customizer */
						'description' => esc_html__(
							'This setting allows you to upload an image. This image is optimized for retina displays, so the original image size should be twice as big as the final image that appears on the website.',
							'landkit'
						),
						'mime_type'   => 'image',

					]
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'blog_cover_image',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'blog_search_placeholder',
				[
					'default'           => esc_html__( 'Search our blog...', 'landkit' ),
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				]
			);
			$wp_customize->add_control(
				'blog_search_placeholder',
				[
					'section'     => 'landkit_blog',
					'type'        => 'text',
					/* translators: label field of setting responsible for keeping the page title of blog in posts listing (no Static Front Page). */
					'label'       => esc_html__( 'Blog Search Placeholder', 'landkit' ),
					'description' => esc_html__( 'This setting allows you to set blog search placeholder text', 'landkit' ),
				]
			);
			$wp_customize->selective_refresh->add_partial(
				'blog_search_placeholder',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'blog_before_footer_content',
				array(
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'absint',
				)
			);

			$wp_customize->add_control(
				'blog_before_footer_content',
				array(
					'section'     => 'landkit_blog',
					'label'       => esc_html__( 'Posts Footer Before Content', 'landkit' ),
					'description' => esc_html__( 'Choose a static block that will be displayed before the footer in Blog/Posts archive pages.', 'landkit' ),
					'type'        => 'select',
					'choices'     => $this->static_contents,
				)
			);

			$wp_customize->add_setting(
				'enable_single_post_custom_header',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_single_post_custom_header',
				[
					'type'        => 'radio',
					'section'     => 'landkit_blog',
					'label'       => esc_html__( 'Enable Custom Header for Single Post', 'landkit' ),
					'description' => esc_html__( 'This setting allows you to show or hide custom header for Single Post', 'landkit' ),
					'choices'     => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_single_post_custom_header',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'single_post_header_skin',
				[
					'default'           => 'light',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'single_post_header_skin',
				[
					'type'            => 'select',
					'section'         => 'landkit_blog',
					'label'           => esc_html__( 'Skin', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to choose your header skin.', 'landkit' ),
					'choices'         => [
						'light' => esc_html__( 'Light', 'landkit' ),
						'dark'  => esc_html__( 'Dark', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_single_post_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'single_post_header_skin',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_single_post_border_bottom',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_single_post_border_bottom',
				[
					'type'            => 'radio',
					'section'         => 'landkit_blog',
					'label'           => esc_html__( 'Enable Border Bottom', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to show or hide border bottom', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_single_post_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_single_post_border_bottom',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_single_post_navbar_togglable',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_single_post_navbar_togglable',
				[
					'type'            => 'radio',
					'section'         => 'landkit_blog',
					'label'           => esc_html__( 'Enable Navbar Togglable', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to enable or disable navbar toggle option', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return in_array(
							get_theme_mod( 'single_post_header_skin' ),
							[ 'dark' ],
							true
						) && filter_var( get_theme_mod( 'enable_single_post_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_single_post_navbar_togglable',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_single_post_fixed_top',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_single_post_fixed_top',
				[
					'type'            => 'radio',
					'section'         => 'landkit_blog',
					'label'           => esc_html__( 'Enable Fixed Top', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to enable or disable fixed top option', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_single_post_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_single_post_fixed_top',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'single_post_header_is_full_width',
				[
					'default'           => 'yes',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'single_post_header_is_full_width',
				[
					'type'            => 'radio',
					'section'         => 'landkit_blog',
					'label'           => esc_html__( 'Make header full-width?', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to control header width. Default is boxed.', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_single_post_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'single_post_header_is_full_width',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'single_post_container_layout',
				[
					'default'           => 'container',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'single_post_container_layout',
				[
					'type'            => 'select',
					'section'         => 'landkit_blog',
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
						return ( 'no' === get_theme_mod( 'single_post_header_is_full_width', 'no' ) ) && filter_var( get_theme_mod( 'enable_single_post_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'single_post_container_layout',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_single_post_action_button',
				[
					'default'           => 'yes',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_single_post_action_button',
				[
					'type'            => 'radio',
					'section'         => 'landkit_blog',
					'label'           => esc_html__( 'Enable Button', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to show or hide Buy Now button', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_single_post_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_single_post_action_button',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_single_post_custom_footer',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_single_post_custom_footer',
				[
					'type'        => 'radio',
					'section'     => 'landkit_blog',
					'label'       => esc_html__( 'Enable Custom Footer for Single Post', 'landkit' ),
					'description' => esc_html__( 'This setting allows you to show or hide custom footer for Single Post', 'landkit' ),
					'choices'     => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_single_post_custom_footer',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'landkit_single_post_footer_background',
				[
					'default'           => 'bg-gray-200',
					'sanitize_callback' => 'sanitize_key',
				]
			);
			$wp_customize->add_control(
				'landkit_single_post_footer_background',
				[
					'type'            => 'select',
					'section'         => 'landkit_blog',
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
						return filter_var( get_theme_mod( 'enable_single_post_custom_footer' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);
			$wp_customize->selective_refresh->add_partial(
				'landkit_single_post_footer_background',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_single_post_footer_border_top',
				[
					'default'           => 'yes',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_single_post_footer_border_top',
				[
					'type'            => 'radio',
					'section'         => 'landkit_blog',
					'label'           => esc_html__( 'Enable Border Top', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to show or hide border top in footer', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],

					'active_callback' => function () {
						return in_array(
							get_theme_mod( 'landkit_single_post_footer_background' ),
							[ 'bg-dark' ],
							true
						) && filter_var( get_theme_mod( 'enable_single_post_custom_footer' ), FILTER_VALIDATE_BOOLEAN );
					},

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_single_post_footer_border_top',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'single_post_before_footer_content',
				array(
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'absint',
				)
			);

			$wp_customize->add_control(
				'single_post_before_footer_content',
				array(
					'section'     => 'landkit_blog',
					'label'       => esc_html__( 'Single Post Footer Before Content', 'landkit' ),
					'description' => esc_html__( 'Choose a static block that will be displayed before the footer in single blog posts.', 'landkit' ),
					'type'        => 'select',
					'choices'     => $this->static_contents,
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'single_post_before_footer_content',
				[
					'fallback_refresh' => true,
				]
			);

		}

		/**
		 * Customize portfolio
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_portfolio( $wp_customize ) {
			$wp_customize->add_section(
				'landkit_portfolio',
				[
					'title'       => esc_html__( 'Portfolio', 'landkit' ),
					'description' => esc_html__( 'This section contains settings related to single portfolio.', 'landkit' ),
					'priority'    => 40,
				]
			);

			$this->add_portfolio_section( $wp_customize );
		}
		/**
		 * Customizer Controls For Portfolio.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		private function add_portfolio_section( $wp_customize ) {
			$this->static_contents = static_content_options();

			$wp_customize->add_setting(
				'enable_portfolio_custom_header',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_portfolio_custom_header',
				[
					'type'        => 'radio',
					'section'     => 'landkit_portfolio',
					'label'       => esc_html__( 'Enable Custom Header', 'landkit' ),
					'description' => esc_html__( 'This setting allows you to show or hide custom header for Single Portfolio', 'landkit' ),
					'choices'     => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_portfolio_custom_header',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'portfolio_header_skin',
				[
					'default'           => 'light',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'portfolio_header_skin',
				[
					'type'            => 'select',
					'section'         => 'landkit_portfolio',
					'label'           => esc_html__( 'Skin', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to choose your header skin.', 'landkit' ),
					'choices'         => [
						'light' => esc_html__( 'Light', 'landkit' ),
						'dark'  => esc_html__( 'Dark', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_portfolio_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'portfolio_header_skin',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_portfolio_border_bottom',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_portfolio_border_bottom',
				[
					'type'            => 'radio',
					'section'         => 'landkit_portfolio',
					'label'           => esc_html__( 'Enable Border Bottom', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to show or hide border bottom', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_portfolio_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_portfolio_border_bottom',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_portfolio_navbar_togglable',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_portfolio_navbar_togglable',
				[
					'type'            => 'radio',
					'section'         => 'landkit_portfolio',
					'label'           => esc_html__( 'Enable Navbar Togglable', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to enable or disable navbar toggle option', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],

					'active_callback' => function () {
						return in_array(
							get_theme_mod( 'portfolio_header_skin' ),
							[ 'dark' ],
							true
						) && filter_var( get_theme_mod( 'enable_portfolio_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_portfolio_navbar_togglable',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_portfolio_fixed_top',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_portfolio_fixed_top',
				[
					'type'            => 'radio',
					'section'         => 'landkit_portfolio',
					'label'           => esc_html__( 'Enable Fixed Top', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to enable or disable fixed top option', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_portfolio_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_portfolio_fixed_top',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'portfolio_header_is_full_width',
				[
					'default'           => 'yes',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'portfolio_header_is_full_width',
				[
					'type'            => 'radio',
					'section'         => 'landkit_portfolio',
					'label'           => esc_html__( 'Make header full-width?', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to control header width. Default is boxed.', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_portfolio_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'portfolio_header_is_full_width',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'portfolio_container_layout',
				[
					'default'           => 'container',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'portfolio_container_layout',
				[
					'type'            => 'select',
					'section'         => 'landkit_portfolio',
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
						return ( 'no' === get_theme_mod( 'portfolio_header_is_full_width', 'no' ) ) && filter_var( get_theme_mod( 'enable_portfolio_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'portfolio_container_layout',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_portfolio_action_button',
				[
					'default'           => 'yes',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_portfolio_action_button',
				[
					'type'            => 'radio',
					'section'         => 'landkit_portfolio',
					'label'           => esc_html__( 'Enable Button', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to show or hide Buy Now button', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
					'active_callback' => function () {
						return filter_var( get_theme_mod( 'enable_portfolio_custom_header' ), FILTER_VALIDATE_BOOLEAN );
					},

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_portfolio_action_button',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_portfolio_custom_footer',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_portfolio_custom_footer',
				[
					'type'        => 'radio',
					'section'     => 'landkit_portfolio',
					'label'       => esc_html__( 'Enable Custom Footer', 'landkit' ),
					'description' => esc_html__( 'This setting allows you to show or hide custom footer for Single Portfolio', 'landkit' ),
					'choices'     => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_portfolio_custom_footer',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'landkit_portfolio_footer_background',
				[
					'default'           => 'bg-gray-200',
					'sanitize_callback' => 'sanitize_key',
				]
			);
			$wp_customize->add_control(
				'landkit_portfolio_footer_background',
				[
					'type'            => 'select',
					'section'         => 'landkit_portfolio',
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
						return filter_var( get_theme_mod( 'enable_portfolio_custom_footer' ), FILTER_VALIDATE_BOOLEAN );
					},
				]
			);
			$wp_customize->selective_refresh->add_partial(
				'landkit_portfolio_footer_background',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_portfolio_footer_border_top',
				[
					'default'           => 'yes',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'enable_portfolio_footer_border_top',
				[
					'type'            => 'radio',
					'section'         => 'landkit_portfolio',
					'label'           => esc_html__( 'Enable Border Top', 'landkit' ),
					'description'     => esc_html__( 'This setting allows you to show or hide border top in footer', 'landkit' ),
					'choices'         => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],

					'active_callback' => function () {
						return in_array(
							get_theme_mod( 'landkit_portfolio_footer_background' ),
							[ 'bg-dark' ],
							true
						) && filter_var( get_theme_mod( 'enable_portfolio_custom_footer' ), FILTER_VALIDATE_BOOLEAN );
					},

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_portfolio_footer_border_top',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'portfolio_before_footer_content',
				array(
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'absint',
				)
			);

			$wp_customize->add_control(
				'portfolio_before_footer_content',
				array(
					'section'     => 'landkit_portfolio',
					'label'       => esc_html__( 'Single Portfolio Footer Before Content', 'landkit' ),
					'description' => esc_html__( 'Choose a static content that will be displayed before the footer in Single Portfolio page.', 'landkit' ),
					'type'        => 'select',
					'choices'     => $this->static_contents,
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'portfolio_before_footer_content',
				[
					'fallback_refresh' => true,
				]
			);
		}
		/**
		 * Customize 404
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_404( $wp_customize ) {
			$wp_customize->add_section(
				'landkit_404',
				[
					'title'    => esc_html__( '404', 'landkit' ),
					'priority' => 70,
				]
			);

			$this->add_404_section( $wp_customize );
		}
		/**
		 * Customizer Controls For 404.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		private function add_404_section( $wp_customize ) {
			$wp_customize->add_setting(
				'404_style',
				array(
					'default'           => 'style-v1',
					'sanitize_callback' => 'sanitize_key',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'404_style',
				array(
					'type'        => 'select',
					'section'     => 'landkit_404',
					'label'       => esc_html__( 'Error Page Style', 'landkit' ),
					'description' => esc_html__( 'Select the style for Error page', 'landkit' ),
					'choices'     => [
						'style-v1' => esc_html__( 'Error - Basic', 'landkit' ),
						'style-v2' => esc_html__( 'Error - Side Cover', 'landkit' ),
						'style-v3' => esc_html__( 'Error - Illustration', 'landkit' ),
					],
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'404_style',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'404_image',
				[
					'default'           => 0,
					'sanitize_callback' => 'absint',
				]
			);
			$wp_customize->add_control(
				new WP_Customize_Media_Control(
					$wp_customize,
					'404_image',
					[
						'label'           => esc_html__( 'Upload an image', 'landkit' ),
						'description'     => esc_html__( 'If you have a cool picture that you want to display on the 404 page you can upload it here.', 'landkit' ),
						'section'         => 'landkit_404',
						'mime_type'       => 'image',
						'active_callback' => function () {
							return in_array(
								get_theme_mod( '404_style' ),
								[ 'style-v2', 'style-v3' ],
								true
							);
						},
					]
				)
			);
			$wp_customize->selective_refresh->add_partial(
				'404_image',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'404_title',
				[
					'default'           => esc_html__( 'Uh Oh.', 'landkit' ),
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				]
			);
			$wp_customize->add_control(
				'404_title',
				[
					'type'    => 'text',
					'section' => 'landkit_404',
					'label'   => esc_html__( 'Error Page Title', 'landkit' ),

				]
			);
			$wp_customize->selective_refresh->add_partial(
				'404_title',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'404_description',
				[
					'default'           => esc_html__( 'We ran into an issue, but don’t worry, we’ll take care of it for sure.', 'landkit' ),
					'sanitize_callback' => 'sanitize_textarea_field',
					'transport'         => 'postMessage',
				]
			);
			$wp_customize->add_control(
				'404_description',
				[
					'type'    => 'textarea',
					'section' => 'landkit_404',
					'label'   => esc_html__( 'Error Page Description', 'landkit' ),

				]
			);
			$wp_customize->selective_refresh->add_partial(
				'404_description',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'404_button_text',
				[
					'default'           => esc_html__( 'Back to safety', 'landkit' ),
					'sanitize_callback' => 'wp_kses_post',
					'transport'         => 'postMessage',
				]
			);

			$wp_customize->add_control(
				'404_button_text',
				[
					'type'        => 'text',
					'section'     => 'landkit_404',
					'label'       => esc_html__( 'Error Page Button Text', 'landkit' ),
					'description' => esc_html__( 'This setting allows you to change the button text', 'landkit' ),

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'404_button_text',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'404_button_url',
				[
					'transport'         => 'postMessage',
					'sanitize_callback' => 'esc_url_raw',
				]
			);

			$wp_customize->add_control(
				'404_button_url',
				[
					'type'        => 'url',
					'section'     => 'landkit_404',
					'label'       => esc_html__( 'Error Page Button Link', 'landkit' ),
					'description' => esc_html__( 'This setting allows you to change the default homepage link', 'landkit' ),

				]
			);

			$wp_customize->selective_refresh->add_partial(
				'404_button_url',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'404_section_border_top_color',
				[
					'default'           => 'primary',
					'sanitize_callback' => 'sanitize_key',
				]
			);
			$wp_customize->add_control(
				'404_section_border_top_color',
				[
					'type'    => 'select',
					'section' => 'landkit_404',
					'label'   => esc_html__( 'Section Border Top Color', 'landkit' ),
					'choices' => [
						'primary'       => esc_html__( 'Primary', 'landkit' ),
						'secondary'     => esc_html__( 'Secondary', 'landkit' ),
						'success'       => esc_html__( 'Success', 'landkit' ),
						'danger'        => esc_html__( 'Danger', 'landkit' ),
						'warning'       => esc_html__( 'Warning', 'landkit' ),
						'info'          => esc_html__( 'Info', 'landkit' ),
						'dark'          => esc_html__( 'Dark', 'landkit' ),
						'primary-desat' => esc_html__( 'Primary Desat', 'landkit' ),
						'black'         => esc_html__( 'Black', 'landkit' ),
						'white'         => esc_html__( 'White', 'landkit' ),
					],
				]
			);

		}


		/**
		 * Includes Landkit_Customizer when WordPress Initializes
		 */
		public function includes() {
			/**
			 * Core classes.
			 */
			require_once get_template_directory() . '/inc/customizer/landkit-customizer-multi-select.php';
		}

		/**
		 * Returns an array of the desired default Landkit Options
		 *
		 * @return array
		 */
		public function get_landkit_default_setting_values() {
			return apply_filters(
				'landkit_setting_default_values',
				$args = array(
					'landkit_header_button_background' => '#335eea',
				)
			);
		}

		/**
		 * Adds a value to each Landkit setting if one isn't already present.
		 *
		 * @uses get_landkit_default_setting_values()
		 */
		public function default_theme_mod_values() {
			foreach ( $this->get_landkit_default_setting_values() as $mod => $val ) {
				add_filter( 'theme_mod_' . $mod, array( $this, 'get_theme_mod_value' ), 10 );
			}
		}

		/**
		 * Get theme mod value.
		 *
		 * @param string $value Theme modification value.
		 * @return string
		 */
		public function get_theme_mod_value( $value ) {
			$key = substr( current_filter(), 10 );

			$set_theme_mods = get_theme_mods();

			if ( isset( $set_theme_mods[ $key ] ) ) {
				return $value;
			}

			$values = $this->get_landkit_default_setting_values();

			return isset( $values[ $key ] ) ? $values[ $key ] : $value;
		}

		/**
		 * Set Customizer setting defaults.
		 * These defaults need to be applied separately as child themes can filter landkit_setting_default_values
		 *
		 * @param  array $wp_customize the Customizer object.
		 * @uses   get_landkit_default_setting_values()
		 */
		public function edit_default_customizer_settings( $wp_customize ) {
			foreach ( $this->get_landkit_default_setting_values() as $mod => $val ) {
				$wp_customize->get_setting( $mod )->default = $val;
			}
		}


		/**
		 * Get all of the Landkit theme mods.
		 *
		 * @return array $landkit_theme_mods The Landkit Theme Mods.
		 */
		public function get_landkit_theme_mods() {
			$landkit_theme_mods = array(
				'header_button_background' => get_theme_mod( 'landkit_header_button_background' ),
			);

			return apply_filters( 'landkit_theme_mods', $landkit_theme_mods );
		}

		/**
		 * Get Customizer css.
		 *
		 * @see get_landkit_theme_mods()
		 * @return array $styles the css
		 */
		public function get_css() {
			$landkit_theme_mods      = $this->get_landkit_theme_mods();
			$button_color            = $landkit_theme_mods['header_button_background'];
			$button_color_yiq        = landkit_sass_yiq( $button_color );
			$button_color_darken_10p = landkit_adjust_color_brightness( $button_color, -10 );

			$styles = '
            .btn-primary {
                background-color: ' . $button_color . ';
                color: ' . $button_color_yiq . ';
            }

            .btn-primary:hover,
            .btn-primary:focus {
                 background-color: ' . $button_color_darken_10p . ';
                 color: ' . $button_color_yiq . ';
            }';

			return apply_filters( 'landkit_customizer_css', $styles );
		}

		/**
		 * Add CSS in <head> for styles handled by the theme customizer
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function add_customizer_css() {
			if ( get_theme_mod( 'button_color' ) === 'custom' ) {
				wp_add_inline_style( 'landkit-style', $this->get_css() );
			}
		}

		/**
		 * Customize site Custom Theme Color
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_customcolor( $wp_customize ) {
			/*
			 * Custom Color Enable / Disble Toggle
			 */
			$wp_customize->add_setting(
				'landkit_enable_custom_color',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'landkit_enable_custom_color',
				[
					'type'        => 'radio',
					'section'     => 'colors',
					'label'       => esc_html__( 'Enable Custom Color?', 'landkit' ),
					'description' => esc_html__(
						'This settings allow you to apply your custom color option.',
						'landkit'
					),
					'choices'     => [
						'yes' => esc_html__( 'Yes', 'landkit' ),
						'no'  => esc_html__( 'No', 'landkit' ),
					],
				]
			);

			/**
			 * Primary Color
			 */
			$wp_customize->add_setting(
				'landkit_primary_color',
				array(
					'default'           => apply_filters( 'landkit_default_primary_color', '#335eea' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'landkit_primary_color',
					array(
						'label'           => esc_html__( 'Primary color', 'landkit' ),
						'section'         => 'colors',
						'settings'        => 'landkit_primary_color',
						'active_callback' => function () {
							return get_theme_mod( 'landkit_enable_custom_color', 'no' ) === 'yes';
						},
					)
				)
			);
		}
	}

endif;

return new Landkit_Customizer();
