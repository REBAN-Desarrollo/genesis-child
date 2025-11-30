<?php
/**
 * Template helpers for archive-style layouts.
 */

/**
 * Set up shared archive loop settings (layout, breadcrumbs, meta).
 *
 * @param array $loop_args Optional arguments forwarded to reban_loop_archive().
 */
function reban_setup_archive_template( $loop_args = array() ) {
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
	add_filter( 'the_time', 'reban_custom_date_format' );
	remove_action( 'genesis_after_post_content', 'genesis_post_meta' );

	remove_action( 'genesis_loop', 'genesis_do_loop' );
	add_action(
		'genesis_loop',
		function () use ( $loop_args ) {
			reban_loop_archive( $loop_args );
		}
	);
}
