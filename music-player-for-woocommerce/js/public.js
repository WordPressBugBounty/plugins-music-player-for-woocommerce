var $jscomp=$jscomp||{};$jscomp.scope={};$jscomp.findInternal=function(a,e,c){a instanceof String&&(a=String(a));for(var h=a.length,k=0;k<h;k++){var m=a[k];if(e.call(c,m,k,a))return{i:k,v:m}}return{i:-1,v:void 0}};$jscomp.ASSUME_ES5=!1;$jscomp.ASSUME_NO_NATIVE_MAP=!1;$jscomp.ASSUME_NO_NATIVE_SET=!1;$jscomp.SIMPLE_FROUND_POLYFILL=!1;
$jscomp.defineProperty=$jscomp.ASSUME_ES5||"function"==typeof Object.defineProperties?Object.defineProperty:function(a,e,c){a!=Array.prototype&&a!=Object.prototype&&(a[e]=c.value)};$jscomp.getGlobal=function(a){return"undefined"!=typeof window&&window===a?a:"undefined"!=typeof global&&null!=global?global:a};$jscomp.global=$jscomp.getGlobal(this);
$jscomp.polyfill=function(a,e,c,h){if(e){c=$jscomp.global;a=a.split(".");for(h=0;h<a.length-1;h++){var k=a[h];k in c||(c[k]={});c=c[k]}a=a[a.length-1];h=c[a];e=e(h);e!=h&&null!=e&&$jscomp.defineProperty(c,a,{configurable:!0,writable:!0,value:e})}};$jscomp.polyfill("Array.prototype.find",function(a){return a?a:function(a,c){return $jscomp.findInternal(this,a,c).v}},"es6","es3");
(function(){var a=[],e=0;window.generate_the_wcmp=function(c){function h(b){var a=b.closest(".wcmp-single-player"),f=a.find(".wcmp-first-player");d("audio").each(function(){this.pause();this.currentTime=0});a.find(".wcmp-player-container:not(.wcmp-first-player)").hide();b.hasClass(".wcmp-first-player")||(b.show().offset(f.offset()).outerWidth(f.outerWidth()),b.find("audio")[0].play())}function k(b,g){if(b+1<e||g){var f=b+1;!g||f!=e&&0!=d('[playernumber="'+f+'"]').closest("[data-loop]").length&&d('[playernumber="'+
f+'"]').closest("[data-loop]")[0]==d('[playernumber="'+b+'"]').closest("[data-loop]")[0]||(f=d('[playernumber="'+b+'"]').closest("[data-loop]").find("[playernumber]:first").attr("playernumber"));a[f]instanceof d&&a[f].is("a")?a[f].closest(".wcmp-single-player").length?h(a[f].closest(".wcmp-player-container")):a[f].is(":visible")?a[f].trigger("click"):k(b+1,g):d(a[f].domNode).closest(".wcmp-single-player").length?h(d(a[f].domNode).closest(".wcmp-player-container")):d(a[f].domNode).closest(".wcmp-player-container").is(":visible")?
a[f].domNode.play():k(b+1,g)}}function m(b){var a=b.data("product");d('[data-product="'+a+'"]').each(function(){var b=d(this).closest(".product"),g=b.find("img.product-"+a);if(g.length&&0==b.closest(".wcmp-player-list").length&&0==b.find(".wcmp-player-list").length){var c=g.offset();b=b.find("div.wcmp-player");b.length&&b.css({position:"absolute","z-index":999999}).offset({left:c.left+(g.width()-b.width())/2,top:c.top+(g.height()-b.height())/2})}})}if(!("boolean"!==typeof c&&"undefined"!=typeof wcmp_global_settings&&
1*wcmp_global_settings.onload)&&"undefined"===typeof generated_the_wcmp){generated_the_wcmp=!0;var d=jQuery;d(".wcmp-player-container").on("click","*",function(b){b.preventDefault();b.stopPropagation();return!1}).parent().removeAttr("title");d.expr.pseudos.regex=function(b,a,f){a=f[3].split(",");var c=/^(data|css):/;f=a[0].match(c)?a[0].split(":")[0]:"attr";c=a.shift().replace(c,"");return(new RegExp(a.join("").replace(/^\s+|\s+$/g,""),"ig")).test(d(b)[f](c))};var r="undefined"!=typeof wcmp_global_settings?
wcmp_global_settings.play_all:!0,l="undefined"!=typeof wcmp_global_settings?!(1*wcmp_global_settings.play_simultaneously):!0,p="undefined"!=typeof wcmp_global_settings?1*wcmp_global_settings.fade_out:!0,q="undefined"!=typeof wcmp_global_settings&&"ios_controls"in wcmp_global_settings&&1*wcmp_global_settings.ios_controls?!0:!1;c=d("audio.wcmp-player:not(.track):not([playernumber])");var t=d("audio.wcmp-player.track:not([playernumber])"),n={pauseOtherPlayers:l,iPadUseNativeControls:q,iPhoneUseNativeControls:q,
success:function(b,c){var f=d(c).data("duration"),e=d(c).data("estimated_duration"),g=d(c).attr("playernumber");"undefined"!=typeof e&&(b.getDuration=function(){return e});"undefined"!=typeof f&&setTimeout(function(b,c){return function(){a[b].updateDuration=function(){d(this.media).closest(".wcmp-player").find(".mejs-duration").html(c)};a[b].updateDuration()}}(g,f),50);d(c).attr("volume")&&(b.setVolume(parseFloat(d(c).attr("volume"))),0==b.volume&&b.setMuted(!0));b.addEventListener("playing",function(a){var c=
d(b),f=c.closest(".wcmp-single-player");try{var e=d(a.detail.target).attr("data-product");if("undefined"!=typeof e){var g=window.location.protocol+"//"+window.location.host+"/"+window.location.pathname.replace(/^\//g,"").replace(/\/$/g,"")+"?wcmp-action=play&wcmp-product="+e;d.get(g)}}catch(v){}f.length&&(a=c.closest(".wcmp-player-container").attr("data-wcfm-pair"),f.find('.wcmp-player-title[data-wcfm-pair="'+a+'"]').addClass("wcmp-playing"))});b.addEventListener("timeupdate",function(a){a=b.getDuration();
isNaN(b.currentTime)||isNaN(a)||(p&&4>a-b.currentTime?b.setVolume(b.volume-b.volume/3):b.currentTime&&("undefined"==typeof b.bkVolume&&(b.bkVolume=parseFloat(d(b).find("audio,video").attr("volume")||b.volume)),b.setVolume(b.bkVolume),0==b.bkVolume&&b.setMuted(!0)))});b.addEventListener("volumechange",function(a){a=b.getDuration();isNaN(b.currentTime)||isNaN(a)||!(4<a-b.currentTime)&&p||!b.currentTime||(b.bkVolume=b.volume)});b.addEventListener("ended",function(a){a=d(b);var c=a.closest('[data-loop="1"]');
a[0].currentTime=0;a.closest(".wcmp-single-player").length&&a.closest(".wcmp-single-player").find(".wcmp-playing").removeClass("wcmp-playing");if(1*r||c.length){var f=1*a.attr("playernumber");isNaN(f)&&(f=1*a.find("[playernumber]").attr("playernumber"));k(f,c.length)}})}};l=".product-type-grouped :regex(name,quantity\\[\\d+\\])";c.each(function(){var b=d(this);b.find("source").attr("src");b.attr("playernumber",e);n.audioVolume="vertical";try{a[e]=new MediaElementPlayer(b[0],n)}catch(g){"console"in
window&&console.log(g)}e++});t.each(function(){var b=d(this);b.find("source").attr("src");b.attr("playernumber",e);n.features=["playpause"];try{a[e]=new MediaElementPlayer(b[0],n)}catch(g){"console"in window&&console.log(g)}e++;m(b);d(window).on("resize",function(){m(b)})});d(l).length||(l=".product-type-grouped [data-product_id]");d(l).length||(l=".woocommerce-grouped-product-list [data-product_id]");d(l).length||(l='.woocommerce-grouped-product-list [id*="product-"]');d(l).each(function(){try{var b=
d(this),a=(b.data("product_id")||b.attr("name")||b.attr("id")).replace(/[^\d]/g,""),c=d(".wcmp-player-list.merge_in_grouped_products .product-"+a+":first .wcmp-player-title"),e=d("<table></table>");c.length&&!c.closest(".wcmp-first-in-product").length&&(c.closest("tr").addClass("wcmp-first-in-product"),0==c.closest("form").length&&c.closest(".wcmp-player-list").prependTo(b.closest("form")),e.append(b.closest("tr").prepend("<td>"+c.html()+"</td>")),c.html("").append(e))}catch(u){}});d(document).on("click",
"[data-wcfm-pair]",function(){var b=d(this),a=b.closest(".wcmp-single-player");if(a.length){d(".wcmp-player-title").removeClass("wcmp-playing");var c=b.attr("data-wcfm-pair");b.addClass("wcmp-playing");h(a.find('.wcmp-player-container[data-wcfm-pair="'+c+'"]'))}})}};window.wcmp_force_init=function(){delete window.generated_the_wcmp;generate_the_wcmp(!0)};jQuery(generate_the_wcmp);jQuery(window).on("load",function(){generate_the_wcmp(!0);var a=jQuery,e=window.navigator.userAgent;a("[data-lazyloading]").each(function(){var c=
a(this);c.attr("preload",c.data("lazyloading"))});if(e.match(/iPad/i)||e.match(/iPhone/i))if("undefined"!=typeof wcmp_global_settings?wcmp_global_settings.play_all:1)a(".wcmp-player .mejs-play button").one("click",function(){if("undefined"==typeof wcmp_preprocessed_players){wcmp_preprocessed_players=!0;var c=a(this);a(".wcmp-player audio").each(function(){this.play();this.pause()});setTimeout(function(){c.trigger("click")},500)}})}).on("popstate",function(){jQuery("audio[data-product]:not([playernumber])").length&&
wcmp_force_init()});jQuery(document).on("scroll wpfAjaxSuccess woof_ajax_done yith-wcan-ajax-filtered wpf_ajax_success berocket_ajax_products_loaded berocket_ajax_products_infinite_loaded lazyload.wcpt",wcmp_force_init)})();