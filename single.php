<?php
/**
 * Single post template: critical CSS, hero rendering, and related widgets.
 */

add_action( 'genesis_meta', 'reban_single_init' );
function reban_single_init() {
    // Head / performance.
    add_action( 'wp_head', 'reban_single_preload_image', 1 );
    add_action( 'wp_head', 'reban_single_critical_css', 2 );

    // Layout / content.
    add_action( 'genesis_before_content_sidebar_wrap', 'reban_single_header', 1 );
    add_filter( 'the_content', 'reban_single_fullwidth_images' );
    add_filter( 'the_time', 'reban_custom_date_format' );

    // Extras / cleanup.
    add_action( 'wp_print_styles', 'reban_single_remove_yarpp_css' );
    add_action( 'wp_footer', 'reban_single_remove_yarpp_css' );
    add_action( 'genesis_before_footer', 'custom_related_posts' );
    add_action( 'genesis_after_entry', 'mpp_after_entry', 5 );
}

/**
 * Critical CSS for single posts served from an external file.
 */
function reban_single_critical_css() {
    $critical_relative = '/critical-single.css';
    $critical_path     = get_stylesheet_directory() . $critical_relative;

    if ( ! file_exists( $critical_path ) ) {
        return;
    }

    $critical_src = function_exists( 'reban_perf_versioned_asset' )
        ? reban_perf_versioned_asset( $critical_relative )
        : add_query_arg( 'v', filemtime( $critical_path ), get_stylesheet_directory_uri() . $critical_relative );
    $critical_src = esc_url( $critical_src );

    echo '<link rel="preload" href="' . $critical_src . '" as="style">';
    echo '<link rel="stylesheet" id="reban-critical-single" href="' . $critical_src . '">';
}

/**
 * Build image sources (jpg/png + WebP variant when it exists on disk) for featured images.
 *
 * Esto mantiene sincronizado el preload con el <picture> y evita descargas dobles
 * cuando EWWW o Cloudflare sirven .webp.
 */
function reban_single_get_featured_image_sources( $thumbnail_id, $size = 'large' ) {
    $srcset      = wp_get_attachment_image_srcset( $thumbnail_id, $size );
    $sizes_attr  = wp_get_attachment_image_sizes( $thumbnail_id, $size );
    $img_url_arr = wp_get_attachment_image_src( $thumbnail_id, $size );

    if ( ! $srcset || empty( $img_url_arr[0] ) ) {
        return null;
    }

    $file_path   = get_attached_file( $thumbnail_id );
    $uploads_dir = wp_upload_dir();
    $sizes_value = $sizes_attr ?: '(max-width: 800px) 100vw, 730px';
    $webp_data   = null;

    if ( $file_path && ! empty( $uploads_dir['basedir'] ) && ! empty( $uploads_dir['baseurl'] ) ) {
        $path_parts      = pathinfo( $file_path );
        $webp_candidates = array(
            array(
                'path'   => $path_parts['dirname'] . '/' . $path_parts['filename'] . '.webp',
                'srcset' => preg_replace( '/\.(jpe?g|png)(\s+\d+w)/i', '.webp$2', $srcset ),
            ),
            array(
                'path'   => $file_path . '.webp',
                'srcset' => preg_replace( '/\.(jpe?g|png)(\s+\d+w)/i', '.$1.webp$2', $srcset ),
            ),
        );

        foreach ( $webp_candidates as $candidate ) {
            if ( file_exists( $candidate['path'] ) ) {
                $webp_data = array(
                    'url'    => str_replace( $uploads_dir['basedir'], $uploads_dir['baseurl'], $candidate['path'] ),
                    'srcset' => $candidate['srcset'],
                );
                break;
            }
        }
    }

    return array(
        'url'    => $img_url_arr[0],
        'srcset' => $srcset,
        'sizes'  => $sizes_value,
        'webp'   => $webp_data,
    );
}

// Preload featured image to improve Largest Contentful Paint (LCP).
function reban_single_preload_image() {
    if ( ! is_singular() || ! has_post_thumbnail() ) {
        return;
    }

    $thumbnail_id = get_post_thumbnail_id();
    $image_data   = reban_single_get_featured_image_sources( $thumbnail_id );

    if ( ! $image_data ) {
        return;
    }

    if ( ! empty( $image_data['webp'] ) ) {
        echo '<link rel="preload" as="image" href="' . esc_url( $image_data['webp']['url'] ) . '" imagesrcset="' . esc_attr( $image_data['webp']['srcset'] ) . '" imagesizes="' . esc_attr( $image_data['sizes'] ) . '" type="image/webp" fetchpriority="high">';
        return;
    }

    echo '<link rel="preload" as="image" href="' . esc_url( $image_data['url'] ) . '" imagesrcset="' . esc_attr( $image_data['srcset'] ) . '" imagesizes="' . esc_attr( $image_data['sizes'] ) . '" fetchpriority="high">';
}

/**
 * Add class to image links in content for full-width images.
 */
function reban_single_fullwidth_images( $content ) {
    if ( is_single() ) {
        $pattern     = '/<a(.*?)href=["\']([^"\']+?\.(jpg|jpeg|png|gif))["\'](.*?)><img/i';
        $replacement = '<a$1href="$2"$4 class="full-img"><img';
        $content     = preg_replace( $pattern, $replacement, $content );
    }
    return $content;
}

// Remove YARPP styles in head/footer.
function reban_single_remove_yarpp_css() {
    wp_dequeue_style( 'yarppWidgetCss' );
    wp_dequeue_style( 'yarppRelatedCss' );
    wp_deregister_style( 'yarppRelatedCss' );
}

// Adds header box - Category, entry-title, entry-meta & the_post_thumbnail (featured image).
function reban_single_header() {
    $post_id      = get_the_ID();
    $author_id    = get_post_field( 'post_author', $post_id );
    $thumbnail_id = get_post_thumbnail_id( $post_id );

    reban_single_remove_meta();
    ?>
    <div class="header-box oc-article-header">
        <?php reban_single_author_box( $author_id, $post_id ); ?>
        <?php reban_single_featured_image( $thumbnail_id ); ?>
    </div>
    <?php
}

/**
 * Remove default title and post meta from the entry header.
 */
function reban_single_remove_meta() {
    remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
    remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
}

/**
 * Output the category, title and author information in the post header.
 *
 * @param int $author_id Author identifier.
 * @param int $post_id   Current post identifier.
 */
function reban_single_author_box( $author_id, $post_id ) {
    $author_url  = get_author_posts_url( $author_id );
    $author_name = get_the_author_meta( 'display_name', $author_id );
    $categories  = get_the_category_list( ' ', '', $post_id );
    ?>
    <div class="single-post-category oc-article-header__category">
        <span><?php echo $categories; ?></span>
    </div>
    <h1 class="entry-title oc-article-header__title" itemprop="headline"><?php echo esc_html( get_the_title( $post_id ) ); ?></h1>
    <p class="entry-meta oc-article-header__meta">Por
        <span class="entry-author oc-article-header__author" itemprop="author" itemscope itemtype="https://schema.org/Person">
            <a href="<?php echo esc_url( $author_url ); ?>" class="entry-author-link" itemprop="url" rel="author">
                <span class="entry-author-name" itemprop="name"><?php echo esc_html( $author_name ); ?></span>
            </a>
        </span>
        <time class="oc-article-header__time" itemprop="datePublished" content="<?php echo esc_attr( get_the_date( 'Y-m-d', $post_id ) ); ?>">
            <?php echo esc_html( get_the_date( 'F j Y', $post_id ) ); ?>
        </time>
    </p>
    <?php
}

/**
 * Display the featured image with WebP support when available.
 *
 * @param int $thumbnail_id Attachment ID for the featured image.
 */
function reban_single_featured_image( $thumbnail_id ) {
    $image_data  = reban_single_get_featured_image_sources( $thumbnail_id );
    $sizes_value = $image_data['sizes'] ?? '(max-width: 800px) 100vw, 730px';
    ?>
    <div class="full-img oc-article-header__media">
        <?php
        if ( ! empty( $image_data ) && ! empty( $image_data['webp'] ) ) {
            echo '<picture>';
            echo '<source srcset="' . esc_attr( $image_data['webp']['srcset'] ) . '" sizes="' . esc_attr( $sizes_value ) . '" type="image/webp">';
            echo '<source srcset="' . esc_attr( $image_data['srcset'] ) . '" sizes="' . esc_attr( $sizes_value ) . '">';
            echo wp_get_attachment_image(
                $thumbnail_id,
                'large',
                false,
                array(
                    'class'         => 'aligncenter',
                    'fetchpriority' => 'high',
                    'loading'       => 'eager',
                    'decoding'      => 'async',
                    'sizes'         => $sizes_value,
                )
            );
            echo '</picture>';
        } else {
            $img_sizes = array(
                'class'         => 'aligncenter',
                'fetchpriority' => 'high',
                'loading'       => 'eager',
                'decoding'      => 'async',
                'sizes'         => $sizes_value,
            );

            echo wp_get_attachment_image(
                $thumbnail_id,
                'large',
                false,
                $img_sizes
            );
        }
        ?>
    </div>
    <?php
}

// Wordpress Popular Posts & YARPP Related at the end of posts.
function custom_related_posts() {
    ?>
    <div class="custom-related-posts clearfix">
        <div class="yarpp-related">
            <?php
            $args = array(
                'header'          => 'Tendencias',
                'header_start'    => '<div style="text-align:center"><h3 id="tendencias" class="trending-title"><i class="icon-trending"> </i>',
                'header_end'      => '</h3></div>',
                'wpp_start'       => '<div class="yarpp-grids">',
                'wpp_end'         => '</div>',
                'post_html'       => '<a href="{url}" class="yarpp-thumbnail"><img src="{thumb_url}" width="360" height="188" alt="{text_title}" loading="lazy" /><div class="desc"><span>{text_title}</span></div></a>',
                'post_type'       => 'post',
                'limit'           => 6,
                'range'           => 'custom',
                'time_quantity'   => 3,
                'time_unit'       => 'day',
                'freshness'       => 0,
                'thumbnail_width' => 360,
                'thumbnail_height'=> 188,
            );
            wpp_get_mostpopular( $args );
            ?>
        </div>
    </div>
    <div class="custom-related-posts clearfix">
        <?php yarpp_related(); ?>
    </div>
    <?php
}

// Hook after post widget after the entry content.
function mpp_after_entry() {
    genesis_widget_area(
        'after-entry',
        array(
            'before' => '<div class="after-entry widget-area">',
            'after'  => '</div>',
        )
    );
}

genesis();
