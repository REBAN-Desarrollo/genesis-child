<?php
/**
 * Primary sidebar template.
 *
 * Renders the default Genesis primary sidebar widget area.
 */

genesis_markup(
    array(
        'open'    => '<aside class="sidebar sidebar-primary widget-area" ' . genesis_attr( 'sidebar-primary' ) . '>',
        'context' => 'sidebar-primary',
    )
);

do_action( 'genesis_before_sidebar_widget_area' );
dynamic_sidebar( 'sidebar' );
do_action( 'genesis_after_sidebar_widget_area' );

genesis_markup(
    array(
        'close'   => '</aside>',
        'context' => 'sidebar-primary',
    )
);
