//>>built
define("dojox/drawing/manager/keys",["dojo","../util/common"],function(b,l){var d=!1,e=!0,k={arrowIncrement:1,arrowShiftIncrement:10,shift:!1,ctrl:!1,alt:!1,cmmd:!1,meta:!1,onDelete:function(a){},onEsc:function(a){},onEnter:function(a){},onArrow:function(a){},onKeyDown:function(a){},onKeyUp:function(a){},listeners:[],register:function(a){var b=l.uid("listener");this.listeners.push({handle:b,scope:a.scope||window,callback:a.callback,keyCode:a.keyCode})},_getLetter:function(a){return!a.meta&&65<=a.keyCode&&
90>=a.keyCode?"abcdefghijklmnopqrstuvwxyz".charAt(a.keyCode-65):null},_mixin:function(a){a.meta=this.meta;a.shift=this.shift;a.alt=this.alt;a.cmmd=this.cmmd;a.ctrl=this.ctrl;a.letter=this._getLetter(a);return a},editMode:function(a){d=a},enable:function(a){e=a},scanForFields:function(){this._fieldCons&&b.forEach(this._fieldCons,b.disconnect,b);this._fieldCons=[];b.query("input").forEach(function(a){var c=b.connect(a,"focus",this,function(a){this.enable(!1)});a=b.connect(a,"blur",this,function(a){this.enable(!0)});
this._fieldCons.push(c);this._fieldCons.push(a)},this)},init:function(){setTimeout(b.hitch(this,"scanForFields"),500);b.connect(document,"blur",this,function(a){this.meta=this.shift=this.ctrl=this.cmmd=this.alt=!1});b.connect(document,"keydown",this,function(a){e&&(16==a.keyCode&&(this.shift=!0),17==a.keyCode&&(this.ctrl=!0),18==a.keyCode&&(this.alt=!0),224==a.keyCode&&(this.cmmd=!0),this.meta=this.shift||this.ctrl||this.cmmd||this.alt,d||(this.onKeyDown(this._mixin(a)),(8==a.keyCode||46==a.keyCode)&&
b.stopEvent(a)))});b.connect(document,"keyup",this,function(a){if(e){var c=!1;16==a.keyCode&&(this.shift=!1);17==a.keyCode&&(this.ctrl=!1);18==a.keyCode&&(this.alt=!1);224==a.keyCode&&(this.cmmd=!1);this.meta=this.shift||this.ctrl||this.cmmd||this.alt;!d&&this.onKeyUp(this._mixin(a));13==a.keyCode&&(this.onEnter(a),c=!0);27==a.keyCode&&(this.onEsc(a),c=!0);if(8==a.keyCode||46==a.keyCode)this.onDelete(a),c=!0;c&&!d&&b.stopEvent(a)}});b.connect(document,"keypress",this,function(a){if(e){var c=this.shift?
this.arrowIncrement*this.arrowShiftIncrement:this.arrowIncrement,f=a.alt||this.cmmd,g=0,h=0;32==a.keyCode&&!d&&b.stopEvent(a);37==a.keyCode&&!f&&(g=-c);38==a.keyCode&&!f&&(h=-c);39==a.keyCode&&!f&&(g=c);40==a.keyCode&&!f&&(h=c);if(g||h)a.x=g,a.y=h,a.shift=this.shift,d||(this.onArrow(a),b.stopEvent(a))}})}};b.addOnLoad(k,"init");return k});
//# sourceMappingURL=keys.js.map