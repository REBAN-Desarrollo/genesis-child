<?php
/**
 * Theme setup and foundational configuration.
 *
 * Bootstraps Genesis, loads translations, and adjusts core layout supports.
 */

include_once get_template_directory() . '/lib/init.php';

load_child_theme_textdomain(
    'mpp',
    apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'mpp' )
);

add_filter( 'language_attributes', 'reban_setup_lang' );
function reban_setup_lang() {
    return 'lang="es"';
}

add_theme_support(
    'html5',
    array(
        'search-form',
        'comment-form',
        'comment-list',
    )
);

// Add custom Viewport meta tag for mobile browsers.
// add_action( 'genesis_meta', 'reban_setup_viewport' );
function reban_setup_viewport() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
}

// Remove the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );

// Add support for custom header logo image.
add_theme_support(
    'custom-header',
    array(
        'header_image'    => '',
        'header-selector' => '.site-title a',
        'header-text'     => false,
        'height'          => 110,
        'width'           => 619,
    )
);

// Add new image sizes.
add_image_size( 'portfolio', 520, 272, true );

// Unregister layout settings.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Unregister secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Remove entry footer components we don't use.
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
