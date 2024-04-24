<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package landkit
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked in to landkit_page add_action
	 *
	 * @hooked landkit_page_header          - 10
	 * @hooked landkit_page_content         - 20
	 */
	do_action( 'landkit_page' );
	?>
</article><!-- #post-## -->
