<?php
/**
 * Landkit engine room
 *
 * @package landkit
 */

/**
 * Assign the Landkit version to a var
 */
$theme           = wp_get_theme( 'landkit' );
$landkit_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 653; /* pixels */
}

$landkit = (object) array(
	'version'    => $landkit_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-landkit.php',
	'customizer' => require 'inc/customizer/class-landkit-customizer.php',
);

/**
 * TGM Plugin Activation class.
 */
require get_template_directory() . '/inc/classes/class-tgm-plugin-activation.php';

/**
 * Customizer Functions.
 */
require get_template_directory() . '/inc/customizer/landkit-customizer-functions.php';

require get_template_directory() . '/inc/landkit-functions.php';
require get_template_directory() . '/inc/landkit-template-functions.php';
require get_template_directory() . '/inc/landkit-template-hooks.php';
require get_template_directory() . '/inc/wordpress-shims.php';

if ( function_exists( 'wpforms' ) ) {
	require get_template_directory() . '/inc/wpforms/integration.php';

}

if ( landkit_is_wp_job_manager_activated() ) {
	$landkit->wpjm            = require get_template_directory() . '/inc/wpjm/class-landkit-wpjm.php';
	$landkit->wpjm_customizer = require get_template_directory() . '/inc/wpjm/class-landkit-wpjm-customizer.php';

	require get_template_directory() . '/inc/wpjm/landkit-wpjm-functions.php';
	require get_template_directory() . '/inc/wpjm/landkit-wpjm-template-hooks.php';
	require get_template_directory() . '/inc/wpjm/landkit-wpjm-template-functions.php';
}

if ( landkit_is_wedocs_activated() ) {
	$landkit->wedocs            = require get_template_directory() . '/inc/wedocs/class-landkit-wedocs.php';
	$landkit->wedocs_customizer = require get_template_directory() . '/inc/wedocs/class-landkit-wedocs-customizer.php';

	require get_template_directory() . '/inc/wedocs/landkit-wedocs-template-functions.php';
	require get_template_directory() . '/inc/wedocs/landkit-wedocs-template-hooks.php';
	require get_template_directory() . '/inc/wedocs/landkit-wedocs-functions.php';
}

if ( landkit_is_ocdi_activated() ) {
	$landkit->ocdi = require get_template_directory() . '/inc/ocdi/class-landkit-ocdi.php';
}

if ( is_admin() ) {
	$landkit->admin = require get_template_directory() . '/inc/admin/class-landkit-admin.php';

	require get_template_directory() . '/inc/admin/class-landkit-plugin-install.php';
	require get_template_directory() . '/inc/classes/class-landkit-post-taxonomies.php';
}

/**
 * Menu Walker
 */
require get_template_directory() . '/inc/classes/class-wp-bootstrap-navwalker.php';

/**
 * Custom Comment Walker template.
 */
require get_template_directory() . '/inc/classes/class-landkit-walker-comment.php';

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */

/**
 * Functions used for Lankit Custom Theme Color
 */
require get_template_directory() . '/inc/landkit-custom-color-functions.php';
