<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package landkit
 */

get_header();

	do_action( 'landkit_archive_before' );

if ( have_posts() ) :

	get_template_part( 'loop', get_post_type() );

	else :

		get_template_part( 'templates/contents/content', 'none' );

	endif;

	do_action( 'landkit_archive_after' );

	get_footer();
