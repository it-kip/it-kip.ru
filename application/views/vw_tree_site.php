<link rel="stylesheet" href="/public/js/jquery-ui-1.8.18.custom/css/redmond/jquery-ui-1.8.18.custom.css" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="/public/js/elrte-1.3/css/elrte.min.css" type="text/css" media="screen" charset="utf-8">
<link rel="stylesheet" href="/public/js/elfinder-1.2/css/elfinder.css" type="text/css" media="screen" charset="utf-8">

<?
function subset($keys, $arr){
    $res = array();
    foreach($keys as $val)
        if(isset($arr[$val])) $res[] = $arr[$val];
        
    return $res;
}
?>

<h2><?= $table['info']['n']; ?></h2>
<input type="button" onclick="addnode();" value="Добавить">
<? if(isset($table['tree'])){ ?>
<input type="button" onclick="addchild();" value="Добавить потомка">
<? } ?>
<input type="button" onclick="delnode();" value="Удалить">
<ul id="tree" name="<?= $table['name']; ?>">
    <?
    $html = "";
    foreach($data as $d){
        $n = implode (" | ", subset($table['names'], $d));
        $html .= "<li><p value='".$d[$table['key']]."' onclick='info(this);'>".$n."</p></li>";
    }
    echo $html;
    ?>
</ul>

<!----------------------------------------------------------------------------->
<h3 id="h3"></h3>
<form id="fields" method="post">
    <input type="hidden" id="<?= $table['key']; ?>" name="<?= $table['key']; ?>" />
    <table>
        <?php
        $html = "";
        $arr = array();
        foreach($table['out'] as $key => $val){
            $html .= "<tr><td>".(isset($names[$key])? $names[$key]: $key)."</td><td>";
            switch ($val) {
                case "varchar":
                    $html .= "<input id='{$key}' name='{$key}' type='text' />";
                    break;
                 case "tinyint":
                    $html .= "<input id='{$key}' name='{$key}' type='checkbox' value='1' />";
                    break;
                 case "int":
                    $html .= "<input id='{$key}' name='{$key}' type='text' onkeypress='return jqmask(event);' />";
                    break;
                 case "text":
                    $html .= "<textarea id='{$key}' name='{$key}'></textarea>";
                    $arr[] = $key;
                    break;
            }

            $html .= "</td></tr>";
        }

        echo $html;
        ?>
    </table>
    <input type="submit" value="Сохранить" />
</form>

<div id="new" style="display: none;">
    <?php
        $html = "";
        foreach($table['names'] as $val){
            $html .= "<tr><td>".(isset($names[$val])? $names[$val]: $val)."</td>";
            $html .= "<td><input name='{$val}' type='text' /></td>";
            $html .= "</tr>";
        }

        echo $html;
        ?>
</div>

<script>
    
    <? foreach($arr as $val){ ?>
        $('#<?= $val; ?>').elrte({
            //cssClass : 'el-rte',
            //height   : 450,
            toolbar  : 'maxi',
            //cssfiles : ['/public/js/elrte-1.3/css/elrte-inner.css'],
            lang: 'ru',
            fmOpen : function(callback) {
               $('<div id="myelfinder" />').elfinder({
                  url : '/public/js/elfinder-1.2/connectors/php/connector.php',
                  lang : 'ru',
                  places: '',
                  dialog : {modal : true, title : 'Файлы' },// открываем в диалоговом окне
                  closeOnEditorCallback : true,             // закрываем после выбора файла
                  editorCallback : callback                 // передаем callback файловому менеджеру
               })
            }
        });
    <? } ?>
    
    // -------------------------------------------------------------------------
    var sel;
    
    function delnode(){
        if(sel !== undefined){
            $.ajax({
                url:        '/manager/delrec/<?= $table['name']; ?>',
                type:       "post",
                data:       {id : sel.getAttribute("value")},
                success :   function(){
                                sel.parentNode.parentNode.removeChild(sel.parentNode);
                                sel = undefined;
                            },
                error:      function(){
                                alert("Невозможно удалить.\n\rПроверте наличие потомков");
                            }
            });
        }
    }
    
    function addnode(parent){
        $("#new input").each(function(){
            this.value = "";
        });
        
        $("#new").dialog({
            width: 'auto',
            title: 'Новый элемент',
            buttons: {
                'Сохранить': function(){
                    var post = {};
                    var names = [];
                    $("#new input").each(function(){
                        post[this.name] = this.value;
                        names.push(this.value);
                    });
                    if(parent !== undefined) post['<?= $table['tree']; ?>'] = parent;
                    $.post('/manager/addrec/<?= $table['name']; ?>', post, function(data){
                        var li = document.createElement('li');
                        var p = li.appendChild(document.createElement('p'));
                        p.setAttribute("value", data);
                        p.addEvent('click', function(){expand(this)});
                        p.innerHTML = names.join(" | ");
                        if(parent !== undefined)
                            sel.appendChild(li);
                        else
                            document.getElementById("tree").appendChild(li);
                        
                        p.click();
                        $('#new').dialog('close');
                    }, 'json')
                },
                'Закрыть': function(){$('#new').dialog('close');}
            }
        });
    }
    
    function info(el){
        sel = el;
        document.getElementById("h3").innerHTML = el.innerHTML;
        var id = el.getAttribute('value');
        $.post("/manager/dir_vals/<?= $table['name']; ?>/" + id, null, function(data){
            $("#fields input[type='hidden']").each(function(){
                this.value = data[this.id];
            })
            $("#fields input[type='text']").each(function(){
                this.value = data[this.id];
            })
            $("#fields input[type='checkbox']").each(function(){
                if(data[this.id] == 1) this.checked = true;
            })
            $("#fields textarea").each(function(){
                var str =  data[this.id] === null? " ": data[this.id];
                $(this).elrte('val', str);
                //this.innerHTML = data[this.id];
            })
        }, 'json');
        
        <? if(isset($table['tree'])) echo "expand(el);" ?>
    }
    
    <? if(isset($table['tree'])){ ?>
    function expand(el, add){
        var id = el.getAttribute('value');
        var li = el.parentNode;
        var uls = li.getElementsByTagName('ul');
        if(uls.length > 0){
            if(uls[0].style.display == 'none')
                uls[0].style.display = 'block';
            else
                uls[0].style.display = 'none';
        }
        else{
            $.post('/manager/tree_children/<?= $table['name']; ?>/' + id, null, function(data){
                if(data.length > 0){
                    var ul = document.createElement('ul');
                    for(var i = 0; i < data.length; i++){
                        var lli = ul.appendChild(document.createElement('li'));
                        var p = lli.appendChild(document.createElement('p'));
                        p.setAttribute('value', data[i]['<?= $table['key'] ;?>']);
                        p.addEvent('click', function(){info(this)}); // expand(p)
                        p.innerHTML = <?= "data[i]['".implode("'] + ' | ' + data[i]['", $table['names'])."']"; ?>;
                    }
                    
                    if(add != undefined)
                        ul.appendChild(add);
                    
                    li.appendChild(ul);
                    dd();
                }
                else
                    if(add != undefined){
                        var ul = document.createElement("ul");
                        ul.appendChild(add);
                        el.parentNode.appendChild(ul);
                    }
            }, 'json');
        }
    }
    
    function addchild(){
        if(sel !== undefined){
            var id = sel.getAttribute('value');
            addnode(id);
        }
    }
    
    // -------------------------------------------------------------------------
    var d;
    $(function(){ dd();});
    
    function dd(){
        $("#tree li").draggable({
            revert: "invalid",
            //revertDuration: 500,
            helper: 'clone',
            stop: function(){
                if(d != undefined){
                    var ul = $(d).find('ul')[0];
                    if(ul != undefined)
                        ul.appendChild(this);
                    else
                        expand($(d).find('p')[0], this);
                    
                    $.post('/manager/rechild/<?= $table['name']; ?>', {parent: $(d).find('p')[0].getAttribute("value"), child: $(this).find('p')[0].getAttribute("value")});
                    d = undefined;
                }
            }
        });
        
        $("#tree li").droppable({
            drop: function(){
                d = this;
            }
        });
    }
    <? } ?>
</script>