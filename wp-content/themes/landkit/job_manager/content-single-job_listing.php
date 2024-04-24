<?php
/**
 * Single job listing.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/content-single-job_listing.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @package     wp-job-manager
 * @since       1.0.0
 * @version     1.28.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;

if ( get_option( 'job_manager_hide_expired_content', 1 ) && 'expired' === $post->post_status ) : ?>
	<div class="alert alert-secondary"><?php esc_html_e( 'This listing has expired.', 'landkit' ); ?></div>
	<?php
else :

	do_action( 'landkit_single_job_listing_top' );

	/**
	 * Functions hooked into landkit_single_job_listing add_action
	 *
	 * @hooked landkit_wpjm_job_listing_header_start    - 10
	 * @hooked landkit_wpjm_job_listing_title_area      - 20
	 * @hooked landkit_wpjm_job_listing_action_btn      - 30
	 * @hooked landkit_wpjm_job_listing_header_end      - 40
	 * @hooked landkit_wpjm_job_listing_divider         - 50
	 * @hooked landkit_wpjm_job_listing_content_start   - 60
	 * @hooked landkit_wpjm_job_listing_description     - 70
	 * @hooked landkit_wpjm_job_listing_content_end     - 80
	 */
	do_action( 'landkit_single_job_listing' );

	/**
	 * Functions hooked in to landkit_single_job_listing_bottom action
	 *
	 * @hooked landkit_job_listing_nav         - 10
	 * @hooked landkit_display_comments - 20
	 */
	do_action( 'landkit_single_job_listing_bottom' );

endif;
