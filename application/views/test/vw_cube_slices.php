<!--script src="/public/js/jquery.js"></script>
<script src="/public/js/DragObject.js"></script>
<script src="/public/js/DropTarget.js"></script>
<script src="/public/js/dragMaster.js"></script-->

<link rel='stylesheet' href='/public/js/jquery-ui-1.10.3.custom/css/smoothness/jquery-ui-1.10.3.custom.css' />
<script src='/public/js/jquery-ui-1.10.3.custom/js/jquery-1.9.1.js'></script>
<script src='/public/js/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js'></script>

<script src="/public/js/jquery.json-2.3.min.js"></script>

<script src="/public/js/cube/cube.js"></script>
<script src="/public/js/main.js"></script>

<link rel="stylesheet" type="text/css" href="/public/js/multiselect/jquery.multiselect.css" />
<script src="/public/js/multiselect/src/jquery.multiselect.js"></script>

<!--link href="/public/css/main.css" type="text/css" rel="stylesheet"-->

 <style type="text/css">
     #tab td{
         border: 5px solid #FDBE33;
         min-height: 100px;
         min-width: 50px;
         vertical-align: top;
    }
    
    #tab td ul{
        list-style-type: none;
        padding: 2px;
        min-width: 50px;
        min-height: 100px;
        width: 100%;
        height: 100%;
        padding-bottom: 50px;
    }

    div{
        border: 1px solid black;
        background: white;
    }

    .uponMe{
        background: blue;
    }

    #res{
        border: none;
    }
 </style>

<h1 id="cube"><? echo $name; ?></h1>

<table id="tab" style="max-width: 100%;">
    <tr>
        <td>
            <ul id="fixed" class="connected">
            <?php            
            foreach ($in as $dir => $values){
                echo "<li> \n";
                echo "<p>".$dir."</p> \n";
                echo "<select multiple='multiple'> \n";
                foreach ($values as $val){
                    $val = (array)$val;
                    echo "<option value='".$val["id"]."'>".$val["name"]."</option>";
                }
                echo "</select> \n";
                echo "</li> \n";
            }
            ?>
            </ul>
        </td>

        <td>
            <ul id="top_dir" class="connected"></ul>
        </td>
    </tr>

    <tr>
        <td>
            <ul id="left_dir" class="connected"></ul>
        </td>
        <td>
            Вывод:<br /><br />
            <select id="val">
                <? 
                foreach ($out as $key => $val){
                    echo "<option value='".$key."'>".$key."</option>";
                }
                ?>
            </select>
            <br /><br />
            <input type="button" value="ok" onclick="send();">
        </td>
    </tr>
</table>

<hr />

<div id="res">

</div>

<script>
    $( "#fixed, #top_dir, #left_dir" ).sortable({
        connectWith: ".connected"
    }).disableSelection();
    
//    $(document).ready(function(){
////        var div = document.getElementById("top_dir");
////        div.style.height = div.parentNode.style.height;
//        var div = $("#top_dir");
//        div.css("height", div.parent().height());
//    })
    
    $("select").multiselect();
</script>

<style>
  select .sel {
  max-width: 200px;
  border: 2px solid;
 }
</style>
  
  
