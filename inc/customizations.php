<?php
/**
 * Theme-specific visual and content customizations.
 */

/**
 * Devuelve la fecha formateada con nombres de meses en español.
 *
 * @return string
 */
function reban_custom_date_format() {
    return date_i18n( 'F j, Y', get_the_time( 'U' ) );
}

add_filter( 'genesis_seo_title', 'reban_site_title_with_logo', 10, 3 );
/**
 * Renderiza el titulo del sitio con logo (custom, header o fallback).
 *
 * @param string $title  Markup actual.
 * @param string $inside Contenido interno.
 * @param string $wrap   Etiqueta contenedora (p/h1).
 * @return string
 */
function reban_site_title_with_logo( $title, $inside, $wrap ) {
    $logo_src = '';
    $width    = 0;
    $height   = 0;

    if ( has_custom_logo() ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $custom_logo    = $custom_logo_id ? wp_get_attachment_image_src( $custom_logo_id, 'full' ) : false;
        if ( $custom_logo ) {
            list( $logo_src, $width, $height ) = $custom_logo;
        }
    } elseif ( get_header_image() ) {
        $header   = get_custom_header();
        $logo_src = get_header_image();
        $width    = $header ? (int) $header->width : 0;
        $height   = $header ? (int) $header->height : 0;
    } else {
        $fallback_path = get_stylesheet_directory() . '/images/Logo-OK-footer-blanco.png';
        $logo_src      = get_stylesheet_directory_uri() . '/images/Logo-OK-footer-blanco.png';
        if ( file_exists( $fallback_path ) ) {
            $logo_src = add_query_arg( 'v', filemtime( $fallback_path ), $logo_src );
        } else {
            $logo_src = '/wp-content/themes/genesis-child/images/Logo-OK-footer-blanco.png';
        }
        $width  = 287;
        $height = 110;
    }

    if ( ! $logo_src ) {
        return $title;
    }

    $home = esc_url( home_url( '/' ) );
    $alt  = esc_attr( get_bloginfo( 'name' ) );

    $dimensions = '';
    if ( $width && $height ) {
        $dimensions = sprintf( ' width="%d" height="%d"', $width, $height );
    }

    return sprintf(
        '<%1$s class="site-title"><a href="%2$s" rel="home"><img src="%3$s"%4$s alt="%5$s"></a></%1$s>',
        esc_attr( $wrap ),
        $home,
        esc_url( $logo_src ),
        $dimensions,
        $alt
    );
}

/**
 * Shared post loop used by home, archive and search templates.
 *
 * @param array $args {
 *     Optional. Loop options.
 *
 *     @type string $date_format     Date format for the visible time tag. Default WP date format.
 *     @type string $empty_message   Message to show when there are no posts. Default empty.
 *     @type string $wrap_author_box CSS class to wrap author/time markup. Default empty (no wrapper).
 * }
 */
function reban_loop_archive( $args = array() ) {
    $defaults = array(
        'date_format'     => get_option( 'date_format' ),
        'empty_message'   => '',
        'wrap_author_box' => '',
    );

    $args = wp_parse_args( $args, $defaults );

    if ( have_posts() ) {
        $count = 0;
        while ( have_posts() ) {
            the_post(); ?>
            <div <?php post_class( array( 'oc-card' ) ); ?> id="post-<?php the_ID(); ?>">
                <div class="full-post-container oc-card__inner <?php echo esc_attr( (++$count % 2 ? 'oc-card__inner--reversed odd' : 'even') ); ?> clearfix">
                    <div class="post-left-col oc-card__media">
                        <a href="<?php echo esc_url( get_permalink() ); ?>">
                            <?php
                            $thumbnail_id  = get_post_thumbnail_id();
                            $thumbnail_alt = $thumbnail_id ? get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ) : '';

                            if ( '' === $thumbnail_alt ) {
                                $thumbnail_alt = get_the_title();
                            }

                            if ( $thumbnail_id ) {
                                echo wp_get_attachment_image(
                                    $thumbnail_id,
                                    'portfolio',
                                    false,
                                    array(
                                        'alt'     => $thumbnail_alt,
                                        'loading' => 'lazy',
                                        'sizes'   => '(max-width: 600px) 100vw, (max-width: 1024px) 70vw, 520px',
                                    )
                                );
                            }
                            ?>
                        </a>
                    </div>
                    <div class="post-right-col oc-card__body">
                        <h2 class="oc-card__title">
                            <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
                                <?php echo esc_html( get_the_title() ); ?>
                            </a>
                        </h2>
                        <div class="oc-card__meta<?php echo $args['wrap_author_box'] ? ' ' . esc_attr( $args['wrap_author_box'] ) : ''; ?>">
                            <span class="author oc-card__author">Por <?php the_author_posts_link(); ?></span>
                            <span class="time oc-card__time">
                                <time itemprop="datePublished" content="<?php echo esc_attr( get_the_date('Y-m-d') ); ?>">
                                    <?php echo esc_html( get_the_date( $args['date_format'] ) ); ?>
                                </time>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
    } elseif ( $args['empty_message'] ) {
        echo '<p>' . esc_html( $args['empty_message'] ) . '</p>';
    }

    genesis_posts_nav();
}

/**
 * Add clearfix utility class to common Genesis containers.
 */
function reban_custom_clearfix( $attr ) {
    if ( isset( $attr['class'] ) ) {
        $attr['class'] .= ' clearfix';
    } else {
        $attr['class'] = 'clearfix';
    }
    return $attr;
}

foreach ( array(
    'entry',
    'entry-content',
    'footer-widgets',
    'nav-primary',
    'nav-secondary',
    'pagination',
    'site-container',
    'site-footer',
    'site-header',
    'site-inner',
    'widget',
    'wrap',
) as $context ) {
    add_filter( "genesis_attr_{$context}", 'reban_custom_clearfix' );
}

/**
 * Customize the entry meta in the entry header (requires HTML5 theme support).
 *
 * @param string $post_info Default post info markup.
 * @return string Filtered post info markup.
 */
add_filter( 'genesis_post_info', 'reban_post_info_filter' );
function reban_post_info_filter( $post_info ) {
    $category  = get_the_category();
    //$post_info =  '<span class="post-category">' . $category[0]->cat_name .'</span>'. '[post_author_posts_link] [post_date]';
    $post_info = '- Por [post_author_posts_link]';
    return $post_info;
}

//* Customize search form input box text
add_filter( 'genesis_search_text', 'reban_custom_search_text' );
function reban_custom_search_text( $text ) {
    return esc_attr( 'Buscar en el sitio' );
}
// Buscador del header desactivado; el buscador vive en la slidebar izquierda.

/* Custom embeds
    1 - Youtube Videos remove show info related etc
    2 - Hide Instagram Captions
*/
// 1 - Youtube Videos remove show info related etc
function custom_youtube_settings( $html, $url = '', $attr = array(), $post_id = 0 ) {
    $haystack = $url ? $url : $html;

    if ( false === stripos( $haystack, 'youtube.com' ) && false === stripos( $haystack, 'youtu.be' ) ) {
        return $html;
    }

    if ( ! $url && preg_match( '/\bsrc\s*=\s*(["\']?)(.*?)\\1/i', $html, $matches ) ) {
        $url = $matches[2];
    }

    if ( ! $url ) {
        return $html;
    }

    if ( 0 === strpos( $url, '//' ) ) {
        $url = 'https:' . $url;
    }

    $parts = wp_parse_url( $url );

    if ( empty( $parts['host'] ) ) {
        return $html;
    }

    $host          = strtolower( $parts['host'] );
    $allowed_hosts = array(
        'youtube.com',
        'www.youtube.com',
        'm.youtube.com',
        'youtu.be',
        'www.youtu.be',
        'youtube-nocookie.com',
        'www.youtube-nocookie.com',
    );

    if ( ! in_array( $host, $allowed_hosts, true ) ) {
        return $html;
    }

    $url = set_url_scheme( $url, 'https' );

    if ( 'youtu.be' === $host || 'www.youtu.be' === $host ) {
        $video_id = empty( $parts['path'] ) ? '' : ltrim( $parts['path'], '/' );

        if ( '' === $video_id ) {
            return $html;
        }

        $url = 'https://www.youtube.com/embed/' . rawurlencode( $video_id );
    }

    $url = add_query_arg(
        array(
            'cc_lang_pref'   => 'es',
            'hl'             => 'es',
            'showinfo'       => '0',
            'rel'            => '0',
            'autohide'       => '1',
            'modestbranding' => '1',
            'iv_load_policy' => '3',
        ),
        $url
    );

    $safe_url = wp_http_validate_url( $url );

    if ( ! $safe_url ) {
        return $html;
    }

    $safe_url = esc_url( $safe_url );

    $sanitized_html = preg_replace_callback(
        '/\bsrc\s*=\s*(["\']?)(.*?)\\1/i',
        function () use ( $safe_url ) {
            return 'src="' . $safe_url . '"';
        },
        $html,
        1
    );

    $allowed = wp_kses_allowed_html( 'post' );
    $allowed['iframe'] = array(
        'src'             => true,
        'width'           => true,
        'height'          => true,
        'frameborder'     => true,
        'allow'           => true,
        'allowfullscreen' => true,
        'loading'         => true,
        'title'           => true,
        'referrerpolicy'  => true,
    );

    return wp_kses( $sanitized_html ? $sanitized_html : $html, $allowed );
}
add_filter( 'embed_handler_html', 'custom_youtube_settings', 10, 4 );
add_filter( 'embed_oembed_html', 'custom_youtube_settings', 10, 4 );
// 2 - Hide Instagram Captions
function custom_instagram_settings( $code ) {
    if ( strpos( $code, 'instagr.am' ) !== false || strpos( $code, 'instagram.com' ) !== false ) { // if instagram embed
        $code = preg_replace( "@data-instgrm-captioned@", '', $code ); // remove caption class

        $code = preg_replace_callback(
            '/<script[^>]+src=(["\']?)([^"\'>\\s]+)\\1[^>]*><\\/script>/i',
            static function ( $matches ) {
                $src = $matches[2];

                if ( false !== stripos( $src, '://www.instagram.com/embed.js' ) || 0 === strpos( $src, '//www.instagram.com/embed.js' ) ) {
                    return $matches[0];
                }

                return '';
            },
            $code
        );

        $allowed = wp_kses_allowed_html( 'post' );
        $allowed['iframe']    = array(
            'src'             => true,
            'width'           => true,
            'height'          => true,
            'frameborder'     => true,
            'allow'           => true,
            'allowfullscreen' => true,
            'loading'         => true,
            'title'           => true,
            'referrerpolicy'  => true,
        );
        $allowed['blockquote'] = array(
            'class'                 => true,
            'data-instgrm-permalink'=> true,
            'data-instgrm-version'  => true,
            'style'                 => true,
        );
        $allowed['script']    = array(
            'src'   => true,
            'async' => true,
            'defer' => true,
        );

        return wp_kses( $code, $allowed );
    }
    return $code;
}
add_filter( 'embed_handler_html', 'custom_instagram_settings' );
add_filter( 'embed_oembed_html', 'custom_instagram_settings' );


/* Footer Mods */
add_action( 'genesis_footer', 'reban_custom_footer', 5 );
function reban_custom_footer() {
    ?>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/images/Logo-OK-footer-blanco.png" width="287" height="110" class="footer-logo" loading="lazy" alt="<?php echo esc_attr( 'Logo OKChicas footer transparente y blanco' ); ?>"/> </a>
    <ul id="footer-social">
        <li><a class="social-button-link" href="https://www.facebook.com/OkChicasBlog/" aria-label="Facebook footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-facebook"></i><span class="screen-reader-text">Facebook</span></a></li>
        <li><a class="social-button-link" href="https://www.instagram.com/okchicas/" aria-label="Instagram footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-instagram"></i><span class="screen-reader-text">Instagram</span></a></li>
        <li><a class="social-button-link" href="https://www.youtube.com/channel/UC4emviWglNnjU6en1P_e5uQ" aria-label="YouTube footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-youtube"></i><span class="screen-reader-text">YouTube</span></a></li>
        <li><a class="social-button-link" href="https://www.pinterest.com.mx/okchicas/" aria-label="Pinterest footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-pinterest"></i><span class="screen-reader-text">Pinterest</span></a></li>
        <li><a class="social-button-link" href="https://twitter.com/OkChicasOficial" aria-label="Twitter footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-twitter"></i><span class="screen-reader-text">Twitter</span></a></li>
        <li><a class="social-button-link" href="mailto:soporte@okchicas.com" aria-label="email footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-mail"></i><span class="screen-reader-text">Correo</span></a></li>
        <li><a class="social-button-link" href="https://www.okchicas.com/feed/" aria-label="RSS footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-rss"></i><span class="screen-reader-text">RSS</span></a></li>
    </ul>
    <?php
}
//* Footer menu
add_action( 'init', 'register_my_menus' );
function register_my_menus() {
    register_nav_menus(
        array(
            'footer-menu' => __( 'Footer Menu' ),
        )
    );
}
remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'genesis_user_footer' );
function genesis_user_footer() {
    ?>
        <div id="footer-menu">
                <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer-menu',
                        'menu_class' => '',
                        'fallback_cb' => false,
                    ));
                ?>
        </div>
        <div id="copyright"><p>&copy;<?php echo esc_html( date_i18n('Y') ); ?> Grupo Reban. Todos los derechos reservados</p></div>
        <div id="footer-menu">
                <?php
                    wp_nav_menu(array(
                        'theme_location' => 'secondary',
                        'menu_class' => '',
                        'fallback_cb' => false,
                    ));
                ?>
        </div>
    <?php
}


add_filter( 'genesis_prev_link_text', 'reban_custom_pagination_prev' );
function reban_custom_pagination_prev($text) {
        $text = 'Más reciente';
        return $text;
}
add_filter( 'genesis_next_link_text', 'reban_custom_pagination_next' );
function reban_custom_pagination_next($text) {
        $text = 'Más Antiguo';
        return $text;
}

/* Change Author & Comment Box Gravatar/Avatar Image Size */
add_filter( 'genesis_author_box_gravatar_size', 'reban_custom_gravatar_size' );
function reban_custom_gravatar_size($size) {
    return '120';
}

//* Customize the author box title
add_filter( 'genesis_author_box_title', 'reban_custom_author_title' );
function reban_custom_author_title() {
        return esc_html( get_the_author() );
}

add_filter( 'genesis_author_box', 'be_author_box', 10, 6 );
/**
 * Customize Author Box
 * @author Bill Erickson
 * @link http://www.billerickson.net/code/customize-author-box
 *
 * @param string $output
 * @param string $context
 * @param string $pattern
 * @param string $gravatar
 * @param string $title
 * @param string $description
 * @return string $output
 */
function be_author_box( $output, $context, $pattern, $gravatar, $title, $description ) {
                $output = '';
                $output .= '<div class="author-box clearfix">';
                $output .= '<div class="alignleft">';
                $output .= get_avatar( get_the_author_meta( 'email' ), 120 );
                $output .= '</div><!-- .left -->';
                $output .= '<div class="alignright">';
                $name = esc_html( get_the_author() );
                $title = get_the_author_meta( 'title' );
                        if ( ! empty( $title ) )
                                $name .= ', ' . esc_html( $title );
                $output .= '<h2 class="title">'. $name;
                $output .= '</h2>';
                $output .= '<p class="desc">' . wp_kses_post( get_the_author_meta( 'description' ) ) . '</p>';
                $output .= '</div>';
                $output .= '</div><!-- .author-box -->';
        return $output;
}
