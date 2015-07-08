/*
 * option:
 * title    - заголовок окна
 * content  - содержимое окна (либо html, либо объект)
 */
function MoveWindow(option){
    var divMove = false;
    var divX;
    var divY;

    this.getWindow = getWindow;
    this.getContent = getContent;

    this.remove = remove;
    this.displace = displace;

    var div = document.body.appendChild(document.createElement('div'));
    div.style.position = "absolute";
    div.style.left = "0px";
    div.style.top = "0px";
    div.style.border = "1px solid black";
    div.style.backgroundColor = "white"; // ??

    var divHeader = div.appendChild(document.createElement('div'));
    divHeader.style.backgroundColor = "blue";
    divHeader.style.width = "100%";
    divHeader.style.cursor = "pointer";

    divHeader.style.minHeight = "26px";
    
    if(option["title"] != undefined)
        divHeader.innerHTML = option["title"];

    var but = divHeader.appendChild(document.createElement("div"));
    but.style.cssFloat = "right";
    but.style.styleFloat = "right";
    but.style.border = "1px black solid";
    but.style.margin = "2px";
    but.style.width = "20px";
    but.align = "center";
    but.innerHTML = "X";
    but.onclick = this.remove;

    var but = divHeader.appendChild(document.createElement("div"));
    but.style.cssFloat = "right";
    but.style.styleFloat = "right";
    but.style.border = "1px black solid";
    but.style.margin = "2px";
    but.style.width = "20px";
    but.align = "center";
    but.innerHTML = "_";
    but.onclick = this.displace

    addEvent(divHeader, "mousedown", DDown);
    addEvent(document.body, "mouseup", DUp); //addEvent(divHeader, "mouseup", DUp); возможно лучше
    addEvent(document.body, "mousemove", DMove);

    //divHeader.onmousedown = DDown;
    //document.body.onmouseup = DUp;
    //document.body.onmousemove = DMove;

    var divContent = div.appendChild(document.createElement('div'));
    
    if(typeof option["content"] == "object")
    	divContent.appendChild(option["content"]);
    else
    	divContent.innerHTML = option["content"];

    function DDown(event){
        divMove = true;
        divX = event.screenX - parsePx(div.style.left);
        divY = event.screenY - parsePx(div.style.top);
    }

    function DUp(){
        divMove = false;
    }

    function DMove(event){
        if(divMove){
            event = event || window.event;
            div.style.left = (event.screenX - divX) + "px";
            div.style.top = (event.screenY - divY) + "px";
        }
    }

    function parsePx(str){
            return parseInt(str.replace("px", ""));
    }

    function getWindow(){
        return div;
    }

    function getContent(){
        return divContent;
    }

    function remove(){
        div.parentNode.removeChild(div);
    }

    function displace(){
        if(divContent.style.display != "none")
            divContent.style.display = "none";
        else
            divContent.style.display = "block";
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