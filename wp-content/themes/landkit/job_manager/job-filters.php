<?php
/**
 * Filters in `[jobs]` shortcode.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/job-filters.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @package     wp-job-manager
 * @version     1.33.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

wp_enqueue_script( 'wp-job-manager-ajax-filters' );

do_action( 'job_manager_job_filters_before', $atts );
$show_button = apply_filters( 'job_manager_job_filters_show_submit_button', false );


if ( $show_categories ) {
	$width_css = $show_button ? 'col-md-3' : 'col-md-4';
} else {
	$width_css = $show_button ? 'col-md-4' : 'col-md-6';
}

?>

<form class="job_filters">
	<?php do_action( 'job_manager_job_filters_start', $atts ); ?>
	<div class="search_jobs row mb-4">
		<?php do_action( 'job_manager_job_filters_search_jobs_start', $atts ); ?>
		<div class="col-12 col-md-
		<?php
		if ( $show_categories ) :
			?>
			4
			<?php
else :
	?>
			6<?php endif; ?>">
			<div class="search_keywords form-group mb-5 mb-md-0">
				<label for="search_keywords"><?php esc_html_e( 'Keywords', 'landkit' ); ?></label>
				<input type="text" class="form-control" name="search_keywords" id="search_keywords" placeholder="<?php esc_attr_e( 'Keywords', 'landkit' ); ?>" value="<?php echo esc_attr( $keywords ); ?>" />
			</div>
		</div>

		<div class="col-12 <?php echo esc_attr( $width_css ); ?>">
			<div class="search_location form-group mb-5 mb-md-0">
				<label for="search_location"><?php esc_html_e( 'Location', 'landkit' ); ?></label>
				<input type="text" class="form-control" name="search_location" id="search_location" placeholder="<?php esc_attr_e( 'Location', 'landkit' ); ?>" value="<?php echo esc_attr( $location ); ?>" />
			</div>
		</div>

		<?php if ( $categories ) : ?>
			<?php foreach ( $categories as $category ) : ?>
				<input type="hidden" name="search_categories[]" value="<?php echo esc_attr( sanitize_title( $category ) ); ?>" />
			<?php endforeach; ?>
		<?php elseif ( $show_categories && ! is_tax( 'job_listing_category' ) && get_terms( [ 'taxonomy' => 'job_listing_category' ] ) ) : ?>
			<div class="col-12 <?php echo esc_attr( $width_css ); ?>">
				<div class="search_categories form-group mb-5 mb-md-0">
					<label for="search_categories"><?php esc_html_e( 'Category', 'landkit' ); ?></label>
					<?php if ( $show_category_multiselect ) : ?>
						<?php
						job_manager_dropdown_categories(
							[
								'taxonomy'     => 'job_listing_category',
								'hierarchical' => 1,
								'name'         => 'search_categories',
								'orderby'      => 'name',
								'selected'     => $selected_category,
								'hide_empty'   => true,
							]
						);
						?>
					<?php else : ?>
						<?php
						job_manager_dropdown_categories(
							[
								'taxonomy'        => 'job_listing_category',
								'hierarchical'    => 1,
								'show_option_all' => __( 'Any category', 'landkit' ),
								'name'            => 'search_categories',
								'orderby'         => 'name',
								'selected'        => $selected_category,
								'multiple'        => false,
								'hide_empty'      => true,
								'class'           => 'custom-select',
							]
						);
						?>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php
		/**
		 * Show the submit button on the job filters form.
		 *
		 * @since 1.33.0
		 *
		 * @param bool $show_submit_button Whether to show the button. Defaults to true.
		 * @return bool
		 */
		if ( $show_button ) :
			?>
		<div class="col-12 col-md-2 d-flex align-items-end">
			<div class="form-group mb-5 mb-md-0">
				<div class="search_submit">
					<input type="submit" class="btn btn-primary btn-block" value="<?php esc_attr_e( 'Search Jobs', 'landkit' ); ?>">
				</div>
			</div>
		</div>
		<?php endif; ?>

		<?php do_action( 'job_manager_job_filters_search_jobs_end', $atts ); ?>
	</div>

	<?php do_action( 'job_manager_job_filters_end', $atts ); ?>

</form>

<?php do_action( 'job_manager_job_filters_after', $atts ); ?>

<noscript><?php esc_html_e( 'Your browser does not support JavaScript, or it is disabled. JavaScript must be enabled in order to view listings.', 'landkit' ); ?></noscript>
