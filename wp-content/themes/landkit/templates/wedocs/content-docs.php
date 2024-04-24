<?php
/**
 * Template used to display docs content.
 *
 * @package landkit
 */

$post_class = 'wedocs-docs-single col-12 col-md-6 col-lg-4 mb-grid-gutter';
$color      = lk_get_post_featured_icon_bg( get_the_ID() );
$color      = empty( $color ) ? 'primary' : $color;
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
	<a class="card card-border border-<?php echo esc_attr( $color ); ?> shadow-lg mb-6 mb-md-8 lift lift-lg" href="<?php echo esc_url( get_permalink() ); ?>">
		<div class="card-body text-center">
			<?php lk_wedocs_featured_icon( get_the_ID() ); ?>

			<h4 class="font-weight-bold"><?php the_title(); ?></h4>

			<?php if ( ! empty( get_the_excerpt() ) ) : ?>
				<div class="text-gray-700 mb-5 d-box show-lines-2 box-orient-vertical overflow-hidden docs__excerpt"><?php the_excerpt(); ?></div>
			<?php endif; ?>

			<?php
			$pages = get_pages( // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				array(
					'child_of'  => get_the_ID(),
					'post_type' => 'docs',
				)
			);
			$count = count( $pages );
			if ( $count > 0 ) :
				?>
				<span class="badge badge-pill badge-dark-soft"><span class="h6 text-uppercase">
					<?php
					/* translators: %d: entry count */
					echo esc_html( sprintf( _nx( '%d entry', '%d entries', $count, 'front-end', 'landkit' ), $count ) );
					?>
				</span>
			<?php endif; ?>
		</div>
	</a>
</div>
