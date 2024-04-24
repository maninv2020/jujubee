<?php
/**
 * Single view job meta box.
 *
 * Hooked into single_job_listing_start priority 20
 *
 * This template can be overridden by copying it to yourtheme/job_manager/content-single-job_listing-meta.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @package     wp-job-manager
 * @since       1.14.0
 * @version     1.28.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;

do_action( 'single_job_listing_meta_before' ); ?>

<ul class="job-listing__meta list-unstyled font-size-lg text-gray-700 mb-5 mb-md-0 d-flex align-items-center">
	<?php do_action( 'single_job_listing_meta_start' ); ?>
	<li class="location"><?php the_job_location(); ?></li>
	<?php if ( get_option( 'job_manager_enable_types' ) ) { ?>
		<?php $types = wpjm_get_the_job_types(); ?>
		<?php
		if ( ! empty( $types ) ) :
			foreach ( $types as $type ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				?>

			<li class="job-type <?php echo esc_attr( sanitize_title( $type->slug ) ); ?>"><?php echo esc_html( $type->name ); ?></li>

					<?php
		endforeach;
endif;
		?>
	<?php } ?>

	<li class="date-posted"><?php the_job_publish_date(); ?></li>

	<?php if ( is_position_filled() ) : ?>
		<li class="position-filled"><?php echo esc_html__( 'This position has been filled', 'landkit' ); ?></li>
	<?php elseif ( ! candidates_can_apply() && 'preview' !== $post->post_status ) : ?>
		<li class="listing-expired"><?php echo esc_html__( 'Applications have closed', 'landkit' ); ?></li>
	<?php endif; ?>

	<?php do_action( 'single_job_listing_meta_end' ); ?>

	</ul>

<?php do_action( 'single_job_listing_meta_after' ); ?>
