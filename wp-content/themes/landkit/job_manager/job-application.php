<?php
/**
 * Show job application when viewing a single job listing.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/job-application.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @package     wp-job-manager
 * @version     1.31.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<?php
if ( ! empty( get_the_job_application_method() ) ) :

	$apply = get_the_job_application_method();
	wp_enqueue_script( 'wp-job-manager-job-application' );
	?>
	<div id="apply-job" class="job_application application">
		<?php do_action( 'job_application_start', $apply ); ?>
		<?php
			/**
			 * Job manager application details email or Job manager application details url hook
			 */
			do_action( 'job_manager_application_details_' . $apply->type, $apply );
		?>
		<?php do_action( 'job_application_end', $apply ); ?>
		</div>
<?php endif; ?>
