<?php
/**
 * Job listing preview when submitting job listings.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/job-preview.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @package     wp-job-manager
 * @version     1.32.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<form method="post" id="job_preview" action="<?php echo esc_url( $form->get_action() ); ?>">
	<?php
	/**
	 * Fires at the top of the preview job form.
	 *
	 * @since 1.32.2
	 */
	do_action( 'preview_job_form_start' );
	?>
	<div class="row align-items-center">
		<div class="col-12 col-md">

			<span class="badge badge-primary mb-3">
				<?php esc_html_e( 'Preview', 'landkit' ); ?>
			</span>

			<!-- Heading -->
			<h1 class="display-4 mb-2">
				<?php wpjm_the_job_title(); ?>
			</h1>

			<!-- Text -->
			<?php job_listing_meta_display(); ?>

		</div>
		<div class="col-auto">

			<!-- Buttons -->
			<input type="submit" name="edit_job" class="button job-manager-button-edit-listing btn btn-primary-soft mr-1" value="<?php esc_attr_e( 'Edit listing', 'landkit' ); ?>" />

			<input type="submit" name="continue" id="job_preview_submit_button" class="button job-manager-button-submit-listing btn btn-primary mr-1" value="<?php echo esc_attr( apply_filters( 'submit_job_step_preview_submit_text', __( 'Submit Listing', 'landkit' ) ) ); ?>" />

		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<!-- Divider -->
			<hr class="my-6 my-md-8 border-gray-300">
		</div>
	</div>
	<div class="job_listing_preview single_job_listing">

		<?php

			remove_action( 'landkit_single_job_listing_before', 'landkit_wpjm_job_listing_header_start', 10 );
			remove_action( 'landkit_single_job_listing_before', 'landkit_wpjm_job_listing_title_area', 20 );
			remove_action( 'landkit_single_job_listing_before', 'landkit_wpjm_job_listing_action_btn', 30 );
			remove_action( 'landkit_single_job_listing_before', 'landkit_wpjm_job_listing_header_end', 40 );
			remove_action( 'landkit_single_job_listing_before', 'landkit_wpjm_job_listing_divider', 50 );

			do_action( 'landkit_single_job_listing_before' );

			get_job_manager_template_part( 'content-single', 'job_listing' );

			do_action( 'landkit_single_job_listing_after' );
		?>

		<input type="hidden" name="job_id" value="<?php echo esc_attr( $form->get_job_id() ); ?>" />
		<input type="hidden" name="step" value="<?php echo esc_attr( $form->get_step() ); ?>" />
		<input type="hidden" name="job_manager_form" value="<?php echo esc_attr( $form->get_form_name() ); ?>" />
	</div>
	<?php
	/**
	 * Fires at the bottom of the preview job form.
	 *
	 * @since 1.32.2
	 */
	do_action( 'preview_job_form_end' );
	?>
</form>
