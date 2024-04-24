<?php
/**
 * Landkit Theme Customizer
 *
 * @package landkit
 */

/**
 * Mas Static block
 */
function static_content_options() {
	if ( landkit_is_mas_static_content_activated() ) {
		$static_block  = array();
		$args          = array(
			'post_type'      => 'mas_static_content',
			'post_status'    => 'publish',
			'limit'          => '-1',
			'posts_per_page' => '-1',
		);
		$static_blocks = get_posts( $args );
		$options       = array( '' => esc_html__( '— Select —', 'landkit' ) ) + array();
		foreach ( $static_blocks as $static_block ) {
			$options[ $static_block->ID ] = $static_block->post_title;

		}
		return $options;
	}
}
