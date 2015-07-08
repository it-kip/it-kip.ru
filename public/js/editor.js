function Editor(option){
    var arr_stop = new Array("STYLE", "SCRIPT", "TBODY");
    var selElem;
    var tabEditor;
    
    // drag & drop
    var Move = false;
    var workelem;
    var clone;
    var where = firstRealChild();
    var X;
    var Y;
    // -----------

    var tree = document.createElement("UL");
    var start = (option != undefined)?((option["start"] != undefined)?option["start"]:document.body):document.body;

    tree.appendChild(elementsTree(start, arr_stop));
    

    function elementsTree(node, stop) {
        if(node.tagName != undefined && stop.indexOf(node.tagName) == -1){
            var growing_tree = document.createElement("LI");
            var txt = node.tagName + ((node.id!="")?"; id = " + node.id:"") + ((node.name!="" && node.name!=undefined)?"; name = " + node.name:"") + ((node.className!="")?"; class = " + node.className:"");
            growing_tree.innerHTML = txt;
            
            connection(node, growing_tree);
            dragdrop(node);

            if(node.childNodes.length > 0){
            	var flag = true;
            	for (var i = 0; i < node.childNodes.length; i++){            		
                    var nextnode = elementsTree(node.childNodes[i], stop);
                    if(nextnode !== false) {
                    	if(flag){
                    		var ul = growing_tree.appendChild(document.createElement("UL"));
                        	ul.appendChild(nextnode);
                        	flag = false;
                    	}
                    	else{
                    		if(ul == undefined) alert(node.childNodes.length);
                    		ul.appendChild(nextnode);
                    	}
                    }
            	}
            }

            return growing_tree;
        }
        else
            return false;
    }
    
    var win = new MoveWindow({
        title: "Редактор",
        content: tree
    });
    
    function connection(elem1, elem2){
    	elem1.connect = elem2;
    	elem2.connect = elem1;
    	
    	addEvent(elem1, "mouseover", preselNode);
        addEvent(elem2, "mouseover", preselNode);
        
        addEvent(elem1, "mouseout", depreselNode);
        addEvent(elem2, "mouseout", depreselNode);
        
        addEvent(elem1, "click", selectionNode);
        addEvent(elem2, "click", selectionNode);
    	
    	/*
    	elem1.onmouseover = preselNode;
    	elem2.onmouseover = preselNode;
    	
    	elem1.onmouseout = depreselNode;
    	elem2.onmouseout = depreselNode;
    	
    	elem1.onclick = selectionNode;
    	elem2.onclick = selectionNode;
    	*/

        if(elem1.tagName == "TABLE" || elem2.tagName == "TABLE"){
        	addEvent(elem1, "dblclick", createTableEdit);
            addEvent(elem2, "dblclick", createTableEdit);
            
        	//elem1.ondblclick = createTableEdit;
            //elem2.ondblclick = createTableEdit;
        }
    }
    
    function selectionNode(event){
    	if(selElem != undefined){
            selElem.className = selElem.className.replace(new RegExp("sel",'g'), "");
            selElem.connect.className = selElem.connect.className.replace(new RegExp("sel",'g'), "");
        }
    	
    	event = event || window.event; // для ебучего IE
        var targ = event.target||event.srcElement;

        if(targ.connect != undefined){
                targ.className = targ.className + " sel";
                selElem = targ.connect;
                selElem.className = selElem.className + " sel";
        }
    }
    
    function preselNode(event){
		event = event || window.event; // для ебучего IE
		var targ = event.target||event.srcElement;
		
		if(targ.connect != undefined){
			targ.className = targ.className + " presel";
                        targ.connect.className = targ.connect.className + " presel";
                }
	}

	function depreselNode(event){
		event = event || window.event; // для ебучего IE
		var targ = event.target||event.srcElement;
		
		if(targ.connect != undefined){
			targ.className = targ.className.replace(new RegExp("presel",'g'), "");
                        targ.connect.className = targ.connect.className.replace(new RegExp("presel",'g'), "");
                }
	}

        function createTableEdit(event){
            event = event || window.event; // для ебучего IE
            var targ = event.target||event.srcElement;

            var t = (targ.tagName == "TABLE")?targ:targ.connect;
            t.className = t.className.replace(new RegExp("presel",'g'), "");
            t.className = t.className.replace(new RegExp("sel",'g'), "");

            tabEditor = new TableEditor({table: t});
        }
        
        
        // --------------------
        function dragdrop(node){
        	if(node.tagName != "BODY"){
        		addEvent(node, "mousedown", createClone);
        		//node.onmousedown = createClone;
        	}
        }
        
        function createClone(event){
        	event = event || window.event; // для ебучего IE
        	workelem = event.target||event.srcElement;
        	
        	clone = document.body.appendChild(workelem.cloneNode(true));
        	clone.className = clone.className.replace(new RegExp("presel",'g'), "");
        	setElementOpacity(clone, 0.5);
        	
        	var coord = getCoord(workelem);
        	
        	clone.style.position = "absolute";
        	clone.style.left = coord[0] + "px";
        	clone.style.top = coord[1] + "px";
        	
        	addEvent(document.body, "mouseup", DUp);
            addEvent(document.body, "mousemove", DMove);
            
            Move = true;
            var xx = event.pageX; //event.screenX;
            var yy = event.pageY; //event.screenY;
            X = xx - parsePx(clone.style.left);
            Y = yy - parsePx(clone.style.top);
        }
        
        // -------------------
        function DUp(event){
            Move = false;
            
            where.appendChild(workelem);
            removeClass(where, "where");
            
            clone.parentNode.removeChild(clone);
        }

        function DMove(event){
            if(Move){
            	
                event = event || window.event;
                
                var xx = event.pageX; //event.screenX;
                var yy = event.pageY; //event.screenY;
                
                clone.style.left = (xx - X) + "px";
                clone.style.top = (yy - Y) + "px";        
                //if(clone.offsetLeft >= event.screenX || clone.offsetLeft + clone.offsetWidth <= event.screenX || clone.offsetTop >= event.screenY || clone.offsetTop + clone.offsetHeight <= event.screenY){
                	removeClass(where, "where");
                	where = document.elementFromPoint(xx, yy);
                	//where = getElemByCoord(xx, event.screenY);
                	addClass(where, "where");
                //}
            }
        }
        
        /*
        function getElemByCoord(left, top, elem){
        	var obj = (elem == undefined)?document.body:elem;
        	
        	for (var key in obj.childNodes) {
        		var elem = obj.childNodes[key];
        		if(elem.tagName != undefined && elem.offsetLeft <= left && elem.offsetLeft + elem.offsetWidth >= left && elem.offsetTop <= top && elem.offsetTop + elem.offsetHeight >= top)
        			var res = elem;
        	}
        	
        	return res;
        }
        */
        
        // -------------------
        
        function firstRealChild(){
        	var els = document.body.childNodes;
        	var i = 0;
        	while(els[i].tagName == undefined) i++;
        	
        	return els[i];
        }
        
        function getCoord(el) {
            var obj = el;
            var left = el.offsetLeft;
            var top = el.offsetTop;
            while (obj.tagName == "BODY") {
                obj = obj.parentNode;
                left += obj.offsetLeft;
                top += obj.offsetTop;
            }
            return new Array(left, top);
        }
        
        function parsePx(str){
            return parseInt(str.replace("px", ""));
        }
        
        function setElementOpacity(sElemId, nOpacity){
          var opacityProp = getOpacityProperty();
          var elem = (typeof sElemId == "string")?document.getElementById(sElemId):sElemId;

          if (!elem || !opacityProp) return; // Если не существует элемент с указанным id или браузер не поддерживает ни один из известных функции способов управления прозрачностью
          
          if (opacityProp=="filter")  // Internet Exploder 5.5+
          {
            nOpacity *= 100;
        	
            // Если уже установлена прозрачность, то меняем её через коллекцию filters, иначе добавляем прозрачность через style.filter
            var oAlpha = elem.filters['DXImageTransform.Microsoft.alpha'] || elem.filters.alpha;
            if (oAlpha) oAlpha.opacity = nOpacity;
            else elem.style.filter += "progid:DXImageTransform.Microsoft.Alpha(opacity="+nOpacity+")"; // Для того чтобы не затереть другие фильтры используем "+="
          }
          else // Другие браузеры
            elem.style[opacityProp] = nOpacity;
        }

        function getOpacityProperty(){
          if (typeof document.body.style.opacity == 'string') // CSS3 compliant (Moz 1.7+, Safari 1.2+, Opera 9)
            return 'opacity';
          else if (typeof document.body.style.MozOpacity == 'string') // Mozilla 1.6 и младше, Firefox 0.8 
            return 'MozOpacity';
          else if (typeof document.body.style.KhtmlOpacity == 'string') // Konqueror 3.1, Safari 1.1
            return 'KhtmlOpacity';
          else if (document.body.filters && navigator.appVersion.match(/MSIE ([\d.]+);/)[1]>=5.5) // Internet Exploder 5.5+
            return 'filter';

          return false; //нет прозрачности
        }
        
        /*
        function addEvent(elem, type, handler){
          if (elem.addEventListener){
            elem.addEventListener(type, handler, false)
          } else {
            elem.attachEvent("on"+type, handler)
          }
        }
        */
}