<?php
/**
 * Template used to display post content on single pages.
 *
 * @package landkit
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	$post_type = get_post_type(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

	do_action( "landkit_single_{$post_type}_top" );

	/**
	 * Functions hooked into landkit_single_post add_action
	 *
	 * @hooked landkit_post_header          - 10
	 * @hooked landkit_post_content         - 30
	 */
	do_action( "landkit_single_{$post_type}" );

	/**
	 * Functions hooked in to landkit_single_post_bottom action
	 *
	 * @hooked landkit_post_nav         - 10
	 * @hooked landkit_display_comments - 20
	 */
	do_action( "landkit_single_{$post_type}_bottom" );
	?>

</article><!-- #post-## -->
