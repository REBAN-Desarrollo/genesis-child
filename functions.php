<?php
//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'reban', 'mpp' ) );
define( 'CHILD_THEME_URL', 'http://www.okchicas.com/' );
define( 'CHILD_THEME_VERSION', '25.12.29' );

//* Cargar funcionalidades del tema
require_once get_stylesheet_directory() . '/inc/setup.php';
require_once get_stylesheet_directory() . '/inc/cleanup.php';
require_once get_stylesheet_directory() . '/inc/assets.php';
require_once get_stylesheet_directory() . '/inc/performance.php';
require_once get_stylesheet_directory() . '/inc/accessibility.php';
require_once get_stylesheet_directory() . '/inc/customizations.php';
require_once get_stylesheet_directory() . '/inc/template-helpers.php';

$sidebar_offcanvas = get_stylesheet_directory() . '/sidebar-offcanvas.php';
if ( file_exists( $sidebar_offcanvas ) ) {
	include $sidebar_offcanvas;
}
