dojo.require('dijit.Menu');

dojo.declare ("SMainMenu", dijit.Menu, {
    targetId: null,
    items: [],
    subMenus: [],
    popupDelay: 0,
    //quickAccessMenu: null,

    setup: function (id, targetId) {
        // TODO: armazenar id do menu, se necess�rio
        //this.id = id;
        this.targetId = targetId;
        this.targetNodeIds = [targetId];
        this._openMyself = function() {};
    },

    show: function(actionNodeId, id) {

        var menu;
        var element;


        if ( actionNodeId )
        {
            menu = this.subMenus[id];
            element = dojo.byId(actionNodeId);
            element.className += ' m-main-menu-navbar-item-focused';
        }
        else
        {
            menu = this;
            element = dojo.byId(this.targetId);
        }

        dijit.popup.open( {
            popup: menu,
            around: element,
            /*orient: ['below'],*/
            onExecute: function() { 
                dijit.popup.close(menu);
            },
            onCancel: function() {
                dijit.popup.close(menu);
            }
        });

        menu._onBlur = function() {

            if ( actionNodeId )
            {
                element.className = 'm-main-menu-navbar-item m-main-menu-navbar-item-clickable';
            }

            menu.inherited('_onBlur', arguments);
            dijit.popup.close(menu);
        }

        //dijit.focus(dojo.query('tr', this.domNode)[0]);
        menu.focus();
    },

    addItem: function(id, title, url, iconCSS, disabled, parent, quickAccessDescription) {
        
        if ( !url )
        {
            action = function() {};
        }
        else
        {
            url = url.replace(/&amp;/g,"&");
            action = function(evt) {
                // Middle button
                if( evt.which == 2 )
                {
                    evt.preventDefault();
                    window.open(url, '_blank');
                }
                else
                {
                    showLoading();
                    GotoURL(url);
                }
            };
        }

        var item = new dijit.MenuItem({
            label: title,
            onClick: action,
            iconClass: iconCSS,
            disabled: disabled
        });
        
        if ( this.subMenus[parent] )
        {
            this.subMenus[parent].addChild(item);
        }
        else
        {
            this.addChild(item);
        }

        this.items[id] = {
            label: title,
            action: url,
            quickAccess: quickAccessDescription
        };
    },

    addSubMenu: function(id, title, iconCSS, disabled, parent) {

        this.subMenus[id] = new dijit.Menu({
            popupDelay: 0
        });

        var item = new dijit.PopupMenuItem({
            label: title,
            popup: this.subMenus[id],
            iconClass: iconCSS,
            disabled: disabled
        });

        if ( this.subMenus[parent] )
        {
            this.subMenus[parent].addChild(item);
        }
        else
        {
            this.addChild(item);
        }
    },

    quickaccess: function (event, id) {

        var menu = new dijit.Menu({
            popupDelay: 0
        });
        var search = dojo.byId(id).value;

        /*if ( this.quickAccessMenu == null )
        {
            menu = new dijit.Menu();
            this.quickAccessMenu = menu;
        }
        else
        {
            menu = this.quickAccessMenu;
        }*/

        if ( search.length > 2 )
        {
            //dojo.forEach(menu.getChildren(), function(element) { menu.removeChild(element); });

            var found = dojo.filter( this.items, function(element) {
                if ( element )
                {
                    return smainmenu.removeAccentsFromString(element.label.toLowerCase()).match(smainmenu.removeAccentsFromString(search.toLowerCase()));
                }

                return false;
            } );
                
                //transforma o found em uma array ordenada
                var arrAux = [];
                arrAux = smainmenu.sortingElement(found);
                
                dojo.every(arrAux, function (element, index) {
                if ( index <= 10 )
                {
                    menu.addChild(new dijit.MenuItem({
                        label: element.quickAccess, 
                        onClick: function() {
                            GotoURL(element.action);
                        }
                    }));
                }
                return true;
            }
            );

            menu.startup();

            dijit.popup.open( {
                popup: menu,
                around: dojo.byId(id),
                orient: ['below'],
                onCancel: function() {
                    dijit.popup.close(menu);
                }
            });	
            menu._onBlur = function()
            {
                menu.inherited('_onBlur', arguments);
                dijit.popup.close(menu);
            }

            menu.isActive = true;

            if ( event.keyCode == dojo.keys.DOWN_ARROW )
            {
                event.preventDefault();
                menu.focus();
                dijit.focus(dojo.query('tr', menu.domNode)[0]);
            }
        }
    },

    removeAccentsFromString: function(strAccents) {
        strAccents = strAccents.split('');
        strAccentsOut = new Array();
        strAccentsLen = strAccents.length;
        
        var accents = '�����������������������������������������������';
        var accentsOut = ['A','A','A','A','A','a','a','a','a','a','O','O','O','O','O','O','o','o','o','o','o','E','E','E','E','e','e','e','e','C','c','I','I','I','I','i','i','i','i','U','U','U','U','u','u','u','u'];
        
        for ( var y = 0; y < strAccentsLen; y++ )
        {
            if ( accents.indexOf( strAccents[y] ) != -1 )
            {
                strAccentsOut[y] = accentsOut[accents.indexOf( strAccents[y] )];
            }
            else
            {
                strAccentsOut[y] = strAccents[y];
            }
        }
        
        strAccentsOut = strAccentsOut.join('');
        
        return strAccentsOut;
    },
    
    /*
     * Metodo que ordena os elementos buscados no menu
     * 
     * params: found, variavel que busca, sem ordem, os campos do menu que tenham 
     * no minimo 3 letras iguais a entrada no quickAccess
     * 
     * @params array de objetos sem ordem
     * 
     * @return array de objetos ordenada
     */
    sortingElement: function(element){
                
        var arr = [];
        
        for (y = 0; y<element.length; y++) {
            arr.push(element[y]);
        }
        
        arr.sort(function(a, b){
            var qaA = a.quickAccess.toLowerCase(), qaB = b.quickAccess.toLowerCase()

            //sort crescente
            if (qaA < qaB) {
                return -1 
            }
            if (qaA > qaB) {
                return 1
            }
            //retorno ZERO caso nao encontre string
            return 0 
       })         

        return arr;
        }
});



var smainmenu;
var sbookmarksmenu;
var smostaccessedmenu;
var sunidades;