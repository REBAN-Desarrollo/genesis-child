<?php
//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'reban', 'mpp' ) );
define( 'CHILD_THEME_URL', 'http://www.okchicas.com/' );
define( 'CHILD_THEME_VERSION', '240518a' );

require_once get_stylesheet_directory() . '/inc/setup.php';
require_once get_stylesheet_directory() . '/inc/cleanup.php';
require_once get_stylesheet_directory() . '/inc/assets.php';
require_once get_stylesheet_directory() . '/inc/performance.php';
require_once get_stylesheet_directory() . '/inc/accessibility.php';
require_once get_stylesheet_directory() . '/inc/customizations.php';
require_once get_stylesheet_directory() . '/inc/template-helpers.php';

include get_stylesheet_directory() . '/sidebar.php';
