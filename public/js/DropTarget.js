function DropTarget(option) {            var element = option["obj"];        var callback = option["callback"];		element.dropTarget = this                this.onLeave = function() {		element.removeClass('uponMe');	}		this.onEnter = function() {		element.addClass('uponMe');	}		this.canAccept = function(dragObject) {                // ����� �������� ������ ��� ��������		return true;	}		this.accept = function(dragObject) {		this.onLeave();		dragObject.hide();                                var obj = dragObject.getElement();                                if(callback != undefined)                    callback(obj, element);                                var x = dragObject.getX() + parsePx(obj.style.left);                var y = dragObject.getY() + parsePx(obj.style.top);                                var elem = document.elementFromPoint(x, y);                                var ch = getChildTarget(element, elem);                                if(ch == false)                    appendObj(element, obj);                else{                    var c = ch.getCoord();                    if(y < c.top + ch.offsetHeight/2)                        beforeInsObj(element, obj, ch);                    else{                        var next = ch.nextElementSibling;                        if(typeof next == "object")                            beforeInsObj(element, obj, next);                        else                            appendObj(element, obj);                    }                }                                var clone = dragObject.getClone();                if(clone != undefined){                    clone.remove();                    obj.setOpacity(1);                }	}                function beforeInsObj(element, obj, ch){            element.insertBefore(obj, ch);            obj.style.display = 'block';            obj.style.position = 'static';            //obj.getElementsByTagName("SELECT")[0].multiple = (element.id=='fixed')?"":"multiple";        }            function appendObj(element, obj){            element.appendChild(obj);            obj.style.display = 'block';            obj.style.position = 'static';            //obj.getElementsByTagName("SELECT")[0].multiple = (element.id=='fixed')?"":"multiple";        }                function parsePx(str){            return parseInt(str.replace("px", ""));        }                function getChildTarget(target, elem){            var child = target.childNodes;            while (elem != target) {                var i = iOf(child, elem);                if (i != undefined) {                        return child[i];                }                elem = elem.parentNode            }                        return false;        }                function iOf(child, elem){            for(i = 0; i < child.length; i++){                if(child[i] == elem) var res = i;            }                        return res;        }}