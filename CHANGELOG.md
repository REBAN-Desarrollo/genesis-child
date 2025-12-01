# Changelog

Todas las notas de version del tema okchicas.com.

## 25.12.32

- CSS critico: los `critical-home.css`, `critical-page.css` y `critical-single.css` ahora se imprimen inline en sus plantillas (home/page/single) con `data-version` basado en `filemtime` para evitar la peticion extra del stylesheet.

## 25.12.31

- Seguridad: los preloads de fuentes y CSS cr�tico se imprimen con `esc_url()` para sanear los href en `inc/performance.php`.

## 25.12.30

- Loop de archivo: thumbnails usan `wp_get_attachment_image()` con `sizes` ajustado al ancho de tarjeta y `width/height` expl�citos para fijar el layout y evitar CLS.
- WPP: `wpp-js` solo se carga en single posts cuando el widget est� activo y se imprime con `defer` v�a `script_loader_tag` para quitar el bloqueo de render en home/archivo.
- CSS cr�tico: reglas de tarjetas/primer fold se mueven a `critical-home.css`/`critical-page.css` y se eliminan de `css/style.css` para bajar el payload async.

## 25.12.29

- Single: el hero ahora fuerza `aspect-ratio` y rellena `width`/`height` de thumbnails antiguos aunque les falte metadata, para reducir layout work y acelerar el pintado del LCP.

## 25.12.28

- WordPress Popular Posts: se deja de forzar `wpp-js` al footer para respetar el orden original del plugin.
- WordPress Popular Posts: se usa `defer` via `script_loader_tag` (recomendaci�n del autor) para que `wpp.min.js` no bloquee el render sin alterar su orden en el head; se conserva la etiqueta original (id/data-*) al inyectar el atributo.
- Preloads: se eliminan `preconnect`/`dns-prefetch` innecesarios a orígenes de terceros para evitar advertencias de hints sin uso.

## 25.12.27

- Home/archivo/blog/search migran a tarjetas BEM (`oc-card`) con contenedores, media y meta alineados; se eliminan separadores manuales de fecha/autor y se conservan clases legacy para compatibilidad.
- Header de single adopta bloque BEM (`oc-article-header*`) con meta en flex y espaciado vía tokens para títulos/categorías.
- Los espaciados clave ahora usan tokens (`--spacing-*`) en tarjetas y header; se alinea el CSS crítico de home con los nuevos paddings/gaps.
- Limpieza de `!important` innecesarios (prefers-reduced-motion y sidebar derecho) y version bump del tema.

## 25.12.26

- CSS crítico (home/blog, páginas y single) se sirve desde archivos externos `critical-*.css` con `filemtime` para cache busting y rutas de fuentes relativas al child theme en vez de paths hardcodeados.
- Nuevo helper `reban_perf_versioned_asset()` reutiliza el versionado de assets en preload de fuentes, logos y hojas críticas sin repetir closures.

## 25.12.25

- Los embeds de YouTube ahora validan el host permitido, normalizan el `src` a HTTPS (incluyendo shortlinks `youtu.be`) y escapan la URL antes de reimprimirla en los filtros `embed_*` para evitar inyecciones.

## 25.12.24

- Seguridad: sanitizado de embeds de YouTube/Instagram con `wp_kses` y whitelist de hosts/atributos.
- Rendimiento/seguridad: se elimina el `ob_start` de WPP y se usa `script_loader_tag` para async seguro.
- Sanitizado: salidas de footer/author box y menus escapan datos dinámicos.
- Version del tema sincronizada (`CHILD_THEME_VERSION` y style.css) a 25.12.24.
- Accesibilidad: boton de busqueda de Genesis usa texto descriptivo en lugar de glifo.
- Tooling: se agrega PHPCS (WPCS) con hook `pre-commit` opcional (`git config core.hooksPath .githooks`).
- Docs: AGENTS documenta el uso obligatorio del prefijo `reban_` en funciones/hooks nuevos.
- WordPress Popular Posts: `wpp-js` va al footer para aligerar el head.

## 25.12.23

- Headroom movil lee el breakpoint dentro de `requestAnimationFrame` para agrupar lecturas/escrituras de layout y evitar reflows forzados al alternar clases del header.

## 25.12.22

- CSS: el line-height global vuelve a 1.6 y se eliminan los `line-height: 0` de contenedores para heredar tipografía consistente sin re-declarar en cada bloque.
- CSS: colores sueltos (#222, #333, #F83371, #AC0D12, #999) ahora viven en tokens (`--color-surface-contrast`, `--color-highlight`, etc.) y los textos/enlaces se alinean al sistema de color.
- Accesibilidad: se añade `prefers-reduced-motion` para acortar animaciones/transiciones y un estado `:focus-visible` dedicado en los menús de navegación.

## 25.12.21

- CSS critico: se agrega bloque dedicado para paginas estaticas (is_page, p. ej. Acerca de) separado del global de home/blog.

## 25.12.20

- CSS: se actualiza el índice y los breakpoints listados para reflejar las media queries reales y se eliminan los encabezados vacíos de plugins.
- CSS: se estandarizan los cortes a 1200/992/600/480 (más un `min-width:600` puntual) para usar una escala corta alineada al mercado sin sumar breakpoints extra.
- CSS: se centraliza el azul de acento en `--color-accent` y se limpian reglas vacías/duplicadas (`.fa.pull-right`, hover de YARPP) para reducir ruido y mantener consistencia cromática.

## 25.12.02

- El off-canvas cambia el prefijo abreviado `sb` por `sidebar-*` en IDs, clases y template (incluyendo el contenedor `#sidebar-site` y los toggles de header/menu).
- `sb-offcanvas.php` se renombra a `sidebar-offcanvas.php` y se actualizan `functions.php`, JS y CSS (principal y crítico) para usar el nuevo `sidebar-offcanvas-left` como `aria-controls`/`DEFAULT_SIDEBAR_ID`.
- Las hojas críticas y la README de JS reflejan el nuevo naming y el sidebar derecho legacy se oculta vía `#sidebar-offcanvas-right`.

## 25.12.19

- Las imagenes de contenido que llegan como `.jpg.webp` (Cloudflare/EWWW) ahora buscan el archivo original (.jpg/.png) en uploads para rellenar `width`/`height` y evitar CLS con fotos centradas antiguas sin dimensiones.

## 25.12.18

- Se agregan `preconnect` a los hosts de terceros m\u00e1s usados (Cloudflare, GTM/GA y ScorecardResearch) para reducir la latencia de conexi\u00f3n antes del LCP.
- CSS critico: se recorta y separa el bloque global y de single (header/hero/primer fold) para bajar peso inline sin afectar el primer render.

## 25.12.17

- Headroom movil se habilita en `requestAnimationFrame`, agrupa lecturas/escrituras de scroll y evita reflows forzados al iniciar o cambiar de breakpoint.

## 25.12.16

- Las imagenes del contenido ahora rellenan automaticamente `width`/`height` cuando faltan (usando metadata del adjunto o el archivo en uploads) para que el navegador reserve espacio y evitar CLS con fotos centradas sin tamano.
- El toggle del menu movil se imprime con `role="button"` y un tamaño fijo (CSS inline + hoja principal) para que el icono no provoque layout shift al cargar la fuente.

## 25.12.15

- Slidebar: al bloquear el scroll se calcula el ancho de la barra y se aplica padding compensado para que el body no se mueva al abrir/cerrar el menú.
- Header móvil: el ícono de menú se fija al lado izquierdo con el espaciado de la escala de diseño, gap 0 y centrado vertical junto al logo.
- Header: padding top/bottom afinado a 5px para que el logo quede a 5px de la línea rosa superior y del borde inferior.

## 25.12.14

- Header: el contenedor del logo ahora usa flex/gap para centrar el PNG de 110px sin recortes raros y baja el padding a .5rem en movil.
- CSS critico (global y single) replica el mismo ajuste para que el header se vea consistente en el primer render antes de que cargue style.css.

## 25.12.13

- Se ajusta el header para dar un padding ligero arriba/abajo al logo (img máx 110px) y centrarlo en desktop, replicado en el critical CSS global/single para que no haya huecos raros al cargar.

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

## 25.12.01

- El preload de la imagen destacada ahora reutiliza el mismo `srcset/sizes` que el `<picture>` y detecta tanto `.webp` como `.jpg.webp`, evitando descargas duplicadas cuando EWWW/Cloudflare sirven WebP.

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
