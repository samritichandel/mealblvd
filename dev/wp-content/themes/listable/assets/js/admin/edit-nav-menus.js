// insertionQuery v1.0.0 (2015-07-15)
// https://github.com/naugtur/insertionQuery
// license:MIT
// naugtur <naugtur@gmail.com> (http://naugtur.pl/)
var insertionQ=function(){"use strict";function a(a,b){var d,e="insQ_"+g++,f=function(a){(a.animationName===e||a[i]===e)&&(c(a.target)||b(a.target))};d=document.createElement("style"),d.innerHTML="@"+j+"keyframes "+e+" {  from {  outline: 1px solid transparent  } to {  outline: 0px solid transparent }  }\n"+a+" { animation-duration: 0.001s; animation-name: "+e+"; "+j+"animation-duration: 0.001s; "+j+"animation-name: "+e+";  } ",document.head.appendChild(d);var h=setTimeout(function(){document.addEventListener("animationstart",f,!1),document.addEventListener("MSAnimationStart",f,!1),document.addEventListener("webkitAnimationStart",f,!1)},n.timeout);return{destroy:function(){clearTimeout(h),d&&(document.head.removeChild(d),d=null),document.removeEventListener("animationstart",f),document.removeEventListener("MSAnimationStart",f),document.removeEventListener("webkitAnimationStart",f)}}}function b(a){a.QinsQ=!0}function c(a){return n.strictlyNew&&a.QinsQ===!0}function d(a){return c(a.parentNode)?a:d(a.parentNode)}function e(a){for(b(a),a=a.firstChild;a;a=a.nextSibling)void 0!==a&&1===a.nodeType&&e(a)}function f(f,g){var h=[],i=function(){var a;return function(){clearTimeout(a),a=setTimeout(function(){h.forEach(e),g(h),h=[]},10)}}();return a(f,function(a){if(!c(a)){b(a);var e=d(a);h.indexOf(e)<0&&h.push(e),i()}})}var g=100,h=!1,i="animationName",j="",k="Webkit Moz O ms Khtml".split(" "),l="",m=document.createElement("div"),n={strictlyNew:!0,timeout:20};if(m.style.animationName&&(h=!0),h===!1)for(var o=0;o<k.length;o++)if(void 0!==m.style[k[o]+"AnimationName"]){l=k[o],i=l+"AnimationName",j="-"+l.toLowerCase()+"-",h=!0;break}var p=function(b){return h&&b.match(/[^{}]/)?(n.strictlyNew&&e(document.body),{every:function(c){return a(b,c)},summary:function(a){return f(b,a)}}):!1};return p.config=function(a){for(var b in a)a.hasOwnProperty(b)&&(n[b]=a[b])},p}();"undefined"!=typeof module&&"undefined"!=typeof module.exports&&(module.exports=insertionQ);


(function($){

	$(document).ready(function(){
		//run it once on page load
		hide_all_custom_menu_items_url();

		//run it every time one adds a new menu item
		insertionQ('#menu-to-edit .menu-item').every(function(menu_item){
			hide_custom_menu_item_url(menu_item);
		});

	});

	var hide_all_custom_menu_items_url = function() {
		//hide the URL field of all our User Menu items
		$('#menu-to-edit').find('.menu-item-custom').each( function(idx,menu_item) {
			hide_custom_menu_item_url(menu_item);
		});
	}

	var hide_custom_menu_item_url = function(menu_item) {
		var $this = $(menu_item);
		//hide the URL field it is one of our User Menu type
		$url_field = $this.find('.edit-menu-item-url').first();
		if ( $url_field.val() == '#listablelogin' ) {
			$this.find('.field-url').first().hide();
		}

		if ( $url_field.val() == '#listablelogout' ) {
			$this.find('.field-url').first().hide();
		}

		if ( $url_field.val() == '#listablecurrentusername' ) {
			$this.find('.field-url').first().hide();
		}
	}

})(jQuery);

