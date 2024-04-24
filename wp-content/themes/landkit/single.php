<?php
/**
 * The template for displaying all single posts.
 *
 * @package landkit
 */

get_header();

while ( have_posts() ) :

	the_post();

	$post_type = get_post_type(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

	do_action( "landkit_single_{$post_type}_before" );

	get_template_part( 'templates/contents/content', 'single' );

	do_action( "landkit_single_{$post_type}_after" );

	endwhile; // End of the loop.

get_footer();
