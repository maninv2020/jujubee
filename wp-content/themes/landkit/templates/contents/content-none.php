<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package landkit
 */

$h_class = 'pb-6';
if ( ! is_search() ) {
	$h_class .= ' mt-n6';
}

?>
<div class="<?php echo esc_attr( $h_class ); ?>">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<?php get_search_form(); ?>
			</div>
		</div> <!-- / .row -->
	</div>
</div>
<section>
	<div class="container d-flex flex-column">
		<div class="row align-items-center justify-content-center no-gutters">
			<div class="col-12 col-md-5 col-lg-5 py-8 py-md-12 mb-8">

				<!-- Heading -->
				<h1 class="display-3 font-weight-bold text-center">
					<?php esc_html_e( 'Nothing Found', 'landkit' ); ?>
				</h1>

				<!-- Text -->
				<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

					<p class="mb-5 text-center text-muted">
					<?php
						/* translators: %1$s - Link to new post page */
						printf( wp_kses( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'landkit' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php' ) ) );
					?>
					</p>

				<?php elseif ( is_search() ) : ?>

					<p class="mb-5 text-center text-muted"><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'landkit' ); ?></p>

				<?php else : ?>

					<p class="mb-5 text-center text-muted"><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'landkit' ); ?></p>

				<?php endif; ?>

				<!-- Link -->
				<div class="text-center">
					<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php echo esc_html__( 'Back to home', 'landkit' ); ?>
					</a>
				</div>

			</div>
		</div> <!-- / .row -->
	</div> <!-- / .container -->
</section>
