# okchicas.com

Plantilla de WordPress para el sitio [OkChicas](https://okchicas.com).

## Características

- Diseño responsivo basado en HTML5 y CSS3.
- Plantillas personalizadas para páginas de inicio, archivos y entradas.
- Integración con scripts y estilos específicos del sitio.
- Estructura organizada para facilitar la personalización.

## Requisitos

- WordPress 5.0 o superior.
- PHP 7.4 o superior.
- Servidor con soporte para MySQL.

## Instalación

1. Clona este repositorio dentro de la carpeta `wp-content/themes/` de tu instalación de WordPress.
2. Accede al panel de administración de WordPress y activa el tema **okchicas.com**.
3. Ajusta las opciones del tema según sea necesario.

```bash
cd wp-content/themes
git clone https://github.com/<usuario>/okchicas.com.git okchicas
```

## Estructura del proyecto

- `style.css` – Hoja de estilos principal del tema.
- `functions.php` – Funciones y configuraciones del tema.
- `home.php`, `archive.php`, `single.php` – Plantillas principales.
- `css/`, `js/`, `fonts/`, `images/` – Recursos estáticos.

## Desarrollo

Para modificar el tema:

1. Realiza los cambios en los archivos correspondientes.
2. Si añades dependencias de JavaScript o CSS, colócalas en las carpetas `js/` o `css/`.
3. Comprueba que no haya errores de sintaxis:

```bash
php -l functions.php
```

## Contribuciones

Las contribuciones son bienvenidas. Abre un *pull request* describiendo tus cambios y asegúrate de seguir las buenas prácticas de WordPress.

## Licencia

El código se proporciona tal cual. Consulta al equipo de OkChicas para detalles sobre su uso o distribución.

