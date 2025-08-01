<?php
/**
 * Plantilla de archivo personalizada.
 * Gestiona el diseño y el contenido utilizando el tema hijo de Genesis Framework.
 */

/** Forzar el contenido de ancho completo */
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

/** Eliminar la ruta de navegación (breadcrumb) */
remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');

/** Modificar el formato de fecha y la etiqueta del mes */
add_filter('the_time', 'okc_format_date');

/** Bucle personalizado para la página de inicio */
function my_custom_loop() {
    if (have_posts()) {
        $count = 0;
        while (have_posts()) {
            the_post(); ?>
            <div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
                <div class="full-post-container <?php echo (++$count % 2 ? 'odd' : 'even'); ?>">
                    <div class="post-left-col">
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('portfolio'); ?></a>
                    </div>
                    <div class="post-right-col">
                        <h2>
                            <a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                        <span class="author">Por <?php the_author_posts_link(); ?></span> | 
                        <span class="time">
                            <time itemprop="datePublished" content="<?php the_time('Y-m-d'); ?>">
                                <?php echo okc_format_date(); ?>
                            </time>
                        </span>
                    </div>
                </div>
            </div>
        <?php }
    } else {
        echo '<p>No hay publicaciones.</p>';
    }
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