<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package storefront
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
