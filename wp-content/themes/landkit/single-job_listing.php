<?php
/**
 * The template for displaying all single posts.
 *
 * @package landkit
 */

get_header();

while ( have_posts() ) :

	the_post();

	do_action( 'landkit_single_job_listing_before' );

	?><section id="post-<?php the_ID(); ?>" <?php post_class( 'pt-8 pt-md-11 single_job_listing' ); ?>>
		<div class="container">
		<?php

			do_action( 'landkit_single_job_listing_wpjm_before' );

			get_job_manager_template_part( 'content-single', 'job_listing' );

			do_action( 'landkit_single_job_listing_wpjm_after' );

		?>
		</div>
	</section>
	<?php

	do_action( 'landkit_single_job_listing_after' );

endwhile; // End of the loop.

get_footer();
