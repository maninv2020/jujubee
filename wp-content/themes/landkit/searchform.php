<?php
/**
 * The searchform.php template.
 *
 * Used any time that get_search_form() is called.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package landkit
 */

/*
 * Generate a unique ID for each form and a string containing an aria-label
 * if one was passed to get_search_form() in the args array.
 */
$landkit_unique_id  = landkit_unique_id( 'search-form-' );
$landkit_aria_label = ! empty( $args['label'] ) ? 'aria-label="' . esc_attr( $args['label'] ) . '"' : '';
?>
<form role="search" <?php echo esc_attr( $landkit_aria_label ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above. ?> method="get" class="search-form rounded shadow 
	<?php
	if ( ! is_search() ) :
		?>
	mb-4<?php endif; ?>" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="input-group input-group-lg">
		<!-- Prepend -->
		<div class="input-group-prepend">
			<span class="input-group-text border-0 pr-1">
				<i class="fe fe-search"></i>
			</span>
		</div>
		<!-- Input -->
		<input type="search" class="form-control border-0 px-1" id="<?php echo esc_attr( $landkit_unique_id ); ?>" aria-label="<?php esc_attr_e( 'Search our blog...', 'landkit' ); ?>" placeholder="<?php echo esc_html( get_theme_mod( 'blog_search_placeholder', __( 'Search our blog...', 'landkit' ) ) ); ?>" value="<?php echo get_search_query(); ?>" name="s">


		<!-- Append -->
		<div class="input-group-append">
			<span class="input-group-text border-0 py-0 pl-1 pr-3">
				<?php landkit_result_count(); ?>
				<button type="submit" class="btn btn-sm btn-primary search-submit"><?php echo esc_html__( 'Search', 'landkit' ); ?></button>
			</span>
		</div>
	</div>
	<input type="hidden" name="post_type" value="<?php echo esc_attr( get_post_type() ); ?>" />
</form>
