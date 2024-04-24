<?php
/**
 * Landkit Template Functions used in WeDocs Integration
 *
 * @package Landkit/WeDocs
 */

if ( ! function_exists( 'lk_get_post_featured_icon' ) ) {
	/**
	 * Displays Post Featured Icon
	 *
	 * @param object $thepostid . Default is null (none).
	 * @return boolean
	 */
	function lk_get_post_featured_icon( $thepostid = null ) {
		global $post;

		$thepostid = isset( $thepostid ) ? $thepostid : $post->ID;

		$clean_docs_meta_values = get_post_meta( $thepostid, '_docs_options', true );
		$post_featured_icon     = maybe_unserialize( $clean_docs_meta_values );

		$featured_icon = isset( $post_featured_icon['post_featured_icon'] ) && ! empty( $post_featured_icon['post_featured_icon'] ) ? $post_featured_icon['post_featured_icon'] : false;

		return $featured_icon;
	}
}

if ( ! function_exists( 'lk_get_post_featured_icon_bg' ) ) {
	/**
	 * Displays Post Featured Icon
	 *
	 * @param object $thepostid . Default is null (none).
	 * @return boolean
	 */
	function lk_get_post_featured_icon_bg( $thepostid = null ) {
		global $post;

		$thepostid = isset( $thepostid ) ? $thepostid : $post->ID;

		$clean_docs_meta_values = get_post_meta( $thepostid, '_docs_options', true );
		$post_featured_icon     = maybe_unserialize( $clean_docs_meta_values );

		$featured_icon_bg = isset( $post_featured_icon['post_featured_icon_bg'] ) && ! empty( $post_featured_icon['post_featured_icon_bg'] ) ? $post_featured_icon['post_featured_icon_bg'] : false;

		return $featured_icon_bg;
	}
}

if ( ! function_exists( 'lk_wedocs_featured_icon' ) ) :
	/**
	 * Displays Docs Featured Icon
	 *
	 * @param object $thepostid . Default is null (none).
	 * @param string $wrap_css . css class for icon wrap.
	 */
	function lk_wedocs_featured_icon( $thepostid = null, $wrap_css = 'mb-5' ) {
		$featured_icon    = lk_get_post_featured_icon( $thepostid );
		$featured_icon_bg = lk_get_post_featured_icon_bg( $thepostid );

		if ( $featured_icon ) {
			$icon    = $featured_icon;
			$icon_bg = $featured_icon_bg;
		} else {
			$icon    = 'fe fe-users';
			$icon_bg = 'primary';
		}

		$icon_wrap_css = 'icon-circle text-white bg-' . $icon_bg;

		if ( ! empty( $wrap_css ) ) {
			$icon_wrap_css .= ' ' . $wrap_css;
		}

		?><div class="<?php echo esc_attr( $icon_wrap_css ); ?>">
			<i class="<?php echo esc_attr( $icon ); ?>"></i>
		</div>
		<?php
	}
endif;

/**
 * Displays Docs Meta
 *
 * @param boolean $merge_default .
 */
function landkit_get_docs_meta( $merge_default = true ) {
	global $post;

	if ( isset( $post->ID ) ) {

		$clean_docs_options = get_post_meta( $post->ID, '_docs_options', true );

		$docs_options = maybe_unserialize( $clean_docs_options );

		if ( ! is_array( $docs_options ) ) {
			$docs_options = json_decode( $clean_docs_options, true );
		}

		$docs = $docs_options;

		return apply_filters( 'landkit_docs_meta', $docs, $post );
	}
}

if ( ! function_exists( 'landkit_wedocs_docs_entry_header' ) ) :
	/**
	 * Displays docs header
	 */
	function landkit_wedocs_docs_entry_header() {
		global $post;
		$thepostid = isset( $thepostid ) ? $thepostid : $post->ID;
		?>

	<div class="row mb-6 mb-md-8">
		<div class="col-auto">
			<?php lk_wedocs_featured_icon( $thepostid, '' ); ?>
		</div>

		<div class="col ml-n4">
			<?php the_title( '<h2 class="font-weight-bold mb-0">', '</h2>' ); ?>
			<div class="font-size-lg text-gray-700"><?php echo wp_strip_all_tags( get_the_excerpt() ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		</div>
	</div>
		<?php
	}

endif;

if ( ! function_exists( 'landkit_wedocs_docs_entry_content' ) ) :
	/**
	 * Displays docs content
	 */
	function landkit_wedocs_docs_entry_content() {
		global $post;
		?>
	<div class="entry-content" itemprop="articleBody">
		<?php
			the_content(
				sprintf(
				/* translators: %s: Name of current post. */
					wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'landkit' ), array( 'span' => array( 'class' => array() ) ) ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				)
			);

			landkit_wedocs_child_pages();

		?>
	</div>
		<?php
	}

endif;

if ( ! function_exists( 'landkit_wedocs_print_date' ) ) {
	/**
	 * Displays docs print date
	 *
	 * @param object $post_id post id.
	 */
	function landkit_wedocs_print_date( $post_id ) {
		?>
		<meta itemprop="datePublished" content="<?php echo get_the_time( 'c', $post_id ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"/>
		<time itemprop="dateModified" datetime="<?php echo esc_attr( get_the_modified_date( 'c', $post_id ) ); ?>">
			<?php
			/* translators: %s - time */
			printf( esc_html__( 'Updated %s', 'landkit' ), landkit_human_time_diff( get_the_modified_time( 'U', $post_id ) ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</time>
		<?php
	}
}

if ( ! function_exists( 'landkit_wedocs_child_pages' ) ) :
	/**
	 * List all child pages for this topic
	 *
	 * @param array $args An array of arguments.
	 * @param array $post posts.
	 */
	function landkit_wedocs_child_pages( $args = '', $post = null ) {

		if ( is_null( $post ) ) {
			global $post;
		}

		$defaults = apply_filters(
			'landkit_wedocs_child_pages_args',
			array(
				'depth'        => 1,
				'echo'         => false,
				'child_of'     => $post->ID,
				'parent'       => $post->ID,
				'hierarchical' => 0,
				'sort_column'  => 'menu_order',
				'sort_order'   => 'asc',
				'post_type'    => $post->post_type,

			)
		);
		$args = wp_parse_args( $args, $defaults );

		$mypages = get_pages( $args );

		?>
	<div class="single-docs">
		<?php
		foreach ( $mypages as $page ) :
			$child_args = apply_filters(
				'landkit_wedocs_grand_child_pages_args',
				array(
					'sort_order'  => 'ASC',
					'sort_column' => 'menu_order',
					'child_of'    => $page->ID,
					'post_type'   => $post->post_type,
				)
			);

			$child_list = get_pages( $child_args );

			$total_topics = count( $child_list );

			?>

			<?php if ( empty( $child_list ) ) : ?>
				<div class="card shadow-light-lg accordion mb-5 mb-md-6">
					<div class="list-group">
						<div class="list-group-item">
							<a class="d-flex align-items-center text-reset text-decoration-none" data-toggle="collapse" href="#page-item-<?php echo esc_attr( $page->ID ); ?>" role="button" aria-expanded="false" aria-controls="page-item-<?php echo esc_attr( $page->ID ); ?>">
								<span class="mr-4">
									<?php echo esc_html( $page->post_title ); ?>
								</span>
								<div class="text-muted ml-auto">
									<span class="font-size-sm mr-4 d-none d-md-inline">
										<?php landkit_wedocs_print_date( $page->ID ); ?>
									</span>

									<span class="collapse-chevron text-muted">
										<i class="fe fe-lg fe-chevron-down"></i>
									</span>
								</div>
							</a>
							<div class="collapse" id="page-item-<?php echo esc_attr( $page->ID ); ?>" data-parent="#page-item-<?php echo esc_attr( $page->ID ); ?>">
								<div class="py-5">
									<p class="text-gray-700"><?php echo wp_strip_all_tags( $page->post_content ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
									<?php lk_wedocs_display_helpful_feedback(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php else : ?>
				<div class="card shadow-light-lg accordion mb-5 mb-md-6">
					<div class="list-group">
						<div class="list-group-item">
							<div class="d-flex align-items-center">
								<div class="mr-auto">
									<h4 class="font-weight-bold mb-0"><a class="text-dark" href="<?php echo get_permalink( $page->ID ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
										<?php echo esc_html( $page->post_title ); ?>
									</a></h4>
									<p class="font-size-sm text-muted mb-0"><?php echo esc_html( $page->post_excerpt ); ?></p>
								</div>

								<?php if ( 0 !== $total_topics ) : ?>
									<span class="badge badge-pill badge-success-soft ml-4">
										<span class="h6 text-uppercase">
											<?php
											/* translators: %s - number of topics */
											echo sprintf( _n( '%s Answer', '%s Answers', $total_topics, 'landkit' ), $total_topics ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											?>
										</span>
									</span>
								<?php endif; ?>
							</div>
						</div>

						<?php foreach ( $child_list as $child ) : ?>
				   
							<div class="list-group-item">
								<a class="d-flex align-items-center text-reset text-decoration-none" data-toggle="collapse" href="#page-item-<?php echo esc_attr( $child->ID ); ?>" role="button" aria-expanded="false" aria-controls="page-item-<?php echo esc_attr( $child->ID ); ?>">
									<span class="mr-4">
										<?php echo esc_html( $child->post_title ); ?>
									</span>
									<div class="text-muted ml-auto">
										<span class="font-size-sm mr-4 d-none d-md-inline">
											<?php landkit_wedocs_print_date( $child->ID ); ?>
										</span>
										<span class="collapse-chevron text-muted">
											<i class="fe fe-lg fe-chevron-down"></i>
										</span>
									</div>

								</a>
								<div class="collapse" id="page-item-<?php echo esc_attr( $child->ID ); ?>" data-parent="#page-item-<?php echo esc_attr( $child->ID ); ?>">
									<div class="py-5">
										<p class="text-gray-700">
											<?php echo wp_strip_all_tags( $child->post_content ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> 
										</p>
										<?php lk_wedocs_display_helpful_feedback(); ?>
									</div>
								</div>
							</div>

						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
		<?php

	}
endif;

if ( ! function_exists( 'landkit_wedocs_related_articles' ) ) :
	/**
	 * Displays Related Docs
	 */
	function landkit_wedocs_related_articles() {
		if ( apply_filters( 'landkit_enable_related_articles', filter_var( get_theme_mod( 'enable_related_articles', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) ) {
			$article_title       = apply_filters( 'landkit_article_title', get_theme_mod( 'related_article_title', 'Related Help Center Categories' ) );
			$article_description = apply_filters( 'landkit_article_description', get_theme_mod( 'related_article_description', 'If you didn’t find what you needed, these could help!' ) );

			global $post;

			$orig_post = $post;

			$related_articles_number = apply_filters( 'landkit_wedocs_related_articles_number', 3 );

			$tags = wp_get_post_terms( $post->ID, 'doc_tag' );

			if ( $tags ) {
				$tag_ids = array();

				foreach ( $tags as $tag ) {
					$tag_ids[] = $tag->term_id;
				}

				$related_articles_query_args = apply_filters(
					'landkit_wedocs_related_articles_query_args',
					array(
						'tax_query'           => array( //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
							array(
								'taxonomy' => 'doc_tag',
								'terms'    => $tag_ids,
							),
						),
						'post__not_in'        => array( $post->ID ),
						'posts_per_page'      => $related_articles_number, // Number of related posts that will be shown.
						'ignore_sticky_posts' => 1,
						'post_type'           => $post->post_type,
					),
					'tags',
					$tag_ids
				);

			} else {

				$related_articles_query_args = apply_filters(
					'landkit_wedocs_related_articles_query_args',
					array(
						'post__not_in'        => array( $post->ID ),
						'posts_per_page'      => $related_articles_number, // Number of related posts that will be shown.
						'ignore_sticky_posts' => 1,
						'post_type'           => $post->post_type,
					)
				);

				if ( $post->post_parent ) {
					$related_articles_query_args['post_parent'] = $post->post_parent;
				} else {
					$related_articles_query_args['post_parent'] = $post->ID;
				}
			}

			$related_articles_query = new WP_Query( $related_articles_query_args );

			if ( $related_articles_query->have_posts() ) {
				?>
			<hr class="border-gray-300 my-6 my-md-8">
				<?php if ( ! empty( $article_title ) ) : ?>
					<h3 class="font-weight-bold"><?php echo esc_html( $article_title ); ?></h3>
				<?php endif; ?>
				<?php if ( apply_filters( 'landkit_related_article_tagline', true ) && ! empty( $article_description ) ) : ?>
					<p class="text-muted mb-6 mb-md-8"><?php echo wp_kses_post( $article_description ); ?></p>
				<?php endif ?>

			<div class="row mb-n6 mb-md-n8">
				<?php
				while ( $related_articles_query->have_posts() ) :
					$related_articles_query->the_post();
					$docs_border_top_color = lk_get_post_featured_icon_bg( $post->ID );
					if ( $docs_border_top_color ) {
						$border_color = $docs_border_top_color;
					} else {
						$border_color = 'primary';
					}
					?>
					<div class="col-12 col-md-6 col-lg-4">
						<a class="card card-border border-<?php echo esc_attr( $border_color ); ?> shadow-lg mb-6 mb-md-8 lift lift-lg" href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
							<div class="card-body text-center">
								<?php lk_wedocs_featured_icon( $post->ID, 'mb-5' ); ?>

								<h4 class="font-weight-bold"><?php echo esc_html( $post->post_title ); ?></h4>

								<?php if ( ! empty( $post->post_excerpt ) ) : ?>
								<p class="text-gray-700 mb-5"><?php echo esc_html( $post->post_excerpt ); ?></p>
								<?php endif; ?>

								<?php
								$pages = get_pages(
									array(
										'child_of'  => $post->ID,
										'post_type' => 'docs',
									)
								);
								$count = count( $pages );
								if ( 0 !== $count ) {
									?>
									<span class="badge badge-pill badge-dark-soft"><span class="h6 text-uppercase">
										<?php
										/* translators: %d - number of entries */
										echo esc_html( sprintf( _nx( '%d entry', '%d entries', $count, 'front-end', 'landkit' ), $count ) );
										?>
									</span>
							<?php } ?>
							</div>
						</a>
					</div>
				<?php endwhile; ?>
			</div>
				<?php
			}

			$post = $orig_post; // //phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			wp_reset_postdata();
		}
	}

endif;

if ( ! function_exists( 'landkit_custom_excerpt_length' ) ) {
	/**
	 * Docs Excerpt Length
	 *
	 *  @param int $length wedocs excerpt length.
	 */
	function landkit_custom_excerpt_length( $length ) {
		$length = 10;
		return apply_filters( 'landkit_excerpt_length', $length );
	}
}

if ( ! function_exists( 'lk_wedocs_display_helpful_feedback' ) ) :
	/**
	 * Displays Helpful Feedback Links
	 *
	 * @since 1.0.0
	 */
	function lk_wedocs_display_helpful_feedback() {
		if ( wedocs_get_option( 'helpful', 'wedocs_settings', 'on' ) === 'on' ) :
			wedocs_get_template_part( 'content', 'feedback' );
		endif;
	}
endif;


if ( ! function_exists( 'landkit_wedocs_before_footer' ) ) {
	/**
	 * Display Before footer Content
	 */
	function landkit_wedocs_before_footer() {
		$before_footer_content = get_theme_mod( 'article_before_footer_content' );

		if ( $before_footer_content ) {
			print( landkit_render_content( $before_footer_content, false ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}
