<?php
/**
 * Plantilla de la p�gina de inicio.
 * Este archivo gestiona el dise�o y el contenido de la p�gina de inicio del sitio, utilizando el tema hijo de Genesis Framework.
 */

/** Forzar el contenido de ancho completo */
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

/** Eliminar la ruta de navegaci�n (breadcrumb) */
remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');

/** Modificar el formato de fecha y la etiqueta del mes */
add_filter('the_time', 'reban_custom_date_format');

/** Configurar el bucle compartido para la p�gina de inicio */
remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', function() {
    reban_loop_archive(array(
        'date_format'     => 'F j',
        'wrap_author_box' => 'home-author-box',
    ));
});

/** Eliminar la funci�n de meta de la entrada solo en la p�gina principal */
remove_action('genesis_after_post_content', 'genesis_post_meta');

/** Ejecutar Genesis */
genesis();
