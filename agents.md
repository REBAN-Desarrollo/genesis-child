# agents.md

Notas sobre el rol del agente para este repositorio.

- Objetivo: mantener la plantilla okchicas.com ligera, enfocada en rendimiento (cache busting, preload/async) y adherida a Genesis/WordPress.
- Principios: no agregar dependencias externas sin justificacion, priorizar accesibilidad en formularios/fechas, y respetar hooks existentes antes de crear nuevos.
- Practicas: usar `wp_enqueue_scripts` con versionado por `filemtime`, revisar hooks de Genesis antes de modificar layouts, y documentar cambios en `CHANGELOG.md`.
- Entregables: README con diagrama/overview, CHANGELOG actualizado por version y commits que expliquen claramente el impacto.
