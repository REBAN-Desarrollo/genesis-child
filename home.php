<?php
/**
 * Plantilla de la p?gina de inicio.
 * Este archivo gestiona el dise?o y el contenido de la p?gina de inicio del sitio, utilizando el tema hijo de Genesis Framework.
 */

/** Configurar la plantilla con layout y loop unificados */
reban_setup_archive_template(array(
    'date_format'     => 'F j',
    'wrap_author_box' => 'home-author-box',
));

/** Ejecutar Genesis */
genesis();
