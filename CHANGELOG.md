# Changelog

Todas las notas de version del tema okchicas.com.

## 25.12.12

- Home/blog vuelven a un feed de una sola columna (en vez del grid auto-fill) para que cada fila alterne portada y texto como antes.
- La slidebar izquierda se imprime dentro de un `<template>` y se inserta al primer click del toggle, reduciendo nodos y trabajo de layout en la carga inicial.
- La imagen destacada precarga el mismo `srcset/sizes` que usa el `<picture>` para evitar descargas duplicadas (preload + render) y mantener LCP bajo.

## 25.12.11

- Headroom cachea la posicion de scroll y difiere los toggles via requestAnimationFrame para evitar forced reflow al iniciar o al redimensionar.

## 25.12.10

- `js/all.js` inicializa slidebar, headroom y el colapso del billboard tan pronto carga (aunque el script llegue async) revisando `document.readyState`, para que no quede una franja negra en header cuando el slot de anuncio viene vacio.

## 25.12.05

- `sb-functions.php` se renombra a `sidebar.php` y todas las funciones usan prefijo `sidebar_*` para que el naming refleje claramente el rol de sidebars/off-canvas.
- Includes y documentación (README, changelog) apuntan al nuevo archivo.

## 25.12.06

- Se reactiva la slidebar izquierda con buscador y menú primario fijos (sin `dynamic_sidebar`) y vuelven los toggles en header/menú para abrirla.

## 25.12.07

- Se quita el padding global de `#sb-site` (CSS y critical CSS) para que el lienzo sea full width y no encajone el contenido.
- El header vuelve a mostrar un logo (custom, header o fallback del tema) via filtro `genesis_seo_title`, y se desactiva el buscador duplicado del header.

## 25.12.08

- Se separa el off-canvas a `sb-offcanvas.php` y se restaura `sidebar.php` como plantilla de sidebar primario (Genesis) para que reaparezca la barra lateral derecha en single.

## 25.12.09

- El fallback de logo usa `images/Logo-OK-footer-blanco.png` con versionado por `filemtime` (y fallback absoluto `/wp-content/themes/genesis-child/...`) para que siempre cargue en el header.

## 25.12.04

- Simplificamos la deteccion de bajada en headroom al quitar la comprobacion redundante de distancia.
- El listener de resize de headroom ahora usa un debounce ligero para evitar trabajo extra al redimensionar.

## 25.12.03

- Se reestructura la slidebar: solo se imprime la izquierda (se mantiene el registro de la derecha por compatibilidad) y el buscador vive al inicio de ese panel, eliminando el toggle/botón derecho.
- Estilos del off-canvas actualizados (fondo degradado, inputs en píldora, hover con acento y listas sin bullets rojos) para que ambos menús laterales se vean más limpios en móvil.
- El logo del header usa el PNG del tema como fallback explícito para evitar que desaparezca cuando no hay un logo personalizado cargado.

## 25.12.02

- Se desactivo el render de las slidebars en `sidebar.php`, dejando solo el contenedor `#sb-site` para evitar que se impriman menus/widgets en el sidebar y se removieron los toggles del menu primario/header.

## 25.12.01

- Se actualizó la cabecera de `style.css` a la versión 25.12.01 para asegurar que WordPress tome el update del tema.

## 25.11.37

- Limpieza de `css/style.css`: se retiraron `!important` obsoletos y prefijos heredados en menús/slidebar, y el sidebar lateral se queda sticky con `position: sticky` en desktop.
- `js/all.js` ahora es vanilla (slidebar izquierda con overlay, headroom móvil, videos fluidos y guardado de billboard) y ya no depende de jQuery encolado en `inc/assets.php`.

## 25.11.36

- Los prefijos `okc_*`/`sp_*` se normalizan a `reban_*` y se documentan los filtros de accesibilidad/meta para mantener los hooks alineados.
- Nuevo helper `reban_setup_archive_template()` en `inc/template-helpers.php` que desactiva breadcrumbs/meta, fuerza ancho completo y reemplaza el loop en `home.php`, `archive.php` y `search.php`.
- Fuentes: los fallbacks y el CSS critico ahora usan `font-display: swap` y se mantienen los preloads woff2 solo para las tres familias criticas.
- Sistema de diseno con custom properties en `css/style.css` (colores, tipografias, espaciados, focus ring) y primario ajustado a #d6006b para mejorar contraste y estados `:focus-visible` uniformes.
- Accesibilidad en listados: miniaturas con `alt` explicito, botones de busqueda con `aria-label`, y labels/categorias apoyadas en el nuevo primario para legibilidad.

## 25.11.35

- Se eliminaron las fuentes TTF de todas las declaraciones `@font-face` y CSS crítico (sólo se sirven WOFF2/WOFF), manteniendo los archivos en el tema para posibles conversiones futuras.

## 25.11.34

- Las slidebars ahora siempre se pintan (aunque no haya widgets) con menú primario de respaldo y un buscador dedicado, de modo que los toggles vuelven a abrir contenido en móvil.
- El bloque de búsqueda que se renderiza en el header queda oculto para evitar la franja negra; el buscador vive dentro de la slidebar derecha con estilos visibles (y se mantiene incluso si agregas widgets).
- El `preload` del logo toma la ruta del logo personalizado/encabezado activo antes de usar el fallback, eliminando la advertencia de recurso no usado.

## 25.11.33

- Las declaraciones `@font-face` de `style.css` ahora usan rutas relativas a `../fonts/` para evitar 404 si cambia el nombre del directorio del tema.
- El CSS crítico de `single.php` versiona las fuentes con `filemtime` (igual que `performance.php`), reemplazando las rutas hardcodeadas del child theme por URLs dinámicas.

## 25.11.32

- Los toggles de Slidebars ahora incluyen `aria-expanded` y `aria-controls` apuntando a sus sidebars para mejorar la navegacion con lectores de pantalla.
- Enlaces sociales del footer incorporan texto accesible usando la nueva utilidad `.screen-reader-text`.
- Las skip-links usan clipping en vez de desplazar fuera de pantalla, haciendo visible el bloque al navegar con teclado.

## 25.11.31

- Restauramos el handle `jquery` de WordPress al eliminar su desregistracion en cleanup, para que Slidebars y HC-Sticky vuelvan a inicializarse.
- `js/all.js` ahora solo incluye los plugins (Slidebars, Headroom, HC-Sticky) y se encola dependiente de `jquery` en footer, sin la copia embebida de jQuery.

## 25.11.30

- `wp-embed` ahora solo se desregistra cuando la entrada no requiere incrustaciones, evitando romper shortcodes que dependen de ese script.
- La hoja `classic-theme-styles` se mantiene activa en entradas con bloques para no afectar estilos de Gutenberg; solo se elimina en vistas sin bloques.

## 25.11.29

- Ocultamos el billboard superior cuando GPT no llena el slot para evitar franjas negras sobre el titulo, con fondo blanco forzado como respaldo.

## 25.11.28

- Forzamos fondo blanco en el contenedor de billboard superior para evitar que aparezca una franja negra detras del titulo cuando no carga un anuncio.
- La version del CSS/JS ahora usa `filemtime` en segundos completos para que el query string cambie en cada guardado y no solo una vez por minuto.

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
- Registro de menus y sidebars adicionales (header, footer y secciones laterales) via `sidebar.php` (antes `sb-functions.php`).
- Mejoras de contenido: fechas en espanol, etiquetas accesibles en formularios, incrustaciones ajustadas de YouTube/Instagram y botones de navegacion/autor personalizados.
