<?php
/**
 * In job listing creation flow, this template shows above the job creation form.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/account-signin.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @package     wp-job-manager
 * @version     1.33.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<?php if ( is_user_logged_in() ) : ?>

	<fieldset class="border-bottom border-gray-300 mb-4 pb-4 form-group row fieldset-logged_in">
		<label class="col-sm-3 col-form-label"><?php esc_html_e( 'Your account', 'landkit' ); ?></label>
		<div class="col-sm-9 col-form-label field account-sign-in">
			<?php
				$user = wp_get_current_user();
				// translators: Placeholder %s is the username.
				printf( wp_kses_post( __( 'You are currently signed in as <strong>%s</strong>.', 'landkit' ) ), esc_html( $user->user_login ) );
			?>

			<a class="button" href="<?php echo esc_url( apply_filters( 'submit_job_form_logout_url', wp_logout_url( get_permalink() ) ) ); ?>"><?php esc_html_e( 'Sign out', 'landkit' ); ?></a>
		</div>
	</fieldset>

	<?php
else :
	$account_required            = job_manager_user_requires_account();
	$registration_enabled        = job_manager_enable_registration();
	$registration_fields         = wpjm_get_registration_fields();
	$use_standard_password_email = wpjm_use_standard_password_setup_email();
	?>
	<fieldset class="alert alert-white fieldset-login_required mb-7 shadow-light">
		<label class="alert-heading h4 font-weight-bold"><?php esc_html_e( 'Have an account?', 'landkit' ); ?></label>
		<div class="field account-sign-in">
			<a class="button" href="<?php echo esc_url( apply_filters( 'submit_job_form_login_url', wp_login_url( get_permalink() ) ) ); ?>"><?php esc_html_e( 'Sign in', 'landkit' ); ?></a>

			<?php if ( $registration_enabled ) : ?>

				<?php // translators: Placeholder %s is the optionally text. ?>
				<?php printf( esc_html__( 'If you don\'t have an account you can %screate one below by entering your email address/username.', 'landkit' ), $account_required ? '' : esc_html__( 'optionally', 'landkit' ) . ' ' ); ?>
				<?php if ( $use_standard_password_email ) : ?>
					<?php printf( esc_html__( 'Your account details will be confirmed via email.', 'landkit' ) ); ?>
				<?php endif; ?>

			<?php elseif ( $account_required ) : ?>

				<?php echo wp_kses_post( apply_filters( 'submit_job_form_login_required_message', __( 'You must sign in to create a new listing.', 'landkit' ) ) ); ?>

			<?php endif; ?>
		</div>
	</fieldset>
	<?php
	if ( ! empty( $registration_fields ) ) {
		foreach ( $registration_fields as $key => $field ) {
			?>
			<fieldset class="border-bottom border-gray-300 mb-4 pb-4 form-group row fieldset-<?php echo esc_attr( $key ); ?>">
				<label class="col-sm-3 col-form-label"
					for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) . wp_kses_post( apply_filters( 'submit_job_form_required_label', $field['required'] ? '' : ' <small>' . __( '(optional)', 'landkit' ) . '</small>', $field ) ); ?></label>
					<div class="col-sm-9 field
					<?php
					if ( isset( $field['required'] ) && ! empty( $field['required'] ) ) {
						echo esc_attr( ' required-field draft-required' ); }
					?>
					">

						<?php
						get_job_manager_template(
							'form-fields/' . $field['type'] . '-field.php',
							[
								'key'   => $key,
								'field' => $field,
							]
						);
						?>
					</div>
			</fieldset>
			<?php
		}
		do_action( 'job_manager_register_form' );
	}
	?>
<?php endif; ?>
