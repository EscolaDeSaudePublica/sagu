//>>built
define("dojox/app/utils/config",["dojo/sniff"],function(g){return{configProcessHas:function(b){for(var e in b){var a=b[e];if("has"==e){for(var d in a)if(!("_"==d.charAt(0)&&"_"==d.charAt(1))&&a&&"object"===typeof a){var c=d.split(",");if(0<c.length)for(;0<c.length;){var f=c.shift();if(g(f)||"!"==f.charAt(0)&&!g(f.substring(1))){this.configMerge(b,a[d]);break}}}delete b.has}else!("_"==e.charAt(0)&&"_"==e.charAt(1))&&(a&&"object"===typeof a)&&this.configProcessHas(a)}return b},configMerge:function(b,
e){for(var a in e){var d=b[a],c=e[a];d!==c&&!("_"==a.charAt(0)&&"_"==a.charAt(1))&&(d&&"object"===typeof d&&c&&"object"===typeof c?this.configMerge(d,c):b instanceof Array?b.push(c):b[a]=c)}return b}}});
//# sourceMappingURL=config.js.map