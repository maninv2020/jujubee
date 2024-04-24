<?php
/**
 * Template used to display post content.
 *
 * @package landkit
 */

$post_class = landkit_get_blog_post_classes();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
	<div class="card mb-6 shadow-light-lg lift lift-lg">
		<?php
		/**
		 * Functions hooked in to landkit_loop_post action.
		 *
		 * @hooked landkit_post_header          - 10
		 * @hooked landkit_post_content         - 30
		 */
		do_action( 'landkit_loop_post' );
		?>
	</div>
</article><!-- #post-## -->
