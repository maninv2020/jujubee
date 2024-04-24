<?php
/**
 * Landkit hooks
 *
 * @package landkit
 */

/**
 * Navbar
 *
 * @see  landkit_navbar_brand()
 * @see  landkit_navbar_nav()
 */

add_action( 'landkit_before_site', 'landkit_before_site_content', 10 );

add_action( 'landkit_navbar', 'landkit_navbar_brand', 10 );
add_action( 'landkit_navbar', 'landkit_navbar_toggler', 20 );
add_action( 'landkit_navbar', 'landkit_navbar_collapse_start', 30 );
add_action( 'landkit_navbar', 'landkit_navbar_toggler_x', 40 );
add_action( 'landkit_navbar', 'landkit_navbar_nav', 50 );
add_action( 'landkit_navbar', 'landkit_navbar_action_button', 60 );
add_action( 'landkit_navbar', 'landkit_navbar_collapse_end', 70 );

/**
 * Blog
 */
add_action( 'landkit_archive_before', 'landkit_archive_header', 10 );

add_action( 'landkit_loop_before', 'landkit_blog_search', 10 );
add_action( 'landkit_loop_before', 'landkit_loop_post_wrap_start', 20 );

add_action( 'landkit_loop_post', 'landkit_loop_post_card_thumbnail', 10 );
add_action( 'landkit_loop_post', 'landkit_loop_post_card_body', 20 );
add_action( 'landkit_loop_post', 'landkit_loop_post_card_meta', 30 );

add_action( 'landkit_loop_post_sticky', 'landkit_featured_badge', 10 );
add_action( 'landkit_loop_post_sticky', 'landkit_sticky_post_thumbnail', 20 );
add_action( 'landkit_loop_post_sticky', 'landkit_sticky_post_card_body_wrap_start', 30 );
add_action( 'landkit_loop_post_sticky', 'landkit_loop_post_card_body', 40 );
add_action( 'landkit_loop_post_sticky', 'landkit_loop_post_card_meta', 50 );
add_action( 'landkit_loop_post_sticky', 'landkit_sticky_post_card_body_wrap_end', 60 );

add_action( 'landkit_loop_after', 'landkit_loop_post_wrap_end', 10 );
add_action( 'landkit_loop_after', 'landkit_paging_nav', 20 );

add_action( 'landkit_archive_after', 'landkit_archive_footer', 10 );

/**
 * Single Post
 */
add_action( 'landkit_single_post_top', 'landkit_single_post_cover', 10 );
add_action( 'landkit_single_post_top', 'landkit_single_post_header', 20 );
add_action( 'landkit_single_post_top', 'landkit_single_post_content', 30 );

add_action( 'landkit_single_post_the_content_after', 'landkit_link_pages', 10 );

add_action( 'landkit_single_post_content_after', 'landkit_single_post_nav', 10 );
add_action( 'landkit_single_post_content_after', 'landkit_comments', 20 );

add_action( 'landkit_single_post_after', 'landkit_single_post_footer', 10 );

add_action( 'landkit_share', 'landkit_share_display', 10 );

/**
 * Single Post
 */
add_action( 'landkit_single_elementor_library_top', 'landkit_single_post_cover', 10 );
add_action( 'landkit_single_elementor_library_top', 'landkit_single_post_header', 20 );
add_action( 'landkit_single_elementor_library_top', 'landkit_single_post_content', 30 );

add_action( 'landkit_single__elementor_library_the_content_after', 'landkit_link_pages', 10 );

add_action( 'landkit_single__elementor_library_content_after', 'landkit_single_post_nav', 10 );
add_action( 'landkit_single__elementor_library_content_after', 'landkit_comments', 20 );

add_action( 'landkit_single__elementor_library_after', 'landkit_single_post_footer', 10 );

/**
 * Single Jetpack Portfolio
 */
add_action( 'landkit_single_jetpack-portfolio_top', 'landkit_single_post_cover', 10 );
add_action( 'landkit_single_jetpack-portfolio_top', 'landkit_single_post_header', 20 );
add_action( 'landkit_single_jetpack-portfolio_top', 'landkit_single_post_content', 30 );

add_action( 'landkit_single_jetpack-portfolio_after', 'landkit_single_portfolio_footer', 10 );

/**
 * Comments
 */
add_filter( 'comment_form_default_fields', 'landkit_comment_form_default_fields', 20 );

/**
 * Protected Post Custom Password Form
 */
add_filter( 'the_password_form', 'landkit_post_protected_password_form' );

/**
 * Page
 */
add_action( 'landkit_page', 'landkit_page_header', 10 );
add_action( 'landkit_page', 'landkit_page_content', 20 );

/**
 * Footer
 *
 * @see  landkit_footer_widgets()
 */
add_action( 'landkit_footer', 'landkit_footer_widgets', 10 );

/**
 * Portfolio Hooks
 */
if ( ! function_exists( 'landkit_portfolio_archive_hooks' ) ) {
	/**
	 * Portfolio masonry hooks
	 */
	function landkit_portfolio_archive_hooks() {
		landkit_add_portfolio_masonry_hooks();
	}
}
landkit_portfolio_archive_hooks();

/**
 * Elementor Templates
 */
add_action( 'elementor/page_templates/header-footer/after_content', 'landkit_single_post_type_after', 10 );
