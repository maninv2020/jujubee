<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Landkit
 */

$style_404 = apply_filters( 'landkit_404_style', get_theme_mod( '404_style', 'style-v1' ) );

$section_additional_class = '';
if ( 'style-v2' !== $style_404 ) {
	$section_additional_class = 'section-border border-' . get_theme_mod( '404_section_border_top_color', 'primary' ) . '';
} else {
	$section_additional_class = 'section-error-cover';
}

get_header( 'canvas' ); ?>

<section class="<?php echo esc_attr( $section_additional_class ); ?>">
	<div class="container d-flex flex-column">
		<div class="row align-items-center justify-content-center no-gutters min-vh-100">
			<?php if ( 'style-v3' === $style_404 && 0 < (int) get_theme_mod( '404_image' ) ) { ?>
				<div class="col-8 col-md-6 col-lg-7 offset-md-1 order-md-2 mt-auto mt-md-0 pt-8 pb-4 py-md-11">
					<?php
					echo wp_get_attachment_image(
						get_theme_mod( '404_image' ),
						'large',
						false,
						[
							'class' => 'img-fluid',
							'alt'   => esc_html__( 'Error 404', 'landkit' ),
						]
					);
					?>
				</div>
				<?php
			} elseif ( 'style-v2' === $style_404 && 0 < (int) get_theme_mod( '404_image' ) ) {
				?>
				<div class="col-lg-7 offset-lg-1 align-self-stretch d-none d-lg-block order-2">
					<div class="h-100 w-cover bg-cover" style="background-image: url( <?php echo( wp_get_attachment_image_url( get_theme_mod( '404_image' ), 'full' ) ); //phpcs:ignore ?> ); "></div>

					<div class="shape shape-left shape-fluid-y svg-shim text-white">
						<svg viewBox="0 0 100 1544" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h100v386l-50 772v386H0V0z" fill="currentColor"></path></svg>
					</div>
			</div>
				<?php
			}
			?>
			<div class="col-12 col-lg-4 py-md-11
			<?php
			if ( 'style-v3' === $style_404 ) :
				?>
				order-md-1 mb-auto mb-md-0 pb-8
				<?php
elseif ( 'style-v2' === $style_404 ) :
	?>
				col-lg-4 py-8
				<?php
else :
	?>
	col-md-5 py-8<?php endif; ?> ">

				<h1 class="display-3 font-weight-bold<?php echo esc_attr( 'style-v2' !== $style_404 || 0 === (int) get_theme_mod( '404_image' ) ? ' text-center' : '' ); ?>"><?php echo esc_html( get_theme_mod( '404_title', esc_html__( 'Uh Oh.', 'landkit' ) ) ); ?>
				</h1>
				<p class="mb-5 text-muted<?php echo esc_attr( 'style-v2' !== $style_404 || 0 === (int) get_theme_mod( '404_image' ) ? ' text-center' : '' ); ?>"><?php echo esc_html( get_theme_mod( '404_description', esc_html__( 'We ran into an issue, but don’t worry, we’ll take care of it for sure.', 'landkit' ) ) ); ?>
				</p>
				<div class="<?php echo esc_attr( 'style-v2' !== $style_404 || 0 === (int) get_theme_mod( '404_image' ) ? ' text-center' : 'text-left' ); ?>">
					<a class="btn btn-primary" href="<?php echo esc_url( get_theme_mod( '404_button_url', home_url( '/' ) ) ); ?>">
						<?php echo esc_html( get_theme_mod( '404_button_text', esc_html__( 'Back to safety', 'landkit' ) ) ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer( 'canvas' );
