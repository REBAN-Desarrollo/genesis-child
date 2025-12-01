<?php
/**
 * Performance-focused tweaks for loading assets and scripts.
 */

/* Headers mods
 * 1 - preconnect / dns preload / preload archivos que se usaran
 *      1.a - Preload: https://web.dev/preload-critical-assets/
 *      1.b - Preconnect / dns-preload:https://web.dev/preconnect-and-dns-prefetch/
 * 2 - Inline ATF Critical CSS:
 */
add_action( 'wp_head', 'reban_perf_preloads', 2 );
function reban_perf_preloads() {
    $theme_dir = get_stylesheet_directory();
    $theme_uri = get_stylesheet_directory_uri();

    $versioned_asset = function ( $relative_path, $fallback_uri = '' ) use ( $theme_dir, $theme_uri ) {
        $file_path = $theme_dir . $relative_path;
        $uri       = $theme_uri . $relative_path;

        static $filetimes = array();

        if ( ! array_key_exists( $file_path, $filetimes ) ) {
            $filetimes[ $file_path ] = file_exists( $file_path ) ? filemtime( $file_path ) : false;
        }

        if ( false !== $filetimes[ $file_path ] ) {
            $uri = add_query_arg( 'v', $filetimes[ $file_path ], $uri );
        } elseif ( $fallback_uri ) {
            $uri = $fallback_uri;
        }

        return esc_url( $uri );
    };

    $logo_data       = array();
    $custom_logo_id  = get_theme_mod( 'custom_logo' );
    $custom_logo_src = $custom_logo_id ? wp_get_attachment_image_src( $custom_logo_id, 'full' ) : false;

    if ( $custom_logo_src ) {
        $logo_data = $custom_logo_src;
    } elseif ( get_header_image() ) {
        $header    = get_custom_header();
        $logo_data = array(
            get_header_image(),
            $header ? (int) $header->width : 0,
            $header ? (int) $header->height : 0,
        );
    } else {
        $logo_data = array(
            $versioned_asset( '/images/Logo-OK-footer-blanco.png', '/wp-content/themes/genesis-child/images/Logo-OK-footer-blanco.png' ),
            287,
            110,
        );
    }

    $logo_src    = $logo_data[0] ?? '';
    $logo_width  = isset( $logo_data[1] ) ? (int) $logo_data[1] : 0;
    $logo_height = isset( $logo_data[2] ) ? (int) $logo_data[2] : 0;

    $preloads = array(
        'reban_woff2'   => $versioned_asset( '/fonts/rebanfont.woff2' ),
        'poppins_woff2' => $versioned_asset( '/fonts/Poppins-SemiBold.woff2' ),
        'proxima_woff2' => $versioned_asset( '/fonts/ProximaNova-Regular.woff2' ),
    );

    $font_urls = array(
        'reban'    => array(
            'woff2' => $preloads['reban_woff2'],
            'woff'  => $versioned_asset( '/fonts/rebanfont.woff' ),
        ),
        'poppins' => array(
            'woff2' => $preloads['poppins_woff2'],
            'woff'  => $versioned_asset( '/fonts/Poppins-SemiBold.woff' ),
        ),
        'proxima' => array(
            'woff2' => $preloads['proxima_woff2'],
            'woff'  => $versioned_asset( '/fonts/ProximaNova-Regular.woff' ),
        ),
    );
    ?>
        <?php if ( $logo_src ) : ?>
            <link rel="preload" href="<?php echo esc_url( $logo_src ); ?>" as="image"<?php echo $logo_width && $logo_height ? ' width="' . esc_attr( $logo_width ) . '" height="' . esc_attr( $logo_height ) . '"' : ''; ?>>
        <?php endif; ?>
        <link rel="preload" href="<?php echo $preloads['reban_woff2']; ?>" as="font" type="font/woff2" crossorigin="anonymous">
        <link rel="preload" href="<?php echo $preloads['poppins_woff2']; ?>" as="font" type="font/woff2" crossorigin="anonymous">
        <link rel="preload" href="<?php echo $preloads['proxima_woff2']; ?>" as="font" type="font/woff2" crossorigin="anonymous">
        
        <link rel="dns-prefetch" href="//ajax.cloudflare.com/">
        <link rel="dns-prefetch" href="//googletagmanager.com">
        <link rel="dns-prefetch" href="//google-analytics.com">
        <link rel="dns-prefetch" href="//sb.scorecardresearch.com">
    <?php
    if ( is_singular( 'post' ) ) {
        return;
    }

    $critical_css = <<<'CSS'
<style id="css-atf-global">i{font-family:'rebanfont'!important;speak:none;font-style:normal;font-weight:normal;font-variant:normal;text-transform:none;line-height:1;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.icon-search:before{content:"\e900"}.icon-menu:before{content:"\e901"}@font-face{font-family:'rebanfont';font-style:normal;font-weight:normal;src:url("/wp-content/themes/okchicas5/fonts/rebanfont.woff2") format("woff2"),url("/wp-content/themes/okchicas5/fonts/rebanfont.woff") format("woff");font-display:swap}@font-face{font-family:"Poppins";font-style:normal;font-weight:500;src:url("/wp-content/themes/okchicas5/fonts/Poppins-SemiBold.woff2") format("woff2"),url("/wp-content/themes/okchicas5/fonts/Poppins-SemiBold.woff") format("woff");font-display:swap}@font-face{font-family:"Poppins-fallback";size-adjust:99.498%;ascent-override:105%;descent-override:46.7%;line-gap-override:0%;font-display:swap;src:local("Verdana")}@font-face{font-family:"Proxima Nova";font-style:normal;font-weight:normal;src:url("/wp-content/themes/okchicas5/fonts/ProximaNova-Regular.woff2") format("woff2"),url("/wp-content/themes/okchicas5/fonts/ProximaNova-Regular.woff") format("woff");font-display:swap}@font-face{font-family:"Proxima-fallback";size-adjust:108%;ascent-override:55%;descent-override:0%;line-gap-override:53.3%;font-display:swap;src:local(Corbel)}html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}main{display:block}h1{font-size:2em;margin:.67em 0}a{background-color:transparent}img{border-style:none}input{font-family:inherit;font-size:100%;line-height:1.15;margin:0}input{overflow:visible}[type=submit]{-webkit-appearance:button}[type=submit]::-moz-focus-inner{border-style:none;padding:0}[type=submit]:-moz-focusring{outline:1px dotted ButtonText}[type=search]{-webkit-appearance:textfield;outline-offset:-2px}[type=search]::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}input[type="search"]{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}.clearfix:before,.entry:before,.nav-primary:before,.site-container:before,.site-header:before,.site-inner:before,.widget:before,.wrap:before{content:" ";display:table}.clearfix:after,.entry:after,.nav-primary:after,.site-container:after,.site-header:after,.site-inner:after,.widget:after,.wrap:after{clear:both;content:" ";display:table}html{font-size:62.5%}body{background-color:#FFFFFF;color:black;font-weight:400;font-size:1.2rem;font-family:Poppins,Poppins-fallback,sans-serif;line-height:0}a{color:#5A5959;text-decoration:none}li{font-family:'Proxima Nova','Proxima-fallback',sans-serif}ul{margin:0;padding:0;line-height:1.5}h1,h2{margin-bottom:1.6rem;padding:0;font-weight:bold}h1{font-size:2.8rem;margin-bottom:1.2rem;line-height:1.4}h2{font-size:2.6rem;margin:5rem 0 1.1rem;line-height:3.2rem}iframe,img{max-width:100%;display:block;margin:0 auto!important}img{height:auto}.post img{display:block;margin-left:auto;margin-right:auto}input{background-color:#fff;border:.1rem solid #ddd;border-radius:.3rem;box-shadow:0 0 .5rem #ddd inset;color:#888;font-size:1.6rem;padding:.8rem;width:100%}::-moz-placeholder{color:#999;opacity:1}::-webkit-input-placeholder{color:#999}input[type="submit"]{background-color:#0096D6;border:none;box-shadow:none;color:#fff;font-size:1.6rem;padding:1.6rem 2.4rem;width:auto;text-align:center;text-decoration:none}input[type="search"]::-webkit-search-cancel-button,input[type="search"]::-webkit-search-results-button{display:none}.wrap{margin:0 auto;max-width:100rem;line-height:0}.site-inner{clear:both;margin:0 auto;max-width:105rem;padding:0 0 2.5rem;margin-top:0}.home .site-inner,.blog .site-inner{clear:both;margin:0 auto;max-width:100rem;padding:0;margin-top:1.5rem}.content{float:right;background-color:white}.content-sidebar-wrap{width:100%;display:table;table-layout:fixed;margin-top:2rem}.blog .content-sidebar-wrap{margin-top:2.5rem}.full-width-content .content{width:100%;padding:0;background-color:white;-moz-box-shadow:none;box-shadow:none}.search-form{display:block;margin:0 auto;overflow:hidden;width:22.4rem}.search-form input[type="text"].search-input{display:block;float:left!important;width:16rem!important;max-width:16rem!important}.search-form input.search-submit{border:0;padding:1.1rem .5rem;padding:1.1rem 0.5rem;clip:rect(auto,auto,auto,auto);display:block;float:right!important;clear:none;width:6rem!important;position:relative!important}.widget-wrap a{text-align:left}.widget{word-wrap:break-word}.widget li{list-style-type:none}.widget li li{border:none}.site-header{width:100%;z-index:9999;position:relative;border-top:.3rem solid #d6006b;background-color:white;padding:0}.site-header .wrap{overflow:hidden;padding:.8rem 0}.site-header .wrap>a{display:none}.title-area{float:left;width:26%}.header-image .title-area{padding:0}.site-title{line-height:1;margin:0}.site-title img{display:block;margin:0 auto;max-height:11rem;height:auto;width:auto}.site-title a{color:#d6006b;text-decoration:none}.header-full-width .title-area,.header-full-width .site-title{width:100%;text-align:center;font-weight:normal}.header-image .site-title a{display:block;text-indent:-9999px}.header-image .site-title a{display:inline-block;width:29rem;height:5.5rem;margin:0;background-size:100%!important}.site-header .search-form{float:right;margin-top:0rem}.genesis-nav-menu{clear:both;color:#fff;line-height:1;width:100%;text-transform:uppercase;font-size:1.5rem;text-align:center}.main-menu-icon{font-size:1.5rem;vertical-align:middle}.genesis-nav-menu .menu-item{display:inline-block;text-align:left;float:none!important}.genesis-nav-menu li.menu-item-has-children a:after{position:relative;top:1.1rem;content:'';border-left:.5rem solid transparent;border-right:.5rem solid transparent;border-top:0.6rem solid #000;margin:0 0 0 .8rem}.genesis-nav-menu li.menu-item-has-children li a:after{display:none;content:'';width:0;height:0;border-left:.5rem solid transparent;border-right:.5rem solid transparent;border-top:.5rem solid #787884}.genesis-nav-menu a{color:#000;display:block;line-height:1.3;padding:1.42rem 1.6rem;position:relative;font-weight:bold}.genesis-nav-menu .sub-menu{left:-9999px;opacity:0;text-transform:none;position:absolute;width:20rem;z-index:99}.genesis-nav-menu .sub-menu a{background-color:#fff;color:#222;border:.1rem solid #BFBDBD;border-top:none;font-size:1.4rem;padding:1rem;position:relative;width:20rem}.nav-primary{background-color:white;border-top:1px solid #ccc;border-bottom:1px solid #ccc}.home .content .entry,.blog .content .entry{position:relative;float:left;margin:0 0 5%;width:100%}.full-post-container{align-items:center;display:flex}.post-left-col{width:55%}.post-right-col{padding:1.4rem 1.4rem 0;position:initial;text-align:center;vertical-align:middle;width:50%}.full-post-container.odd{flex-direction:row-reverse}.home .content .entry h2,.home .content .entry .author,.blog .content .entry h2,.blog .content .entry .author{padding:0;line-height:2.7rem;font-size:1.5rem;margin:0 0 .5rem}.home .content .entry h2 a,.blog .content .entry h2 a{color:#000000;font-weight:600;line-height:3.5rem;font-size:2.7rem}.home .content .entry .author,.blog .content .entry .author{font-size:1.1rem!important;color:#5A5959!important;padding:0 1rem .5rem 0!important;text-transform:uppercase}.home .content .entry .time,.blog .content .entry .time{right:0;font-size:1.1rem;padding:0 1rem;text-transform:none;color:#5A5959!important;text-transform:uppercase}.home .content .entry:nth-of-type(2n+1){clear:both}.entry{margin-bottom:0rem}@media only screen and (max-width:1155px){.site-inner,.wrap{max-width:90%}}@media only screen and (max-width:944px){.header-image .site-header .site-title a{margin:0;width:23rem;height:4.3rem;vertical-align:bottom}.site-inner{max-width:95%;max-width:73.1rem;margin-top:5.7rem!important}.site-header{position:fixed}.site-title{font-size:3.8rem;margin:0}.home .content .entry h2 a{line-height:2.8rem;font-size:2.3rem}.content,.title-area{max-width:100%}.nav-primary{display:none}.responsive-search{margin-top:0}.site-header{border-bottom:.1rem solid #BFBDBD}.wrap{max-width:100%}.site-header{padding:0}.genesis-nav-menu li{float:none}.genesis-nav-menu,.site-header .search-form,.site-header .title-area,.site-title{text-align:center}h2{font-size:2.4rem;font-weight:bold;line-height:3.4rem}.nav-primary{display:block!important;border:none;box-shadow:none}.nav-primary .menu li{display:none}.nav-primary .menu li.mobile-item:last-child{right:0}.site-title{font-size:3.8rem;margin:0}.site-header .wrap>a{display:block;position:absolute;font-size:2.5rem;color:black;padding:1.5rem 1.25rem 1.2rem;top:0}.site-header .wrap>a.sb-toggle-right{right:.1rem}}@media only screen and (max-width:600px){.site-inner{max-width:100%;margin:1.5rem 3% 1rem}.home .content .entry h2 a{font-weight:600;line-height:2.7rem;font-size:2rem}.home .content .entry{textalign:left}.full-post-container{display:block}.post-left-col,.post-right-col{width:100%;text-align:center}.home .content .entry,.home .content .entry:nth-of-type(3n+3){float:none;margin:0 auto 2.5rem;max-width:52rem;width:100%}.home .content .entry:nth-of-type(2n),.home .content .entry:nth-of-type(2n+1){clear:both;float:none}.genesis-nav-menu a{top:.4rem}.nav-primary .menu li.mobile-item{top:0rem}}@media only screen and (max-width:480px){.home .site-inner{padding:0 0 1rem 0}.header-image .site-header .site-title a{background-size:80% 80%}}.sb-slidebar{width:28rem;top:0;bottom:0;position:fixed;overflow-x:hidden;z-index:50000;background:#22282b}.responsive-search{width:100%;margin-top:0;background:#000}.responsive-search .search-form{max-width:22.4rem;height:0;clear:both;float:none;margin:0 auto}.sb-slidebar.sb-left{border-right:.4rem solid #d6006b}.sb-slidebar{padding:2rem 1rem}*{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}#sb-site{padding:0}.sb-slidebar{background-color:#222222;color:#e8e8e8}.sb-slidebar a{color:#d6006b;text-decoration:none}.sb-menu{padding:0;margin:0;list-style-type:none;line-height:2rem;background-color:#222222}.sb-menu li{width:100%;padding:0;margin:0;border-top:.1rem solid rgba(255,255,255,0.1);border-bottom:.1rem solid rgba(0,0,0,0.1)}.sb-menu>li:first-child{border-top:none}.sb-menu>li:last-child{border-bottom:none}.sb-menu li a{width:100%;display:inline-block;padding:1em;color:#f2f2f2}.sb-left .sb-menu li a{border-left:.3rem solid transparent;font-weight:900;padding:.6rem 1.3rem;font-size:1.45rem}.sb-left .sb-menu li li a{padding:.4rem 3rem}.sb-left .sb-menu li li a:before{font-size:1.6rem;padding-right:1.2rem}#sb-site,.sb-slidebar,body,html{margin:0;padding:0;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}body,html{width:100%;overflow-x:hidden}html{height:100%}body{min-height:100%;height:auto;position:relative}#sb-site{width:100%;min-height:100vh;position:relative;z-index:1;background-color:#fff}#sb-site:after,#sb-site:before{content:' ';display:table;clear:both}.sb-slidebar{height:100%;overflow-y:auto;position:fixed;top:0;z-index:0;display:none;background-color:#222}.sb-slidebar,.sb-slidebar *{-webkit-transform:translateZ(0px)}.sb-left{left:0}.sb-right{right:0}.sb-style-overlay{z-index:9999}.sb-slidebar{width:30%}@media (max-width:480px){.sb-slidebar{width:70%}}@media (min-width:481px){.sb-slidebar{width:55%}}@media (min-width:768px){.sb-slidebar{width:40%}}@media (min-width:992px){.sb-slidebar{width:30%}}@media (min-width:1200px){.sb-slidebar{width:20%}}#sb-site,.sb-slidebar{-webkit-backface-visibility:hidden}.sb-slidebar .widget:first-child{margin:0 0 10rem 0}.sb-menu li li{margin:0;line-height:2.5rem;border-top:.1rem solid #2f2f2f}.sb-slidebar .widget_search{background:#d6006b;padding:1.2rem 1.5rem}.sb-slidebar .search-form{background:rgba(0,0,0,0.25);border-radius:6rem;display:block;width:98%}.sb-slidebar form input[type="search"]{background:none;border:none;box-shadow:none;color:#fff;display:block;float:left;font-size:1.4rem;line-height:1.6rem;margin:0;padding:1.2rem 0 .9rem 2rem;width:85%}.search-form input[type="submit"]{font-family:'rebanfont';display:block;float:left;padding:1.2rem 1.2rem 0 0;line-height:1.6rem;background:none;border:none;width:13%;content:"\e900"}</style>
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

// Transform styles.css markup to load CSS asynchronously and add style.css on head position #2.
add_filter( 'style_loader_tag', 'reban_perf_async_css', 10, 2 );
function reban_perf_async_css( $html, $handle ) {
    if ( $handle == CHILD_THEME_NAME ) {
        $async_html = preg_replace(
            '/media=("|\')all\\1/',
            'media=$1print$1 onload="this.media=\'all\'"',
            $html
        );
        return $async_html . "<noscript>{$html}</noscript>";
    }
    return $html;
}

/** Add async attributes to enqueued scripts where needed.The ability to filter script tags was added in WordPress 4.1 for this purpose. */
add_filter( 'script_loader_tag', 'reban_perf_async_js', 10, 3 );
function reban_perf_async_js( $tag, $handle, $src ) {
    // the handles of the enqueued scripts we want to async.
    $async_scripts = array( CHILD_THEME_NAME );
    if ( in_array( $handle, $async_scripts ) ) {
        return '<script type="text/javascript" async src="' . $src . '"></script>' . "\n";
    }
    return $tag;
}

// Wordpress popular post async script https://wordpress.org/support/topic/defer-js-3/.
add_action( 'template_redirect', 'add_async_attribute_to_wpp_js', 0 );
function add_async_attribute_to_wpp_js() {
    ob_start(
        function ( $html ) {
            $html = str_replace(
                'id="wpp-js" src=',
                'id="wpp-js" async src=',
                $html
            );
            return $html;
        }
    );
}
