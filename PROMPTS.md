# PROMPTS.md - genesis-child

## [analysis] Entender una plantilla
Quiero que analices `PATH_AQUI.php` del tema hijo y expliques:
- Que hace a alto nivel y que hooks/filters de Genesis o WordPress usa.
- Que salida visual produce y si depende de assets criticos (`critical-*.css`) o enqueue versionado.
- Riesgos de accesibilidad (formularios/fechas) o rendimiento (preload/async/defer) segun `AGENTS.md`.

## [refactor] Limpiar un archivo PHP
Refactoriza `PATH_AQUI.php` siguiendo `AGENTS.md`:
- Mantener hooks existentes y el prefijo `reban_`.
- Mejorar sanitizacion/escape de salidas y evitar queries directas.
- Confirmar que el enqueue de assets mantiene `filemtime` para cache busting.

## [perf-check] Revisar carga de assets
Revisa como se cargan CSS/JS en `functions.php` o en la plantilla `PATH_AQUI`:
- Confirma que los preloads/async/defer siguen las reglas del tema y no generan descargas duplicadas.
- Sugiere mejoras manteniendo compatibilidad con Genesis/WordPress y sin agregar dependencias externas.
- Indica que pruebas rapidas harias (PHPCS, revisar home/archive/single/search).
