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
         border: 1px solid black;
         /*min-height: 100px;*/
         max-width: 50px;
         vertical-align: top;
         min-width: 100px;
    }
    
    #tab td ul.connected {
        min-width: 100px;
        min-height: 100px;
        width: 100%;
        height: 100%;
        
        /*border: 1px solid black;*/
        list-style-type: none;
        margin: 0;
        padding: 5px 0 0 0;
        /*float: left;*/
        margin-right: 10px;
    }
    #tab td ul.connected li {
        margin: 0 5px 5px 5px;
        padding: 5px;
        font-size: 1.2em;
        /*width: 120px;*/
        border: 1px solid #eee;
        /*background-color: white;*/
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
    
    select.sel{
        max-width: 800px;
    }
    
    .ui-state-highlight{
        min-height: 100px;
    }
 </style>

<h1 id="cube"><? echo $name; ?></h1>

<table id="tab" style="max-width: 100%;">
    <tr>
        <td style="max-width: 50%;">
            <ul id="fixed" class="connected" style="max-width: 100%">
            <?php            
            foreach ($in as $dir => $values){
                echo "<li> \n";
                echo "<p>".$dir."</p> \n";
                echo "<select class='sel' multiple='multiple'> \n";
                foreach ($values as $val){
                    $val = (array)$val;
                    echo "<option value='".$val["id"]."'>".$val["name"]."</option> \n";
                }
                echo "</select> \n";
                echo "</li> \n";
            }
            ?>
            </ul>
        </td>

        <td style="max-width: 50%;">
            <ul id="top_dir" class="connected"></ul>
        </td>
    </tr>

    <tr>
        <td style="max-width: 50%;">
            <ul id="left_dir" class="connected"></ul>
        </td>
        <td style="max-width: 50%;">
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
        connectWith: ".connected",
        placeholder: "ui-state-highlight"
    }).disableSelection();
    
//    $(document).ready(function(){
////        var div = document.getElementById("top_dir");
////        div.style.height = div.parentNode.style.height;
//        var div = $("#top_dir");
//        div.css("height", div.parent().height());
//    })

    $(".sel").multiselect();
</script>