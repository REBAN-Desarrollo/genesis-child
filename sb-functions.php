<?php
/**
 * Archivo sb-functions.php
 *
 * Gestor de slidebars laterales para navegación y buscador en el tema hijo.
 */

/**
 * Registrar barras laterales.
 *
 * Se mantienen los registros izquierdo y derecho para compatibilidad, aunque solo se imprime la izquierda.
 */
function sb_register_sidebars() {
    genesis_register_sidebar(
        array(
            'id'   => 'sb-sidebar-right',
            'name' => __( 'Right Sidebar', 'mpp' ),
        )
    );

    genesis_register_sidebar(
        array(
            'id'   => 'sb-sidebar-left',
            'name' => __( 'Left Sidebar', 'mpp' ),
        )
    );
}
add_action( 'widgets_init', 'sb_register_sidebars' );

/**
 * Abrir contenedor del sitio.
 */
function sb_site_open() {
    echo '<div id="sb-site">';
}
add_action( 'genesis_before', 'sb_site_open' );

/**
 * Agregar widgets de barras laterales después del contenido.
 *
 * Solo se renderiza la slidebar izquierda con buscador y menú primario.
 */
function sb_side_bar_widgets() {
    echo '</div>';

    $render_sidebar = function ( $sidebar_id, $classes, $label, $render_default ) {
        echo '<div id="' . esc_attr( $sidebar_id ) . '" class="' . esc_attr( $classes ) . '" role="complementary" aria-label="' . esc_attr( $label ) . '">';

        call_user_func( $render_default );

        if ( is_active_sidebar( $sidebar_id ) ) {
            dynamic_sidebar( $sidebar_id );
        }

        echo '</div>';
    };

    $render_sidebar(
        'sb-sidebar-left',
        'sb-slidebar sb-left widget-area sb-menu',
        'Menu lateral izquierdo',
        function () {
            $search_id = wp_unique_id( 'sb-search-' );
            ?>
            <div class="responsive-search">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <label for="<?php echo esc_attr( $search_id ); ?>">
                        <span class="screen-reader-text"><?php esc_html_e( 'Buscar en el sitio', 'mpp' ); ?></span>
                    </label>
                    <input id="<?php echo esc_attr( $search_id ); ?>" type="search" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" class="search-input" placeholder="<?php esc_attr_e( 'Buscar en el sitio', 'mpp' ); ?>" />
                    <button type="submit" class="search-submit" aria-label="<?php esc_attr_e( 'Buscar', 'mpp' ); ?>">
                        <i class="icon-search" aria-hidden="true"></i>
                        <span class="search-submit-text"><?php esc_html_e( 'Buscar', 'mpp' ); ?></span>
                    </button>
                </form>
            </div>
            <?php
            if ( has_nav_menu( 'primary' ) ) {
                echo '<nav class="sb-menu" aria-label="Menu principal movil">';
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'menu_class'     => 'responsive-nav-menu',
                        'container'      => false,
                        'depth'          => 2,
                        'fallback_cb'    => false,
                    )
                );
                echo '</nav>';
            }
        }
    );
}
add_action( 'genesis_after', 'sb_side_bar_widgets' );

/**
 * Añadir icono de menú a la navegación primaria.
 *
 * @param string   $menu HTML de elementos.
 * @param stdClass $args Argumentos del menú.
 *
 * @return string
 */
function sb_menu_extras( $menu, $args ) {
    if ( 'primary' !== $args->theme_location ) {
        return $menu;
    }

    $menu = '<li class="menu-item mobile-item"><a class="sb-toggle-left" href="#" aria-label="Abrir menú principal" aria-expanded="false" aria-controls="sb-sidebar-left"><i class="icon-menu main-menu-icon"></i></a></li>' . $menu;
    return $menu;
}
add_filter( 'wp_nav_menu_items', 'sb_menu_extras', 10, 2 );

/**
 * Personalizar el texto del botón de búsqueda.
 *
 * @param string $text Texto del botón.
 * @return string
 */
function sb_search_button_text( $text ) {
    return esc_attr( '&#xe900;' );
}
add_filter( 'genesis_search_button_text', 'sb_search_button_text' );

/**
 * Añadir botón de menú en el header.
 */
function sb_add_header_buttons() {
    echo '<a class="sb-toggle-left" href="#" aria-label="Abrir menú principal" aria-expanded="false" aria-controls="sb-sidebar-left"><i class="icon-menu"></i></a>';
}
add_action( 'genesis_header', 'sb_add_header_buttons' );
