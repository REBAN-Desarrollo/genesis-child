# Changelog

Notas resumidas por semana.

## Semana 25.12.36-09

- CSS crítico: se purgan los tokens/iconos duplicados en critical-*.css porque ahora se inyectan desde `css/design-tokens.css` en `wp_head`.

## Semana 25.12.35-02

- Seguridad: functions.php valida Genesis activo y solo incluye sidebar-offcanvas.php si el archivo existe.
- Sidebar: funciones offcanvas prefijadas con `reban_` y guardadas tras verificar `genesis_register_sidebar`.
- YARPP: la plantilla usa src/srcset nativo y elimina el helper `my_resize`.
- Accesibilidad: `.credit-wrapper` deja de estar oculto para que los créditos sean visibles/alcanzables.
- CI: se añade workflow de GitHub Actions para ejecutar PHPCS en push y PR.

## Semana 25.12.34-03

- CSS: se extraen tokens/icomoon/motion a `css/design-tokens.css` (versionado con filemtime) y `style.css` depende de ese handle.
- Critical CSS: `reban_perf_get_critical_css` preprende el bloque de tokens y purga duplicados/resets antes de minificar, incluyendo el critical de single.

## Semana 25.12.33-30

- Home: se alinean las tarjetas al inicio (sin centrado vertical) para eliminar espacios raros entre filas/columnas.
- WPP: el tracker se auto-encola en singles/bloques y se fuerza defer (con fallback en script_loader_tag) para evitar bloqueo aunque se registre tarde.
- Home: se restaura el layout flex de las tarjetas (imagen a la izquierda/titulo a la derecha en alternancia) en home y archivos tras limpiar el critical CSS.
- Header: se desactiva el CSS inline/background del header image para que el logo solo se pinte vía <img> (sin duplicados).
- Logo: preload y render comparten helper versionado/WebP para que la URL sea única (sin doble descarga).
- Header: altura del logo ajustada a 4rem (40px) para mantener proporción en el header.
- Perf: critical CSS inline (home/page/single) ahora se minifica antes de imprimir para reducir altura del HTML.
- Loop/plantillas: customizaciones ordenadas, helpers de loop y tarjetas con alt/meta preparados.
- CSS/JS: critical-* se imprimen en plantillas con filemtime y versionado consistente; preloads sanos.
- Seguridad: esc_url en preloads y sanitizado de embeds (YouTube/Instagram).
- Fuentes: URLs absolutas/versionadas en critical CSS inyectado para eliminar 404 de fonts en rutas de pagina.
- CLS: bloque inline de toggles minificado para reservar espacio sin overhead en head.
- Critical CSS: home/page/single actualizados con nuevos tokens y layout base ligero.
- Logo: preload ahora detecta variante WebP local/versionada y la usa para evitar doble descarga cuando el HTML pinta otro formato.
- Logo/header: si hay custom logo se desactiva el header image y su CSS inline para que no se duplique el asset (png/webp) por background + markup.
- WPP: el script wpp-js se marca con strategy defer para que no bloquee el render.

## Semana 25.12.29-25

- UI: hero con aspect-ratio, tarjetas BEM y tokens; header/meta en flex.
- WPP/ads: scripts defer y solo donde aplica; loop ajustado para bajo CLS.
- Helpers perf: versionado de assets y helper reban_perf_versioned_asset para reutilizar rutas/mtime.

## Semana 25.12.24-20

- Sanitizado general (footer, author box, embeds), PHPCS y prefijo reban documentado.
- Accesibilidad: textos de busqueda, focus visible, line-height global y tokens de color/spacing.
- Breakpoints y critical CSS alineados, con bloque para paginas estaticas.

## Semana 25.12.26-30

- Critical CSS cacheado en transients por filemtime para evitar lecturas en cada request y mantener busting.
- Plantilla YARPP evita resizes on-the-fly y valida rutas locales antes de leer dimensiones.
- Toggles off-canvas ahora usan `<button>` accesible y estilos móviles actualizados.
- Billboard reserva altura responsive (desktop/móvil) para evitar CLS y sigue colapsando cuando el slot viene vacío.

## Semana 25.12.19-15

- Imagenes rellenan width/height (incluyendo .jpg.webp) y preload ajustado.
- Rendimiento: preconnect a terceros clave; headroom/toggles en requestAnimationFrame y con debounce ligero.
- Header/slidebar: ajustes de padding, boton de menu fijo y compensacion de scroll al abrir.

## Semana 25.12.14-10

- Header en flex/gap y critical CSS consistente; feed vuelve a una columna.
- Slidebar izquierda lazy via <template>, hover/inputs estilizados; hero preload sin descargas dobles.
- JS: inicializacion temprana (slidebar/headroom/billboard) aun con scripts async.

## Semana 25.12.09-05

- Off-canvas renombrado a sidebar-offcanvas con nuevos IDs/prefijos y logo fallback versionado.
- Reactivacion de slidebar izquierda con buscador/menu; padding global removido para lienzo full width.
- Sidebar derecho restaurado en single y rutas de assets versionadas por filemtime.

## Semana 25.12.04-01

- Reestructura slidebar: solo la izquierda visible; buscador vive ahi; toggle derecho fuera.
- Limpieza de deteccion de headroom y resize debounce; style.css version 25.12.01.

## Semana 25.11.37-33

- Limpieza CSS/JS: sin jQuery embebido, slidebar/headroom en vanilla; sidebar sticky y prefijos reban.
- Helper reban_setup_archive_template reemplaza loops y estandariza ganchos; fonts woff2/woff con rutas relativas y critical CSS versionado.
- Accesibilidad: alt explicitos, skip-links visibles, toggles con aria y formularios con IDs unicos.

## Semana 25.11.32-29

- Restaurar handle jquery cuando se necesita y condicionar wp-embed/classic-theme-styles segun bloques.
- Preloads de logo/fuentes y CSS/JS versionados con filemtime; fondo blanco/billboard para evitar franjas.

## 24.05.17 (base)

- Tema hijo Genesis con plantillas home/archive/single/search, cache busting de CSS/JS y preloads/async.
- Hooks de header/footer para limpiar dependencias y personalizar el footer; registros de menus/sidebars.
- Mejoras de contenido: fechas en espanol, formularios accesibles, embeds YouTube/Instagram saneados y navegacion/autor personalizados.
