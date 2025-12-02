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

/**
 * Convert relative font URLs in inline CSS to absolute, versioned assets.
 *
 * When CSS is inlined into the document, relative URLs resolve against the page
 * path (e.g. /belleza/prueba-html/fonts/*) and trigger 404s. This normalizes
 * the font references so they always point to the theme directory.
 *
 * @param string $css Inline CSS contents.
 *
 * @return string CSS with fixed font URLs.
 */
function reban_perf_inline_font_urls( $css ) {
    if ( ! $css ) {
        return $css;
    }

    $font_map = array(
        'rebanfont.woff2'           => reban_perf_versioned_asset( '/fonts/rebanfont.woff2' ),
        'rebanfont.woff'            => reban_perf_versioned_asset( '/fonts/rebanfont.woff' ),
        'Poppins-SemiBold.woff2'    => reban_perf_versioned_asset( '/fonts/Poppins-SemiBold.woff2' ),
        'Poppins-SemiBold.woff'     => reban_perf_versioned_asset( '/fonts/Poppins-SemiBold.woff' ),
        'ProximaNova-Regular.woff2' => reban_perf_versioned_asset( '/fonts/ProximaNova-Regular.woff2' ),
        'ProximaNova-Regular.woff'  => reban_perf_versioned_asset( '/fonts/ProximaNova-Regular.woff' ),
    );

    foreach ( $font_map as $filename => $url ) {
        $pattern = '~url\\((["\']?)fonts/' . preg_quote( $filename, '~' ) . '\\1\\)~i';
        $css     = preg_replace( $pattern, "url('{$url}')", $css );
    }

    return $css;
}

/**
 * Reduce inline CSS size for critical blocks to keep HTML lean.
 *
 * @param string $css Raw CSS contents.
 *
 * @return string Minified CSS.
 */
function reban_perf_minify_css( $css ) {
    if ( ! $css ) {
        return '';
    }

    $css = preg_replace( '#/\\*(?!\\!)[\\s\\S]*?\\*/#', '', $css ); // Drop comments except /*! ... */.
    $css = preg_replace( '/\\s+/', ' ', $css ); // Collapse whitespace/newlines.
    $css = preg_replace( '/\\s*([{};:,>])\\s*/', '$1', $css ); // Trim around separators.
    $css = preg_replace( '/;}/', '}', $css ); // Clean trailing semicolons.

    return trim( $css );
}

/**
 * Map a URL to a local path when it lives in uploads or the child theme.
 *
 * @param string $url URL to map.
 * @return array|null Array with path, basedir, baseurl or null when not local.
 */
function reban_perf_url_to_local_path( $url ) {
    if ( ! $url ) {
        return null;
    }

    $uploads    = wp_upload_dir();
    $candidates = array();

    if ( ! empty( $uploads['basedir'] ) && ! empty( $uploads['baseurl'] ) ) {
        $candidates[] = array(
            'basedir' => trailingslashit( $uploads['basedir'] ),
            'baseurl' => $uploads['baseurl'],
        );
    }

    $theme_dir = get_stylesheet_directory();
    $theme_url = get_stylesheet_directory_uri();

    if ( $theme_dir && $theme_url ) {
        $candidates[] = array(
            'basedir' => trailingslashit( $theme_dir ),
            'baseurl' => $theme_url,
        );
    }

    foreach ( $candidates as $candidate ) {
        $base_urls = array(
            $candidate['baseurl'],
            set_url_scheme( $candidate['baseurl'], 'https' ),
            set_url_scheme( $candidate['baseurl'], 'http' ),
        );

        foreach ( array_unique( $base_urls ) as $base_url ) {
            if ( 0 === strpos( $url, $base_url ) ) {
                $relative = ltrim( str_replace( $base_url, '', $url ), '/' );
                $path     = $candidate['basedir'] . $relative;

                return array(
                    'path'    => $path,
                    'basedir' => $candidate['basedir'],
                    'baseurl' => $base_url,
                );
            }
        }
    }

    return null;
}

/**
 * Append filemtime version to a URL when the asset exists locally.
 *
 * @param string $url URL to version.
 * @return string Versioned URL or original.
 */
function reban_perf_version_url( $url ) {
    $local = reban_perf_url_to_local_path( $url );

    if ( ! $local || empty( $local['path'] ) || ! file_exists( $local['path'] ) ) {
        return $url;
    }

    return add_query_arg( 'v', filemtime( $local['path'] ), $url );
}

/**
 * Obtener el logo activo (custom logo o header) con versionado y WebP cuando exista.
 *
 * Devuelve un array [ src, width, height ] ya versionado para que preload y markup
 * usen exactamente el mismo recurso.
 *
 * @return array
 */
function reban_perf_get_logo_asset() {
    $logo_src = '';
    $width    = 0;
    $height   = 0;

    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $custom_logo    = $custom_logo_id ? wp_get_attachment_image_src( $custom_logo_id, 'full' ) : false;

    if ( $custom_logo ) {
        list( $logo_src, $width, $height ) = $custom_logo;
    } elseif ( get_header_image() ) {
        $header   = get_custom_header();
        $logo_src = get_header_image();
        $width    = $header ? (int) $header->width : 0;
        $height   = $header ? (int) $header->height : 0;
    } else {
        $fallback_path = get_stylesheet_directory() . '/images/Logo-OK-footer-blanco.png';
        $logo_src      = get_stylesheet_directory_uri() . '/images/Logo-OK-footer-blanco.png';

        if ( ! file_exists( $fallback_path ) ) {
            $logo_src = '/wp-content/themes/genesis-child/images/Logo-OK-footer-blanco.png';
        }
        $width  = 287;
        $height = 110;
    }

    if ( $logo_src ) {
        $logo_local = reban_perf_url_to_local_path( $logo_src );

        if ( $logo_local && ! empty( $logo_local['path'] ) ) {
            $path_info       = pathinfo( $logo_local['path'] );
            $webp_candidates = array();

            if ( ! empty( $path_info['extension'] ) && strtolower( $path_info['extension'] ) !== 'webp' ) {
                $webp_candidates[] = $path_info['dirname'] . '/' . $path_info['basename'] . '.webp'; // image.png.webp
                $webp_candidates[] = $path_info['dirname'] . '/' . $path_info['filename'] . '.webp'; // image.webp
            }

            foreach ( $webp_candidates as $candidate_path ) {
                if ( file_exists( $candidate_path ) ) {
                    $logo_src = str_replace( $logo_local['basedir'], trailingslashit( $logo_local['baseurl'] ), $candidate_path );
                    break;
                }
            }
        }

        $logo_src = reban_perf_version_url( $logo_src );
    }

    return array( $logo_src, $width, $height );
}

/**
 * Ensure the custom logo markup uses the same versioned URLs as the preload.
 *
 * @param string $html Logo HTML.
 * @return string
 */
add_filter( 'get_custom_logo', 'reban_perf_version_logo_markup' );
function reban_perf_version_logo_markup( $html ) {
    if ( ! $html ) {
        return $html;
    }

    // Version srcset attributes.
    $html = preg_replace_callback(
        '/srcset="([^"]+)"/i',
        function ( $matches ) {
            $items = explode( ',', $matches[1] );
            $items = array_map(
                function ( $item ) {
                    $parts = preg_split( '/\s+/', trim( $item ), 2 );
                    $url   = $parts[0];
                    $rest  = isset( $parts[1] ) ? ' ' . $parts[1] : '';

                    return reban_perf_version_url( $url ) . $rest;
                },
                $items
            );

            return 'srcset="' . implode( ', ', $items ) . '"';
        },
        $html
    );

    // Version src attributes.
    $html = preg_replace_callback(
        '/src="([^"]+)"/i',
        function ( $matches ) {
            return 'src="' . esc_url( reban_perf_version_url( $matches[1] ) ) . '"';
        },
        $html
    );

    return $html;
}

/* Headers mods
 * 1 - preconnect / dns preload / preload archivos que se usaran
 *      1.a - Preload: https://web.dev/preload-critical-assets/
 *      1.b - Preconnect / dns-preload:https://web.dev/preconnect-and-dns-prefetch/
 * 2 - Critical CSS inline (home/page) cargado desde archivos versionados.
 */
add_action( 'wp_head', 'reban_perf_preloads', 2 );
function reban_perf_preloads() {
    $theme_dir = get_stylesheet_directory();
    list( $logo_src, $logo_width, $logo_height ) = reban_perf_get_logo_asset();

    $preloads = array(
        'reban_woff2'   => reban_perf_versioned_asset( '/fonts/rebanfont.woff2' ),
        'poppins_woff2' => reban_perf_versioned_asset( '/fonts/Poppins-SemiBold.woff2' ),
        'proxima_woff2' => reban_perf_versioned_asset( '/fonts/ProximaNova-Regular.woff2' ),
    );
    ?>
        <?php if ( $logo_src ) : ?>
            <link rel="preload" href="<?php echo esc_url( $logo_src ); ?>" as="image"<?php echo $logo_width && $logo_height ? ' width="' . esc_attr( $logo_width ) . '" height="' . esc_attr( $logo_height ) . '"' : ''; ?>>
        <?php endif; ?>
        <link rel="preload" href="<?php echo esc_url( $preloads['reban_woff2'] ); ?>" as="font" type="font/woff2" crossorigin="anonymous">
        <link rel="preload" href="<?php echo esc_url( $preloads['poppins_woff2'] ); ?>" as="font" type="font/woff2" crossorigin="anonymous">
        <link rel="preload" href="<?php echo esc_url( $preloads['proxima_woff2'] ); ?>" as="font" type="font/woff2" crossorigin="anonymous">
        
        <style id="sidebar-toggle-cls-fix">@media (max-width:944px){.site-header .wrap{position:relative}.site-header .wrap>a.sidebar-toggle-left,.site-header .wrap>a.sidebar-toggle-right{display:inline-flex;align-items:center;justify-content:center;width:3.6rem;height:3.6rem;padding:.5rem;position:absolute;top:50%;transform:translateY(-50%);line-height:1}.site-header .wrap>a.sidebar-toggle-left{left:1rem}.site-header .wrap>a.sidebar-toggle-right{right:.1rem}.nav-primary .menu li.mobile-item>a.sidebar-toggle-left{display:inline-flex;align-items:center;justify-content:center;width:3.6rem;height:3.6rem;padding:.5rem}}.sidebar-toggle-left .icon-menu,.sidebar-toggle-right .icon-menu{display:block;line-height:1;width:1em;height:1em}</style>
    <?php
    if ( is_singular( 'post' ) ) {
        return;
    }

    $critical_relative = is_page() ? '/critical-page.css' : '/critical-home.css';
    $critical_path     = $theme_dir . $critical_relative;

    if ( ! file_exists( $critical_path ) ) {
        return;
    }

    $critical_css = file_get_contents( $critical_path );

    if ( false === $critical_css ) {
        return;
    }

    $critical_css = reban_perf_minify_css( reban_perf_inline_font_urls( $critical_css ) );

    $style_id      = is_page() ? 'reban-critical-page' : 'reban-critical-home';
    $last_modified = filemtime( $critical_path );
    $version_attr  = $last_modified ? ' data-version="' . esc_attr( $last_modified ) . '"' : '';

    printf(
        '<style id="%1$s" data-type="inline-critical"%2$s>%3$s</style>',
        esc_attr( $style_id ),
        $version_attr,
        $critical_css
    );
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
add_filter( 'script_loader_tag', 'reban_perf_async_js', PHP_INT_MAX, 3 );
function reban_perf_async_js( $tag, $handle, $src ) {
    // the handles of the enqueued scripts we want to async.
    $async_scripts = array( CHILD_THEME_NAME );

    if ( in_array( $handle, $async_scripts, true ) ) {
        return '<script type="text/javascript" async src="' . esc_url( $src ) . '"></script>' . "\n";
    }
    return $tag;
}

// Defer WordPress Popular Posts tracker to avoid render blocking while keeping order.
add_action( 'wp_enqueue_scripts', 'reban_perf_defer_wpp', 20 );
function reban_perf_defer_wpp() {
    if ( ! wp_script_is( 'wpp-js', 'registered' ) && ! wp_script_is( 'wpp-js', 'enqueued' ) ) {
        return;
    }

    wp_script_add_data( 'wpp-js', 'strategy', 'defer' );
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


