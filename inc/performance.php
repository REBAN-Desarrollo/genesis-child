<?php
/**
 * Performance-focused tweaks for loading assets and scripts.
 */

/**
 * Build a versioned asset URL using filemtime when the file exists.
 *
 * @param string $relative_path Relative path inside the child theme.
 * @param string $fallback_uri  Optional absolute fallback URL.
 *
 * @return string Versioned URL or the fallback when provided.
 */
function reban_perf_versioned_asset( $relative_path, $fallback_uri = '' ) {
    static $theme_dir  = null;
    static $theme_uri  = null;
    static $filetimes = array();

    if ( null === $theme_dir ) {
        $theme_dir = get_stylesheet_directory();
        $theme_uri = get_stylesheet_directory_uri();
    }

    $file_path = $theme_dir . $relative_path;
    $uri       = $theme_uri . $relative_path;

    if ( ! array_key_exists( $file_path, $filetimes ) ) {
        $filetimes[ $file_path ] = file_exists( $file_path ) ? filemtime( $file_path ) : false;
    }

    if ( false !== $filetimes[ $file_path ] ) {
        $uri = add_query_arg( 'v', $filetimes[ $file_path ], $uri );
    } elseif ( $fallback_uri ) {
        $uri = $fallback_uri;
    }

    return esc_url( $uri );
}

/* Headers mods
 * 1 - preconnect / dns preload / preload archivos que se usaran
 *      1.a - Preload: https://web.dev/preload-critical-assets/
 *      1.b - Preconnect / dns-preload:https://web.dev/preconnect-and-dns-prefetch/
 * 2 - Critical CSS externo con versionado:
 */
add_action( 'wp_head', 'reban_perf_preloads', 2 );
function reban_perf_preloads() {
    $theme_dir = get_stylesheet_directory();

    $logo_data       = array();
    $custom_logo_id  = get_theme_mod( 'custom_logo' );
    $custom_logo_src = $custom_logo_id ? wp_get_attachment_image_src( $custom_logo_id, 'full' ) : false;

    if ( $custom_logo_src ) {
        $logo_data = $custom_logo_src;
    } elseif ( get_header_image() ) {
        $header    = get_custom_header();
        $logo_data = array(
            get_header_image(),
            $header ? (int) $header->width : 0,
            $header ? (int) $header->height : 0,
        );
    } else {
        $logo_data = array(
            reban_perf_versioned_asset( '/images/Logo-OK-footer-blanco.png', '/wp-content/themes/genesis-child/images/Logo-OK-footer-blanco.png' ),
            287,
            110,
        );
    }

    $logo_src    = $logo_data[0] ?? '';
    $logo_width  = isset( $logo_data[1] ) ? (int) $logo_data[1] : 0;
    $logo_height = isset( $logo_data[2] ) ? (int) $logo_data[2] : 0;

    $preloads = array(
        'reban_woff2'   => reban_perf_versioned_asset( '/fonts/rebanfont.woff2' ),
        'poppins_woff2' => reban_perf_versioned_asset( '/fonts/Poppins-SemiBold.woff2' ),
        'proxima_woff2' => reban_perf_versioned_asset( '/fonts/ProximaNova-Regular.woff2' ),
    );
    ?>
        <?php if ( $logo_src ) : ?>
            <link rel="preload" href="<?php echo esc_url( $logo_src ); ?>" as="image"<?php echo $logo_width && $logo_height ? ' width="' . esc_attr( $logo_width ) . '" height="' . esc_attr( $logo_height ) . '"' : ''; ?>>
        <?php endif; ?>
        <link rel="preload" href="<?php echo $preloads['reban_woff2']; ?>" as="font" type="font/woff2" crossorigin="anonymous">
        <link rel="preload" href="<?php echo $preloads['poppins_woff2']; ?>" as="font" type="font/woff2" crossorigin="anonymous">
        <link rel="preload" href="<?php echo $preloads['proxima_woff2']; ?>" as="font" type="font/woff2" crossorigin="anonymous">
        
        <style id="sidebar-toggle-cls-fix">
            @media (max-width: 944px) {
                .site-header .wrap {
                    position: relative;
                }
                .site-header .wrap > a.sidebar-toggle-left,
                .site-header .wrap > a.sidebar-toggle-right {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    width: 3.6rem;
                    height: 3.6rem;
                    padding: 0.5rem;
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    line-height: 1;
                }
                .site-header .wrap > a.sidebar-toggle-left {
                    left: 1rem;
                }
                .site-header .wrap > a.sidebar-toggle-right {
                    right: 0.1rem;
                }
                .nav-primary .menu li.mobile-item > a.sidebar-toggle-left {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    width: 3.6rem;
                    height: 3.6rem;
                    padding: 0.5rem;
                }
            }
            .sidebar-toggle-left .icon-menu,
            .sidebar-toggle-right .icon-menu {
                display: block;
                line-height: 1;
                width: 1em;
                height: 1em;
            }
        </style>
    <?php
    if ( is_singular( 'post' ) ) {
        return;
    }

    $critical_relative = is_page() ? '/critical-page.css' : '/critical-home.css';
    $critical_path     = $theme_dir . $critical_relative;

    if ( file_exists( $critical_path ) ) {
        $critical_href = reban_perf_versioned_asset( $critical_relative );
        ?>
            <link rel="preload" href="<?php echo $critical_href; ?>" as="style">
            <link rel="stylesheet" id="reban-critical-css" href="<?php echo $critical_href; ?>">
        <?php
    }
}

// Transform styles.css markup to load CSS asynchronously and add style.css on head position #2.
add_filter( 'style_loader_tag', 'reban_perf_async_css', 10, 2 );
function reban_perf_async_css( $html, $handle ) {
    if ( $handle == CHILD_THEME_NAME ) {
        $async_html = preg_replace(
            '/media=("|\')all\\1/',
            'media=$1print$1 onload="this.media=\'all\'"',
            $html
        );
        return $async_html . "<noscript>{$html}</noscript>";
    }
    return $html;
}

/** Add async attributes to enqueued scripts where needed.The ability to filter script tags was added in WordPress 4.1 for this purpose. */
add_filter( 'script_loader_tag', 'reban_perf_async_js', 10, 3 );
function reban_perf_async_js( $tag, $handle, $src ) {
    // the handles of the enqueued scripts we want to async.
    $async_scripts = array( CHILD_THEME_NAME );

    $is_wpp_js = ( strpos( $src, '/wpp.min.js' ) !== false || strpos( $src, '/wpp.js' ) !== false );

    if ( $is_wpp_js ) {
        // Defer WPP per plugin author's guidance to avoid blocking render while keeping execution order.
        if ( false === strpos( $tag, 'defer' ) && false === strpos( $tag, 'async' ) ) {
            // Preserve plugin attributes (id/data-*) and only append defer.
            $tag = str_replace( '<script ', '<script defer ', $tag );
        }
        return $tag;
    }

    if ( in_array( $handle, $async_scripts, true ) ) {
        return '<script type="text/javascript" async src="' . esc_url( $src ) . '"></script>' . "\n";
    }
    return $tag;
}

/**
 * Hint WordPress to defer WPP when the strategy API is available.
 */
add_action(
    'wp_enqueue_scripts',
    function () {
        if ( ! is_admin() && wp_script_is( 'wpp-js', 'registered' ) ) {
            // `strategy` is available in newer WP versions; fallback handled by script_loader_tag above.
            wp_script_add_data( 'wpp-js', 'strategy', 'defer' );
        }
    },
    15
);

add_action( 'wp_enqueue_scripts', 'reban_perf_gate_wpp_assets', 20 );
/**
 * Limit WPP assets to single posts where the widget actually renders.
 */
function reban_perf_gate_wpp_assets() {
    if ( is_admin() ) {
        return;
    }

    $wpp_handle     = 'wpp-js';
    $widget_is_used = is_active_widget( false, false, 'wppwidget', true );

    if ( is_singular( 'post' ) && $widget_is_used ) {
        return;
    }

    if ( wp_script_is( $wpp_handle, 'enqueued' ) ) {
        wp_dequeue_script( $wpp_handle );
    }
}

// Add missing width/height to inline images so the browser can reserve space and avoid CLS.
/**
 * Get image dimensions for a content image served from uploads (including .jpg.webp variants).
 *
 * @param string $src     Image URL found in the content.
 * @param array  $uploads Result of wp_upload_dir().
 *
 * @return array|null Array with width/height or null when it cannot be resolved.
 */
function reban_perf_get_content_image_size( $src, $uploads ) {
    static $dimension_cache = array();

    if ( isset( $dimension_cache[ $src ] ) ) {
        return $dimension_cache[ $src ];
    }

    if ( empty( $uploads['baseurl'] ) || empty( $uploads['basedir'] ) ) {
        $dimension_cache[ $src ] = null;
        return null;
    }

    $base_urls = array(
        $uploads['baseurl'],
        set_url_scheme( $uploads['baseurl'], 'https' ),
        set_url_scheme( $uploads['baseurl'], 'http' ),
    );

    $relative_path = null;

    foreach ( array_unique( $base_urls ) as $baseurl ) {
        if ( 0 === strpos( $src, $baseurl ) ) {
            $relative_path = ltrim( str_replace( $baseurl, '', $src ), '/' );
            break;
        }
    }

    if ( null === $relative_path ) {
        $dimension_cache[ $src ] = null;
        return null;
    }

    $base_dir     = trailingslashit( $uploads['basedir'] );
    $paths_to_try = array( $base_dir . $relative_path );

    // Handle CDN-style "image.jpg.webp" URLs by falling back to the original file.
    if ( preg_match( '/\.(jpe?g|png|gif)\.webp$/i', $relative_path ) ) {
        $paths_to_try[] = $base_dir . preg_replace( '/\.(jpe?g|png|gif)\.webp$/i', '.$1', $relative_path );
    } elseif ( preg_match( '/\.webp$/i', $relative_path ) ) {
        $paths_to_try[] = $base_dir . preg_replace( '/\.webp$/i', '.jpg', $relative_path );
        $paths_to_try[] = $base_dir . preg_replace( '/\.webp$/i', '.jpeg', $relative_path );
        $paths_to_try[] = $base_dir . preg_replace( '/\.webp$/i', '.png', $relative_path );
    }

    $paths_to_try = array_values( array_unique( $paths_to_try ) );

    foreach ( $paths_to_try as $path ) {
        if ( file_exists( $path ) ) {
            $image_size = @getimagesize( $path );
            if ( $image_size ) {
                $dimension_cache[ $src ] = array(
                    (int) $image_size[0],
                    (int) $image_size[1],
                );
                return $dimension_cache[ $src ];
            }
        }
    }

    $dimension_cache[ $src ] = null;
    return null;
}

add_filter( 'the_content', 'reban_perf_fill_image_dimensions', 15 );
function reban_perf_fill_image_dimensions( $content ) {
    if ( is_admin() || ! is_singular() || stripos( $content, '<img' ) === false ) {
        return $content;
    }

    $uploads = wp_upload_dir();

    return preg_replace_callback(
        '/<img\s+([^>]*?)\/?>/i',
        function ( $matches ) use ( $uploads ) {
            $attributes = trim( $matches[1] );
            $has_width  = preg_match( '/\bwidth\s*=\s*/i', $attributes );
            $has_height = preg_match( '/\bheight\s*=\s*/i', $attributes );

            if ( $has_width && $has_height ) {
                return $matches[0];
            }

            if ( ! preg_match( '/\bsrc\s*=\s*(["\'])(.*?)\\1/i', $attributes, $src_match ) ) {
                return $matches[0];
            }

            $src    = $src_match[2];
            $width  = 0;
            $height = 0;

            $image_size = reban_perf_get_content_image_size( $src, $uploads );

            if ( $image_size ) {
                $width  = (int) $image_size[0];
                $height = (int) $image_size[1];
            }

            if ( ( ! $width || ! $height ) && preg_match( '/wp-image-(\d+)/', $attributes, $id_match ) ) {
                $meta = wp_get_attachment_metadata( (int) $id_match[1] );
                if ( $meta && ! empty( $meta['width'] ) && ! empty( $meta['height'] ) ) {
                    $width  = (int) $meta['width'];
                    $height = (int) $meta['height'];
                }
            }

            if ( ! $width || ! $height ) {
                return $matches[0];
            }

            if ( ! $has_width ) {
                $attributes .= ' width="' . esc_attr( $width ) . '"';
            }

            if ( ! $has_height ) {
                $attributes .= ' height="' . esc_attr( $height ) . '"';
            }

            return '<img ' . trim( $attributes ) . ' />';
        },
        $content
    );
}


