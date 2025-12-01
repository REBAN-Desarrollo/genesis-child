# Changelog

Notas resumidas por semana.

## Semana 25.12.33-30

- Loop/plantillas: customizaciones ordenadas, helpers de loop y tarjetas con alt/meta preparados.
- CSS/JS: critical-* se imprimen en plantillas con filemtime y versionado consistente; preloads sanos.
- Seguridad: esc_url en preloads y sanitizado de embeds (YouTube/Instagram).
- Fuentes: URLs absolutas/versionadas en critical CSS inyectado para eliminar 404 de fonts en rutas de pagina.
- WPP: ya no se descola; `wpp-js` queda disponible en todas las plantillas y se marca con `defer` via filtro.

## Semana 25.12.29-25

- UI: hero con aspect-ratio, tarjetas BEM y tokens; header/meta en flex.
- WPP/ads: scripts defer y solo donde aplica; loop ajustado para bajo CLS.
- Helpers perf: versionado de assets y helper reban_perf_versioned_asset para reutilizar rutas/mtime.

## Semana 25.12.24-20

- Sanitizado general (footer, author box, embeds), PHPCS y prefijo reban documentado.
- Accesibilidad: textos de busqueda, focus visible, line-height global y tokens de color/spacing.
- Breakpoints y critical CSS alineados, con bloque para paginas estaticas.

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
