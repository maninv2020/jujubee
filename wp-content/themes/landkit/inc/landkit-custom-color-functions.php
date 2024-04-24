<?php
/**
 * Landkit Theme Customizer
 *
 * @package Landkit
 */

if ( ! function_exists( 'landkit_sass_hex_to_rgba' ) ) {
	/**
	 * Query WooCommerce activation
	 *
	 * @param string $hex The HEX color.
	 * @param string $alpa Alpha.
	 */
	function landkit_sass_hex_to_rgba( $hex, $alpa = '' ) {
		$hex = sanitize_hex_color( $hex );
		preg_match( '/^#?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i', $hex, $matches );
		for ( $i = 1; $i <= 3; $i++ ) {
			$matches[ $i ] = hexdec( $matches[ $i ] );
		}
		if ( ! empty( $alpa ) ) {
			$rgb = 'rgba(' . $matches[1] . ', ' . $matches[2] . ', ' . $matches[3] . ', ' . $alpa . ')';
		} else {
			$rgb = 'rgba(' . $matches[1] . ', ' . $matches[2] . ', ' . $matches[3] . ')';
		}
		return $rgb;
	}
}

if ( ! function_exists( 'landkit_sass_yiq' ) ) {
	/**
	 * Hex color value
	 *
	 * @param string $hex The HEX color.
	 */
	function landkit_sass_yiq( $hex ) {
		$hex    = sanitize_hex_color( $hex );
		$length = strlen( $hex );
		if ( $length < 5 ) {
			$hex = ltrim( $hex, '#' );
			$hex = '#' . $hex . $hex;
		}

		preg_match( '/^#?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i', $hex, $matches );

		for ( $i = 1; $i <= 3; $i++ ) {
			$matches[ $i ] = hexdec( $matches[ $i ] );
		}
		$yiq = ( ( $matches[1] * 299 ) + ( $matches[2] * 587 ) + ( $matches[3] * 114 ) ) / 1000;
		return ( $yiq >= 128 ) ? '#000' : '#fff';
	}
}

if ( ! function_exists( 'landkit_get_theme_colors' ) ) {
	/**
	 * Get all of the landkit theme colors.
	 *
	 * @return array $landkit_theme_colors The Landkit Theme Colors.
	 */
	function landkit_get_theme_colors() {
		$landkit_theme_colors = array(
			'primary_color' => get_theme_mod( 'landkit_primary_color', apply_filters( 'landkit_default_primary_color', '#335eea' ) ),
		);

		return apply_filters( 'landkit_get_theme_colors', $landkit_theme_colors );
	}
}


if ( ! function_exists( 'landkit_get_custom_color_css' ) ) {
	/**
	 * Get Customizer Color css.
	 *
	 * @see landkit_get_custom_color_css()
	 * @return array $styles the css
	 */
	function landkit_get_custom_color_css() {
		$landkit_theme_colors      = landkit_get_theme_colors();
		$primary_color             = $landkit_theme_colors['primary_color'];
		$primary_color_yiq         = landkit_sass_yiq( $primary_color );
		$primary_color_darken_10p  = landkit_adjust_color_brightness( $primary_color, -4.5 );
		$primary_color_darken_15p  = landkit_adjust_color_brightness( $primary_color, -5.7 );
		$primary_color_lighten_20p = landkit_adjust_color_brightness( $primary_color, 20 );
		$primary_color_lighten_10p = landkit_adjust_color_brightness( $primary_color, 27.7 );

		$styles =
		'
/*
 * Primary Color
 */


';

		return apply_filters( 'landkit_get_custom_color_css', $styles );
	}
}


/**
 * Add CSS in <head> for styles handled by the theme customizer
 *
 * @since 1.0.0
 * @return void
 */
if ( ! function_exists( 'landkit_enqueue_custom_color_css' ) ) {
	/**
	 * Add CSS in <head> for styles handled by the theme customizer
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function landkit_enqueue_custom_color_css() {
		if ( get_theme_mod( 'landkit_enable_custom_color', 'no' ) === 'yes' ) {
			$landkit_theme_colors = landkit_get_theme_colors();

			$primary_color            = $landkit_theme_colors['primary_color'];
			$primary_color_yiq        = landkit_sass_yiq( $primary_color );
			$primary_color_darken_10p = landkit_adjust_color_brightness( $primary_color, -18 );
			$primary_color_darken_15p = landkit_adjust_color_brightness( $primary_color, -5.7 );
			$primary_color_darken_45p = landkit_adjust_color_brightness( $primary_color, -45 );
			$primary_color_soft_10    = landkit_sass_hex_to_rgba( $primary_color, '.1' );
			$primary_color_soft_10d   = landkit_sass_hex_to_rgba( $primary_color, '.12' );
			$primary_color_desat      = landkit_sass_hex_to_rgba( $primary_color, '.6' );
			$primary_color_opacity    = landkit_sass_hex_to_rgba( $primary_color, '.05' );
			$primary_color_outline_25 = landkit_sass_hex_to_rgba( $primary_color, '.3' );
			$primary_color_bg_outline = landkit_sass_hex_to_rgba( $primary_color, '.08' );
			$primary_color_outline_70 = landkit_sass_hex_to_rgba( $primary_color, '.7' );
			$primary_color_outline_5  = landkit_sass_hex_to_rgba( $primary_color, '.5' );
			$primary_color_opacity_15 = landkit_sass_hex_to_rgba( $primary_color, '.20' );
			$primary_color_opacity_8  = landkit_sass_hex_to_rgba( $primary_color, '.8' );

			$color_root = ':root {
				--lk-primary: 				' . $landkit_theme_colors['primary_color'] . ';
				--lk-primary-bg-d: 			' . $primary_color_darken_10p . ';
				--lk-primary-border-d: 		' . $primary_color_darken_15p . ';
				--lk-primary-soft: 			' . $primary_color_soft_10 . ';
				--lk-primary-soft-d: 		' . $primary_color_soft_10d . ';
				--lk-primary-desat: 		' . $primary_color_desat . ';
				--lk-primary-o-5: 			' . $primary_color_opacity . ';
				--lk-primary-outline-25: 	' . $primary_color_outline_25 . ';
				--lk-primary-outline-bg: 	' . $primary_color_bg_outline . ';
				--lk-dark-primary: 			' . $primary_color_darken_45p . ';
				--lk-primary-outline-5: 	' . $primary_color_outline_5 . ';
				--lk-primary-outline-75: 	' . $primary_color_outline_70 . ';
				--lk-primary-opacity-15: 	' . $primary_color_opacity_15 . ';
				--lk-primary-opacity-8: 	' . $primary_color_opacity_8 . ';
			}';
			$styles     = $color_root . landkit_get_custom_color_css();

			wp_add_inline_style( 'landkit-color', $styles );
		}
	}
}


add_action( 'wp_enqueue_scripts', 'landkit_enqueue_custom_color_css', 130 );
