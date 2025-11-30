<?php
/**
 * Plantilla de archivo personalizada.
 * Gestiona el dise?o y el contenido utilizando el tema hijo de Genesis Framework.
 */

/** Configurar la plantilla de archivo con helper compartido */
reban_setup_archive_template(array(
    'empty_message' => 'No hay publicaciones.',
));

/** Ejecutar Genesis */
genesis();
