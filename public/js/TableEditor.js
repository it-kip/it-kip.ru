function TableEditor(option){
    var table = option["table"];
    table = table.cloneNode(true);

    var div = document.createElement("DIV");
    var panel = div.appendChild(document.createElement("DIV"));
    div.appendChild(table);
    var bottom_panel = div.appendChild(document.createElement("DIV"));

    var winTable = new MoveWindow({
        title: "Редактор таблиц",
        content: div
    });

    var editor = new WebExcel({table: table, header: true});

    var but = panel.appendChild(document.createElement("INPUT"));
    but.type = "button";
    but.value = "Добавить колонку";
    but.onclick = editor.addCol;

    but = panel.appendChild(document.createElement("INPUT"));
    but.type = "button";
    but.value = "Добавить строку";
    but.onclick = editor.addRow;

    but = panel.appendChild(document.createElement("INPUT"));
    but.type = "button";
    but.value = "Объеденить";
    but.onclick = editor.merge;

    but = panel.appendChild(document.createElement("INPUT"));
    but.type = "button";
    but.value = "Разъеденить";
    but.onclick = editor.unmerge;


    but = bottom_panel.appendChild(document.createElement("INPUT"));
    but.type = "button";
    but.value = "Сохранить";
    but.onclick = save;

    but = bottom_panel.appendChild(document.createElement("INPUT"));
    but.type = "button";
    but.value = "Отмена";
    but.onclick = winTable.remove;

    
    function save(){
        var t = option["table"];
        editor.unselect();
        editor.headings();
        t.innerHTML = table.innerHTML;
        winTable.remove();
    }
}