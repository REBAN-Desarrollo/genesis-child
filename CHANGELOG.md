# Changelog

Todas las notas de version del tema okchicas.com.

## 25.11.27

- Preloads de logo/fuentes y el CSS critico ahora usan rutas del child theme con versionado via `filemtime`, evitando 404 si cambia el nombre del directorio.
- Formularios de busqueda (header y filtro global) generan IDs unicos con `wp_unique_id()` para que las etiquetas apunten al input correcto sin duplicados.

## 25.11.26

- Preloads de logo/fuentes ahora usan helpers de rutas del child y `filemtime`; se elimina el bloque de "critical CSS" duplicado para dejar que la hoja principal se cachee.
- `js/all.js` se encola en footer con versionado por `filemtime` y `async` solo para los handles esperados (tema y `wpp-js`) via `script_loader_tag`.
- Formularios de busqueda (header y filtro global) generan IDs unicos con `wp_unique_id()` para que las etiquetas apunten al control correcto.
- La desregistracion de jQuery es ahora condicional y respeta colas/dependencias activas para evitar romper plugins que lo requieran.

## 25.11.25

- Estandarizamos los prefijos `reban_` por modulo (setup, assets, performance, cleanup, accesibilidad, customizaciones, single y loops de archivos) para que las busquedas sean coherentes.
- Ajustamos hooks y filtros a los nuevos nombres, incluyendo el helper de loop `reban_loop_archive()` y las rutinas de limpieza (emojis, Gutenberg, wp-embed) con funciones dedicadas.

## 24.05.17

- Base del tema hijo sobre Genesis con plantillas personalizadas para `home.php`, `archive.php`, `single.php` y `search.php`.
- Optimizaciones de rendimiento: busting de cache para CSS/JS, `preload` del CSS principal y `async` para scripts seleccionados.
- Ajustes de layout: ancho completo en listados y bucles de contenido personalizados para home/archivo/busqueda.
- Hooks de cabecera y pie que insertan scripts propios, limpian dependencias innecesarias y personalizan el footer.
- Registro de menus y sidebars adicionales (header, footer y secciones laterales) via `sb-functions.php`.
- Mejoras de contenido: fechas en espanol, etiquetas accesibles en formularios, incrustaciones ajustadas de YouTube/Instagram y botones de navegacion/autor personalizados.
