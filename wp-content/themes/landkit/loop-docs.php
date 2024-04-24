<?php
/**
 * The loop template file for post type docs.
 *
 * Included on pages like index.php, archive.php and search.php to display a loop of posts
 * Learn more: https://codex.wordpress.org/The_Loop
 *
 * @package landkit
 */

do_action( 'landkit_loop_before' );

global $landkit_loop_docs_index;
$landkit_loop_docs_index = 0;

while ( have_posts() ) :

	the_post();

	/**
	 * Include the Post-Format-specific template for the content.
	 * If you want to override this in a child theme, then include a file
	 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
	 */
	get_template_part( 'templates/wedocs/content', 'docs' );
	$landkit_loop_docs_index++;

endwhile;

unset( $GLOBALS['landkit_loop_docs_index'] );

do_action( 'landkit_loop_after' );
