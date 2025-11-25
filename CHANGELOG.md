# Changelog

Todas las notas de version del tema okchicas.com.

## 25.11.25

- Estandarizamos los prefijos `reban_` por módulo (setup, assets, performance, cleanup, accesibilidad, customizaciones, single y loops de archivos) para que las búsquedas sean coherentes.
- Ajustamos hooks y filtros a los nuevos nombres, incluyendo el helper de loop `reban_loop_archive()` y las rutinas de limpieza (emojis, Gutenberg, wp-embed) con funciones dedicadas.

## 24.05.17

- Base del tema hijo sobre Genesis con plantillas personalizadas para `home.php`, `archive.php`, `single.php` y `search.php`.
- Optimizaciones de rendimiento: busting de cache para CSS/JS, `preload` del CSS principal y `async` para scripts seleccionados.
- Ajustes de layout: ancho completo en listados y bucles de contenido personalizados para home/archivo/busqueda.
- Hooks de cabecera y pie que insertan scripts propios, limpian dependencias innecesarias y personalizan el footer.
- Registro de menus y sidebars adicionales (header, footer y secciones laterales) via `sb-functions.php`.
- Mejoras de contenido: fechas en español, etiquetas accesibles en formularios, incrustaciones ajustadas de YouTube/Instagram y botones de navegacion/autor personalizados.
