// Plugins only. Requires jQuery from WordPress; no bundled copy.
!function(t){t.slidebars=function(s){function e(){!c.disableOver||"number"==typeof c.disableOver&&c.disableOver>=k?(w=!0,t("html").addClass("sb-init"),c.hideControlClasses&&T.removeClass("sb-hide"),i()):"number"==typeof c.disableOver&&c.disableOver<k&&(w=!1,t("html").removeClass("sb-init"),c.hideControlClasses&&T.addClass("sb-hide"),g.css("minHeight",""),(p||y)&&o())}function i(){g.css("minHeight","");var s=parseInt(g.css("height"),10),e=parseInt(t("html").css("height"),10);e>s&&g.css("minHeight",t("html").css("height")),m&&m.hasClass("sb-width-custom")&&m.css("width",m.attr("data-sb-width")),C&&C.hasClass("sb-width-custom")&&C.css("width",C.attr("data-sb-width")),m&&(m.hasClass("sb-style-push")||m.hasClass("sb-style-overlay"))&&m.css("marginLeft","-"+m.css("width")),C&&(C.hasClass("sb-style-push")||C.hasClass("sb-style-overlay"))&&C.css("marginRight","-"+C.css("width")),c.scrollLock&&t("html").addClass("sb-scroll-lock")}function n(t,s,e){function n(){a.removeAttr("style"),i()}var a;if(a=t.hasClass("sb-style-push")?g.add(t).add(O):t.hasClass("sb-style-overlay")?t:g.add(O),"translate"===x)"0px"===s?n():a.css("transform","translate( "+s+" )");else if("side"===x)"0px"===s?n():("-"===s[0]&&(s=s.substr(1)),a.css(e,"0px"),setTimeout(function(){a.css(e,s)},1));else if("jQuery"===x){"-"===s[0]&&(s=s.substr(1));var o={};o[e]=s,a.stop().animate(o,400)}}function a(s){function e(){w&&"left"===s&&m?(t("html").addClass("sb-active sb-active-left"),m.addClass("sb-active"),n(m,m.css("width"),"left"),setTimeout(function(){p=!0},400)):w&&"right"===s&&C&&(t("html").addClass("sb-active sb-active-right"),C.addClass("sb-active"),n(C,"-"+C.css("width"),"right"),setTimeout(function(){y=!0},400))}"left"===s&&m&&y||"right"===s&&C&&p?(o(),setTimeout(e,400)):e()}function o(s,e){(p||y)&&(p&&(n(m,"0px","left"),p=!1),y&&(n(C,"0px","right"),y=!1),setTimeout(function(){t("html").removeClass("sb-active sb-active-left sb-active-right"),m&&m.removeClass("sb-active"),C&&C.removeClass("sb-active"),"undefined"!=typeof s&&(void 0===typeof e&&(e="_self"),window.open(s,e))},400))}function l(t){"left"===t&&m&&(p?o():a("left")),"right"===t&&C&&(y?o():a("right"))}function r(t,s){t.stopPropagation(),t.preventDefault(),"touchend"===t.type&&s.off("click")}var c=t.extend({siteClose:!0,scrollLock:!1,disableOver:!1,hideControlClasses:!1},s),h=document.createElement("div").style,d=!1,f=!1;(""===h.MozTransition||""===h.WebkitTransition||""===h.OTransition||""===h.transition)&&(d=!0),(""===h.MozTransform||""===h.WebkitTransform||""===h.OTransform||""===h.transform)&&(f=!0);var u=navigator.userAgent,b=!1,v=!1;/Android/.test(u)?b=u.substr(u.indexOf("Android")+8,3):/(iPhone|iPod|iPad)/.test(u)&&(v=u.substr(u.indexOf("OS ")+3,3).replace("_",".")),(b&&3>b||v&&5>v)&&t("html").addClass("sb-static");var g=t("#sb-site, .sb-site-container");if(t(".sb-left").length)var m=t(".sb-left"),p=!1;if(t(".sb-right").length)var C=t(".sb-right"),y=!1;var w=!1,k=t(window).width(),T=t(".sb-toggle-left, .sb-toggle-right, .sb-open-left, .sb-open-right, .sb-close"),O=t(".sb-slide");e(),t(window).resize(function(){var s=t(window).width();k!==s&&(k=s,e(),p&&a("left"),y&&a("right"))});var x;d&&f?(x="translate",b&&4.4>b&&(x="side")):x="jQuery",this.slidebars={open:a,close:o,toggle:l,init:function(){return w},active:function(t){return"left"===t&&m?p:"right"===t&&C?y:void 0},destroy:function(t){"left"===t&&m&&(p&&o(),setTimeout(function(){m.remove(),m=!1},400)),"right"===t&&C&&(y&&o(),setTimeout(function(){C.remove(),C=!1},400))}},t(".sb-toggle-left").on("touchend click",function(s){r(s,t(this)),l("left")}),t(".sb-toggle-right").on("touchend click",function(s){r(s,t(this)),l("right")}),t(".sb-open-left").on("touchend click",function(s){r(s,t(this)),a("left")}),t(".sb-open-right").on("touchend click",function(s){r(s,t(this)),a("right")}),t(".sb-close").on("touchend click",function(s){if(t(this).is("a")||t(this).children().is("a")){if("click"===s.type){s.stopPropagation(),s.preventDefault();var e=t(this).is("a")?t(this):t(this).find("a"),i=e.attr("href"),n=e.attr("target")?e.attr("target"):"_self";o(i,n)}}else r(s,t(this)),o()}),g.on("touchend click",function(s){c.siteClose&&(p||y)&&(r(s,t(this)),o())})}}(jQuery);

/*! 
 * headroom.js v0.12.0 - Give your page some headroom. Hide your header until you need it
 * Copyright (c) 2020 Nick Williams - http://wicky.nillia.ms/headroom.js
 * License: MIT
 */
!function(t,n){"object"==typeof exports&&"undefined"!=typeof module?module.exports=n():"function"==typeof define&&define.amd?define(n):(t=t||self).Headroom=n()}(this,function(){"use strict";function t(){return"undefined"!=typeof window}function d(t){return function(t){return t&&t.document&&function(t){return 9===t.nodeType}(t.document)}(t)?function(t){var n=t.document,o=n.body,s=n.documentElement;return{scrollHeight:function(){return Math.max(o.scrollHeight,s.scrollHeight,o.offsetHeight,s.offsetHeight,o.clientHeight,s.clientHeight)},height:function(){return t.innerHeight||s.clientHeight||o.clientHeight},scrollY:function(){return void 0!==t.pageYOffset?t.pageYOffset:(s||o.parentNode||o).scrollTop}}}(t):function(t){return{scrollHeight:function(){return Math.max(t.scrollHeight,t.offsetHeight,t.clientHeight)},height:function(){return Math.max(t.offsetHeight,t.clientHeight)},scrollY:function(){return t.scrollTop}}}(t)}function n(t,s,e){var n,o=function(){var n=!1;try{var t={get passive(){n=!0}};window.addEventListener("test",t,t),window.removeEventListener("test",t,t)}catch(t){n=!1}return n}(),i=!1,r=d(t),l=r.scrollY(),a={};function c(){var t=Math.round(r.scrollY()),n=r.height(),o=r.scrollHeight();a.scrollY=t,a.lastScrollY=l,a.direction=l<t?"down":"up",a.distance=Math.abs(t-l),a.isOutOfBounds=t<0||o<t+n,a.top=t<=s.offset[a.direction],a.bottom=o<=t+n,a.toleranceExceeded=a.distance>s.tolerance[a.direction],e(a),l=t,i=!1}function h(){i||(i=!0,n=requestAnimationFrame(c))}var u=!!o&&{passive:!0,capture:!1};return t.addEventListener("scroll",h,u),c(),{destroy:function(){cancelAnimationFrame(n),t.removeEventListener("scroll",h,u)}}}function o(t){return t===Object(t)?t:{down:t,up:t}}function s(t,n){n=n||{},Object.assign(this,s.options,n),this.classes=Object.assign({},s.options.classes,n.classes),this.elem=t,this.tolerance=o(this.tolerance),this.offset=o(this.offset),this.initialised=!1,this.frozen=!1}return s.prototype={constructor:s,init:function(){return s.cutsTheMustard&&!this.initialised&&(this.addClass("initial"),this.initialised=!0,setTimeout(function(t){t.scrollTracker=n(t.scroller,{offset:t.offset,tolerance:t.tolerance},t.update.bind(t))},100,this)),this},destroy:function(){this.initialised=!1,Object.keys(this.classes).forEach(this.removeClass,this),this.scrollTracker.destroy()},unpin:function(){!this.hasClass("pinned")&&this.hasClass("unpinned")||(this.addClass("unpinned"),this.removeClass("pinned"),this.onUnpin&&this.onUnpin.call(this))},pin:function(){this.hasClass("unpinned")&&(this.addClass("pinned"),this.removeClass("unpinned"),this.onPin&&this.onPin.call(this))},freeze:function(){this.frozen=!0,this.addClass("frozen")},unfreeze:function(){this.frozen=!1,this.removeClass("frozen")},top:function(){this.hasClass("top")||(this.addClass("top"),this.removeClass("notTop"),this.onTop&&this.onTop.call(this))},notTop:function(){this.hasClass("notTop")||(this.addClass("notTop"),this.removeClass("top"),this.onNotTop&&this.onNotTop.call(this))},bottom:function(){this.hasClass("bottom")||(this.addClass("bottom"),this.removeClass("notBottom"),this.onBottom&&this.onBottom.call(this))},notBottom:function(){this.hasClass("notBottom")||(this.addClass("notBottom"),this.removeClass("bottom"),this.onNotBottom&&this.onNotBottom.call(this))},shouldUnpin:function(t){return"down"===t.direction&&!t.top&&t.toleranceExceeded},shouldPin:function(t){return"up"===t.direction&&t.toleranceExceeded||t.top},addClass:function(t){this.elem.classList.add.apply(this.elem.classList,this.classes[t].split(" "))},removeClass:function(t){this.elem.classList.remove.apply(this.elem.classList,this.classes[t].split(" "))},hasClass:function(t){return this.classes[t].split(" ").every(function(t){return this.classList.contains(t)},this.elem)},update:function(t){t.isOutOfBounds||!0!==this.frozen&&(t.top?this.top():this.notTop(),t.bottom?this.bottom():this.notBottom(),this.shouldUnpin(t)?this.unpin():this.shouldPin(t)&&this.pin())}},s.options={tolerance:{up:0,down:0},offset:0,scroller:t()?window:null,classes:{frozen:"headroom--frozen",pinned:"headroom--pinned",unpinned:"headroom--unpinned",top:"headroom--top",notTop:"headroom--not-top",bottom:"headroom--bottom",notBottom:"headroom--not-bottom",initial:"headroom"}},s.cutsTheMustard=!!(t()&&function(){}.bind&&"classList"in document.documentElement&&Object.assign&&Object.keys&&requestAnimationFrame),s});

// jQuery.headroom.js 
!function(o){o&&(o.fn.headroom=function(t){return this.each(function(){var a=o(this),e=a.data("headroom"),n="object"==typeof t&&t;n=o.extend(!0,{},Headroom.options,n),e||((e=new Headroom(this,n)).init(),a.data("headroom",e)),"string"==typeof t&&(e[t](),"destroy"===t&&a.removeData("headroom"))})},o("[data-headroom]").each(function(){var t=o(this);t.headroom(t.data())}))}(window.Zepto||window.jQuery);

/* HC-Sticky
 * Version: 2.2.7
 * Plugin URL: https://github.com/somewebmedia/hc-sticky
 */
"use strict";!function(t,e){if("object"==typeof module&&"object"==typeof module.exports){if(!t.document)throw new Error("HC-Sticky requires a browser to run.");module.exports=e(t)}else"function"==typeof define&&define.amd?define("hcSticky",[],e(t)):e(t)}("undefined"!=typeof window?window:this,function(V){var i,n,Q=V.document,U={top:0,bottom:0,bottomEnd:0,innerTop:0,innerSticker:null,stickyClass:"sticky",stickTo:null,followScroll:!0,responsive:null,mobileFirst:!1,onStart:null,onStop:null,onBeforeResize:null,onResize:null,resizeDebounce:100,disable:!1},Y=function(t,e,o){console.warn("%cHC Sticky:%c "+o+"%c '"+t+"'%c is now deprecated and will be removed. Use%c '"+e+"'%c instead.","color: #fa253b","color: default","color: #5595c6","color: default","color: #5595c6","color: default")},$=function(n,f){var o=this;if(f=f||{},!(n="string"==typeof n?Q.querySelector(n):n))return!1;f.queries&&Y("queries","responsive","option"),f.queryFlow&&Y("queryFlow","mobileFirst","option");var p={},u=$.Helpers,s=n.parentNode;"static"===u.getStyle(s,"position")&&(s.style.position="relative");function d(t){u.isEmptyObject(t=t||{})&&!u.isEmptyObject(p)||(p=Object.assign({},U,p,t))}function t(){return p.disable}function e(){var t,e=p.responsive||p.queries;if(e){var o=V.innerWidth;if(t=f,(p=Object.assign({},U,t||{})).mobileFirst)for(var i in e)i<=o&&!u.isEmptyObject(e[i])&&d(e[i]);else{var n,s=[];for(n in e){var r={};r[n]=e[n],s.push(r)}for(var l=s.length-1;0<=l;l--){var a=s[l],c=Object.keys(a)[0];o<=c&&!u.isEmptyObject(a[c])&&d(a[c])}}}}function i(){var t,e,o,i;I.css=(t=n,e=u.getCascadedStyle(t),o=u.getStyle(t),i={height:t.offsetHeight+"px",left:e.left,right:e.right,top:e.top,bottom:e.bottom,position:o.position,display:o.display,verticalAlign:o.verticalAlign,boxSizing:o.boxSizing,marginLeft:e.marginLeft,marginRight:e.marginRight,marginTop:e.marginTop,marginBottom:e.marginBottom,paddingLeft:e.paddingLeft,paddingRight:e.paddingRight},e.float&&(i.float=e.float||"none"),e.cssFloat&&(i.cssFloat=e.cssFloat||"none"),o.MozBoxSizing&&(i.MozBoxSizing=o.MozBoxSizing),i.width="auto"!==e.width?e.width:"border-box"===i.boxSizing||"border-box"===i.MozBoxSizing?t.offsetWidth+"px":o.width,i),P.init(),y=!(!p.stickTo||!("document"===p.stickTo||p.stickTo.nodeType&&9===p.stickTo.nodeType||"object"==typeof p.stickTo&&p.stickTo instanceof("undefined"!=typeof HTMLDocument?HTMLDocument:Document))),h=p.stickTo?y?Q:u.getElement(p.stickTo):s,z=(R=function(){var t=n.offsetHeight+(parseInt(I.css.marginTop)||0)+(parseInt(I.css.marginBottom)||0),e=(z||0)-t;return-1<=e&&e<=1?z:t})(),v=(H=function(){return y?Math.max(Q.documentElement.clientHeight,Q.body.scrollHeight,Q.documentElement.scrollHeight,Q.body.offsetHeight,Q.documentElement.offsetHeight):h.offsetHeight})(),S=y?0:u.offset(h).top,w=p.stickTo?y?0:u.offset(s).top:S,E=V.innerHeight,N=n.offsetTop-(parseInt(I.css.marginTop)||0),b=u.getElement(p.innerSticker),L=isNaN(p.top)&&-1<p.top.indexOf("%")?parseFloat(p.top)/100*E:p.top,k=isNaN(p.bottom)&&-1<p.bottom.indexOf("%")?parseFloat(p.bottom)/100*E:p.bottom,x=b?b.offsetTop:p.innerTop||0,T=isNaN(p.bottomEnd)&&-1<p.bottomEnd.indexOf("%")?parseFloat(p.bottomEnd)/100*E:p.bottomEnd,j=S-L+x+N}function r(){z=R(),v=H(),O=S+v-L-T,C=E<z;var t,e=V.pageYOffset||Q.documentElement.scrollTop,o=u.offset(n).top,i=o-e;B=e<F?"up":"down",A=e-F,j<(F=e)?O+L+(C?k:0)-(p.followScroll&&C?0:L)<=e+z-x-(E-(j-x)<z-x&&p.followScroll&&0<(t=z-E-x)?t:0)?I.release({position:"absolute",bottom:w+s.offsetHeight-O-L}):C&&p.followScroll?"down"==B?i+z+k<=E+.9?I.stick({bottom:k}):"fixed"===I.position&&I.release({position:"absolute",top:o-L-j-A+x}):Math.ceil(i+x)<0&&"fixed"===I.position?I.release({position:"absolute",top:o-L-j+x-A}):e+L-x<=o&&I.stick({top:L-x}):I.stick({top:L-x}):I.release({stop:!0})}function l(){M&&(V.removeEventListener("scroll",r,u.supportsPassive),M=!1)}function a(){null!==n.offsetParent&&"none"!==u.getStyle(n,"display")?(i(),v<z?l():(r(),M||(V.addEventListener("scroll",r,u.supportsPassive),M=!0))):l()}function c(){n.style.position="",n.style.left="",n.style.top="",n.style.bottom="",n.style.width="",n.classList?n.classList.remove(p.stickyClass):n.className=n.className.replace(new RegExp("(^|\\b)"+p.stickyClass.split(" ").join("|")+"(\\b|$)","gi")," "),I.css={},!(I.position=null)===P.isAttached&&P.detach()}function g(){c(),e(),(t()?l:a)()}function m(){q&&(V.removeEventListener("resize",W,u.supportsPassive),q=!1),l()}var y,h,b,v,S,w,E,L,k,x,T,j,O,C,z,N,H,R,A,B,I={css:{},position:null,stick:function(t){t=t||{},u.hasClass(n,p.stickyClass)||(!1===P.isAttached&&P.attach(),I.position="fixed",n.style.position="fixed",n.style.left=P.offsetLeft+"px",n.style.width=P.width,void 0===t.bottom?n.style.bottom="auto":n.style.bottom=t.bottom+"px",void 0===t.top?n.style.top="auto":n.style.top=t.top+"px",n.classList?n.classList.add(p.stickyClass):n.className+=" "+p.stickyClass,p.onStart&&p.onStart.call(n,Object.assign({},p)))},release:function(t){var e;(t=t||{}).stop=t.stop||!1,!0!==t.stop&&"fixed"!==I.position&&null!==I.position&&(void 0===t.top&&void 0===t.bottom||void 0!==t.top&&(parseInt(u.getStyle(n,"top"))||0)===t.top||void 0!==t.bottom&&(parseInt(u.getStyle(n,"bottom"))||0)===t.bottom)||(!0===t.stop?!0===P.isAttached&&P.detach():!1===P.isAttached&&P.attach(),e=t.position||I.css.position,I.position=e,n.style.position=e,n.style.left=!0===t.stop?I.css.left:P.positionLeft+"px",n.style.width=("absolute"!==e?I.css:P).width,void 0===t.bottom?n.style.bottom=!0===t.stop?"":"auto":n.style.bottom=t.bottom+"px",void 0===t.top?n.style.top=!0===t.stop?"":"auto":n.style.top=t.top+"px",n.classList?n.classList.remove(p.stickyClass):n.className=n.className.replace(new RegExp("(^|\\b)"+p.stickyClass.split(" ").join("|")+"(\\b|$)","gi")," "),p.onStop&&p.onStop.call(n,Object.assign({},p)))}},P={el:Q.createElement("div"),offsetLeft:null,positionLeft:null,width:null,isAttached:!1,init:function(){for(var t in P.el.className="sticky-spacer",I.css)P.el.style[t]=I.css[t];P.el.style["z-index"]="-1";var e=u.getStyle(n);P.offsetLeft=u.offset(n).left-(parseInt(e.marginLeft)||0),P.positionLeft=u.position(n).left,P.width=u.getStyle(n,"width")},attach:function(){s.insertBefore(P.el,n),P.isAttached=!0},detach:function(){P.el=s.removeChild(P.el),P.isAttached=!1}},F=V.pageYOffset||Q.documentElement.scrollTop,M=!1,q=!1,D=function(){p.onBeforeResize&&p.onBeforeResize.call(n,Object.assign({},p)),g(),p.onResize&&p.onResize.call(n,Object.assign({},p))},W=p.resizeDebounce?u.debounce(D,p.resizeDebounce):D,D=function(){q||(V.addEventListener("resize",W,u.supportsPassive),q=!0),e(),(t()?l:a)()};this.options=function(t){return t?p[t]:Object.assign({},p)},this.refresh=g,this.update=function(t){d(t),f=Object.assign({},f,t||{}),g()},this.attach=D,this.detach=m,this.destroy=function(){m(),c()},this.triggerMethod=function(t,e){"function"==typeof o[t]&&o[t](e)},this.reinit=function(){Y("reinit","refresh","method"),g()},d(f),D(),V.addEventListener("load",g)};return void 0!==V.jQuery&&(i=V.jQuery,n="hcSticky",i.fn.extend({hcSticky:function(e,o){return this.length?"options"===e?i.data(this.get(0),n).options():this.each(function(){var t=i.data(this,n);t?t.triggerMethod(e,o):(t=new $(this,e),i.data(this,n,t))}):this}})),V.hcSticky=V.hcSticky||$,$}),function(a){var t=a.hcSticky,c=a.document;"function"!=typeof Object.assign&&Object.defineProperty(Object,"assign",{value:function(t,e){if(null==t)throw new TypeError("Cannot convert undefined or null to object");for(var o=Object(t),i=1;i<arguments.length;i++){var n=arguments[i];if(null!=n)for(var s in n)Object.prototype.hasOwnProperty.call(n,s)&&(o[s]=n[s])}return o},writable:!0,configurable:!0}),Array.prototype.forEach||(Array.prototype.forEach=function(t){var e,o;if(null==this)throw new TypeError("this is null or not defined");var i,n=Object(this),s=n.length>>>0;if("function"!=typeof t)throw new TypeError(t+" is not a function");for(1<arguments.length&&(e=arguments[1]),o=0;o<s;)o in n&&(i=n[o],t.call(e,i,o,n)),o++});var e=!1;try{var o=Object.defineProperty({},"passive",{get:function(){e={passive:!1}}});a.addEventListener("testPassive",null,o),a.removeEventListener("testPassive",null,o)}catch(t){}function n(t,e){return a.getComputedStyle?e?c.defaultView.getComputedStyle(t,null).getPropertyValue(e):c.defaultView.getComputedStyle(t,null):t.currentStyle?e?t.currentStyle[e.replace(/-\w/g,function(t){return t.toUpperCase().replace("-","")})]:t.currentStyle:void 0}function s(t){var e=t.getBoundingClientRect(),o=a.pageYOffset||c.documentElement.scrollTop,t=a.pageXOffset||c.documentElement.scrollLeft;return{top:e.top+o,left:e.left+t}}t.Helpers={supportsPassive:e,isEmptyObject:function(t){for(var e in t)return!1;return!0},debounce:function(i,n,s){var r;return function(){var t=this,e=arguments,o=s&&!r;clearTimeout(r),r=setTimeout(function(){r=null,s||i.apply(t,e)},n),o&&i.apply(t,e)}},hasClass:function(t,e){return t.classList?t.classList.contains(e):new RegExp("(^| )"+e+"( |$)","gi").test(t.className)},offset:s,position:function(t){var e=t.offsetParent,o=s(e),i=s(t),e=n(e),t=n(t);return o.top+=parseInt(e.borderTopWidth)||0,o.left+=parseInt(e.borderLeftWidth)||0,{top:i.top-o.top-(parseInt(t.marginTop)||0),left:i.left-o.left-(parseInt(t.marginLeft)||0)}},getElement:function(t){var e=null;return"string"==typeof t?e=c.querySelector(t):a.jQuery&&t instanceof a.jQuery&&t.length?e=t[0]:t instanceof Element&&(e=t),e},getStyle:n,getCascadedStyle:function(t){var e,o=t.cloneNode(!0);o.style.display="none",Array.prototype.slice.call(o.querySelectorAll('input[type="radio"]')).forEach(function(t){t.removeAttribute("name")}),t.parentNode.insertBefore(o,t.nextSibling),o.currentStyle?e=o.currentStyle:a.getComputedStyle&&(e=c.defaultView.getComputedStyle(o,null));var i,n,s,r={};for(i in e)!isNaN(i)||"string"!=typeof e[i]&&"number"!=typeof e[i]||(r[i]=e[i]);if(Object.keys(r).length<3)for(var l in r={},e)isNaN(l)||(r[e[l].replace(/-\w/g,function(t){return t.toUpperCase().replace("-","")})]=e.getPropertyValue(e[l]));return r.margin||"auto"!==r.marginLeft?r.margin||r.marginLeft!==r.marginRight||r.marginLeft!==r.marginTop||r.marginLeft!==r.marginBottom||(r.margin=r.marginLeft):r.margin="auto",r.margin||"0px"!==r.marginLeft||"0px"!==r.marginRight||(s=(n=t.offsetLeft-t.parentNode.offsetLeft)-(parseInt(r.left)||0)-(parseInt(r.right)||0),0!=(s=t.parentNode.offsetWidth-t.offsetWidth-n-(parseInt(r.right)||0)+(parseInt(r.left)||0)-s)&&1!=s||(r.margin="auto")),o.parentNode.removeChild(o),o=null,r}}}(window);


// Las opciones del jQuery

jQuery(document).ready(function($) {
// Start Slidebars
	$.slidebars({
		scrollLock: true
	});
	$('a.search-icon').on('click', function() {
		$('.sb-right input[type="search"]').focus();
	});

// Opcions Headroom
	if ($(window).width() <= 927) {
		$(".site-header").headroom({
			"offset": 40,
			"tolerance": {
				down: 0,
				up: 20
			},
			"classes": {
				"initial": "headroom",
				"pinned": "subiendo",
				"unpinned" : "bajando",
				"top": "topando",
				"notTop": "noesarriba"
			}
		});
	};

// HC-Sticky sidebar fixed options usage
	$('.sidebar .widget:last-child').hcSticky({
		stickTo: '.sidebar',
		followscroll: true,
		top: 0,
		responsive: {
			944: {
				disable: true
			}
		}
	});
	
// Fluid videos
	var videoSelectors = [
		'iframe[src*="player.vimeo.com"]',
		'iframe[src*="youtube.com"]',
		'iframe[src*="youtube-nocookie.com"]',
		'iframe[src*="kickstarter.com"][src*="video.html"]',
		'iframe[src*="screenr.com"]',
		'iframe[src*="blip.tv"]',
		'iframe[src*="dailymotion.com"]',
		'iframe[src*="viddler.com"]',
		'iframe[src*="qik.com"]',
		'iframe[src*="revision3.com"]',
		'iframe[src*="hulu.com"]',
		'iframe[src*="funnyordie.com"]',
		'iframe[src*="flickr.com"]',
	'embed[src*="v.wordpress.com"]'
		// add more selectors here
	];
	var allVideos = videoSelectors.join( ',' );
	$( allVideos ).wrap( "<div class='full-movil'><div class='fluid-video'></div></div>" ); // Fluid videos and full width

// Oculta el billboard superior cuando no hay fill para evitar bloques negros
	var billboardSlotId = 'div-gpt-ad-1624665948236-0';
	var $billboardWrap = $('.topbillboard');

	if ($billboardWrap.length) {
		var collapseBillboard = function() {
			var $slot = $('#' + billboardSlotId);

			if (!$slot.length) {
				return;
			}

			var hasQueryId = !!$slot.attr('data-google-query-id');
			var hasIframe = $slot.find('iframe').length > 0;

			if (!hasQueryId && !hasIframe) {
				$billboardWrap.css({ display: 'none', minHeight: 0 });
			} else {
				$billboardWrap.css('background', '#fff');
			}
		};

		collapseBillboard();

		if (window.googletag && googletag.cmd && typeof googletag.pubads === 'function') {
			googletag.cmd.push(function() {
				try {
					googletag.pubads().addEventListener('slotRenderEnded', function(event) {
						var slotId = event && event.slot && event.slot.getSlotElementId && event.slot.getSlotElementId();

						if (slotId === billboardSlotId && event.isEmpty) {
							$billboardWrap.css({ display: 'none', minHeight: 0 });
						}
					});
				} catch (error) {
					// Ignorar si GPT no está disponible
				}
			});
		}

		setTimeout(collapseBillboard, 2500);
	}

});
