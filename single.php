<?php
add_action( 'genesis_meta', 'reban_single_init' );
function reban_single_init() {

/* Headers mods
 * 1 - Inline ATF Critical CSS: https://web.dev/extract-critical-css/ | https://jonassebastianohlsson.com/criticalpathcssgenerator/
 * 2 - Preload featured image
 * 3 - Función para agregar una clase a los enlaces de imagenes / Classes to images for full width when width lower than 600px
 * 4 - Remove comment-reply.min.js
 * 5 - YARPP: Remove CSS
 */
add_action( 'wp_head', 'reban_single_critical_css', 2);
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
<style id="css-atf-single">i{font-family:'rebanfont'!important;speak:none;font-style:normal;font-weight:normal;font-variant:normal;text-transform:none;line-height:1;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.icon-search:before{content:"\e900"}.icon-menu:before{content:"\e901"}@font-face{font-family:'rebanfont';font-style:normal;font-weight:normal;src:url("/wp-content/themes/okchicas5/fonts/rebanfont.woff2") format("woff2"),url("/wp-content/themes/okchicas5/fonts/rebanfont.woff") format("woff");font-display:swap;}@font-face{font-family:"Poppins";font-style:normal;font-weight:500;src:url("/wp-content/themes/okchicas5/fonts/Poppins-SemiBold.woff2") format("woff2"),url("/wp-content/themes/okchicas5/fonts/Poppins-SemiBold.woff") format("woff");font-display:swap}@font-face{font-family:"Poppins-fallback";size-adjust:99.498%;ascent-override:105%;descent-override:46.7%;line-gap-override:0%;font-display:swap;src:local("Verdana")}@font-face{font-family:"Proxima Nova";font-style:normal;font-weight:normal;src:url("/wp-content/themes/okchicas5/fonts/ProximaNova-Regular.woff2") format("woff2"),url("/wp-content/themes/okchicas5/fonts/ProximaNova-Regular.woff") format("woff");font-display:swap}@font-face{font-family:"Proxima-fallback";size-adjust:108%;ascent-override:55%;descent-override:0%;line-gap-override:53.3%;font-display:swap;src:local(Corbel)}html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}main{display:block}h1{font-size:2em;margin:.67em 0}a{background-color:transparent}img{border-style:none}input{font-family:inherit;font-size:100%;line-height:1.15;margin:0}input{overflow:visible}[type=submit]{-webkit-appearance:button}[type=submit]::-moz-focus-inner{border-style:none;padding:0}[type=submit]:-moz-focusring{outline:1px dotted ButtonText}[type=search]{-webkit-appearance:textfield;outline-offset:-2px}[type=search]::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}input[type="search"]{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}.clearfix:before,.entry-content:before,.entry:before,.nav-primary:before,.site-container:before,.site-header:before,.site-inner:before,.widget:before,.wrap:before{content:" ";display:table}.clearfix:after,.entry-content:after,.entry:after,.nav-primary:after,.site-container:after,.site-header:after,.site-inner:after,.widget:after,.wrap:after{clear:both;content:" ";display:table}html{font-size:62.5%}body{background-color:#FFFFFF;color:black;font-weight:400;font-size:1.2rem;font-family:Poppins,Poppins-fallback,sans-serif;line-height:0}a{color:#5A5959;text-decoration:none}p,li{font-family:'Proxima Nova','Proxima-fallback',sans-serif}p{padding:0;margin:2rem 0}ul{margin:0;padding:0;line-height:1.5}h1{margin-bottom:1.6rem;padding:0;font-weight:bold}h1{font-size:2.8rem;margin-bottom:1.2rem;line-height:1.4}iframe,img{max-width:100%;display:block;margin:0 auto!important}img{height:auto}input{background-color:#fff;border:.1rem solid #ddd;border-radius:.3rem;box-shadow:0 0 .5rem #ddd inset;color:#888;font-size:1.6rem;padding:.8rem;width:100%}::-moz-placeholder{color:#999;opacity:1}::-webkit-input-placeholder{color:#999}input[type="submit"]{background-color:#0096D6;border:none;box-shadow:none;color:#fff;font-size:1.6rem;padding:1.6rem 2.4rem;width:auto;text-align:center;text-decoration:none}input[type="search"]::-webkit-search-cancel-button,input[type="search"]::-webkit-search-results-button{display:none}.wrap{margin:0 auto;max-width:100rem;line-height:0}.site-inner{clear:both;margin:0 auto;max-width:105rem;padding:0 0 2.5rem;margin-top:0}.content{float:right;background-color:white}.single .content .entry{padding:0 1.5rem;margin-bottom:0}.content-sidebar-wrap{width:100%;display:table;table-layout:fixed;margin-top:2rem}.content-sidebar .content{display:table-cell!important;vertical-align:top;width:100%;margin-top:0}.sidebar-primary{width:31.6rem;max-width:31.6rem;padding:0 0 0 1.5rem;display:table-cell!important;vertical-align:top}.search-form{display:block;margin:0 auto;overflow:hidden;width:22.4rem}.search-form input[type="text"].search-input{display:block;float:left!important;width:16rem!important;max-width:16rem!important}.search-form input.search-submit{border:0;padding:1.1rem .5rem;padding:1.1rem 0.5rem;clip:rect(auto,auto,auto,auto);display:block;float:right!important;clear:none;width:6rem!important;position:relative!important}.aligncenter{display:block;margin:0 auto!important}.entry-title{font-size:3.5rem;line-height:4.7rem;color:black;font-weight:bold;text-align:center;margin:1.5rem 0rem}.widget-wrap a{textalign:left}.widget{word-wrap:break-word}.widget li{list-style-type:none}.widget li li{border:none}.site-header{width:100%;z-index:9999;position:relative;border-top:.3rem solid #d6006b;background-color:white;padding:0}.site-header .wrap{overflow:hidden;padding:.8rem 0}.site-header .wrap>a{display:none}.title-area{float:left;width:26%}.header-image .title-area{padding:0}.site-title{line-height:1;margin:0}.site-title img{display:block;margin:0 auto;max-height:11rem;height:auto;width:auto}.site-title a{color:#d6006b;text-decoration:none}.header-full-width .title-area,.header-full-width .site-title{width:100%;text-align:center;font-weight:normal}.header-image .site-title a{display:block;text-indent:-9999px}.header-image .site-title a{display:inline-block;width:29rem;height:5.5rem;margin:0;background-size:100%!important}.site-header .search-form{float:right;margin-top:0rem}.genesis-nav-menu{clear:both;color:#fff;line-height:1;width:100%;text-transform:uppercase;font-size:1.5rem;text-align:center}.main-menu-icon{font-size:1.5rem;vertical-align:middle}.genesis-nav-menu .menu-item{display:inline-block;text-align:left;float:none!important}.genesis-nav-menu li.menu-item-has-children a:after{position:relative;top:1.1rem;content:'';border-left:.5rem solid transparent;border-right:.5rem solid transparent;border-top:0.6rem solid #000;margin:0 0 0 .8rem}.genesis-nav-menu li.menu-item-has-children li a:after{display:none;content:'';width:0;height:0;border-left:.5rem solid transparent;border-right:.5rem solid transparent;border-top:.5rem solid #787884}.genesis-nav-menu a{color:#000;display:block;line-height:1.3;padding:1.42rem 1.6rem;position:relative;font-weight:bold}.genesis-nav-menu .sub-menu{left:-9999px;opacity:0;text-transform:none;position:absolute;width:20rem;z-index:99}.genesis-nav-menu .sub-menu a{background-color:#fff;color:#222;border:.1rem solid #BFBDBD;border-top:none;font-size:1.4rem;padding:1rem;position:relative;width:20rem}.genesis-nav-menu .sub-menu .sub-menu{margin:-5.6rem 0 0 20rem}.nav-primary{background-color:white;border-top:1px solid #ccc;border-bottom:1px solid #ccc}.sidebarad{padding:0 0 2rem;margin-bottom:1rem;text-align:center}.entry-meta .entry-author{font-size:1.1rem!important;color:#5A5959!important;padding:0 1rem .5rem 0!important;text-transform:uppercase}.entry-author-name{color:#5A5959!important}.entry-meta time{right:0;font-size:1.1rem;padding:0 1rem;text-transform:none;color:#5A5959!important;text-transform:uppercase}.entry{margin-bottom:0rem}.single-post .entry-content>:first-child{margin-top:0}.entry-content p{font-size:1.7rem;line-height:1.4}.entry-content em{margin-right:.35rem}.entry-meta{clear:both;color:#5A5959!important;text-transform:capitalize;font-size:1.1rem;margin:1.5rem 0;padding:1rem 0;text-transform:uppercase;font-weight:700}.entry-meta a{color:#000000}.header-box{padding:0;margin:0 auto;text-align:center;max-width:85rem}.single-post .header-box .full-img{min-height:18.8rem}.single-post-category{margin:1.8rem 0 1.5rem}.single-post-category a{font-size:1.2rem;color:#fff;padding:0.2rem .5rem;background:#d6006b;border:.1rem solid rgba(0,0,0,0.09);line-height:1}@media only screen and (max-width:1155px){.site-inner,.wrap{max-width:90%}.header-box,.single .content .entry,.sidebar{border:none}.header-box{max-width:80rem}}@media only screen and (max-width:944px){.header-image .site-header .site-title a{margin:0;width:23rem;height:4.3rem;vertical-align:bottom}.site-inner{max-width:95%;max-width:73.1rem;margin-top:5.7rem!important}.site-header{position:fixed}.entry-title{font-size:3.1rem;line-height:4rem;margin:1.5rem .5rem}.site-title{font-size:3.8rem;margin:0}.content,.sidebar-primary,.title-area{max-width:100%}.sidebar-primary{margin-top:0;margin-left:0}.nav-primary{display:none}.responsive-search{margin-top:0}.site-header{border-bottom:.1rem solid #BFBDBD}.wrap{max-width:100%}.sidebar{display:none!important}.site-header{padding:0}.genesis-nav-menu li{float:none}.genesis-nav-menu,.site-header .search-form,.site-header .title-area,.site-title{text-align:center}.nav-primary{display:block!important;border:none;box-shadow:none}.nav-primary .menu li{display:none}.nav-primary .menu li.mobile-item:last-child{right:0}.site-title{font-size:3.8rem;margin:0}.site-header .wrap>a{display:block;position:absolute;font-size:2.5rem;color:black;padding:1.5rem 1.25rem 1.2rem;top:0}.site-header .wrap>a.sb-toggle-right{right:.1rem}}@media only screen and (max-width:600px){.site-inner{max-width:100%;margin:1.5rem 3% 1rem}.entry-title{font-size:2.4rem;margin:1.5rem 0 0;line-height:1.4}.entry-content p{font-size:1.6rem;line-height:1.4}.full-img{display:block;position:relative;width:100vw;left:calc(-50vw + 50%);color:unset!important}.header-box{padding:0}.single .content .entry{padding:0;background-color:white;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none}.genesis-nav-menu a{top:.4rem}.nav-primary .menu li.mobile-item{top:0rem}}@media only screen and (max-width:480px){.header-box{border:none;margin:0 0 2rem;margin:0}.header-image .site-header .site-title a{background-size:80% 80%}}.sb-slidebar{width:28rem;top:0;bottom:0;position:fixed;overflow-x:hidden;z-index:50000;background:#22282b}.responsive-search{width:100%;margin-top:0;background:#000}.responsive-search .search-form{max-width:22.4rem;height:0;clear:both;float:none;margin:0 auto}.sb-slidebar.sb-left{border-right:.4rem solid #d6006b}.sb-slidebar{padding:2rem 1rem}*{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}#sb-site{padding:0}.sb-slidebar{background-color:#222222;color:#e8e8e8}.sb-slidebar a{color:#d6006b;text-decoration:none}.sb-menu{padding:0;margin:0;list-style-type:none;line-height:2rem;background-color:#222222}.sb-menu li{width:100%;padding:0;margin:0;border-top:.1rem solid rgba(255,255,255,0.1);border-bottom:.1rem solid rgba(0,0,0,0.1)}.sb-menu>li:first-child{border-top:none}.sb-menu>li:last-child{border-bottom:none}.sb-menu li a{width:100%;display:inline-block;padding:1em;color:#f2f2f2}.sb-left .sb-menu li a{border-left:.3rem solid transparent;font-weight:900;padding:.6rem 1.3rem;font-size:1.45rem}.sb-left .sb-menu li li a{padding:.4rem 3rem}.sb-left .sb-menu li li a:before{font-size:1.6rem;padding-right:1.2rem}#sb-site,.sb-slidebar,body,html{margin:0;padding:0;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}body,html{width:100%;overflow-x:hidden}html{height:100%}body{min-height:100%;height:auto;position:relative}#sb-site{width:100%;min-height:100vh;position:relative;z-index:1;background-color:#fff}#sb-site:after,#sb-site:before{content:' ';display:table;clear:both}.sb-slidebar{height:100%;overflow-y:auto;position:fixed;top:0;z-index:0;display:none;background-color:#222}.sb-slidebar,.sb-slidebar *{-webkit-transform:translateZ(0px)}.sb-left{left:0}.sb-right{right:0}.sb-style-overlay{z-index:9999}.sb-slidebar{width:30%}@media (max-width:480px){.sb-slidebar{width:70%}}@media (min-width:481px){.sb-slidebar{width:55%}}@media (min-width:768px){.sb-slidebar{width:40%}}@media (min-width:992px){.sb-slidebar{width:30%}}@media (min-width:1200px){.sb-slidebar{width:20%}}#sb-site,.sb-slidebar{-webkit-backface-visibility:hidden}.sb-slidebar .widget:first-child{margin:0 0 10rem 0}.sb-menu li li{margin:0;line-height:2.5rem;border-top:.1rem solid #2f2f2f}.sb-slidebar .widget_search{background:#d6006b;padding:1.2rem 1.5rem}.sb-slidebar .search-form{background:rgba(0,0,0,0.25);border-radius:6rem;display:block;width:98%}.sb-slidebar form input[type="search"]{background:none;border:none;box-shadow:none;color:#fff;display:block;float:left;font-size:1.4rem;line-height:1.6rem;margin:0;padding:1.2rem 0 .9rem 2rem;width:85%}.search-form input[type="submit"]{font-family:'rebanfont';display:block;float:left;padding:1.2rem 1.2rem 0 0;line-height:1.6rem;background:none;border:none;width:13%;content:"\e900"}.topbillboard{margin:2rem 0!important;display:block;position:relative;width:100vw;left:calc(-50vw + 50%);background-color:#fff}</style>
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

// 2 - Preload featured image to improve Largest Contentful Paint (LCP) - see: https://www.corewebvitals.io/pagespeed/preload-largest-contentful-paint-image
add_action('wp_head', 'reban_single_preload_image', 1); // Prioridad 1 para que sea más temprano
function reban_single_preload_image() {
    if (is_singular() && has_post_thumbnail()) {
        $thumbnail_id = get_post_thumbnail_id();
        $file_path    = get_attached_file($thumbnail_id);
        $path_parts   = pathinfo($file_path);
        $webp_file    = $path_parts['dirname'] . '/' . $path_parts['filename'] . '.webp';
        $srcset       = wp_get_attachment_image_srcset($thumbnail_id, 'large');
        $sizes        = wp_get_attachment_image_sizes($thumbnail_id, 'large');
        $img_url_arr  = wp_get_attachment_image_src($thumbnail_id, 'large');
        $img_url      = $img_url_arr[0] ?? '';
        $sizes_attr   = $sizes ?: '(max-width: 800px) 100vw, 730px';

        if ($srcset && $img_url) {
            if (file_exists($webp_file)) {
                $uploads_dir  = wp_upload_dir();
                $webp_url     = str_replace($uploads_dir['basedir'], $uploads_dir['baseurl'], $webp_file);
                $webp_srcset  = preg_replace('/\.(jpe?g|png)(\s+\d+w)/i', '.webp$2', $srcset);
                echo '<link rel="preload" as="image" href="' . esc_url($webp_url) . '" imagesrcset="' . esc_attr($webp_srcset) . '" imagesizes="' . esc_attr($sizes_attr) . '" type="image/webp" fetchpriority="high">';
            } else {
                echo '<link rel="preload" as="image" href="' . esc_url($img_url) . '" imagesrcset="' . esc_attr($srcset) . '" imagesizes="' . esc_attr($sizes_attr) . '" fetchpriority="high">';
            }
        }
    }
}
/**
 * Función para agregar una clase a los enlaces de imagen en genesis_entry_content full-img / Classes to images for full width when width lower than 600px
 */
function reban_single_fullwidth_images($content) {
    // Verifica si estamos en una sola publicación
    if (is_single()) {
        // Utiliza expresiones regulares para encontrar y modificar los enlaces de imagen dentro de genesis_entry_content
        $pattern = '/<a(.*?)href=["\']([^"\']+?\.(jpg|jpeg|png|gif))["\'](.*?)><img/i';
        $replacement = '<a$1href="$2"$4 class="full-img"><img';
        $content = preg_replace($pattern, $replacement, $content);
    }
    return $content;
}
add_filter('the_content', 'reban_single_fullwidth_images');
// 4 - Remove comment-reply.min.js
// 5 - YARPP: Remove CSS stylesheets in the header and footer
add_action('wp_print_styles', 'reban_single_remove_yarpp_css');
add_action('wp_footer', 'reban_single_remove_yarpp_css');
function reban_single_remove_yarpp_css() {
    wp_dequeue_style('yarppWidgetCss');
    wp_dequeue_style('yarppRelatedCss');
    wp_deregister_style('yarppRelatedCss');
}	

	
/** Modificar el formato de fecha y la etiqueta del mes */
add_filter('the_time', 'reban_custom_date_format');

// Adds header box - Category, entry-title, entry-meta & the_post_thumbnail (featured image)
add_action('genesis_before_content_sidebar_wrap', 'reban_single_header', 1);
function reban_single_header() {
    $post_id     = get_the_ID();
    $author_id   = get_post_field('post_author', $post_id);
    $thumbnail_id = get_post_thumbnail_id($post_id);

    reban_single_remove_meta();
    ?>

    <div class="header-box">
        <?php reban_single_author_box($author_id, $post_id); ?>
        <?php reban_single_featured_image($thumbnail_id); ?>
    </div>
    <?php
}

/**
 * Remove default title and post meta from the entry header.
 */
function reban_single_remove_meta() {
    remove_action('genesis_entry_header', 'genesis_do_post_title');
    remove_action('genesis_entry_header', 'genesis_post_info', 12);
}

/**
 * Output the category, title and author information in the post header.
 *
 * @param int $author_id Author identifier.
 * @param int $post_id   Current post identifier.
 */
function reban_single_author_box($author_id, $post_id) {
    $author_url = get_author_posts_url($author_id);
    $author_name = get_the_author_meta('display_name', $author_id);
    $categories = get_the_category_list(' ', '', $post_id);
    ?>
    <div class="single-post-category">
        <span><?php echo $categories; ?></span>
    </div>
    <h1 class="entry-title" itemprop="headline"><?php echo esc_html(get_the_title($post_id)); ?></h1>
    <p class="entry-meta">Por
        <span class="entry-author" itemprop="author" itemscope itemtype="https://schema.org/Person">
            <a href="<?php echo esc_url($author_url); ?>" class="entry-author-link" itemprop="url" rel="author">
                <span class="entry-author-name" itemprop="name"><?php echo esc_html($author_name); ?></span>
            </a>
        </span>&nbsp;|&nbsp;
        <time itemprop="datePublished" content="<?php echo esc_attr( get_the_date('Y-m-d', $post_id) ); ?>">
            <?php echo esc_html( get_the_date('F j Y', $post_id) ); ?>
        </time>
    </p>
    <?php
}

/**
 * Display the featured image with WebP support when available.
 *
 * @param int $thumbnail_id Attachment ID for the featured image.
 */
function reban_single_featured_image($thumbnail_id) {
    ?>
    <div class="full-img">
        <?php
        $file_path   = get_attached_file($thumbnail_id);
        $path_parts  = pathinfo($file_path);
        $webp_file   = $path_parts['dirname'] . '/' . $path_parts['filename'] . '.webp';
        $srcset      = wp_get_attachment_image_srcset($thumbnail_id, 'large');
        $sizes_attr  = wp_get_attachment_image_sizes($thumbnail_id, 'large');
        $sizes_value = $sizes_attr ?: '(max-width: 800px) 100vw, 730px';

        if (file_exists($webp_file) && $srcset) {
            $uploads_dir = wp_upload_dir();
            $webp_srcset = preg_replace('/\.(jpe?g|png)(\s+\d+w)/i', '.webp$2', $srcset);

            echo '<picture>';
            echo '<source srcset="' . esc_attr($webp_srcset) . '" sizes="' . esc_attr($sizes_value) . '" type="image/webp">';
            echo '<source srcset="' . esc_attr($srcset) . '" sizes="' . esc_attr($sizes_value) . '">';
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
        }
        ?>
    </div>
    <?php
}
	
/* Wordpress Popular Posts & YARPP Related - Al final de posts  https://github.com/cabrerahector/wordpress-popular-posts/wiki */
add_action('genesis_before_footer', 'custom_related_posts');
function custom_related_posts() {
    ?>   
    <div class="custom-related-posts clearfix">
        <div class="yarpp-related">
            <?php 
            $args = array(
				'header' => 'Tendencias', 
				'header_start' => '<div style="text-align:center"><h3 id="tendencias" class="trending-title"><i class="icon-trending"> </i>',
				'header_end' => '</h3></div>',
				'wpp_start' => '<div class="yarpp-grids">',
				'wpp_end' => '</div>',
				'post_html' => '<a href="{url}" class="yarpp-thumbnail"><img src="{thumb_url}" width="360" height="188" alt="{text_title}" loading="lazy" /><div class="desc"><span>{text_title}</span></div></a>',
				'post_type' => 'post',
                'limit' => 6, // Sets the maximum number of popular posts to be shown on the listing
                'range' => 'custom', // Retrieve the most popular entries within the time range specified by you last24hours, last7days, last30days, all, custom
                'time_quantity' => 3, // Specifies the number of time units of the custom time range
                'time_unit' => 'day', // https://github.com/cabrerahector/wordpress-popular-posts/wiki/2.-Template-tags#parameters
                'freshness' => 0, // Tells WordPress Popular Posts to retrieve the most popular entries published within the time range specified by you
                'thumbnail_width' => 360,
                'thumbnail_height' => 188,
            );
            wpp_get_mostpopular($args);
            ?>
			
		</div>
    </div>
    <div class="custom-related-posts clearfix">
        <?php yarpp_related(); ?>   
    </div>
    <?php
}
	
//* Hook after post widget after the entry content
add_action('genesis_after_entry', 'mpp_after_entry', 5);
function mpp_after_entry() {
        genesis_widget_area('after-entry', array(
            'before' => '<div class="after-entry widget-area">',
            'after' => '</div>'
        ));
}
	
}
genesis();

