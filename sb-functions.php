<?php
/**
 * Archivo sb-functions.php
 * 
 * Este archivo gestiona las barras laterales (sidebars) y la navegación del sitio web en el tema hijo de Genesis Framework.
 * Implementa slidebars para una navegación más dinámica y accesible.
 */

/**
 * Registrar barras laterales.
 * 
 * Estas funciones registran dos barras laterales: una para el lado derecho y otra para el lado izquierdo del sitio web.
 * Estas barras laterales se pueden utilizar para agregar widgets a través del personalizador de WordPress.
 */
function sb_register_sidebars() {
    genesis_register_sidebar(array(
        'id' => 'sb-sidebar-right',
        'name' => __('Right Sidebar', 'mpp')
    ));

    genesis_register_sidebar(array(
        'id' => 'sb-sidebar-left',
        'name' => __('Left Sidebar', 'mpp')
    ));
}
add_action('widgets_init', 'sb_register_sidebars');

/**
 * Abrir contenedor del sitio.
 * 
 * Esta función se ejecuta antes de que Genesis cargue el contenido del sitio. Abre un contenedor <div> con el id `sb-site` 
 * que envuelve todo el contenido del sitio.
 */
function sb_site_open() {
    echo '<div id="sb-site">';
}
add_action('genesis_before', 'sb_site_open');

/**
 * Agregar widgets de barras laterales después del contenido.
 * 
 * Esta función cierra el contenedor `#sb-site` y agrega las áreas de widgets para las barras laterales izquierda y derecha si están activas.
 */
function sb_side_bar_widgets() {
    echo '</div>';

    $render_sidebar = function( $sidebar_id, $classes, $label, $render_default ) {
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
            if ( has_nav_menu( 'primary' ) ) {
                echo '<nav class="sb-menu" aria-label="Menú principal móvil">';
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

    $render_sidebar(
        'sb-sidebar-right',
        'sb-slidebar sb-right sb-style-overlay widget-area',
        'Buscador lateral',
        function () {
            $search_id = wp_unique_id( 'sb-search-' );
            ?>
            <div class="responsive-search">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <label for="<?php echo esc_attr( $search_id ); ?>">
                        <span class="screen-reader-text"><?php esc_html_e( 'Buscar en el sitio', 'mpp' ); ?></span>
                    </label>
                    <input id="<?php echo esc_attr( $search_id ); ?>" type="search" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" class="search-input" placeholder="<?php esc_attr_e( 'Buscar en el sitio', 'mpp' ); ?>" />
                    <input type="submit" class="search-submit" value="<?php esc_attr_e( 'Buscar', 'mpp' ); ?>" aria-label="<?php esc_attr_e( 'Buscar', 'mpp' ); ?>"/>
                </form>
            </div>
            <?php
        }
    );
}
add_action('genesis_after', 'sb_side_bar_widgets');

/**
 * Añadir íconos de menú y búsqueda a la navegación.
 * 
 * Filtra los elementos del menú, agregando un ícono de menú hamburguesa antes y un ícono de búsqueda después.
 *
 * @param string   $menu HTML string de elementos de la lista.
 * @param stdClass $args Argumentos del menú.
 *
 * @return string Cadena HTML modificada de elementos de la lista.
 */
function sb_menu_extras($menu, $args) {
    // Cambiar 'primary' a 'secondary' para agregar extras al menú de navegación secundario
    if ('primary' !== $args->theme_location) {
        return $menu;
    }
    $menu = '<li class="menu-item mobile-item"><a class="sb-toggle-left" href="#" aria-label="Abrir menú principal" aria-expanded="false" aria-controls="sb-sidebar-left"><i class="icon-menu main-menu-icon"></i></a></li>' . $menu;
    $menu .= '<li class="menu-item mobile-item"><a class="sb-toggle-right search-icon" href="#" aria-label="Abrir buscador" aria-expanded="false" aria-controls="sb-sidebar-right"><i class="icon-search main-menu-icon"></i></a></li>';
    return $menu;
}
add_filter('wp_nav_menu_items', 'sb_menu_extras', 10, 2);

/**
 * Personalizar el texto del botón de búsqueda.
 * 
 * Esta función personaliza el texto del botón de búsqueda en Genesis utilizando un icono.
 *
 * @param string $text El texto del botón de búsqueda.
 * @return string Texto del botón de búsqueda personalizado.
 */
function sb_search_button_text($text) {
    return esc_attr('&#xe900;');
}
add_filter('genesis_search_button_text', 'sb_search_button_text');

/**
 * Añadir botones de menú y búsqueda al encabezado.
 * 
 * Esta función añade botones de menú y búsqueda en el encabezado del sitio.
 */
function sb_add_header_buttons() {
    echo '<a class="sb-toggle-left" href="#" aria-label="Abrir menú principal" aria-expanded="false" aria-controls="sb-sidebar-left"><i class="icon-menu"></i></a>';
    echo '<a class="sb-toggle-right search" href="#" aria-label="Abrir buscador" aria-expanded="false" aria-controls="sb-sidebar-right"><i class="icon-search"></i></a>';
}
add_action('genesis_header', 'sb_add_header_buttons');
