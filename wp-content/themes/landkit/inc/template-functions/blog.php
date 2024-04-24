<?php
/**
 * Template Functions related to Blog
 */

if ( ! function_exists( 'landkit_archive_header' ) ) {
	/**
	 * Displays Archive Header
	 *
	 * @return void
	 */
	function landkit_archive_header() {

		if ( is_search() ) {
			landkit_display_breadcrumb();
		} else {
			if ( is_home() && is_front_page() ) {
				$title = esc_html__( 'Blog', 'landkit' );
				$desc  = '';
				$thumb = '';
			} elseif ( is_home() ) {
				$blog_page_id = get_option( 'page_for_posts', true );
				$title        = get_the_title( $blog_page_id );
				$desc         = '';
				$thumb        = get_the_post_thumbnail_url( $blog_page_id );
			} else {
				$title = get_the_archive_title();
				$desc  = get_the_archive_description();
				$thumb = '';
			}

			landkit_content_header( $title, $desc, $thumb );
		}
	}
}

if ( ! function_exists( 'landkit_display_breadcrumb' ) ) {
	/**
	 * Displays Breadcrumb
	 */
	function landkit_display_breadcrumb() {
		?><nav class="bg-gray-200 mb-6">
			<div class="container">
				<div class="row">
					<div class="col-12"><?php landkit_breadcrumb(); ?></div>
				</div> <!-- / .row -->
			</div> <!-- / .container -->
		</nav>
		<?php
	}
}

if ( ! function_exists( 'landkit_blog_search' ) ) {
	/**
	 * Displays Blog Search Form
	 *
	 * @return void
	 */
	function landkit_blog_search() {
		?>
		<!-- SEARCH
		================================================== -->
		<div class="
		<?php
		if ( ! is_search() ) :
			?>
			mt-n6<?php endif; ?>">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<?php get_search_form(); ?>
						<?php landkit_popular_tags(); ?>
					</div>
				</div> <!-- / .row -->
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'landkit_result_count' ) ) {
	/**
	 * Search Results
	 */
	function landkit_result_count() {
		if ( is_search() ) :
			global $wp_query;

			$total       = $wp_query->found_posts;
			$total_pages = $wp_query->max_num_pages;
			$per_page    = $wp_query->get( 'posts_per_page' );
			$current     = max( 1, $wp_query->get( 'paged', 1 ) );

			if ( 0 === $total ) {
				return;
			}

			?>
		<span class="h6 text-uppercase text-muted d-none d-md-block mb-0 mr-5">
			<?php
			// phpcs:disable WordPress.Security
			if ( 1 === intval( $total ) ) {
				esc_html_e( 'Showing the single result', 'landkit' );
			} elseif ( $total <= $per_page || -1 === $per_page ) {
				/* translators: %d: total results */
				printf( _n( 'Showing all %d result', 'Showing all %d results', $total, 'landkit' ), $total );
			} else {
				$first = ( $per_page * $current ) - $per_page + 1;
				$last  = min( $total, $per_page * $current );
				/* translators: 1: first result 2: last result 3: total results */
				printf( _nx( 'Showing %1$d&ndash;%2$d of %3$d result', 'Showing %1$d&ndash;%2$d of %3$d results', $total, 'with first and last result', 'landkit' ), $first, $last, $total );
			}
			// phpcs:enable WordPress.Security
			?>
		</span>
			<?php
		endif;
	}
}

if ( ! function_exists( 'landkit_popular_tags' ) ) {
	/**
	 * Displays popular tags as badges
	 *
	 * @param int $no_of_tags Tags count.
	 * @return void
	 */
	function landkit_popular_tags( $no_of_tags = 4 ) {

		if ( is_search() ) {
			return;
		}
		$args         = apply_filters(
			'landkit_popular_tags_args',
			array(
				'orderby' => 'count',
				'order'   => 'desc',
				'number'  => $no_of_tags,
			)
		);
		$popular_tags = get_tags( $args );
		if ( empty( $popular_tags ) ) {
			return;
		}
		?>
		<!-- Badges -->
		<div class="row align-items-center">
			<div class="col-auto">
				<!-- Heading -->
				<h6 class="font-weight-bold text-uppercase text-muted mb-0"><?php echo esc_html__( 'Tags:', 'landkit' ); ?></h6>
			</div>
			<div class="col ml-n5">
				<!-- Badges -->
				<?php foreach ( $popular_tags as $tag ) : ?>
				<a class="badge badge-pill badge-secondary-soft" href="<?php echo esc_url( get_term_link( $tag ) ); ?>">
					<span class="h6 text-uppercase"><?php echo esc_html( $tag->name ); ?></span>
				</a>
				<?php endforeach; ?>
			</div>
		</div> <!-- / .row -->
		<?php
	}
}

if ( ! function_exists( 'landkit_loop_post_wrap_start' ) ) {
	/**
	 * Wraps Posts Loop
	 *
	 * @return void
	 */
	function landkit_loop_post_wrap_start() {
		global $wp_query;

		if ( is_search() ) {
			$section_class = 'py-6';
		} else {
			if ( $wp_query->max_num_pages <= 1 ) {
				$section_class = 'py-6 py-md-9';
			} else {
				$section_class = 'pt-6 pt-md-9';
			}
		}
		?>
		<section class="blog-posts <?php echo esc_attr( $section_class ); ?>">
			<div class="container">
				<div class="row mb-n6">
				<?php
	}
}

if ( ! function_exists( 'landkit_loop_post_card_thumbnail' ) ) {
	/**
	 * Displays Post thumbnail in Card
	 *
	 * @return void
	 */
	function landkit_loop_post_card_thumbnail() {
		if ( ! has_post_thumbnail() ) {
			return;
		}

		?>
		<a href="<?php the_permalink(); ?>" class="card-img-top">

			<?php the_post_thumbnail( 'post-thumbnail', array( 'class' => 'card-img-top' ) ); ?>

			<div class="position-relative">
				<div class="shape shape-bottom shape-fluid-x svg-shim text-white">
					<?php require get_theme_file_path( 'assets/img/shapes/curves/curve-3.svg' ); ?>
				</div>
			</div>
		</a>
		<?php
	}
}

if ( ! function_exists( 'landkit_featured_badge' ) ) {
	/**
	 * Displays Featured Badge
	 *
	 * @return void
	 */
	function landkit_featured_badge() {
		?>
		<div class="col-12">
			<span class="badge badge-pill 
			<?php
			if ( has_post_thumbnail() ) :
				?>
				badge-light
				<?php
else :
	?>
				badge-secondary-soft<?php endif; ?> badge-float badge-float-inside">
				<span class="h6 text-uppercase"><?php echo esc_html__( 'Featured', 'landkit' ); ?></span>
			</span>
		</div>
		<?php
	}
}

if ( ! function_exists( 'landkit_sticky_post_thumbnail' ) ) {
	/**
	 * Displays Sticky Post Thumbnail
	 *
	 * @return void
	 */
	function landkit_sticky_post_thumbnail() {
		if ( ! has_post_thumbnail() ) {
			return;
		}
		$post_thumbnail_url = get_the_post_thumbnail_url();
		?>
		<a class="col-12 col-md-6 order-md-2 bg-cover card-img-right"  href="<?php the_permalink(); ?>"
			<?php
			if ( $post_thumbnail_url ) :
				?>
			style="background-image: url(<?php echo esc_url( $post_thumbnail_url ); ?>);"<?php endif; ?>>

			<?php the_post_thumbnail( 'post-thumbnail', array( 'class' => 'img-fluid d-md-none invisible' ) ); ?>
			<!-- Shape -->
			<div class="shape shape-left shape-fluid-y svg-shim text-white d-none d-md-block">
				<svg viewBox="0 0 112 690" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h62.759v172C38.62 384 112 517 112 517v173H0V0z" fill="currentColor"></path></svg>
			</div>

		</a>
		<?php
	}
}

if ( ! function_exists( 'landkit_sticky_post_card_body_wrap_start' ) ) {
	/**
	 * Wraps Sticky Post card body
	 *
	 * @return void
	 */
	function landkit_sticky_post_card_body_wrap_start() {
		?>
		<div class="col-12
		<?php
		if ( has_post_thumbnail() ) :
			?>
			col-md-6 order-md-1<?php endif; ?>">
		<?php
	}
}

if ( ! function_exists( 'landkit_loop_post_card_body' ) ) {
	/**
	 * Displays Card Body in Loop Posts
	 *
	 * @return void
	 */
	function landkit_loop_post_card_body() {
		?>
		<a class="card-body
		<?php
		if ( ! has_post_thumbnail() ) :
			?>
			my-auto<?php endif; ?>" href="<?php the_permalink(); ?>">
			<?php the_title( '<h3 class="post__title overflow-hidden d-box box-orient-vertical">', '</h3>' ); ?>
			<div class="post__excerpt text-muted overflow-hidden">
				<?php the_excerpt(); ?>
			</div>
		</a>
		<?php
	}
}

if ( ! function_exists( 'landkit_loop_post_card_meta' ) ) {
	/**
	 * Displays Card Meta in Loop Posts
	 *
	 * @return void
	 */
	function landkit_loop_post_card_meta() {
		// Posted on.
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published sr-only" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$date_format = function_exists( 'Landkit_Extensions' ) ? apply_filters( 'landkit_posts_date_format', 'M d' ) : '';

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date( $date_format ) ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date( $date_format ) )
		);

		?>
		<a class="card-meta mt-auto" href="<?php the_permalink(); ?>">
			<hr class="card-meta-divider">
			<div class="avatar avatar-sm mr-2">
				<?php echo wp_kses_post( get_avatar( get_the_author_meta( 'user_email' ), 24, '', '', array( 'class' => 'avatar-img rounded-circle' ) ) ); ?>
			</div>
			<h6 class="text-uppercase text-muted mr-2 mb-0">
				<?php the_author(); ?>
			</h6>
			<?php if ( class_exists( 'Landkit_Extensions' ) ) : ?>
			<p class="h6 text-uppercase text-muted mb-0 ml-auto">
				<?php
				echo wp_kses(
					$time_string,
					array(
						'time' => array(
							'datetime' => array(),
							'class'    => array(),
						),
					)
				);
				?>
			</p>
			<?php endif; ?>
		</a>
		<?php
	}
}

if ( ! function_exists( 'landkit_sticky_post_card_body_wrap_end' ) ) {
	/**
	 * Wraps Sticky Post card body endi
	 *
	 * @return void
	 */
	function landkit_sticky_post_card_body_wrap_end() {
		?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'landkit_loop_post_wrap_end' ) ) {
	/**
	 * Wraps Posts Loop End
	 *
	 * @return void
	 */
	function landkit_loop_post_wrap_end() {
		?>
			</div><!-- /.row -->
		</div><!-- /.container -->
	</section>
		<?php
	}
}

if ( ! function_exists( 'landkit_paging_nav' ) ) {
	/**
	 * Pagination for Posts
	 *
	 * @param string $ul_class css class for pagination wrap.
	 * @return void
	 */
	function landkit_paging_nav( $ul_class ) {
		global $wp_query;
		if ( $wp_query->max_num_pages <= 1 ) {
			return;
		}
		$ul_class = empty( $ul_class ) ? 'pagination-sm justify-content-center' : $ul_class;
		?>
		<section class="py-7 py-md-10">
			<div class="container">
				<?php landkit_bootstrap_pagination( $wp_query, true, $ul_class, 'page-link-transparent' ); ?>
			</div>
		</section>
		<?php
	}
}

if ( ! function_exists( 'landkit_archive_footer' ) ) {
	/**
	 * Displays content before Footer
	 */
	function landkit_archive_footer() {
		$before_footer_content = get_theme_mod( 'blog_before_footer_content' );

		if ( $before_footer_content ) {
			print( landkit_render_content( $before_footer_content, false ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}


if ( ! function_exists( 'landkit_comments_navigation' ) ) {
	/**
	 * Displays comments navigation
	 */
	function landkit_comments_navigation() {
		if ( absint( get_comment_pages_count() ) === 1 ) {
			return;
		}

		/* translators: label for link to the previous comments page */
		$prev_text = esc_html__( 'Older comments', 'landkit' );
		$prev_link = get_previous_comments_link( '<i class="fe fe-arrow-left mr-2"></i>' . $prev_text );

		/* translators: label for link to the next comments page */
		$next_text = esc_html__( 'Newer comments', 'landkit' );
		$next_link = get_next_comments_link( $next_text . '<i class="fe fe-arrow-right ml-2"></i>' );

		?>
		<nav class="navigation comment-navigation d-flex justify-content-between my-6" role="navigation">
			<h3 class="screen-reader-text sr-only">
			<?php
			/* translators: navigation through comments */
			echo esc_html__( 'Comment navigation', 'landkit' );
			?>
			</h3>
			<?php if ( $prev_link ) : ?>
				<?php echo str_replace( '<a ', '<a class="text-decoration-none font-weight-bold" ', $prev_link ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> 
			<?php endif; ?>
			<?php if ( $next_link ) : ?>
				<?php echo str_replace( '<a ', '<a class="text-decoration-none ml-auto font-weight-bold" ', $next_link ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php endif; ?>
		</nav>
		<?php
	}
}
