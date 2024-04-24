<?php
/**
 * Template functions for Navbar
 */

if ( ! function_exists( 'landkit_navbar' ) ) {
	/**
	 * Displays the navbar
	 *
	 * @return void
	 */
	function landkit_navbar() {
		extract( landkit_get_navbar_classes() ); //phpcs:ignore
		?><nav class="navbar navbar-expand-lg <?php echo esc_attr( $navbar ); ?>">
			<div class="<?php echo esc_attr( $container ); ?>">
				<?php
				do_action( 'landkit_navbar' );
				?>
			</div>
		</nav>
		<?php
	}
}

if ( ! function_exists( 'landkit_navbar_brand' ) ) {
	/**
	 * Displays Navbar Brand
	 *
	 * @return void
	 */
	function landkit_navbar_brand() {
		landkit_site_title_or_logo();
	}
}

if ( ! function_exists( 'landkit_site_title_or_logo' ) ) {
	/**
	 * Display the site title or logo
	 *
	 * @since 1.0.0
	 * @param bool  $echo Echo the string or return it.
	 * @param array $classes Array of classes.
	 * @return string
	 */
	function landkit_site_title_or_logo( $echo = true, $classes = array() ) {
		$defaults = array(
			'custom-logo-link' => 'navbar-brand',
			'custom-logo'      => 'navbar-brand-img',
			'site-title'       => 'navbar-brand font-weight-900 line-height-1 h3 m-0',
		);
		$classes  = wp_parse_args( $classes, $defaults );

		if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
			$html = get_custom_logo();
		} else {
			$html = '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home" class="site-title">' . esc_html( get_bloginfo( 'name' ) ) . '</a>';
		}

		foreach ( $classes as $search => $replace ) {
			$html = str_replace( $search, $replace, $html );
		}

		if ( ! $echo ) {
			return $html;
		}

		echo wp_kses_post( $html ); // phpcs:ignore Standard.Category.SniffName.ErrorCode
	}
}

if ( ! function_exists( 'landkit_navbar_toggler' ) ) {
	/**
	 * Displays Navbar toggler
	 *
	 * @return void
	 */
	function landkit_navbar_toggler() {
		$toggler_icon = get_theme_mod( 'landkitnavbar_toggler_icon', 'navbar-toggler-icon' );
		?>
		<!-- Toggler -->
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
			<span class="<?php echo esc_attr( $toggler_icon ); ?>"></span>
		</button>
		<?php
	}
}

if ( ! function_exists( 'landkit_navbar_collapse_start' ) ) {
	/**
	 * Displays the navbar collapse starting tags
	 *
	 * @return void
	 */
	function landkit_navbar_collapse_start() {
		?>
		<!-- Collapse -->
		<div class="collapse navbar-collapse" id="navbarCollapse">
		<?php
	}
}

if ( ! function_exists( 'landkit_navbar_toggler_x' ) ) {
	/**
	 * Displays Navbar toggler
	 *
	 * @return void
	 */
	function landkit_navbar_toggler_x() {
		$toggler_icon_x = get_theme_mod( 'landkitnavbar_toggler_icon_x', 'fe fe-x' );
		?>
		<!-- Toggler -->
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
			<i class="<?php echo esc_attr( $toggler_icon_x ); ?>"></i>
		</button>
		<?php
	}
}

if ( ! function_exists( 'landkit_navbar_nav' ) ) {
	/**
	 * Displays the navbar nav
	 *
	 * @return void
	 */
	function landkit_navbar_nav() {
		wp_nav_menu(
			array(
				'theme_location' => 'navbar_nav',
				'container'      => false,
				'menu_class'     => 'navbar-nav ml-auto',
				'walker'         => new WP_Bootstrap_Navwalker(),
				'fallback_cb'    => 'WP_Bootstrap_Navwalker::fallback',
			)
		);
	}
}

if ( ! function_exists( 'landkit_navbar_collapse_end' ) ) {
	/**
	 * Closes Navbar Collapse
	 *
	 * @return void
	 */
	function landkit_navbar_collapse_end() {
		?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'landkit_navbar_action_button' ) ) {
	/**
	 * Navbar action button
	 */
	function landkit_navbar_action_button() {
		global $post;
		$lk_page_options = array();
		$btn_classes     = '';

		if ( $post && function_exists( 'landkit_option_enabled_post_types' ) && is_singular( landkit_option_enabled_post_types() ) ) {
			$clean_meta_data  = get_post_meta( $post->ID, '_lk_page_options', true );
			$_lk_page_options = maybe_unserialize( $clean_meta_data );

			if ( is_array( $_lk_page_options ) ) {
				$lk_page_options = $_lk_page_options;
			}
		}

		if ( 'yes' === isset( $lk_page_options['header'] ) && isset( $lk_page_options['header']['enable_custom_header'] ) && $lk_page_options['header']['enable_custom_header'] ) {
			$btn_classes   = ' custom-header';
			$enable_button = 'yes' === isset( $lk_page_options['header']['enable_action_button'] ) && $lk_page_options['header']['enable_action_button'] ? true : false;
		} elseif ( is_singular( 'post' ) && filter_var( get_theme_mod( 'enable_single_post_custom_header', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) {
			$enable_button = apply_filters( 'landkit_enable_single_post_buy_button', filter_var( get_theme_mod( 'enable_single_post_action_button', 'no' ), FILTER_VALIDATE_BOOLEAN ) );

		} elseif ( is_singular( 'job_listing' ) && filter_var( get_theme_mod( 'enable_single_job_custom_header', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) {
			$enable_button = apply_filters( 'landkit_enable_single_job_buy_button', filter_var( get_theme_mod( 'enable_single_job_action_button', 'no' ), FILTER_VALIDATE_BOOLEAN ) );

		} elseif ( is_singular( [ 'docs' ] ) && filter_var( get_theme_mod( 'enable_docs_custom_header', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) {
			$enable_button = apply_filters( 'landkit_enable_docs_buy_button', filter_var( get_theme_mod( 'enable_docs_action_button', 'no' ), FILTER_VALIDATE_BOOLEAN ) );

		} elseif ( is_singular( 'jetpack-portfolio' ) && filter_var( get_theme_mod( 'enable_portfolio_custom_header', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) {
			$enable_button = apply_filters( 'landkit_enable_portfolio_buy_button', filter_var( get_theme_mod( 'enable_portfolio_action_button', 'no' ), FILTER_VALIDATE_BOOLEAN ) );

		} else {
			$enable_button = apply_filters( 'landkit_enable_buy_button', filter_var( get_theme_mod( 'enable_action_button', 'no' ), FILTER_VALIDATE_BOOLEAN ) );

		}

		$button_url  = apply_filters( 'landkit_buy_button_url', get_theme_mod( 'button_url', '#' ) );
		$button_text = apply_filters( 'landkit_buy_button_text', get_theme_mod( 'button_text', esc_html__( 'Buy now', 'landkit' ) ) );
		$button_size = apply_filters( 'landkit_buy_button_size', get_theme_mod( 'button_size', 'btn-sm' ) );
		$button_css  = apply_filters( 'landkit_buy_button_css', get_theme_mod( 'button_css', '' ) );

		if ( ! empty( $button_size ) ) {
			$btn_classes .= ' ' . $button_size;
		}

		if ( ! empty( $button_css ) ) {
			$btn_classes .= ' ' . $button_css;
		}

		if ( $enable_button && ! empty( $button_text ) ) :
			?>
			<a class="header-button navbar-btn btn lift ml-auto btn-primary<?php echo esc_attr( $btn_classes ); ?>" href="<?php echo esc_url( $button_url ); ?>" target="_blank" >
				<?php echo esc_html( $button_text ); ?>
			</a>
			<?php
		endif;
	}
}


