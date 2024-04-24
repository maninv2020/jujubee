<?php
/**
 * Single Job Listing Hooks
 */

add_action( 'landkit_single_job_listing_wpjm_before', 'landkit_wpjm_job_listing_header_start', 10 );
add_action( 'landkit_single_job_listing_wpjm_before', 'landkit_wpjm_job_listing_title_area', 20 );
add_action( 'landkit_single_job_listing_wpjm_before', 'landkit_wpjm_job_listing_action_btn', 30 );
add_action( 'landkit_single_job_listing_wpjm_before', 'landkit_wpjm_job_listing_header_end', 40 );
add_action( 'landkit_single_job_listing_wpjm_before', 'landkit_wpjm_job_listing_divider', 50 );
add_action( 'landkit_single_job_listing_wpjm_before', 'landkit_wpjm_job_listing_content_start', 60 );
add_action( 'landkit_single_job_listing', 'landkit_wpjm_job_listing_description', 70 );
add_action( 'landkit_single_job_listing_wpjm_after', 'landkit_wpjm_job_listing_content_end', 80 );
add_action( 'landkit_single_job_listing_wpjm_after', 'landkit_wpjm_job_listing_sidebar', 90 );
add_action( 'landkit_single_job_listing_wpjm_after', 'landkit_wpjm_job_listing_form', 100 );

add_action( 'landkit_single_job_listing_after', 'landkit_single_job_listing_before_footer', 200 );


add_filter( 'job_manager_get_listings_custom_filter_text', 'landkit_wpjm_listings_custom_filter_text' );

add_action( 'job_manager_job_filters_start', 'landkit_wpjm_filters_wrap_start', 10 );
add_action( 'job_manager_job_filters_end', 'landkit_wpjm_filters_wrap_end', 25 );
