<?php
/**
 * Content shown before job listings in `[jobs]` shortcode.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/job-listings-start.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @package     wp-job-manager
 * @version     1.15.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! get_option( 'job_manager_enable_categories' ) ) {
	$show_categories = false;
} else {
	$show_categories = true;
}

if ( $show_categories ) {
	$column = '6';
} else {
	$column = '9';
}
?>
<ul class="job_listings__header list-unstyled bg-white mb-0 mt-5">
	<li class="row">
		<div class="col-12 col-md-<?php echo esc_attr( $column ); ?>">
			<span class="h6 text-uppercase font-weight-bold"><?php echo esc_html__( 'Job', 'landkit' ); ?></span>
		</div>
		<?php if ( $show_categories ) : ?>
		<div class="col-12 col-md-3">
			<span class="h6 text-uppercase font-weight-bold"><?php echo esc_html__( 'Category', 'landkit' ); ?></span>
		</div>
		<?php endif; ?>
		<div class="col-12 col-md-3">
			<span class="h6 text-uppercase font-weight-bold"><?php echo esc_html__( 'Location', 'landkit' ); ?></span>
		</div>
	</li>
</ul>
<ul class="job_listings list-unstyled bg-white mb-7">
