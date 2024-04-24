<?php
/**
 * The template for displaying a single doc
 *
 * To customize this template, create a folder in your current theme named "wedocs" and copy it there.
 *
 * @package weDocs
 */

get_header(); ?>

	<?php
		/**
		 * Fires before the main content
		 */
		do_action( 'wedocs_before_main_content' );
	?>

	<?php

	while ( have_posts() ) :
		the_post();
		global $post;
		$thepostid = isset( $thepostid ) ? $thepostid : $post->ID;
		?>

		<?php do_action( 'landkit_single_docs_before' ); ?>
		<section class="mt-6 mt-md-8 bg-light">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<?php do_action( 'landkit_wedocs_single_doc' ); ?>
					</div>
				</div>
			</div>
		</section>

		<?php do_action( 'landkit_single_docs_after' ); ?>
	<?php endwhile; ?>

	<?php
		/**
		 * Fires after the main content
		 */
		do_action( 'wedocs_after_main_content' );
	?>

<?php
get_footer();

/**
				 * Fires after the single post content
				 */
