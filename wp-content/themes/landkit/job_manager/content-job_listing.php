<?php
/**
 * Job listing in the loop.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/content-job_listing.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @package     wp-job-manager
 * @since       1.0.0
 * @version     1.34.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;

if ( ! get_option( 'job_manager_enable_categories' ) ) {
	$show_categories = false;
} else {
	$show_categories = true;
}

if ( $show_categories ) {
	$listing_column = '6';
} else {
	$listing_column = '9';
}

?>
<li <?php job_listing_class( 'position-relative row' ); ?> data-longitude="<?php echo esc_attr( $post->geolocation_long ); ?>" data-latitude="<?php echo esc_attr( $post->geolocation_lat ); ?>">
	<div class="col-12 col-md-<?php echo esc_attr( $listing_column ); ?> position-static">
		<a href="<?php the_job_permalink(); ?>" class="text-reset text-decoration-none stretched-link">
			<p class="mb-1"><?php wpjm_the_job_title(); ?></p>
		</a>
		<ul class="d-flex list-unstyled align-items-center meta font-size-sm text-muted mb-0 job-listing__meta">
			<?php do_action( 'job_listing_meta_start' ); ?>

			<?php if ( get_option( 'job_manager_enable_types' ) ) { ?>
				<?php $types = wpjm_get_the_job_types(); ?>
				<?php
				if ( ! empty( $types ) ) :
					foreach ( $types as $type ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
						?>
					<li class="job-type <?php echo esc_attr( sanitize_title( $type->slug ) ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>"><?php echo esc_html( $type->name ); ?></li>
									<?php
									endforeach;
endif;
				?>
			<?php } ?>

			<li class="date"><?php the_job_publish_date(); ?></li>

			<?php do_action( 'job_listing_meta_end' ); ?>
		</ul>
	</div>
	<?php if ( $show_categories ) : ?>
	<div class="col-12 col-md-3 position-static">
		<?php echo esc_html( wp_strip_all_tags( get_the_term_list( $post, 'job_listing_category' ) ) ); ?>
	</div>
	<?php endif; ?>
	<div class="col-12 col-md-3 position-static">
		<?php the_job_location( false ); ?>
	</div>
</li>
