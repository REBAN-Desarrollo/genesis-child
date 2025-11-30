# JS sin jQuery

## Slidebar
- Usa `.sb-slidebar` con un `id` (por defecto `sb-sidebar-left`).
- Los toggles (`.sb-toggle-left`, `.sb-open-left`, etc.) necesitan `aria-controls` apuntando al sidebar; si sólo hay uno se asigna automáticamente.
- La clase `is-open` vive en el sidebar, el body/html se bloquean con `sb-locked` y el overlay `sb-overlay` se añade en runtime.
- `Escape`, clic en el overlay o en el contenido cierran la barra; los toggles de búsqueda enfocan el primer `input[type="search"]` dentro del sidebar.

## Headroom (header móvil)
- Activo sólo en viewports ≤ 927px con offset 40px y tolerancia de 20px al subir.
- Clases usadas: `headroom`, `bajando` (oculta), `subiendo` (muestra), `topando` (en top) y `noesarriba` (scroll > offset).

## Sidebar sticky
- Sin HC-Sticky: `.sidebar .widget:last-child` queda `position: sticky` desde 945px; con admin bar el `top` sube a 3.2rem.

## Otros
- Videos fluidos: se envuelven en `.fluid-video` dentro de `.full-movil` para los iframes de Vimeo, YouTube, Dailymotion, etc. (sólo si no estaban envueltos).
- Billboard: el slot `div-gpt-ad-1624665948236-0` se oculta si no carga GPT o llega vacío; también fuerza fondo blanco.
- `js/all.js` no depende de jQuery y se encola en footer con versión `filemtime` desde `inc/assets.php`.
