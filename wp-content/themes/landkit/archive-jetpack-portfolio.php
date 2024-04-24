<?php
/**
 * The template for displaying the Portfolio archive page.
 *
 * @package landkit
 */

get_header();

	do_action( 'landkit_before_portfolio' );

if ( have_posts() ) {

	get_template_part( 'loop', 'portfolio' );

} else {

	get_template_part( 'templates/contents/content', 'none' );

}

	do_action( 'landkit_after_portfolio' );

get_footer();
