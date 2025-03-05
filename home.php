<?php
/**
 * Plantilla de la página de inicio.
 * Este archivo gestiona el diseño y el contenido de la página de inicio del sitio, utilizando el tema hijo de Genesis Framework.
 */

/** Forzar el contenido de ancho completo */
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

/** Eliminar la ruta de navegación (breadcrumb) */
remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');

/** Modificar el formato de fecha y la etiqueta del mes */
add_filter('the_time', 'modify_date_format');
function modify_date_format($date) {
    $month_names = array(1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
        7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre');
    return $month_names[get_the_time('n')] . ' ' . get_the_time('j') . ', ' . get_the_time('Y');
}

/** Bucle personalizado para la página de inicio */
function my_custom_loop() {
    if (have_posts()) :
        $count = 0;
        while (have_posts()) : the_post(); ?>
            <div <?php post_class() ?> id="post-<?php the_ID(); ?>">
                <div class="full-post-container <?php echo (++$count % 2 ? 'odd' : 'even'); ?>">
                    <div class="post-left-col">
                        <a href="<?php the_permalink() ?>"><?php the_post_thumbnail('portfolio'); ?></a>
                    </div>
                    <div class="post-right-col">
                        <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                        <div class="home-author-box">
                            <span class="author">Por <?php the_author_posts_link(); ?></span> | <span class="time"><time itemprop="datePublished" content="<?php the_time('F j') ?>"><?php the_time('F j') ?></time></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile;
    endif;
    echo '<div class="clearfix"></div>';
    genesis_posts_nav();
}

/** Reemplazar el bucle estándar con nuestro bucle personalizado */
remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'my_custom_loop');

/** Eliminar la función de meta de la entrada solo en la página principal */
remove_action('genesis_after_post_content', 'genesis_post_meta');

/** Ejecutar Genesis */
genesis();
?>