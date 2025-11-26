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
            <div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
                <div class="full-post-container <?php echo esc_attr( (++$count % 2 ? 'odd' : 'even') ); ?> clearfix">
                    <div class="post-left-col">
                        <a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_post_thumbnail('portfolio'); ?></a>
                    </div>
                    <div class="post-right-col">
                        <h2>
                            <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
                                <?php echo esc_html( get_the_title() ); ?>
                            </a>
                        </h2>
                        <?php if ( $args['wrap_author_box'] ) : ?>
                            <div class="<?php echo esc_attr( $args['wrap_author_box'] ); ?>">
                        <?php endif; ?>
                                <span class="author">Por <?php the_author_posts_link(); ?></span> |
                                <span class="time">
                                    <time itemprop="datePublished" content="<?php echo esc_attr( get_the_date('Y-m-d') ); ?>">
                                        <?php echo esc_html( get_the_date( $args['date_format'] ) ); ?>
                                    </time>
                                </span>
                        <?php if ( $args['wrap_author_box'] ) : ?>
                            </div>
                        <?php endif; ?>
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

//* Customize the entry meta in the entry header (requires HTML5 theme support)
add_filter( 'genesis_post_info', 'sp_post_info_filter' );
function sp_post_info_filter( $post_info ) {
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
add_action( 'genesis_header', 'reban_custom_search_form' );
function reban_custom_search_form() {
    $search_id = wp_unique_id( 'search-' );
?>
        <div class="responsive-search sb-right clearfix">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <label for="<?php echo esc_attr( $search_id ); ?>">Busqueda:
                                        <input id="<?php echo esc_attr( $search_id ); ?>" type="text" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" class="search-input" placeholder="Buscar en el sitio" />
                        </label>
                        <input type="submit" class="search-submit" value="Buscar"/>
                </form>
        </div>
<?php
}

/* Custom embeds
    1 - Youtube Videos remove show info related etc
    2 - Hide Instagram Captions
*/
// 1 - Youtube Videos remove show info related etc
function custom_youtube_settings( $code ) {
    if ( strpos( $code, 'youtube.com' ) !== false || strpos( $code, 'youtu.be' ) !== false ) {
        $return = preg_replace( "@src=(['\"])?([^'\">\\s]*)@", "src=$1$2&cc_lang_pref=es&hl=es&showinfo=0&rel=0&autohide=1&modestbranding=1&iv_load_policy=3", $code );
        return $return;
    }
    return $code;
}
add_filter( 'embed_handler_html', 'custom_youtube_settings' );
add_filter( 'embed_oembed_html', 'custom_youtube_settings' );
// 2 - Hide Instagram Captions
function custom_instagram_settings( $code ) {
    if ( strpos( $code, 'instagr.am' ) !== false || strpos( $code, 'instagram.com' ) !== false ) { // if instagram embed
        $return = preg_replace( "@data-instgrm-captioned@", '', $code ); // remove caption class
        return $return;
    }
    return $code;
}
add_filter( 'embed_handler_html', 'custom_instagram_settings' );
add_filter( 'embed_oembed_html', 'custom_instagram_settings' );


/* Footer Mods */
add_action( 'genesis_footer', 'reban_custom_footer', 5 );
function reban_custom_footer() {
    ?>
    <a href="<?php bloginfo('url'); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/Logo-OK-footer-blanco.png" width="287" height="110" class="footer-logo" loading="lazy" alt="Logo OKChicas footer transparente y blanco"/> </a>
    <ul id="footer-social">
        <li><a class="social-button-link" href="https://www.facebook.com/OkChicasBlog/" aria-label="Facebook footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-facebook"></i></a></li>
        <li><a class="social-button-link" href="https://www.instagram.com/okchicas/" aria-label="Instagram footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-instagram"></i></a></li>
        <li><a class="social-button-link" href="https://www.youtube.com/channel/UC4emviWglNnjU6en1P_e5uQ" aria-label="YouTube footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-youtube"></i></a></li>
        <li><a class="social-button-link" href="https://www.pinterest.com.mx/okchicas/" aria-label="Pinterest footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-pinterest"></i></a></li>
        <li><a class="social-button-link" href="https://twitter.com/OkChicasOficial" aria-label="Twitter footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-twitter"></i></a></li>
        <li><a class="social-button-link" href="mailto:soporte@okchicas.com" aria-label="email footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-mail"></i></a></li>
        <li><a class="social-button-link" href="https://www.okchicas.com/feed/" aria-label="RSS footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-rss"></i></a></li>
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
                        'fallback_cb' => 'false'
                    ));
                ?>
        </div>
        <div id="copyright"><p>&copy;<?php echo date('o'); ?> Grupo Reban. Todos los derechos reservados</p></div>
        <div id="footer-menu">
                <?php
                    wp_nav_menu(array(
                        'theme_location' => 'secondary',
                        'menu_class' => '',
                        'fallback_cb' => 'false'
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
        return get_the_author();
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
                $name = get_the_author();
                $title = get_the_author_meta( 'title' );
                        if( !empty( $title ) )
                                $name .= ', ' . $title;
                $output .= '<h2 class="title">'. $name;
                $output .= '</h2>';
                $output .= '<p class="desc">' . get_the_author_meta( 'description' ) . '</p>';
                $output .= '</div>';
                $output .= '</div><!-- .author-box -->';
        return $output;
}
