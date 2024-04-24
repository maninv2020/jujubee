<?php
/**
 * Template functions for Footer
 */

if ( ! function_exists( 'landkit_footer_widgets' ) ) {
	/**
	 * Display the footer widget regions.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function landkit_footer_widgets() {
		$rows    = intval( apply_filters( 'landkit_footer_widget_rows', 1 ) );
		$regions = intval( apply_filters( 'landkit_footer_widget_columns', 5 ) );
		$regions = class_exists( 'Landkit_Extensions' ) ? $regions : 3;

		for ( $row = 1; $row <= $rows; $row++ ) :

			// Defines the number of active columns in this footer row.
			for ( $region = $regions; 0 < $region; $region-- ) {
				if ( is_active_sidebar( 'footer-' . esc_attr( $region + $regions * ( $row - 1 ) ) ) ) {
					$columns = $region;
					break;
				}
			}

			if ( isset( $columns ) ) :
				?>
				<div class="footer-widgets row mb-n6 mb-md-n8">
				<?php
				for ( $column = 1; $column <= $columns; $column++ ) :
					$footer_n = $column + $regions * ( $row - 1 );

					if ( is_active_sidebar( 'footer-' . esc_attr( $footer_n ) ) ) :
						$widget_class = $column . ' col-md-4';

						if ( 1 === $column ) {
							$widget_class .= ' col-12 col-lg-3';
						} else {
							$widget_class .= ' col-6 col-lg-2';
						}

						if ( 4 === $column ) {
							$widget_class .= ' offset-md-4 offset-lg-0';
						}

						$widget_class = class_exists( 'Landkit_Extensions' ) ? $widget_class : 'col-12 col-md-4';

						?>
					<div class="footer-widget footer-widget--<?php echo esc_attr( $widget_class ); ?>">
						<?php dynamic_sidebar( 'footer-' . esc_attr( $footer_n ) ); ?>
					</div>
						<?php
					endif;
				endfor;
				?>
			</div><!-- .footer-widgets.row -->
				<?php
				unset( $columns );
			endif;
		endfor;
	}
}

if ( ! function_exists( 'landkit_social_media_links' ) ) {
	/**
	 * Displays social media links
	 */
	function landkit_social_media_links() {
		if ( has_nav_menu( 'social_media' ) ) {
			wp_nav_menu(
				array(
					'theme_location' => 'social_media',
					'container'      => false,
					'menu_class'     => 'list-unstyled list-inline list-social mb-6 mb-md-0',
					'item_class'     => [ 'list-inline-item', 'list-social-item', 'mr-3' ],
					'anchor_class'   => [ 'text-decoration-none', 'text-muted', 'font-size-xl' ],
					'icon_class'     => [ 'list-social-icon' ],
					'walker'         => new WP_Bootstrap_Navwalker(),
				)
			);
		}
	}
}
