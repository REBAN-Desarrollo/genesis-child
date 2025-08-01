<?php
//* Start the engine
include_once(get_template_directory() . '/lib/init.php');
//* Set Localization (do not remove)
load_child_theme_textdomain('mpp', apply_filters('child_theme_textdomain', get_stylesheet_directory() . '/languages', 'mpp'));
//* Child theme (do not remove)
define('CHILD_THEME_NAME', __('reban', 'mpp'));
define('CHILD_THEME_URL', 'http://www.okchicas.com/');
define('CHILD_THEME_VERSION', '240518a');
// Lang html to spanish
add_filter('language_attributes', 'custom_lang_attr');
function custom_lang_attr() {
	  return 'lang="es"';
}
//* Add HTML5 markup structure
add_theme_support('html5', array(
    'search-form',
    'comment-form',
    'comment-list'
));
//* Add custom Viewport meta tag for mobile browsers
// add_action( 'genesis_meta', 'sp_viewport_meta_tag' );
function sp_viewport_meta_tag() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
}
//* Remove the secondary navigation menu
remove_action('genesis_after_header', 'genesis_do_subnav');

/**
 * Devuelve la fecha formateada con nombres de meses en español.
 *
 * @return string
 */
function okc_format_date() {
    $month_names = array(
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    );

    return $month_names[get_the_time('n')] . ' ' . get_the_time('j') . ', ' . get_the_time('Y');
}

/* Optimize your CSS https://web.dev/fast/#optimize-your-css | STYLE.CSS MODS to optimize delivery
 * 	1 - Remove parent style.css
 * 	2 - Replace style.css with a date query string when modified
 * 	3 - Tansform styles.css markup to defer non critical CSS: https://web.dev/defer-non-critical-css/
 * 	4 - Enqueue /js/all.js script
 * 	5 - We add headers, a) dns preload, b) loadCSS for async css - https://github.com/filamentgroup/loadCSS
*/
// 1 - Remove parent style.css
remove_action( 'genesis_meta', 'genesis_load_stylesheet' );
// 2 - Replace style.css with a date query string when modified
add_action( 'wp_enqueue_scripts', 'cache_buster_styles' );
function cache_buster_styles() {
	// Get the stylesheet info.
	$stylesheet_uri = get_stylesheet_directory_uri() . '/css/style.css';
	$stylesheet_dir = get_stylesheet_directory() . '/css/style.css';
	$last_modified = date ( "ymd.hi", filemtime( $stylesheet_dir ) );
	// Enqueue the stylesheet.
	wp_enqueue_style( CHILD_THEME_NAME , $stylesheet_uri, array(), $last_modified );
}
// 3 - Tansform styles.css markup to load CSS asynchronously and add style.css on head position #2
	// Opcion 1 https://github.com/filamentgroup/loadCSS/blob/master/README.md#how-to-use | https://www.filamentgroup.com/lab/load-css-simpler/#the-code
	/* add_filter( 'style_loader_tag', 'style_transform_loadCSS', 10, 2 );
	function style_transform_loadCSS( $html, $handle ) {
		if ( $handle == CHILD_THEME_NAME  ) {
			$search = array("rel='stylesheet' id='$handle-css'", "type='text/css' media='all'");
			$replace = array("rel=\"stylesheet\"", "media=\"print\" onload=\"this.media='all'; this.onload=null;\"");
			return str_replace($search, $replace, $html)."<noscript>{$html}</noscript>";
		}
		return $html;
	}
	*/
	// Opcion 2 https://web.dev/defer-non-critical-css/#optimize | Contra del preload: https://www.filamentgroup.com/lab/load-css-simpler/#can%E2%80%99t-rel%3Dpreload-do-this-too%3F
	add_filter( 'style_loader_tag', 'style_transform_loadCSS', 10, 2 );
	function style_transform_loadCSS( $html, $handle ) {
		if ( $handle == CHILD_THEME_NAME  ) {
			$search = array("rel='stylesheet' id='$handle-css'", "type='text/css' media='all'");
			$replace = array("rel=\"preload\"", "as=\"style\" onload=\"this.onload=null;this.rel='stylesheet'\"");
			return str_replace($search, $replace, $html)."<noscript>{$html}</noscript>";
		}
		return $html;
	}
// 4 - Enqueue /js/all.js script
add_action( 'wp_enqueue_scripts', 'reban_main_js' );
function reban_main_js() {
	// Get the js info.
	$javascript_uri = get_stylesheet_directory_uri() . '/js/all.js';
	$javascript_dir = get_stylesheet_directory() . '/js/all.js';
	$last_modified = date ( "ymd.hi", filemtime( $javascript_dir ) );
	// Enqueue the stylesheet.
	wp_enqueue_script( CHILD_THEME_NAME , $javascript_uri, array(), $last_modified );
}
	/** Add async attributes to enqueued scripts where needed.The ability to filter script tags was added in WordPress 4.1 for this purpose. */
add_filter( 'script_loader_tag', 'my_main_async_scripts', 10, 3 );
function my_main_async_scripts( $tag, $handle, $src ) {
    	// the handles of the enqueued scripts we want to async
	$async_scripts = array( CHILD_THEME_NAME);
		if ( in_array( $handle, $async_scripts ) ) {
			return '<script type="text/javascript" async src="' . $src . '"></script>' . "\n";
		}
	return $tag;
}


/* Headers mods
 * 1 - preconnect / dns preload / preload archivos que se usaran
 * 		1.a - Preload: https://web.dev/preload-critical-assets/
 * 		1.b - Preconnect / dns-preload:https://web.dev/preconnect-and-dns-prefetch/
 * 2 - Inline ATF Critical CSS: 
 */
add_action( 'wp_head', 'headscripts1', 2);
function headscripts1() { 
	?>
		<link rel="preload" href="/wp-content/uploads/2023/01/Logo-OK-circulo-619x110-02.png" width="619" height="110" as="image">
		<link rel="preload" href="/wp-content/themes/okchicas5/fonts/rebanfont.woff2" as="font" type="font/woff2" crossorigin="anonymous">
		<link rel="preload" href="/wp-content/themes/okchicas5/fonts/Poppins-SemiBold.woff2" as="font" type="font/woff2" crossorigin="anonymous">
		<link rel="preload" href="/wp-content/themes/okchicas5/fonts/ProximaNova-Regular.woff2" as="font" type="font/woff2" crossorigin="anonymous">
		
		<link rel="dns-prefetch" href="//ajax.cloudflare.com/">
		<link rel="dns-prefetch" href="//googletagmanager.com">
		<link rel="dns-prefetch" href="//google-analytics.com">
		<link rel="dns-prefetch" href="//sb.scorecardresearch.com">
	<?php
	if ( is_singular('post') ) {

	} else {
		?>
<style id="css-atf-global">i{font-family:'rebanfont'!important;speak:none;font-style:normal;font-weight:normal;font-variant:normal;text-transform:none;line-height:1;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.icon-search:before{content:"\e900"}.icon-menu:before{content:"\e901"}@font-face{font-family:'rebanfont';font-style:normal;font-weight:normal;src:url("/wp-content/themes/okchicas5/fonts/rebanfont.woff2") format("woff2"),url("/wp-content/themes/okchicas5/fonts/rebanfont.woff") format("woff"),url("/wp-content/themes/okchicas5/fonts/rebanfont.ttf") format("truetype");font-display:swap}@font-face{font-family:"Poppins";font-style:normal;font-weight:500;src:url("/wp-content/themes/okchicas5/fonts/Poppins-SemiBold.woff2") format("woff2"),url("/wp-content/themes/okchicas5/fonts/Poppins-SemiBold.woff") format("woff"),url("/wp-content/themes/okchicas5/fonts/Poppins-SemiBold.ttf") format("truetype");font-display:swap}@font-face{font-family:"Poppins-fallback";size-adjust:99.498%;ascent-override:105%;descent-override:46.7%;line-gap-override:0%;src:local("Verdana")}@font-face{font-family:"Proxima Nova";font-style:normal;font-weight:normal;src:url("/wp-content/themes/okchicas5/fonts/ProximaNova-Regular.woff2") format("woff2"),url("/wp-content/themes/okchicas5/fonts/ProximaNova-Regular.woff") format("woff"),url("/wp-content/themes/okchicas5/fonts/ProximaNova-Regular.ttf") format("truetype");font-display:swap}@font-face{font-family:"Proxima-fallback";size-adjust:108%;ascent-override:55%;descent-override:0%;line-gap-override:53.3%;src:local(Corbel)}html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}main{display:block}h1{font-size:2em;margin:.67em 0}a{background-color:transparent}img{border-style:none}input{font-family:inherit;font-size:100%;line-height:1.15;margin:0}input{overflow:visible}[type=submit]{-webkit-appearance:button}[type=submit]::-moz-focus-inner{border-style:none;padding:0}[type=submit]:-moz-focusring{outline:1px dotted ButtonText}[type=search]{-webkit-appearance:textfield;outline-offset:-2px}[type=search]::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}input[type="search"]{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}.clearfix:before,.entry:before,.nav-primary:before,.site-container:before,.site-header:before,.site-inner:before,.widget:before,.wrap:before{content:" ";display:table}.clearfix:after,.entry:after,.nav-primary:after,.site-container:after,.site-header:after,.site-inner:after,.widget:after,.wrap:after{clear:both;content:" ";display:table}html{font-size:62.5%}body{background-color:#FFFFFF;color:black;font-weight:400;font-size:1.2rem;font-family:Poppins,Poppins-fallback,sans-serif;line-height:0}a{color:#5A5959;text-decoration:none}li{font-family:'Proxima Nova','Proxima-fallback',sans-serif}ul{margin:0;padding:0;line-height:1.5}h1,h2{margin-bottom:1.6rem;padding:0;font-weight:bold}h1{font-size:2.8rem;margin-bottom:1.2rem;line-height:1.4}h2{font-size:2.6rem;margin:5rem 0 1.1rem;line-height:3.2rem}iframe,img{max-width:100%;display:block;margin:0 auto!important}img{height:auto}.post img{display:block;margin-left:auto;margin-right:auto}input{background-color:#fff;border:.1rem solid #ddd;border-radius:.3rem;box-shadow:0 0 .5rem #ddd inset;color:#888;font-size:1.6rem;padding:.8rem;width:100%}::-moz-placeholder{color:#999;opacity:1}::-webkit-input-placeholder{color:#999}input[type="submit"]{background-color:#0096D6;border:none;box-shadow:none;color:#fff;font-size:1.6rem;padding:1.6rem 2.4rem;width:auto;text-align:center;text-decoration:none}input[type="search"]::-webkit-search-cancel-button,input[type="search"]::-webkit-search-results-button{display:none}.wrap{margin:0 auto;max-width:100rem;line-height:0}.site-inner{clear:both;margin:0 auto;max-width:105rem;padding:0 0 2.5rem;margin-top:0}.home .site-inner,.blog .site-inner{clear:both;margin:0 auto;max-width:100rem;padding:0;margin-top:1.5rem}.content{float:right;background-color:white}.content-sidebar-wrap{width:100%;display:table;table-layout:fixed;margin-top:2rem}.blog .content-sidebar-wrap{margin-top:2.5rem}.full-width-content .content{width:100%;padding:0;background-color:white;-moz-box-shadow:none;box-shadow:none}.search-form{display:block;margin:0 auto;overflow:hidden;width:22.4rem}.search-form input[type="text"].search-input{display:block;float:left!important;width:16rem!important;max-width:16rem!important}.search-form input.search-submit{border:0;padding:1.1rem .5rem;padding:1.1rem 0.5rem;clip:rect(auto,auto,auto,auto);display:block;float:right!important;clear:none;width:6rem!important;position:relative!important}.widget-wrap a{text-align:left}.widget{word-wrap:break-word}.widget li{list-style-type:none}.widget li li{border:none}.site-header{width:100%;z-index:9999;position:relative;border-top:.3rem solid DeepPink;background-color:white}.site-header .wrap{overflow:hidden;padding:0 0 0;padding:0 0 0}.site-header .wrap>a{display:none}.title-area{float:left;width:26%}.header-image .title-area{padding:0}.site-title{line-height:3.8rem;margin:.5rem 0}.site-title a{color:#FC166E;text-decoration:none}.header-full-width .title-area,.header-full-width .site-title{width:100%;text-align:center;font-weight:normal}.header-image .site-title a{display:block;text-indent:-9999px}.header-image .site-title a{display:inline-block;width:29rem;height:5.5rem;margin:.5rem 0;background-size:100%!important}.site-header .search-form{float:right;margin-top:0rem}.genesis-nav-menu{clear:both;color:#fff;line-height:1;width:100%;text-transform:uppercase;font-size:1.5rem;text-align:center}.main-menu-icon{font-size:1.5rem;vertical-align:middle}.genesis-nav-menu .menu-item{display:inline-block;text-align:left;float:none!important}.genesis-nav-menu li.menu-item-has-children a:after{position:relative;top:1.1rem;content:'';border-left:.5rem solid transparent;border-right:.5rem solid transparent;border-top:0.6rem solid #000;margin:0 0 0 .8rem}.genesis-nav-menu li.menu-item-has-children li a:after{display:none;content:'';width:0;height:0;border-left:.5rem solid transparent;border-right:.5rem solid transparent;border-top:.5rem solid #787884}.genesis-nav-menu a{color:#000;display:block;line-height:1.3;padding:1.42rem 1.6rem;position:relative;font-weight:bold}.genesis-nav-menu .sub-menu{left:-9999px;opacity:0;text-transform:none;position:absolute;width:20rem;z-index:99}.genesis-nav-menu .sub-menu a{background-color:#fff;color:#222;border:.1rem solid #BFBDBD;border-top:none;font-size:1.4rem;padding:1rem;position:relative;width:20rem}.nav-primary{background-color:white;border-top:1px solid #ccc;border-bottom:1px solid #ccc}.home .content .entry,.blog .content .entry{position:relative;float:left;margin:0 0 5%;width:100%}.full-post-container{align-items:center;display:flex}.post-left-col{width:55%}.post-right-col{padding:1.4rem 1.4rem 0;position:initial;text-align:center;vertical-align:middle;width:50%}.full-post-container.odd{flex-direction:row-reverse}.home .content .entry h2,.home .content .entry .author,.blog .content .entry h2,.blog .content .entry .author{padding:0;line-height:2.7rem;font-size:1.5rem;margin:0 0 .5rem}.home .content .entry h2 a,.blog .content .entry h2 a{color:#000000;font-weight:600;line-height:3.5rem;font-size:2.7rem}.home .content .entry .author,.blog .content .entry .author{font-size:1.1rem!important;color:#5A5959!important;padding:0 1rem .5rem 0!important;text-transform:uppercase}.home .content .entry .time,.blog .content .entry .time{right:0;font-size:1.1rem;padding:0 1rem;text-transform:none;color:#5A5959!important;text-transform:uppercase}.home .content .entry:nth-of-type(2n+1){clear:both}.entry{margin-bottom:0rem}@media only screen and (max-width:1155px){.site-inner,.wrap{max-width:90%}}@media only screen and (max-width:944px){.header-image .site-header .site-title a{margin:0;width:23rem;height:4.3rem;vertical-align:bottom}.site-inner{max-width:95%;max-width:73.1rem;margin-top:5.7rem!important}.site-header{position:fixed}.site-title{font-size:3.8rem}.home .content .entry h2 a{line-height:2.8rem;font-size:2.3rem}.content,.title-area{max-width:100%}.nav-primary{display:none}.responsive-search{margin-top:0}.site-header{border-bottom:.1rem solid #BFBDBD}.wrap{max-width:100%}.site-header{padding:0}.genesis-nav-menu li{float:none}.genesis-nav-menu,.site-header .search-form,.site-header .title-area,.site-title{text-align:center}h2{font-size:2.4rem;font-weight:bold;line-height:3.4rem}.nav-primary{display:block!important;border:none;box-shadow:none}.nav-primary .menu li{display:none}.nav-primary .menu li.mobile-item:last-child{right:0}.site-title{font-size:3.8rem;margin:.5rem 0}.site-header .wrap>a{display:block;position:absolute;font-size:2.5rem;color:black;padding:1.5rem 1.25rem 1.2rem;top:0}.site-header .wrap>a.sb-toggle-right{right:.1rem}}@media only screen and (max-width:600px){.site-inner{max-width:100%;margin:1.5rem 3% 1rem}.home .content .entry h2 a{font-weight:600;line-height:2.7rem;font-size:2rem}.home .content .entry{text-align:left}.full-post-container{display:block}.post-left-col,.post-right-col{width:100%;text-align:center}.home .content .entry,.home .content .entry:nth-of-type(3n+3){float:none;margin:0 auto 2.5rem;max-width:52rem;width:100%}.home .content .entry:nth-of-type(2n),.home .content .entry:nth-of-type(2n+1){clear:both;float:none}.genesis-nav-menu a{top:.4rem}.nav-primary .menu li.mobile-item{top:0rem}}@media only screen and (max-width:480px){.home .site-inner{padding:0 0 1rem 0}.header-image .site-header .site-title a{background-size:80% 80%}}.sb-slidebar{width:28rem;top:0;bottom:0;position:fixed;overflow-x:hidden;z-index:50000;background:#22282b}.responsive-search{width:100%;margin-top:0;background:#000}.responsive-search .search-form{max-width:22.4rem;height:0;clear:both;float:none;margin:0 auto}.sb-slidebar.sb-left{border-right:.4rem solid DeepPink}.sb-slidebar{padding:2rem 1rem}*{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}#sb-site{padding:6.5rem 1.5rem 1.5rem 1.5rem}.sb-slidebar{background-color:#222222;color:#e8e8e8}.sb-slidebar a{color:#FF3971;text-decoration:none}.sb-menu{padding:0;margin:0;list-style-type:none;line-height:2rem;background-color:#222222}.sb-menu li{width:100%;padding:0;margin:0;border-top:.1rem solid rgba(255,255,255,0.1);border-bottom:.1rem solid rgba(0,0,0,0.1)}.sb-menu>li:first-child{border-top:none}.sb-menu>li:last-child{border-bottom:none}.sb-menu li a{width:100%;display:inline-block;padding:1em;color:#f2f2f2}.sb-left .sb-menu li a{border-left:.3rem solid transparent;font-weight:900;padding:.6rem 1.3rem;font-size:1.45rem}.sb-left .sb-menu li li a{padding:.4rem 3rem}.sb-left .sb-menu li li a:before{font-size:1.6rem;padding-right:1.2rem}#sb-site,.sb-slidebar,body,html{margin:0;padding:0;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}body,html{width:100%;overflow-x:hidden}html{height:100%}body{min-height:100%;height:auto;position:relative}#sb-site{width:100%;min-height:100vh;position:relative;z-index:1;background-color:#fff}#sb-site:after,#sb-site:before{content:' ';display:table;clear:both}.sb-slidebar{height:100%;overflow-y:auto;position:fixed;top:0;z-index:0;display:none;background-color:#222}.sb-slidebar,.sb-slidebar *{-webkit-transform:translateZ(0px)}.sb-left{left:0}.sb-right{right:0}.sb-style-overlay{z-index:9999}.sb-slidebar{width:30%}@media (max-width:480px){.sb-slidebar{width:70%}}@media (min-width:481px){.sb-slidebar{width:55%}}@media (min-width:768px){.sb-slidebar{width:40%}}@media (min-width:992px){.sb-slidebar{width:30%}}@media (min-width:1200px){.sb-slidebar{width:20%}}#sb-site,.sb-slidebar{-webkit-backface-visibility:hidden}.sb-slidebar .widget:first-child{margin:0 0 10rem 0}.sb-menu li li{margin:0;line-height:2.5rem;border-top:.1rem solid #2f2f2f}.sb-slidebar .widget_search{background:DeepPink;padding:1.2rem 1.5rem}.sb-slidebar .search-form{background:rgba(0,0,0,0.25);border-radius:6rem;display:block;width:98%}.sb-slidebar form input[type="search"]{background:none;border:none;box-shadow:none;color:#fff;display:block;float:left;font-size:1.4rem;line-height:1.6rem;margin:0;padding:1.2rem 0 .9rem 2rem;width:85%}.search-form input[type="submit"]{font-family:'rebanfont';display:block;float:left;padding:1.2rem 1.2rem 0 0;line-height:1.6rem;background:none;border:none;width:13%;content:"\e900"}</style>
		<?php
	}

}


//* Add support for custom header logo image
add_theme_support('custom-header', array(
    'header_image' => '',
    'header-selector' => '.site-title a',
    'header-text' => false,
    'height' => 110,
    'width' => 619,
));

//* Add new image sizes
add_image_size('portfolio', 520, 272, TRUE);

//* Unregister layout settings
genesis_unregister_layout('content-sidebar-sidebar');
genesis_unregister_layout('sidebar-content-sidebar');
genesis_unregister_layout('sidebar-sidebar-content');

//* Unregister secondary sidebar
unregister_sidebar('sidebar-alt');
remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_open', 5);
remove_action('genesis_entry_footer', 'genesis_post_meta');
remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_close', 15);

//* Customize the entry meta in the entry header (requires HTML5 theme support)
add_filter('genesis_post_info', 'sp_post_info_filter');
function sp_post_info_filter($post_info) {
    $category  = get_the_category();
    //$post_info =  '<span class="post-category">' . $category[0]->cat_name .'</span>'. '[post_author_posts_link] [post_date]';
    $post_info = '- Por [post_author_posts_link]';
    return $post_info;
}

//* Customize search form input box text
add_filter('genesis_search_text', 'sp_search_text');
function sp_search_text($text) {
    return esc_attr('Buscar en el sitio');
}
add_action('genesis_header', 'responsive_search');
function responsive_search() {
?>
	<div class="clearfix"></div>
	<div class="responsive-search sb-right">
		<form role="search" method="get" class="search-form" action="<?php echo home_url('/'); ?>">
			<label>Busqueda:
					<input type="text" value="" name="s" class="search-input" placeholder="Buscar en el sitio" />
			</label>
			<input type="submit" class="search-submit" value="Buscar"/>
		</form>
	</div>
<?php
}



// Remove various unused files
/* Remove html5shiv que es para IE 9 o menor y ya es menos del .3% de los visitantes */
add_action('wp_enqueue_scripts', 'html5shiv_script');
function html5shiv_script() {
    wp_dequeue_script('html5shiv');
}
// Remove DNS-Prefetch WordPress
add_action( 'init', 'remove_dns_prefetch' ); 
function  remove_dns_prefetch () {      
   remove_action( 'wp_head', 'wp_resource_hints', 2, 99 ); 
} 
// How To Disable REST API Link – api.w.org
	//remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
// Remove RSS+xml comments feed 
add_filter( 'feed_links_show_comments_feed', '__return_false' );
//  Remove xmlrpc.php?rsd
remove_action('wp_head', 'rsd_link');
// Remove wp-embed
function my_deregister_scripts(){
 wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'my_deregister_scripts' );
/* Remove emojis */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
/* Remove jquery */
add_action('wp_enqueue_scripts', 'no_more_jquery');
function no_more_jquery(){
    wp_deregister_script('jquery');
}
// Remove classic themes css
add_action( 'wp_enqueue_scripts', 'mywptheme_child_deregister_styles', 20 );
function mywptheme_child_deregister_styles() {
    wp_dequeue_style( 'classic-theme-styles' );

}
/* Remove Wordpress Gutenber Default stylesheet.css /wp-includes/css/dist/block-library/style.min.css?ver=5.1.1 */
add_action( 'wp_enqueue_scripts', function() {
	wp_dequeue_style( 'wp-block-library' );
});



/* Slidebars */
include('sb-functions.php');

/* Plugins mods 
 * 1) Wordpress popular posts
*/ 
// 1) Wordpress popular post async script https://wordpress.org/support/topic/defer-js-3/ 
/**
 * Alter script tag(s) to async/defer it/them.
 *
 * @see https://developer.wordpress.org/reference/hooks/script_loader_tag/
 *
 * @param string $tag The <script> tag for the enqueued script.
 * @param string $handle The script's registered handle.
 * @param string $url The script's source URL.
 * @return string $tag The (modified) <script> tag for the enqueued script.
 **/
/**
 * Añadir 'async' manualmente al script wpp.js usando output buffering
 */
add_action('template_redirect', 'add_async_attribute_to_wpp_js', 0);
function add_async_attribute_to_wpp_js() {
    ob_start(function($html){
        // Añadir async al script wpp.js (siempre que exista en el HTML)
        $html = str_replace(
            'id="wpp-js" src=',
            'id="wpp-js" async src=',
            $html
        );
        return $html;
    });
}

/* Custom embeds
	1 - Youtube Videos remove show info related etc
	2 - Hide Instagram Captions
*/
// 1 - Youtube Videos remove show info related etc
function custom_youtube_settings($code) {
	if(strpos($code, 'youtube.com') !== false || strpos($code, 'youtu.be') !== false){
		$return = preg_replace("@src=(['\"])?([^'\">\s]*)@", "src=$1$2&cc_lang_pref=es&hl=es&showinfo=0&rel=0&autohide=1&modestbranding=1&iv_load_policy=3", $code);
		return $return;
	}
	return $code;
}
add_filter('embed_handler_html', 'custom_youtube_settings');
add_filter('embed_oembed_html', 'custom_youtube_settings');
// 2 - Hide Instagram Captions
function custom_instagram_settings($code) {
    if(strpos($code, 'instagr.am') !== false || strpos($code, 'instagram.com') !== false){ // if instagram embed
        $return = preg_replace("@data-instgrm-captioned@", "", $code); // remove caption class
        return $return;     
    }
return $code;
}
add_filter('embed_handler_html', 'custom_instagram_settings');
add_filter('embed_oembed_html', 'custom_instagram_settings');


/* Footer Mods */ 
add_action( 'genesis_footer', 'sp_custom_footer' ,5);
function sp_custom_footer() {
	?>
    <a href="<?php bloginfo('url'); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/Logo-OK-footer-blanco.png" width="287" height="110" class="footer-logo" loading="lazy" alt="Logo OKChicas footer transparente y blanco"/> </a>
	<ul id="footer-social">
		<li><a class="social-button-link" href="https://www.facebook.com/OkChicasBlog/" aria-label="Facebook footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-facebook"></i></a></li>
		<li><a class="social-button-link" href="https://www.instagram.com/okchicas/" aria-label="Instagram footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-instagram"></i></a></li>
		<li><a class="social-button-link" href="https://www.youtube.com/channel/UC4emviWglNnjU6en1P_e5uQ" aria-label="YouTube footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-youtube"></i></a></li>
		<li><a class="social-button-link" href="https://www.pinterest.com.mx/okchicas/" aria-label="Pinterest footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-pinterest"></i></a></li>
		<li><a class="social-button-link" href="https://twitter.com/OkChicasOficial" aria-label="Twitter footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-twitter"></i></a></li>
		<li><a class="social-button-link" href="mailto:soporte@okchicas.com" aria-label="email footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-mail"></i></a></li>
		<li><a class="social-button-link" href="https://www.okchicas.com/feed/" aria-label="RSS footer icon" target="_blank" rel="nofollow noopener noreferrer"><i class="icon-rss"></i></a></li>
	</ul>
	<?php
}
//* Footer menu
add_action('init', 'register_my_menus');
function register_my_menus() {
    register_nav_menus(array(
        'footer-menu' => __('Footer Menu')
    ));
}
remove_action('genesis_footer', 'genesis_do_footer');
add_action('genesis_footer', 'genesis_user_footer');
function genesis_user_footer() {
	?>
		<div id="footer-menu">
				<?php
					wp_nav_menu(array(
						'theme_location' => 'footer-menu',
						'menu_class' => '',
						'fallback_cb' => 'false'
					));
				?>
		</div>
		<div id="copyright"><p>&copy;<?php echo date('o'); ?> Grupo Reban. Todos los derechos reservados</p></div>
		<div id="footer-menu">
				<?php
					wp_nav_menu(array(
						'theme_location' => 'secondary',
						'menu_class' => '',
						'fallback_cb' => 'false'
					));
				?>
		</div>
	<?php
}




add_filter( 'genesis_prev_link_text', 'modify_previous_link_text' );
function modify_previous_link_text($text) {
        $text = 'Más reciente';
        return $text;
}
add_filter( 'genesis_next_link_text', 'modify_next_link_text' );
function modify_next_link_text($text) {
        $text = 'Más Antiguo';
        return $text;
}

/* Change Author & Comment Box Gravatar/Avatar Image Size */
add_filter( 'genesis_author_box_gravatar_size', 'wpsites_change_gravatar_size' );
function wpsites_change_gravatar_size($size) {
    return '120';
}

//* Customize the author box title
add_filter( 'genesis_author_box_title', 'custom_author_box_title' );
function custom_author_box_title() {
		return get_the_author();
}

// Agregar etiquetas accesibles a los campos de formulario
function okchicas_add_form_labels($form) {
    // Mejorar accesibilidad de formulario de búsqueda
    $form = str_replace(
        '<input type="text" value="" name="s" class="search-input" placeholder="Buscar en el sitio" />',
        '<label for="search-input">Buscar: <input id="search-input" type="text" value="" name="s" class="search-input" placeholder="Buscar en el sitio" /></label>',
        $form
    );
    return $form;
}
add_filter('get_search_form', 'okchicas_add_form_labels');

// Mejorar los formularios de radio y checkbox
function okchicas_fix_radio_checkbox_labels($content) {
    // Patrones para identificar inputs sin etiquetas
    $patterns = [
        '/<input class="radio" name="radio_button" type="radio" value="([^"]+)">/i' => '<label><input class="radio" name="radio_button" type="radio" value="$1"> Opción $1</label>',
        '/<input class="checkbox" name="checkboxes" type="checkbox" value="([^"]+)">/i' => '<label><input class="checkbox" name="checkboxes" type="checkbox" value="$1"> Opción $1</label>'
    ];
    
    return preg_replace(array_keys($patterns), array_values($patterns), $content);
}
add_filter('the_content', 'okchicas_fix_radio_checkbox_labels');

add_filter( 'genesis_author_box', 'be_author_box', 10, 6 );
/**
 * Customize Author Box
 * @author Bill Erickson
 * @link http://www.billerickson.net/code/customize-author-box
 *
 * @param string $output
 * @param string $context
 * @param string $pattern
 * @param string $gravatar
 * @param string $title
 * @param string $description
 * @return string $output
 */
function be_author_box( $output, $context, $pattern, $gravatar, $title, $description ) {
		$output = '';
		$output .= '<div class="author-box">';
		$output .= '<div class="alignleft">';
		$output .= get_avatar( get_the_author_meta( 'email' ), 120 );
		$output .= '</div><!-- .left -->';
		$output .= '<div class="alignright">';
		$name = get_the_author();
		$title = get_the_author_meta( 'title' );
			if( !empty( $title ) )
				$name .= ', ' . $title;
		$output .= '<h2 class="title">'. $name;
		$output .= '</h2>';
		$output .= '<p class="desc">' . get_the_author_meta( 'description' ) . '</p>';
		$output .= '</div><div class="clearfix"></div><!-- .right -->';
		$output .= '</div><!-- .author-box -->';
	return $output;
}
