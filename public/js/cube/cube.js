/*
onload = function() {
    
    var fix = document.getElementById('fixed');
    var cn = fix.childNodes;
    for(i = 0; i < cn.length; i++){
        new DragObject({obj: cn[i]});
    }
    
    var cb = function(object, target){
        object.getElementsByTagName("SELECT")[0].multiple = (target.id=='fixed')?"":"multiple";
    }
    
    new DropTarget({obj: document.getElementById('fixed'), callback: cb})
    new DropTarget({obj: document.getElementById('top_dir'), callback: cb})
    new DropTarget({obj: document.getElementById('left_dir'), callback: cb})
}
*/

var fix;
var top1;
var left;
var val;

function send(){
    fix = document.getElementById("fixed");
    top1 = document.getElementById("top_dir");
    left = document.getElementById("left_dir");
    val = document.getElementById("val").options[document.getElementById("val").selectedIndex].value;
    /*
    fix = getDirData(fix.getElementsByTagName("DIV"));
    top1 = getDirData(top1.getElementsByTagName("DIV"));
    left = getDirData(left.getElementsByTagName("DIV"));
    */
    fix = getDirData(fix.getElementsByTagName("LI"));
    top1 = getDirData(top1.getElementsByTagName("LI"));
    left = getDirData(left.getElementsByTagName("LI"));

    $("#res").load("/kolap/cuberesult", {
        cube: document.getElementById("cube").innerHTML,
        value: val,
        top: $.toJSON(top1),
        left: $.toJSON(left),
        fixed: $.toJSON(fix)
    });
}

function getDirData(divs){
    var arr = new Array();
    for(var key = 0; key < divs.length; key++){
        var d = divs[key];
        arr.push( {name: d.getElementsByTagName("P")[0].innerHTML, value: getSelectedIndexes(d.getElementsByTagName("SELECT")[0])} );
    }
    return arr;
}

function getSelectedIndexes (oListbox){
  var arrIndexes = new Array();
  for (var i=0; i < oListbox.options.length; i++){
      if (oListbox.options[i].selected)
          arrIndexes.push(oListbox.options[i].value);
  }
  return arrIndexes;
}


// возможно закомитить
function changeData(elem){
    
    // если агр не сохранять
    var summ = true;
    for(var f in fix)
        if(fix[f].value.length > 1) summ = false;

    if(summ){
        var inp = elem.getElementsByTagName('input');
        if(inp.length == 0){
            inp = document.createElement("input");
            inp.style.width = elem.offsetWidth;
            inp.style.height = elem.offsetHeight;
            inp.style.border = "none";
            inp.value = (elem.innerHTML == '&nbsp;'? "": elem.innerHTML);
            inp.onblur = function(){
                var txt = inp.value;
                inp.remove();
                elem.innerHTML = (txt.replace(" ", "") == ""? "&nbsp;": txt);
                
                // что сохранять
                $.post("/kolap/savedata", {
                    cube: document.getElementById("cube").innerHTML,
                    value: val,
                    top: $.toJSON(top1),
                    left: $.toJSON(left),
                    fixed: $.toJSON(fix),
                    i: elem.getAttribute('i'),
                    j: elem.getAttribute('j'),
                    val: txt
                });
            }
            elem.innerHTML = "";
            elem.appendChild(inp);
            inp.select();
        }
    }
}