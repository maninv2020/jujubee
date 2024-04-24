<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package landkit
 */

?>
	<?php
	do_action( 'landkit_before_footer' );

	$display_footer = filter_var( get_theme_mod( 'landkit_enable_footer', 'yes' ), FILTER_VALIDATE_BOOLEAN );

	if ( function_exists( 'landkit_option_enabled_post_types' ) && is_singular( landkit_option_enabled_post_types() ) ) {
		$clean_meta_data = get_post_meta( get_the_ID(), '_lk_page_options', true );
		$lk_page_options = maybe_unserialize( $clean_meta_data );

		if ( isset( $lk_page_options['footer'] ) && isset( $lk_page_options['footer']['disable_footer'] ) && filter_var( $lk_page_options['footer']['disable_footer'], FILTER_VALIDATE_BOOLEAN ) ) {
			$display_footer = false;
		}
	}
	if ( ! is_active_sidebar( 'footer-1' ) &&
		! is_active_sidebar( 'footer-2' ) &&
		! is_active_sidebar( 'footer-3' ) ) {
		$display_footer = false;
	}

	if ( $display_footer ) :
		?>
		<footer id="colophon" class="site-footer<?php echo esc_attr( landkit_get_footer_classes() ); ?>">
			<div class="container">

				<?php
				/**
				 * Functions hooked in to landkit_footer action
				 *
				 * @hooked landkit_footer_widgets  - 10
				 */
				do_action( 'landkit_footer' );
				?>

			</div><!-- .container -->
		</footer><!-- #colophon -->
	<?php endif; ?>

	<?php do_action( 'landkit_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
