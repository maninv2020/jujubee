<?php
/**
 * Template Hooks used in WeDocs
 */

add_action( 'landkit_single_docs_before', 'landkit_display_breadcrumb', 10 );

add_action( 'landkit_wedocs_single_doc', 'landkit_wedocs_docs_entry_header', 10 );
add_action( 'landkit_wedocs_single_doc', 'landkit_wedocs_docs_entry_content', 10 );
add_action( 'landkit_wedocs_single_doc', 'landkit_wedocs_related_articles', 20 );

add_action( 'landkit_single_docs_after', 'landkit_wedocs_before_footer', 10 );
