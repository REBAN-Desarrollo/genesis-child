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
 * Inline critical CSS for single posts (unchanged payload).
 */
function reban_single_critical_css() {
    $theme_dir = get_stylesheet_directory();
    $theme_uri = get_stylesheet_directory_uri();

    $versioned_asset = function ( $relative_path, $fallback_uri = '' ) use ( $theme_dir, $theme_uri ) {
        $file_path = $theme_dir . $relative_path;
        $uri       = $theme_uri . $relative_path;

        if ( file_exists( $file_path ) ) {
            $uri = add_query_arg( 'v', filemtime( $file_path ), $uri );
        } elseif ( $fallback_uri ) {
            $uri = $fallback_uri;
        }

        return esc_url( $uri );
    };

    $font_urls = array(
        'reban'    => array(
            'woff2' => $versioned_asset( '/fonts/rebanfont.woff2' ),
            'woff'  => $versioned_asset( '/fonts/rebanfont.woff' ),
        ),
        'poppins' => array(
            'woff2' => $versioned_asset( '/fonts/Poppins-SemiBold.woff2' ),
            'woff'  => $versioned_asset( '/fonts/Poppins-SemiBold.woff' ),
        ),
        'proxima' => array(
            'woff2' => $versioned_asset( '/fonts/ProximaNova-Regular.woff2' ),
            'woff'  => $versioned_asset( '/fonts/ProximaNova-Regular.woff' ),
        ),
    );

    $critical_css = <<<'CSS'
<style id="css-atf-single">i{font-family:'rebanfont'!important;speak:none;font-style:normal;font-weight:normal;font-variant:normal;text-transform:none;line-height:1;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.icon-search:before{content:"\e900"}.icon-menu:before{content:"\e901"}@font-face{font-family:'rebanfont';font-style:normal;font-weight:normal;src:url("/wp-content/themes/okchicas5/fonts/rebanfont.woff2") format("woff2"),url("/wp-content/themes/okchicas5/fonts/rebanfont.woff") format("woff");font-display:swap;}@font-face{font-family:"Poppins";font-style:normal;font-weight:500;src:url("/wp-content/themes/okchicas5/fonts/Poppins-SemiBold.woff2") format("woff2"),url("/wp-content/themes/okchicas5/fonts/Poppins-SemiBold.woff") format("woff");font-display:swap}@font-face{font-family:"Poppins-fallback";size-adjust:99.498%;ascent-override:105%;descent-override:46.7%;line-gap-override:0%;font-display:swap;src:local("Verdana")}@font-face{font-family:"Proxima Nova";font-style:normal;font-weight:normal;src:url("/wp-content/themes/okchicas5/fonts/ProximaNova-Regular.woff2") format("woff2"),url("/wp-content/themes/okchicas5/fonts/ProximaNova-Regular.woff") format("woff");font-display:swap}@font-face{font-family:"Proxima-fallback";size-adjust:108%;ascent-override:55%;descent-override:0%;line-gap-override:53.3%;font-display:swap;src:local(Corbel)}html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}main{display:block}h1{font-size:2em;margin:.67em 0}a{background-color:transparent}img{border-style:none}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}html{font-size:62.5%}body{background-color:#fff;color:#000;font-weight:400;font-size:1.2rem;font-family:Poppins,Poppins-fallback,sans-serif;line-height:0}a{color:#5A5959;text-decoration:none}p,li{font-family:'Proxima Nova','Proxima-fallback',sans-serif}p{padding:0;margin:2rem 0}ul{margin:0;padding:0;line-height:1.5}h1{margin-bottom:1.6rem;padding:0;font-weight:bold;font-size:2.8rem;line-height:1.4}iframe,img{max-width:100%;display:block;margin:0 auto!important}img{height:auto}.wrap{margin:0 auto;max-width:100rem;line-height:0}.site-inner{clear:both;margin:0 auto;max-width:105rem;padding:0 0 2.5rem;margin-top:0}.content{float:right;background-color:#fff}.single .content .entry{padding:0 1.5rem;margin-bottom:0}.content-sidebar-wrap{width:100%;display:table;table-layout:fixed;margin-top:2rem}.content-sidebar .content{display:table-cell!important;vertical-align:top;width:100%;margin-top:0}.sidebar-primary{width:31.6rem;max-width:31.6rem;padding:0 0 0 1.5rem;display:table-cell!important;vertical-align:top}.aligncenter{display:block;margin:0 auto!important}.entry-title{font-size:3.5rem;line-height:4.7rem;color:#000;font-weight:bold;text-align:center;margin:1.5rem 0}.entry-content p{font-size:1.7rem;line-height:1.4}.entry-content em{margin-right:.35rem}.entry-meta{clear:both;color:#5A5959!important;text-transform:uppercase;font-size:1.1rem;margin:1.5rem 0;padding:1rem 0;font-weight:700}.entry-meta a{color:#000}.entry-meta .entry-author{font-size:1.1rem!important;color:#5A5959!important;padding:0 1rem .5rem 0!important}.entry-meta time{font-size:1.1rem;padding:0 1rem;color:#5A5959!important;text-transform:uppercase}.entry{margin-bottom:0}.single-post .entry-content>:first-child{margin-top:0}.header-box{padding:0;margin:0 auto;text-align:center;max-width:85rem}.single-post .header-box .full-img{min-height:18.8rem}.full-img{display:block;position:relative;width:100%;color:inherit}.single-post-category{margin:1.8rem 0 1.5rem}.single-post-category a{font-size:1.2rem;color:#fff;padding:.2rem .5rem;background:#d6006b;border:.1rem solid rgba(0,0,0,0.09);line-height:1}.site-header{width:100%;z-index:9999;position:relative;border-top:.3rem solid #d6006b;background-color:#fff;padding:0}.site-header .wrap{display:flex;align-items:center;justify-content:center;overflow:hidden;padding:.8rem 0;gap:1rem;flex-wrap:wrap}.site-header .wrap>a{display:none}.title-area{float:left;width:26%}.header-image .title-area{padding:0}.site-title{line-height:1;margin:0}.site-title img{display:block;margin:0 auto;max-height:11rem;height:auto;width:auto}.site-title picture{display:block;line-height:0}.site-title a{display:inline-flex;align-items:center;justify-content:center;color:#d6006b;text-decoration:none}.header-full-width .title-area,.header-full-width .site-title{width:100%;text-align:center;font-weight:400}.header-image .site-title a{display:inline-block;width:29rem;height:5.5rem;margin:.5rem 0;background-size:100%!important;text-indent:-9999px}.site-header .search-form{float:right;margin-top:0}.genesis-nav-menu{clear:both;color:#fff;line-height:1;width:100%;text-transform:uppercase;font-size:1.5rem;text-align:center}.main-menu-icon{font-size:1.5rem;vertical-align:middle}.genesis-nav-menu .menu-item{display:inline-block;text-align:left;float:none!important}.genesis-nav-menu li.menu-item-has-children a:after{position:relative;top:1.1rem;content:'';border-left:.5rem solid transparent;border-right:.5rem solid transparent;border-top:.6rem solid #000;margin:0 0 0 .8rem}.genesis-nav-menu li.menu-item-has-children li a:after{display:none}.genesis-nav-menu a{color:#000;display:block;line-height:1.3;padding:1.42rem 1.6rem;position:relative;font-weight:bold}.genesis-nav-menu .sub-menu{left:-9999px;opacity:0;text-transform:none;position:absolute;width:20rem;z-index:99}.genesis-nav-menu .sub-menu a{background-color:#fff;color:#222;border:.1rem solid #BFBDBD;border-top:none;font-size:1.4rem;padding:1rem;position:relative;width:20rem}.nav-primary{background-color:#fff;border-top:1px solid #ccc;border-bottom:1px solid #ccc}.sidebarad{padding:0 0 2rem;margin-bottom:1rem;text-align:center}.entry-content{word-break:break-word}.entry-content img{height:auto}.entry-content img.aligncenter{display:block;margin:1rem auto}.entry-meta .entry-author,.entry-meta time{display:inline-block}.entry-meta .entry-author .entry-author-name{color:#5A5959}.entry-meta{display:flex;gap:.4rem;align-items:center;flex-wrap:wrap;justify-content:center}.entry-meta time{position:relative}.entry-title, .entry-meta{margin-left:auto;margin-right:auto}.genesis-nav-menu .sub-menu .sub-menu{margin:-5.6rem 0 0 20rem}@media only screen and (max-width:1155px){.site-inner,.wrap{max-width:90%}.header-box,.single .content .entry,.sidebar{border:none}.header-box{max-width:80rem}}@media only screen and (max-width:944px){.header-image .site-header .site-title a{margin:0;width:23rem;height:4.3rem;vertical-align:bottom}.site-inner{max-width:95%;max-width:73.1rem;margin-top:5.7rem!important}.site-header{position:fixed}.entry-title{font-size:3.1rem;line-height:4rem;margin:1.5rem .5rem}.site-title{font-size:3.8rem;margin:0}.content,.sidebar-primary,.title-area{max-width:100%}.sidebar-primary{margin-top:0;margin-left:0}.nav-primary{display:none}.site-header{border-bottom:.1rem solid #BFBDBD}.wrap{max-width:100%}.sidebar{display:none!important}.site-header{padding:0}.site-header .wrap{padding:.5rem 0;gap:.75rem}.genesis-nav-menu li{float:none}.genesis-nav-menu,.site-header .search-form,.site-header .title-area,.site-title{text-align:center}.nav-primary{display:block!important;border:none;box-shadow:none}.nav-primary .menu li{display:none}.nav-primary .menu li.mobile-item:last-child{right:0}.site-title{font-size:3.8rem;margin:0}.site-header .wrap>a{display:block;position:absolute;font-size:2.5rem;color:#000;padding:1.5rem 1.25rem 1.2rem;top:0}.site-header .wrap>a.sidebar-toggle-right{right:.1rem}}@media only screen and (max-width:600px){.site-inner{max-width:100%;margin:1.5rem 3% 1rem}.entry-title{font-size:2.4rem;margin:1.5rem 0 0;line-height:1.4}.entry-content p{font-size:1.6rem;line-height:1.4}.full-img{display:block;position:relative;width:100vw;left:calc(-50vw + 50%);color:unset!important}.header-box{padding:0}.single .content .entry{padding:0;background-color:#fff;box-shadow:none}.genesis-nav-menu a{top:.4rem}.nav-primary .menu li.mobile-item{top:0}}@media only screen and (max-width:480px){.header-box{border:none;margin:0 0 2rem 0}.header-image .site-header .site-title a{background-size:80% 80%}}body,html{width:100%;overflow-x:hidden}</style>
CSS;

    echo str_replace(
        array(
            '/wp-content/themes/okchicas5/fonts/rebanfont.woff2',
            '/wp-content/themes/okchicas5/fonts/rebanfont.woff',
            '/wp-content/themes/okchicas5/fonts/Poppins-SemiBold.woff2',
            '/wp-content/themes/okchicas5/fonts/Poppins-SemiBold.woff',
            '/wp-content/themes/okchicas5/fonts/ProximaNova-Regular.woff2',
            '/wp-content/themes/okchicas5/fonts/ProximaNova-Regular.woff',
        ),
        array(
            $font_urls['reban']['woff2'],
            $font_urls['reban']['woff'],
            $font_urls['poppins']['woff2'],
            $font_urls['poppins']['woff'],
            $font_urls['proxima']['woff2'],
            $font_urls['proxima']['woff'],
        ),
        $critical_css
    );
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
    <div class="header-box">
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
    <div class="single-post-category">
        <span><?php echo $categories; ?></span>
    </div>
    <h1 class="entry-title" itemprop="headline"><?php echo esc_html( get_the_title( $post_id ) ); ?></h1>
    <p class="entry-meta">Por
        <span class="entry-author" itemprop="author" itemscope itemtype="https://schema.org/Person">
            <a href="<?php echo esc_url( $author_url ); ?>" class="entry-author-link" itemprop="url" rel="author">
                <span class="entry-author-name" itemprop="name"><?php echo esc_html( $author_name ); ?></span>
            </a>
        </span>&nbsp;|&nbsp;
        <time itemprop="datePublished" content="<?php echo esc_attr( get_the_date( 'Y-m-d', $post_id ) ); ?>">
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
    <div class="full-img">
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
