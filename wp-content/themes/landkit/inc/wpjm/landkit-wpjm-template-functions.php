<?php

if ( ! function_exists( 'landkit_wpjm_job_listing_header_start' ) ) :
	/**
	 * Function to display single job listing starting header
	 */
	function landkit_wpjm_job_listing_header_start() { ?>
		<div class="row align-items-center">
		<?php
	}

endif;

if ( ! function_exists( 'landkit_wpjm_job_listing_all_listings' ) ) :
	/**
	 * Function to display single job all listing text
	 */
	function landkit_wpjm_job_listing_all_listings() {
		$jobs_page_id = get_option( 'job_manager_jobs_page_id', false );

		if ( ! $jobs_page_id ) {
			return;
		}

		?>
		<a href="<?php echo esc_url( get_permalink( $jobs_page_id ) ); ?>" class="font-weight-bold font-size-sm text-decoration-none mb-3">
			<i class="fe fe-arrow-left mr-3"></i> <?php echo esc_html__( 'All listings', 'landkit' ); ?>
		</a>
		<?php
	}

endif;

if ( ! function_exists( 'landkit_wpjm_job_listing_title' ) ) :
	/**
	 * Function to display single job listing title
	 */
	function landkit_wpjm_job_listing_title() {
		?>
		<h1 class="display-4 mb-2">
		<?php echo esc_html( wpjm_the_job_title() ); ?>
		</h1>
		<?php
	}

endif;

if ( ! function_exists( 'landkit_wpjm_job_listing_location' ) ) :
	/**
	 * Function to display single job listing location
	 *
	 * @param object $post Post object.
	 */
	function landkit_wpjm_job_listing_location( $post = null ) {
		echo esc_html( the_job_location( false ) );
	}

endif;

if ( ! function_exists( 'landkit_wpjm_job_listing_type' ) ) :
	/**
	 * Function to display single job listing type
	 */
	function landkit_wpjm_job_listing_type() {
		if ( get_option( 'job_manager_enable_types' ) ) {
			$types = wpjm_get_the_job_types();
			if ( ! empty( $types ) ) :
				foreach ( $types as $type ) :
					echo ' · ' . esc_html( $type->name );
			endforeach;
		endif;
		}
	}

endif;

if ( ! function_exists( 'landkit_wpjm_job_listing_meta' ) ) :
	/**
	 * Function to display single job listing location and type
	 */
	function landkit_wpjm_job_listing_meta() {
		job_listing_meta_display();
	}

endif;

if ( ! function_exists( 'landkit_wpjm_job_listing_title_area' ) ) :
	/**
	 * Function to display single job listing title area
	 */
	function landkit_wpjm_job_listing_title_area() {
		?>
		<div class="col-12 col-md">
			<?php
				landkit_wpjm_job_listing_all_listings();
				landkit_wpjm_job_listing_title();
				landkit_wpjm_job_listing_meta();
			?>
		</div>
		<?php
	}

endif;

if ( ! function_exists( 'landkit_wpjm_job_listing_refer_btn' ) ) :
	/**
	 * Function to display single job listing refer button
	 */
	function landkit_wpjm_job_listing_refer_btn() {
		?>
		<a href="#shareListing" data-toggle="collapse" aria-expanded="false" aria-controls="shareListing" class="btn btn-primary-soft mr-1"><?php echo esc_html( apply_filters( 'landkit_wpjm_refer_text', __( 'Refer a friend', 'landkit' ) ) ); ?>
		</a>
		<?php
	}

endif;

if ( ! function_exists( 'landkit_wpjm_job_listing_apply_btn' ) ) :
	/**
	 * Function to display single job listing apply button
	 */
	function landkit_wpjm_job_listing_apply_btn() {
		?>
		<a href="#apply-job" data-toggle="smooth-scroll" class="application_button btn btn-primary"><?php echo esc_html__( 'Apply', 'landkit' ); ?></a>
		<?php
	}

endif;

if ( ! function_exists( 'landkit_wpjm_job_listing_action_btn' ) ) :
	/**
	 * Function to display single job listing action button
	 */
	function landkit_wpjm_job_listing_action_btn() {
		?>
		<div class="col-auto job_application application">
		<?php
			landkit_wpjm_job_listing_refer_btn();
			landkit_wpjm_job_listing_apply_btn();
		?>
			<div class="collapse mt-4 position-absolute" id="shareListing">
				<?php landkit_share_display(); ?>
			</div>
		</div>
		<?php
	}

endif;

if ( ! function_exists( 'landkit_wpjm_job_listing_header_end' ) ) :
	/**
	 * Function to display single job listing ending header
	 */
	function landkit_wpjm_job_listing_header_end() {
		?>
		</div>
		<?php
	}

endif;

if ( ! function_exists( 'landkit_wpjm_job_listing_divider' ) ) :
	/**
	 * Function to display divider in single job listing page
	 */
	function landkit_wpjm_job_listing_divider() {
		?>
		<div class="row">
			<div class="col-12">
				<!-- Divider -->
				<hr class="my-6 my-md-8 border-gray-300">
			</div>
		</div> <!-- / .row -->
		<?php
	}

endif;

if ( ! function_exists( 'landkit_wpjm_job_listing_content_start' ) ) :
	/**
	 * Function to display single job listing content start
	 */
	function landkit_wpjm_job_listing_content_start() {
		?>
	<div class="row">
		<?php if ( landkit_wpjm_has_single_job_sidebar() ) : ?>
		<div class="col-12 col-md-8">
		<?php else : ?>
		<div class="col-12">
		<?php endif; ?>
		<?php
	}

endif;

if ( ! function_exists( 'landkit_wpjm_job_listing_description' ) ) :
	/**
	 * Function to display single job listing description
	 *
	 * @param obj $post Post object.
	 */
	function landkit_wpjm_job_listing_description( $post = null ) {
		$description = wpjm_get_the_job_description();
		?>
		<div class="single-job_listing__desc"><?php the_content(); ?></div>
		<?php
	}

endif;

if ( ! function_exists( 'landkit_wpjm_job_listing_content_end' ) ) :
	/**
	 * Function to display single job listing content end
	 */
	function landkit_wpjm_job_listing_content_end() {
		?>
		</div>
		<?php
	}

endif;


if ( ! function_exists( 'landkit_wpjm_job_listing_sidebar' ) ) :
	/**
	 * Function to display single job listing sidebar
	 */
	function landkit_wpjm_job_listing_sidebar() {
		if ( landkit_wpjm_has_single_job_sidebar() ) :
			?>
		<div class="col-12 col-md-4">
			<?php
				landkit_wpjm_sidebar_company();
				dynamic_sidebar( 'sidebar-single-job-listing' );
			?>
		</div>
		<?php endif; ?>
	</div><!-- /.row -->
		<?php
	}

endif;

if ( ! function_exists( 'landkit_wpjm_sidebar_company' ) ) {
	/**
	 * Displays Company Info in Sidebar
	 */
	function landkit_wpjm_sidebar_company() {
		$company_name = the_company_name( '', '', false );
		if ( apply_filters( 'landkit_wpjm_sidebar_company_enable', true ) && ! empty( $company_name ) ) {
			?>
			<div class="card shadow-light-lg mb-5">
				<div class="card-body text-gray-800">
					<?php landkit_wpjm_company_display_sidebar(); ?>
				</div>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'landkit_wpjm_company_display_sidebar' ) ) {
	/**
	 * Display Company Sidebar
	 */
	function landkit_wpjm_company_display_sidebar() {
		?>
		<h4><?php echo esc_html__( 'Company Details', 'landkit' ); ?></h4>
		<?php

		ob_start();
		the_company_logo( 'full' );
		$logo = ob_get_clean();
		print( str_replace( '"company_logo"', '"mt-5 company_logo img-fluid d-block mx-auto"', $logo ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$company_name = the_company_name( '', '', false );
		$tagline      = the_company_tagline( '', '', false );
		$twitter      = the_company_twitter( '', '', false );
		$website      = get_the_company_website();

		if ( $company_name ) :
			landkit_wpjm_single_job_sidebar_info( esc_html__( 'Name', 'landkit' ), $company_name );
		endif;

		if ( $tagline ) :
			landkit_wpjm_single_job_sidebar_info( esc_html__( 'Tagline', 'landkit' ), $tagline );
		endif;

		if ( $website ) :
			$website = '<a class="website text-reset" href="' . esc_url( $website ) . ' " rel="nofollow">' . esc_html( $website ) . '</a>';
			landkit_wpjm_single_job_sidebar_info( esc_html__( 'Website', 'landkit' ), $website );
		endif;

		if ( $twitter ) :
			$twitter = str_replace( '"company_twitter"', '"company_twitter text-reset"', $twitter );
			landkit_wpjm_single_job_sidebar_info( esc_html__( 'Twitter', 'landkit' ), $twitter );
		endif;

		ob_start();
		the_company_video();
		$video = ob_get_clean();
		print( str_replace( '"company_video"', '"mt-5 company_video embed-responsive embed-responsive-4by3"', $video ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Job listing form
 */
function landkit_wpjm_job_listing_form() {
	if ( candidates_can_apply() ) :
		?>
		<div class="pt-8 pt-md-11 pb-8 pb-md-14">
			<div class="row">
				<div class="col-12">

					<!-- Card -->
					<div class="card card-border border-primary shadow-light-lg">
						<div class="card-body">
							<?php landkit_wpjm_job_listing_formapply(); ?> 
						</div>
					</div>

				</div>
			</div> <!-- / .row -->
		</div>
		<?php
	endif;
}

/**
 * Job listing applyform
 */
function landkit_wpjm_job_listing_formapply() {
	if ( candidates_can_apply() ) :
		get_job_manager_template( 'job-application.php' );
	endif;
}


if ( ! function_exists( 'landkit_wpjm_listings_custom_filter_text' ) ) {
	/**
	 * Job listing custom filter text
	 *
	 * @param string $message Message.
	 */
	function landkit_wpjm_listings_custom_filter_text( $message ) {
		if ( ! empty( $message ) ) {
			$message = '<span class="h4 d-block font-weight-bold mb-1">' . esc_html__( 'Search Results', 'landkit' ) . '</span><span class="message font-size-sm text-muted mb-0">' . $message . '</span>';
		}

		return $message;
	}
}

if ( ! function_exists( 'landkit_wpjm_filters_wrap_start' ) ) {
	/**
	 * Starting div for filter wrap
	 */
	function landkit_wpjm_filters_wrap_start() {
		?>
		<div class="mb-7 mb-md-9">
		<?php
	}
}

if ( ! function_exists( 'landkit_wpjm_filters_wrap_end' ) ) {
	/**
	 * Ending div for filter wrap
	 */
	function landkit_wpjm_filters_wrap_end() {
		?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'landkit_wpjm_single_job_sidebar_info' ) ) {
	/**
	 * Display Single Job Sidebar Info
	 *
	 * @param string $title  job Sidebar title.
	 * @param string $description Sidebar Description.
	 */
	function landkit_wpjm_single_job_sidebar_info( $title, $description ) {
		?>
		<h6 class="mt-5 font-weight-bold text-uppercase text-gray-700 mb-2"><?php echo esc_html( $title ); ?></h6>
		<p class="font-size-sm text-gray-800 mb-0"><?php echo wp_kses_post( $description ); ?></p>
		<?php
	}
}

if ( ! function_exists( 'landkit_single_job_listing_before_footer' ) ) {
	/**
	 * Display Before footer Content
	 */
	function landkit_single_job_listing_before_footer() {
		$before_footer_content = get_theme_mod( 'single_job_before_footer_content' );

		if ( $before_footer_content ) {
			print( landkit_render_content( $before_footer_content, false ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

