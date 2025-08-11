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
add_filter('the_time', 'okc_format_date');

/** Bucle personalizado para la página de inicio */
function my_custom_loop() {
    if (have_posts()) :
        $count = 0;
        while (have_posts()) : the_post(); ?>
            <div <?php post_class() ?> id="post-<?php the_ID(); ?>">
                <div class="full-post-container <?php echo esc_attr((++$count % 2 ? 'odd' : 'even')); ?> clearfix">
                    <div class="post-left-col">
                        <a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_post_thumbnail('portfolio'); ?></a>
                    </div>
                    <div class="post-right-col">
                        <h2><a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php echo esc_html( get_the_title() ); ?></a></h2>
                        <div class="home-author-box">
                            <span class="author">Por <?php the_author_posts_link(); ?></span> | <span class="time"><time itemprop="datePublished" content="<?php echo esc_attr( get_the_date('Y-m-d') ); ?>"><?php echo esc_html( get_the_date('F j') ); ?></time></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile;
    endif;
    genesis_posts_nav();
}

/** Reemplazar el bucle estándar con nuestro bucle personalizado */
remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'my_custom_loop');

/** Eliminar la función de meta de la entrada solo en la página principal */
remove_action('genesis_after_post_content', 'genesis_post_meta');

/** Ejecutar Genesis */
genesis();
