# AGENTS.md - genesis-child

Este archivo reemplaza al antiguo `agents.md`; usa este canon como referencia principal.

## 1. Proposito (WHY)
- Tema hijo Genesis para okchicas.com centrado en rendimiento (cache busting, preload/async) y accesibilidad (formularios/fechas).
- Mantener la plantilla ligera y compatible con el core de WordPress y el tema padre Genesis.

## 2. Mapa rapido (WHAT)
- Stack: WordPress 5+, PHP 7.4+, Genesis Framework; assets estaticos en `css/`, `js/`, `fonts/`, `images/` y critical CSS (`critical-*.css`).
- Archivos clave: `functions.php` (hooks/perf), `home.php`/`archive.php`/`single.php`/`search.php` (plantillas), `sidebar.php` y `sidebar-offcanvas.php`, carpeta `inc/`, `README.md`, `CHANGELOG.md`, `phpcs.xml.dist`.
- Evita tocar: core de WordPress, tema padre Genesis, `wp-content/uploads/`, dependencias de plugins y archivos de cache/logs.
- Hooks existentes primero: revisa los hooks de Genesis y helpers actuales antes de introducir nuevos.

## 3. Como trabajar (Dev/Test/Build)
- PHP lint/estilo: `composer install` (solo dev) y `composer run lint:php` usando `phpcs.xml.dist` (WordPress + Docs).
- Assets: no hay build; CSS/JS se editan directo. Manten el versionado por `filemtime` al encolar en `wp_enqueue_scripts`.
- Prefijo: funciones/filters/actions propios con `reban_`; documenta excepciones si provienen de terceros.
- Performance: conserva preload/async/defer existentes y los helpers de versionado antes de sumar dependencias nuevas.

## 4. Restricciones / safety
- No agregar dependencias externas sin justificacion clara; prioriza APIs nativas de WordPress/Genesis.
- Sanitiza/escapa salida (formularios, fechas, embeds) y evita queries SQL directas.
- No hardcodees URLs absolutas; usa helpers (`home_url`, etc.) y respeta layouts/hooks de Genesis.
- No expongas datos sensibles en plantillas.

## 5. Definition of Done
- PHPCS pasa limpio con `phpcs.xml.dist`.
- Plantillas clave cargan sin notices/warnings: home, archive, single, search.
- Cache busting y hooks de Genesis se mantienen (enqueue versionado, preloads/async vigentes).
- `CHANGELOG.md` actualizado para cambios relevantes.

## 6. Docs clave
- `README.md`: requisitos, overview y arquitectura.
- `CHANGELOG.md`: log semanal de cambios del tema.
- `phpcs.xml.dist`: estandar WordPress + Docs.
- `composer.json`: dependencias de desarrollo y script `lint:php`.
- (Opcional) `PROMPTS.md` y reglas de Cursor/Claude apuntan a este canon.

## 7. Ignorar / evitar
- No editar `wp-admin/`, `wp-includes/` ni el tema padre Genesis.
- No versionar `node_modules/`, `vendor/`, caches, backups o `uploads/`.

## 8. Prompts y herramientas
- Si usas agentes (Codex/Claude/Cursor), carga este archivo como canon.
- Prompts comunes: revisar plantillas, refactor PHP manteniendo hooks, validar preloads/async, actualizar critical CSS sin duplicar tokens.
