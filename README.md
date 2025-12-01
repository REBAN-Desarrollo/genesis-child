# okchicas.com

Plantilla de WordPress para el sitio [OkChicas](https://okchicas.com).

## Caracteristicas

- Diseno responsivo basado en HTML5 y CSS3.
- Plantillas personalizadas para paginas de inicio, archivos y entradas.
- Integracion con scripts y estilos especificos del sitio.
- Estructura organizada para facilitar la personalizacion.

## Requisitos

- WordPress 5.0 o superior.
- PHP 7.4 o superior.
- Servidor con soporte para MySQL.

## Instalacion

1. Clona este repositorio dentro de la carpeta `wp-content/themes/` de tu instalacion de WordPress.
2. Accede al panel de administracion de WordPress y activa el tema **okchicas.com**.
3. Ajusta las opciones del tema segun sea necesario.

```bash
cd wp-content/themes
git clone https://github.com/<usuario>/okchicas.com.git okchicas
```

## Estructura del proyecto

- `style.css` - Hoja de estilos principal del tema.
- `functions.php` - Funciones y configuraciones del tema.
- `home.php`, `archive.php`, `single.php` - Plantillas principales.
- `css/`, `js/`, `fonts/`, `images/` - Recursos estaticos.

## Sistema de diseno

- Tokens en `css/style.css` bajo `:root`:
  - Colores: `--color-primary` (#d6006b), `--color-text`, `--color-text-muted`, `--color-surface`, `--color-surface-dark`, `--color-border`, `--color-border-strong`.
  - Tipografias: `--font-primary` (Poppins), `--font-secondary` (Proxima Nova), `--font-icon` (rebanfont).
  - Espaciados y foco: `--spacing-xs`/`sm`/`md`/`lg`/`xl`, `--radius-sm`, `--focus-ring`, `--focus-ring-offset`.
- Los estilos de categorias, paginacion, slidebars y los estados `:focus-visible` consumen estos tokens para evitar colores y fuentes sueltas.

## Utilidades de plantillas

- `reban_setup_archive_template( $args )` (`inc/template-helpers.php`): fuerza ancho completo, quita breadcrumbs/meta y sustituye el loop de Genesis por `reban_loop_archive()` con argumentos opcionales (mensaje vacio, formato de fecha, envoltura de autor).

## Diagrama de arquitectura

```
Visitantes HTTP
      |
WordPress Core
      |
Genesis Framework (tema padre)
      |
okchicas.com (tema hijo)
      |-- functions.php (hooks y optimizaciones)
      |-- sidebar.php (sidebars, menus y header/footer extra)
      |-- Plantillas: home.php, archive.php, single.php, search.php
      |-- Assets: css/, js/, fonts/, images/
```

## Hooks usados (vista rapida)

- `wp_enqueue_scripts` (`functions.php`): carga CSS/JS del tema con busting de cache y prioridad sobre Genesis; deregistra estilos heredados que no se usan.
- `style_loader_tag` y `script_loader_tag` (`functions.php`): marca CSS como `preload` y anade `async` a scripts seleccionados para mejorar rendimiento.
- `wp_head` y `wp_footer` (`functions.php`, `single.php`): inserta scripts propios y desregistra librerias de terceros cuando no se necesitan.
- `genesis_*` (varios archivos): ajusta layout (`genesis_pre_get_option_site_layout`), reemplaza el loop (`genesis_loop`), personaliza header/footer (`genesis_header`, `genesis_footer`, `genesis_before`, `genesis_after`) y textos de navegacion/autor.
- `init`, `widgets_init`, `template_redirect` (`functions.php`, `sidebar.php`): registra menus y sidebars, modifica atributos async para scripts de plugins y elimina prefetched DNS.
- `the_content`, `the_time`, `get_search_form`, `embed_handler_html`/`embed_oembed_html` (`functions.php`, `single.php`, `home.php`, `archive.php`, `search.php`): formatea fechas, corrige etiquetas de formularios y ajusta incrustaciones de YouTube e Instagram.

## Desarrollo

Para modificar el tema:

1. Realiza los cambios en los archivos correspondientes.
2. Si anades dependencias de JavaScript o CSS, colocalas en las carpetas `js/` o `css/`.
3. Comprueba que no haya errores de sintaxis:

```bash
php -l functions.php
```

## Contribuciones

Las contribuciones son bienvenidas. Abre un *pull request* describiendo tus cambios y asegurate de seguir las buenas practicas de WordPress.

## Licencia

El codigo se proporciona tal cual. Consulta al equipo de OkChicas para detalles sobre su uso o distribucion.
