<?php

$column_classes = 'col-lg-' . ( 12 / $col ) . ' col-12 col-md-6';

if ( $docs ) : ?>

<div class="wedocs-shortcode-wrap">
	<div class="wedocs-docs-list row">
		<?php
		foreach ( $docs as $main_doc ) :
			$docs_border_top_color = lk_get_post_featured_icon_bg( $main_doc['doc']->ID );
			if ( $docs_border_top_color ) {
				$border_color = $docs_border_top_color;
			} else {
				$border_color = 'primary';
			}
			?>
		<div class="wedocs-docs-single mb-grid-gutter <?php echo esc_attr( $column_classes ); ?>">
			<a class="card card-border border-<?php echo esc_attr( $border_color ); ?> shadow-lg mb-6 mb-md-8 lift lift-lg" href="<?php echo esc_url( get_permalink( $main_doc['doc']->ID ) ); ?>">
				<div class="card-body text-center">
					<?php lk_wedocs_featured_icon( $main_doc['doc']->ID ); ?>

					<h4 class="font-weight-bold"><?php echo esc_html( $main_doc['doc']->post_title ); ?></h4>

					<?php if ( ! empty( $main_doc['doc']->post_excerpt ) ) : ?>
						<p class="text-gray-700 mb-5"><?php echo esc_html( $main_doc['doc']->post_excerpt ); ?></p>
					<?php endif; ?>

					<?php
					$pages = get_pages( // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
						array(
							'child_of'  => $main_doc['doc']->ID,
							'post_type' => 'docs',
						)
					);
					$count = count( $pages );
					if ( 0 !== $count ) {
						?>
						<span class="badge badge-pill badge-dark-soft"><span class="h6 text-uppercase">
							<?php
							/* translators: %d: entry count */
							echo esc_html( sprintf( _nx( '%d entry', '%d entries', $count, 'front-end', 'landkit' ), $count ) );
							?>
						</span>
					<?php } ?>
				</div>
			</a>
		</div>
	<?php endforeach; ?>

	</div>
</div>

	<?php
endif;
