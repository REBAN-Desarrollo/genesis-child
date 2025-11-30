<?php
/**
 * Plantilla de resultados de b?squeda personalizada.
 * Gestiona el dise?o y el contenido utilizando el tema hijo de Genesis Framework.
 */

/** Configurar la plantilla de b?squeda con helper compartido */
reban_setup_archive_template(array(
    'empty_message' => 'No hay publicaciones.',
));

/** Ejecutar Genesis */
genesis();
