<?php
/**
 * Job dashboard shortcode content.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/job-dashboard.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @package     wp-job-manager
 * @version     1.34.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div id="job-manager-job-dashboard">
	<p class="font-size-lg text-gray-700 mb-6"><?php esc_html_e( 'Your listings are shown in the table below.', 'landkit' ); ?></p>
	<div class="table-responsive mb-5">
		<table class="job-manager-jobs table table-align-middle">
			<thead>
				<tr>
					<?php foreach ( $job_dashboard_columns as $key => $column ) : ?>
						<th class="<?php echo esc_attr( $key ); ?>">
							<span class="h6 text-uppercase font-weight-bold"><?php echo esc_html( $column ); ?></span>
						</th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php if ( ! $jobs ) : ?>
					<tr>
						<td colspan="<?php echo intval( count( $job_dashboard_columns ) ); ?>"><?php esc_html_e( 'You do not have any active listings.', 'landkit' ); ?></td>
					</tr>
				<?php else : ?>
					<?php foreach ( $jobs as $job ) : ?>
						<tr>
							<?php foreach ( $job_dashboard_columns as $key => $column ) : ?>
								<td class="<?php echo esc_attr( $key ); ?>">
									<?php if ( 'job_title' === $key ) : ?>
										<?php if ( 'publish' === $job->post_status ) : ?>
											<a class="text-reset" href="<?php echo esc_url( get_permalink( $job->ID ) ); ?>"><?php wpjm_the_job_title( $job ); ?></a>
										<?php else : ?>
											<?php wpjm_the_job_title( $job ); ?> <span class="badge badge-secondary-soft badge-pill"><?php the_job_status( $job ); ?></span>
										<?php endif; ?>
										<?php echo is_position_featured( $job ) ? '<span class="badge badge-success-soft badge-pill featured-job-icon" title="' . esc_attr__( 'Featured Job', 'landkit' ) . '"></span>' : ''; ?>
										<ul class="job-dashboard-actions font-size-sm text-muted mb-0 list-unstyled d-flex">
											<?php
												$actions = [];

											switch ( $job->post_status ) {
												case 'publish':
													if ( WP_Job_Manager_Post_Types::job_is_editable( $job->ID ) ) {
														$actions['edit'] = [
															'label' => __( 'Edit', 'landkit' ),
															'nonce' => false,
														];
													}
													if ( is_position_filled( $job ) ) {
														$actions['mark_not_filled'] = [
															'label' => __( 'Mark not filled', 'landkit' ),
															'nonce' => true,
														];
													} else {
														$actions['mark_filled'] = [
															'label' => __( 'Mark filled', 'landkit' ),
															'nonce' => true,
														];
													}

													$actions['duplicate'] = [
														'label' => __( 'Duplicate', 'landkit' ),
														'nonce' => true,
													];
													break;
												case 'expired':
													if ( job_manager_get_permalink( 'submit_job_form' ) ) {
														$actions['relist'] = [
															'label' => __( 'Relist', 'landkit' ),
															'nonce' => true,
														];
													}
													break;
												case 'pending_payment':
												case 'pending':
													if ( WP_Job_Manager_Post_Types::job_is_editable( $job->ID ) ) {
														$actions['edit'] = [
															'label' => __( 'Edit', 'landkit' ),
															'nonce' => false,
														];
													}
													break;
												case 'draft':
												case 'preview':
													$actions['continue'] = [
														'label' => __( 'Continue Submission', 'landkit' ),
														'nonce' => true,
													];
													break;
											}

												$actions['delete'] = [
													'label' => __( 'Delete', 'landkit' ),
													'nonce' => true,
												];
												$actions           = apply_filters( 'job_manager_my_job_actions', $actions, $job );

												foreach ( $actions as $action => $value ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
													$action_url = add_query_arg(
														[
															'action' => $action,
															'job_id' => $job->ID,
														]
													);
													if ( $value['nonce'] ) {
														$action_url = wp_nonce_url( $action_url, 'job_manager_my_job_actions' );
													}
													echo '<li><a href="' . esc_url( $action_url ) . '" class="text-reset mr-4 job-dashboard-action-' . esc_attr( $action ) . '">' . esc_html( $value['label'] ) . '</a></li>';
												}
												?>
										</ul>
									<?php elseif ( 'date' === $key ) : ?>
										<?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $job->post_date ) ) ); ?>
									<?php elseif ( 'expires' === $key ) : ?>
										<?php echo esc_html( $job->_job_expires ? date_i18n( get_option( 'date_format' ), strtotime( $job->_job_expires ) ) : '&ndash;' ); ?>
									<?php elseif ( 'filled' === $key ) : ?>
										<?php echo is_position_filled( $job ) ? '<span class="badge badge-success-soft badge-rounded-circle"><i class="fe fe-check"></i></span>' : '&ndash;'; ?>
									<?php else : ?>
										<?php do_action( 'job_manager_job_dashboard_column_' . $key, $job ); ?>
									<?php endif; ?>
								</td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php get_job_manager_template( 'pagination.php', [ 'max_num_pages' => $max_num_pages ] ); ?>
</div>
