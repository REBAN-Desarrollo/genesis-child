<?php
/**
 * Theme asset enqueues for styles and scripts.
 */

// 1 - Remove parent style.css.
remove_action( 'genesis_meta', 'genesis_load_stylesheet' );

// 2 - Replace style.css with a date query string when modified.
add_action( 'wp_enqueue_scripts', 'reban_assets_css' );
function reban_assets_css() {
    $stylesheet_uri = get_stylesheet_directory_uri() . '/css/style.css';
    $stylesheet_dir = get_stylesheet_directory() . '/css/style.css';
    $last_modified  = filemtime( $stylesheet_dir ); // segundos exactos para bustear cache al guardar

    wp_enqueue_style( CHILD_THEME_NAME, $stylesheet_uri, array(), $last_modified );
}

// 4 - Enqueue /js/all.js script.
add_action( 'wp_enqueue_scripts', 'reban_assets_js' );
function reban_assets_js() {
    $javascript_uri = get_stylesheet_directory_uri() . '/js/all.js';
    $javascript_dir = get_stylesheet_directory() . '/js/all.js';
    $last_modified  = filemtime( $javascript_dir ); // mantener sync con estilo en segundos

    wp_enqueue_script( CHILD_THEME_NAME, $javascript_uri, array( 'jquery' ), $last_modified, true );
}
