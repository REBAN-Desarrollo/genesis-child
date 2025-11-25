<?php
/**
 * Plantilla de resultados de b�squeda personalizada.
 * Gestiona el dise�o y el contenido utilizando el tema hijo de Genesis Framework.
 */

/** Forzar el contenido de ancho completo */
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

/** Eliminar la ruta de navegaci�n (breadcrumb) */
remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');

/** Modificar el formato de fecha y la etiqueta del mes */
add_filter('the_time', 'reban_custom_date_format');

/** Configurar el bucle compartido para resultados de b�squeda */
remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', function() {
    reban_loop_archive(array(
        'empty_message' => 'No hay publicaciones.',
    ));
});

/** Eliminar la funci�n de meta de la entrada solo en la p�gina principal */
remove_action('genesis_after_post_content', 'genesis_post_meta');

/** Ejecutar Genesis */
genesis();
