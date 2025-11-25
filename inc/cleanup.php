<?php
/**
 * Cleanup tasks that remove unused assets and legacy hooks.
 */

add_action( 'wp_enqueue_scripts', 'reban_cleanup_html5shiv' );
function reban_cleanup_html5shiv() {
    wp_dequeue_script( 'html5shiv' );
}

add_action( 'init', 'reban_cleanup_dns_prefetch' );
function reban_cleanup_dns_prefetch() {
    remove_action( 'wp_head', 'wp_resource_hints', 2, 99 );
}

// Disable comments feed.
add_filter( 'feed_links_show_comments_feed', '__return_false' );

// Remove xmlrpc RSD link.
remove_action( 'wp_head', 'rsd_link' );

function reban_cleanup_wp_embed() {
    wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'reban_cleanup_wp_embed' );

add_action( 'init', 'reban_cleanup_emojis' );
function reban_cleanup_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
}

// Remove jQuery enqueue.
add_action( 'wp_enqueue_scripts', 'reban_cleanup_jquery' );
function reban_cleanup_jquery() {
    wp_deregister_script( 'jquery' );
}

// Remove classic theme styles.
add_action( 'wp_enqueue_scripts', 'reban_cleanup_classic_styles', 20 );
function reban_cleanup_classic_styles() {
    wp_dequeue_style( 'classic-theme-styles' );
}

// Remove block library styles.
add_action( 'wp_enqueue_scripts', 'reban_cleanup_gutenberg' );
function reban_cleanup_gutenberg() {
    wp_dequeue_style( 'wp-block-library' );
}
