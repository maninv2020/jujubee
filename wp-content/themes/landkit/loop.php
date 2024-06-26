<?php
/**
 * The loop template file.
 *
 * Included on pages like index.php, archive.php and search.php to display a loop of posts
 * Learn more: https://codex.wordpress.org/The_Loop
 *
 * @package landkit
 */

do_action( 'landkit_loop_before' );

while ( have_posts() ) :
	the_post();

	if ( is_sticky() && is_home() && ! is_paged() ) {
		get_template_part( 'templates/contents/content', 'sticky' );
	} else {
		/**
		 * Include the Post-Format-specific template for the content.
		 * If you want to override this in a child theme, then include a file
		 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
		 */
		get_template_part( 'templates/contents/content', get_post_format() );
	}

endwhile;

/**
 * Functions hooked in to landkit_paging_nav action
 *
 * @hooked landkit_paging_nav - 10
 */
do_action( 'landkit_loop_after' );
