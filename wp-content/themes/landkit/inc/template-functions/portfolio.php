<?php
/**
 * Template functions related to portfolio
 */

if ( ! function_exists( 'landkit_portfolio_image_display' ) ) :
	/**
	 * Function to display Portfolio Image
	 */
	function landkit_portfolio_image_display() {
		the_post_thumbnail(
			'full',
			[
				'class' => 'card-img-top rounded shadow-light-lg',
				'alt'   => '...',
			]
		);
	}

endif;

if ( ! function_exists( 'landkit_portfolio_category_display' ) ) :
	/**
	 * Function to display Portfolio Category
	 */
	function landkit_portfolio_category_display() { ?>
		<h6 class="text-uppercase mb-1 text-muted"><?php echo wp_strip_all_tags( get_the_term_list( get_the_ID(), 'jetpack-portfolio-type', '', ', ' ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h6>
		<?php
	}

endif;

if ( ! function_exists( 'landkit_portfolio_title_display' ) ) :
	/**
	 * Function to display Portfolio Title
	 */
	function landkit_portfolio_title_display() {
		?>
		<h4 class="mb-0"><?php the_title(); ?></h4>
		<?php
	}

endif;

if ( ! function_exists( 'landkit_loop_portfolio' ) ) :
	/**
	 * Function for Portfolio loop
	 */
	function landkit_loop_portfolio() {
		?>
		<div id="portfolio-<?php the_ID(); ?>" <?php post_class( 'col-12 col-md-4' ); ?>>
		<!-- Card -->
		<a class="card card-flush mb-7" href="<?php echo esc_url( get_permalink() ); ?>">
			<!-- Image -->
			<div class="card-zoom">
				<?php landkit_portfolio_image_display(); ?>
			</div>
			<!-- Footer -->
			<div class="card-footer">
				<!-- Preheading -->
				<?php landkit_portfolio_category_display(); ?>
				<!-- Heading -->
				<?php landkit_portfolio_title_display(); ?>
			</div>
		</a>
	</div>
		<?php
	}

endif;

if ( ! function_exists( 'landkit_section_portfolio_content_start' ) ) :
	/**
	 * Function for Section Portfolio Content Start
	 */
	function landkit_section_portfolio_content_start() {
		?>
		<section class="py-8 py-md-11 mt-n10 mt-md-n14">
			<div class="container">
		<?php
	}
endif;

if ( ! function_exists( 'landkit_portfolio_wrapper_start' ) ) :
	/**
	 * Function for portfolio wrapper start.
	 */
	function landkit_portfolio_wrapper_start() {
		?>
		<div class="row" id="portfolio" data-isotope='{"layoutMode": "fitRows"}'>
		<?php
	}
endif;

if ( ! function_exists( 'landkit_portfolio_wrapper_end' ) ) :
	/**
	 * Function for portfolio wrapper end.
	 */
	function landkit_portfolio_wrapper_end() {
		?>
		</div><!-- /#portfolio -->
		<?php
	}
endif;

if ( ! function_exists( 'landkit_section_portfolio_content_end' ) ) :
	/**
	 * Function for Section Portfolio Content end.
	 */
	function landkit_section_portfolio_content_end() {
		?>
			</div>
		</section>
		<?php
	}
endif;

?>
<?php
if ( ! function_exists( 'landkit_section_portfolio_header' ) ) :
	/**
	 * Function to display portfolio header.
	 */
	function landkit_section_portfolio_header() {
		?>
	<section class="pt-8 pt-md-12 pb-12 pb-md-15">
		<div class="container"> 
			<div class="row justify-content-center">
				<div class="col-12 col-md-10 col-lg-8 text-center">
					<!-- Headin -->
					<h1 class="display-1 font-weight-bold">
							<?php echo esc_html( apply_filters( 'landkit_portfolio_page_title', esc_html__( 'Our Work.', 'landkit' ) ) ); ?>
					</h1>
					<!-- Text -->
					<p class="lead text-secondary mb-4">
							<?php echo esc_html( apply_filters( 'landkit_portfolio_page_subtitle', esc_html__( 'We design & build products, tools, apps, and sites for companies trying to do great things for our planet.', 'landkit' ) ) ); ?>
					</p>
						<?php landkit_portfolio_filters(); ?>
				</div>
			</div> <!-- / .row -->
		</div> <!-- / .container -->
	</section>

		<?php
	}
	endif;

if ( ! function_exists( 'landkit_portfolio_filters' ) ) :
	/**
	 * Output the portfolio category filter.
	 */
	function landkit_portfolio_filters() {

		$portfolio_cats = array();

		while ( have_posts() ) :
			the_post();

			$portfolio_types = get_the_terms( get_the_ID(), 'jetpack-portfolio-type' );
			if ( ! $portfolio_types || is_wp_error( $portfolio_types ) ) {
				$portfolio_types = array();
			}

			$portfolio_types = array_values( $portfolio_types );

			foreach ( array_keys( $portfolio_types ) as $key ) {
				_make_cat_compat( $portfolio_types[ $key ] );
			}

			foreach ( $portfolio_types as $portfolio_type ) {
				$portfolio_cats[ $portfolio_type->slug ] = $portfolio_type->name;
			}

		endwhile;
		?>

		<nav class="nav justify-content-center">
			<a class="badge badge-pill badge-secondary-soft mr-1 mb-1" href="#" data-toggle="pill" data-filter="*" data-target="#portfolio">
				<span class="h6 text-uppercase"><?php echo esc_html__( 'All', 'landkit' ); ?></span>
			</a>
			<?php foreach ( $portfolio_cats as $key => $portfolio_cat ) : ?>
				<a class="badge badge-pill badge-secondary-soft mr-1 mb-1" href="#" data-toggle="pill" data-filter=".jetpack-portfolio-type-<?php echo esc_attr( $key ); ?>" data-target="#portfolio">
					<span class="h6 text-uppercase"><?php echo esc_html( $portfolio_cat ); ?></span>
				</a>
			<?php endforeach; ?>
		</nav>
		<?php
	}
endif;


if ( ! function_exists( 'landkit_portfolio_pagination' ) ) :
	/**
	 * Output the pagination.
	 */
	function landkit_portfolio_pagination() {
		landkit_bootstrap_pagination( null, true, 'justify-content-center' );
	}
endif;


// Portfolio masonry.

if ( ! function_exists( 'landkit_section_portfolio_container' ) ) :
	/**
	 * Portfolio container section
	 */
	function landkit_section_portfolio_container() {
		?>
	<div class="container"> 
		<div class="row justify-content-center">
			<div class="col-12 col-md-10 col-lg-7 text-center">
				<!-- Headin -->
				<h1 class="display-2 font-weight-bold text-white">
						<?php echo esc_html( apply_filters( 'landkit_portfolio_page_title', esc_html__( 'Our Work.', 'landkit' ) ) ); ?>
				</h1>
				<!-- Text -->
				<p class="lead text-white-75 mb-4">
						<?php echo esc_html( apply_filters( 'landkit_portfolio_page_subtitle', esc_html__( 'We design & build products, tools, apps, and sites for companies trying to do great things for our planet.', 'landkit' ) ) ); ?>
				</p>
					<?php landkit_portfolio_filters(); ?>
			</div>
		</div> <!-- / .row -->
	</div> <!-- / .container -->
		<?php
	}
endif;

if ( ! function_exists( 'landkit_section_portfolio_masonry_header' ) ) :
	/**
	 * Function to display portfolio masonry header
	 */
	function landkit_section_portfolio_masonry_header() {
		?>
	<section class="pt-12 pt-md-14 pb-12 pb-md-15 bg-gray-900" style="margin-top: -83px;">
		<?php landkit_section_portfolio_container(); ?>
	</section>

		<?php
	}
	endif;

if ( ! function_exists( 'landkit_portfolio_masonry_image_display' ) ) :
	/**
	 * Function to display Portfolio Image
	 */
	function landkit_portfolio_masonry_image_display() {
		the_post_thumbnail(
			'full',
			[
				'class' => 'card-img',
				'alt'   => '...',
			]
		);
	}

endif;

if ( ! function_exists( 'landkit_portfolio_masonry_wrapper_start' ) ) :
	/**
	 * Function for  Portfolio  masonry wrap
	 */
	function landkit_portfolio_masonry_wrapper_start() {
		?>
		<div class="row" id="portfolio" data-isotope='{"layoutMode": "masonry"}'>
		<?php
	}
endif;

if ( ! function_exists( 'landkit_masonry_loop_portfolio' ) ) :
	/**
	 * Function for masonry Portfolio loop
	 */
	function landkit_masonry_loop_portfolio() {
		?>
		<div id="portfolio-<?php the_ID(); ?>" <?php post_class( 'col-12 col-md-4' ); ?>>
		<!-- Card -->
		<a class="card shadow-light-lg mb-7" href="<?php echo esc_url( get_permalink() ); ?>">
		<!-- Image -->
		<div class="card-zoom">
			<?php landkit_portfolio_masonry_image_display(); ?>
		</div>

		<!-- Overlay -->
		<div class="card-img-overlay card-img-overlay-hover">
			<div class="card-body bg-white">
			<!-- Shape -->
			<div class="shape shape-bottom-100 shape-fluid-x svg-shim text-white">
				<svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 48h2880V0h-720C1442.5 52 720 0 720 0H0v48z" fill="currentColor"/></svg>
			</div>

		<!-- Preheading -->
		<?php landkit_portfolio_category_display(); ?>
		<!-- Heading -->
		<?php landkit_portfolio_title_display(); ?>
		</div>
	</div>
</a>
</div>
		<?php
	}

endif;

// Portfolio grid.
if ( ! function_exists( 'landkit_section_portfolio_grid_header' ) ) :
	/**
	 * Function to display Portfolio section  header
	 */
	function landkit_section_portfolio_grid_header() {
		?>
	<section class="pt-12 pt-md-14 pb-12 pb-md-15 bg-gray-900" style="margin-top: -83px;">
		<?php landkit_section_portfolio_container(); ?>
	</section>

		<?php
	}
endif;

if ( ! function_exists( 'landkit_portfolio_grid_image_display' ) ) :
	/**
	 * Function to display Portfolio  Grid Image
	 */
	function landkit_portfolio_grid_image_display() {
		the_post_thumbnail(
			'full',
			[
				'class' => 'card-img-top',
				'alt'   => '...',
			]
		);
	}

endif;

if ( ! function_exists( 'landkit_grid_loop_portfolio' ) ) :
	/**
	 * Function for grid Portfolio loop
	 */
	function landkit_grid_loop_portfolio() {
		global $wp_query;
		$grid_class = '';
		$grid_num   = $wp_query->current_post;
		$view       = fmod( $grid_num, 8 );
		switch ( $view ) {
			case 0:
				$grid_class .= '';
				break;
			case 1:
			case 4:
				$grid_class .= ' col-md-5';
				break;
			case 2:
			case 3:
				$grid_class .= ' col-md-7';
				break;
			case 5:
			case 6:
			case 7:
					$grid_class .= ' col-md-4';
				break;
		}
		?>
		<div id="portfolio-<?php the_ID(); ?>" <?php post_class( 'col-12' . $grid_class ); ?>>
			<!-- Card -->
			<a class="card lift lift-lg shadow-light-lg mb-7" href="<?php echo esc_url( get_permalink() ); ?>">
				<!-- Image -->
				<?php landkit_portfolio_grid_image_display(); ?>
				<!-- Overlay -->
				<div class="card-body">
					<!-- Shape -->
					<div class="shape shape-bottom-100 shape-fluid-x svg-shim text-white">
						<svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 48h2880V0h-720C1442.5 52 720 0 720 0H0v48z" fill="currentColor"/></svg>
					</div>

					<!-- Preheading -->
					<?php landkit_portfolio_category_display(); ?>
					<!-- Heading -->
					<?php landkit_portfolio_title_display(); ?>
				</div>
			</a>
		</div>
		<?php
	}

endif;

if ( ! function_exists( 'landkit_single_portfolio_footer' ) ) {
	/**
	 * Function for  portfolio footer
	 */
	function landkit_single_portfolio_footer() {
		$before_footer_content = get_theme_mod( 'portfolio_before_footer_content' );

		if ( $before_footer_content ) {
			print( landkit_render_content( $before_footer_content, false ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

/**
 * Hooks for loop portfolio basic
 */
function landkit_add_portfolio_basic_grid_hooks() {
	add_action( 'landkit_loop_portfolio_before', 'landkit_section_portfolio_header', 5 );
	add_action( 'landkit_loop_portfolio_before', 'landkit_section_portfolio_content_start', 10 );
	add_action( 'landkit_loop_portfolio_before', 'landkit_portfolio_wrapper_start', 20 );
	add_action( 'landkit_loop_portfolio', 'landkit_loop_portfolio', 20 );
	add_action( 'landkit_loop_portfolio_after', 'landkit_portfolio_wrapper_end', 10 );
	add_action( 'landkit_loop_portfolio_after', 'landkit_portfolio_pagination', 20 );
	add_action( 'landkit_loop_portfolio_after', 'landkit_section_portfolio_content_end', 30 );
}

/**
 * Hooks for loop portfolio masonry
 */
function landkit_add_portfolio_masonry_hooks() {
	add_action( 'landkit_loop_portfolio_before', 'landkit_section_portfolio_masonry_header', 5 );
	add_action( 'landkit_loop_portfolio_before', 'landkit_section_portfolio_content_start', 10 );
	add_action( 'landkit_loop_portfolio_before', 'landkit_portfolio_masonry_wrapper_start', 20 );
	add_action( 'landkit_loop_portfolio', 'landkit_masonry_loop_portfolio', 20 );
	add_action( 'landkit_loop_portfolio_after', 'landkit_portfolio_wrapper_end', 10 );
	add_action( 'landkit_loop_portfolio_after', 'landkit_portfolio_pagination', 20 );
	add_action( 'landkit_loop_portfolio_after', 'landkit_section_portfolio_content_end', 30 );
}

/**
 * Hooks for loop portfolio grid
 */
function landkit_add_portfolio_grid_rows_hooks() {
	add_action( 'landkit_loop_portfolio_before', 'landkit_section_portfolio_grid_header', 5 );
	add_action( 'landkit_loop_portfolio_before', 'landkit_section_portfolio_content_start', 10 );
	add_action( 'landkit_loop_portfolio_before', 'landkit_portfolio_wrapper_start', 20 );
	add_action( 'landkit_loop_portfolio', 'landkit_grid_loop_portfolio', 20 );
	add_action( 'landkit_loop_portfolio_after', 'landkit_portfolio_wrapper_end', 10 );
	add_action( 'landkit_loop_portfolio_after', 'landkit_portfolio_pagination', 20 );
	add_action( 'landkit_loop_portfolio_after', 'landkit_section_portfolio_content_end', 30 );
}
