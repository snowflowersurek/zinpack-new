(function (factory){
	if(typeof define === 'function' && define.amd){define(['jquery'], factory);}else{factory(jQuery);}
}(function ($){var pluses = /\+/g;
	function raw(s){return s;}
	function decoded(s) {return decodeURIComponent(s.replace(pluses,' '));}
	function converted(s){
		if (s.indexOf('"') === 0){s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
		}try{return config.json ? JSON.parse(s) : s;}catch(er){}
	}
	var config = $.cookie = function (key,value,options){
		if (value !== undefined){
			options = $.extend({},config.defaults,options);
			if (typeof options.expires === 'number'){var days = options.expires, t = options.expires = new Date();t.setDate(t.getDate() + days);}
			value = config.json ? JSON.stringify(value) : String(value);
			return (document.cookie = [
				config.raw ? key : encodeURIComponent(key),'=',config.raw ? value : encodeURIComponent(value),options.expires ? '; expires=' + options.expires.toUTCString() : '',options.path ? '; path=' + options.path : '',options.domain  ? '; domain=' + options.domain : '',options.secure  ? '; secure' : ''].join(''));
		}
		var decode = config.raw ? raw : decoded;var cookies = document.cookie.split('; ');var result = key ? undefined : {};
		for (var i = 0, l = cookies.length; i < l; i++) {
			var parts = cookies[i].split('=');var name = decode(parts.shift());var cookie = decode(parts.join('='));if (key && key === name){result = converted(cookie);break;}if (!key){result[name] = converted(cookie);}
		}return result;
	};
	config.defaults={};
	$.removeCookie = function (key,options){if($.cookie(key) !== undefined){$.cookie(key, '', $.extend({}, options, { expires: -1 }));return true;	}return false;};
}));