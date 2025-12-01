<?php
/**
 * sidebar-offcanvas.php
 *
 * Slidebar izquierda fija con buscador y menu primario (sin widgets dinamicos).
 */

/**
 * Registrar barras laterales (compatibilidad en admin, aunque no se rendericen).
 */
function sidebar_register_sidebars() {
    genesis_register_sidebar(
        array(
            'id'   => 'sidebar-offcanvas-right',
            'name' => __( 'Right Sidebar', 'mpp' ),
        )
    );

    genesis_register_sidebar(
        array(
            'id'   => 'sidebar-offcanvas-left',
            'name' => __( 'Left Sidebar', 'mpp' ),
        )
    );
}
add_action( 'widgets_init', 'sidebar_register_sidebars' );

/**
 * Abrir contenedor del sitio.
 */
function sidebar_site_open() {
    echo '<div id="sidebar-site">';
}
add_action( 'genesis_before', 'sidebar_site_open' );

/**
 * Cerrar contenedor del sitio e imprimir slidebar izquierda fija.
 */
function sidebar_render_offcanvas() {
    echo '</div>';

    $search_id = wp_unique_id( 'sidebar-search-' );
    ?>
    <template id="sidebar-slidebar-template">
        <div id="sidebar-offcanvas-left" class="sidebar-slidebar sidebar-left widget-area sidebar-menu" role="complementary" aria-label="<?php esc_attr_e( 'Menu lateral izquierdo', 'mpp' ); ?>">
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
            <?php if ( has_nav_menu( 'primary' ) ) : ?>
                <nav class="sidebar-menu" aria-label="<?php esc_attr_e( 'Menu principal movil', 'mpp' ); ?>">
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'primary',
                            'menu_class'     => 'responsive-nav-menu',
                            'container'      => false,
                            'depth'          => 2,
                            'fallback_cb'    => false,
                        )
                    );
                    ?>
                </nav>
            <?php endif; ?>
        </div>
    </template>
    <?php
}
add_action( 'genesis_after', 'sidebar_render_offcanvas' );

/**
 * Personalizar el texto del boton de busqueda.
 *
 * @param string $text Texto del boton.
 * @return string
 */
function sidebar_search_button_text( $text ) {
    // Prefer texto descriptivo para accesibilidad en lugar de icono.
    return esc_html__( 'Buscar', 'mpp' );
}
add_filter( 'genesis_search_button_text', 'sidebar_search_button_text' );

/**
 * Anadir toggle de menu en header y menu primario.
 */
function sidebar_add_header_buttons() {
    echo '<a class="sidebar-toggle-left" href="#" aria-label="Abrir menu principal" aria-expanded="false" aria-controls="sidebar-offcanvas-left" role="button"><i class="icon-menu"></i></a>';
}
add_action( 'genesis_header', 'sidebar_add_header_buttons' );

function sidebar_menu_extras( $menu, $args ) {
    if ( 'primary' !== $args->theme_location ) {
        return $menu;
    }

    $menu = '<li class="menu-item mobile-item"><a class="sidebar-toggle-left" href="#" aria-label="Abrir menu principal" aria-expanded="false" aria-controls="sidebar-offcanvas-left" role="button"><i class="icon-menu main-menu-icon"></i></a></li>' . $menu;
    return $menu;
}
add_filter( 'wp_nav_menu_items', 'sidebar_menu_extras', 10, 2 );
