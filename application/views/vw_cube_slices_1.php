<script src="/public/js/jquery.js"></script>

<script src="/public/js/DragObject.js"></script>
<script src="/public/js/DropTarget.js"></script>
<script src="/public/js/dragMaster.js"></script>

<script src="/public/js/jquery.json-2.3.min.js"></script>

<script src="/public/js/cube/cube.js"></script>
<script src="/public/js/main.js"></script>
<!--link href="/public/css/main.css" type="text/css" rel="stylesheet"-->

 <style type="text/css">
     #tab td{
         border: 1px solid black;
         min-height: 50px;
         min-width: 50px;
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

<table id="tab">
    <tr>
        <td id="fixed">

            <?php
                foreach ($in as $dir => $values){
                    echo "<div> \n";
                    echo "<p>".$dir."</p> \n";
                    echo "<select multiple='multiple'> \n";
                    foreach ($values as $val){
                        $val = (array)$val;
                        echo "<option value='".$val["id"]."'>".$val["name"]."</option>";
                    }
                    echo "</select> \n";
                    echo "</div> \n";
                }
            ?>
        </td>

        <td id="top_dir">

        </td>
    </tr>

    <tr>
        <td id="left_dir">

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