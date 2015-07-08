<style>
            #tbl_<?= $table ?> tr td input{
                border: none;
                background: inherit;
                width: 100%;
	padding: 0;
            }
            #tbl_<?= $table ?> tr td{
                min-height: 18px;
	height: 18px;
            }
</style>

<input type="button" value="Новая запись" onclick="newrec()" />

<table id="tbl_<?= $table ?>">
            <tr>
                <? 
                $tds = "";
                foreach($meta as $val) {
                    $tds .= "<td name='{$val['name']}'></td>";
                    echo "<th>{$val['name']}</th>";
                }
                ?>
            </tr>
            <? 
            foreach($data as $row){
                echo "<tr id='{$row[$key]}'>";
                foreach($meta as $val) echo "<td name='{$val['name']}'>{$row[$val['name']]}</td>";
                echo "</tr>";
            }
            ?>
</table>

<script>
            var td = false;
            var tbl = "<?= $table ?>";
            var url_act = '<?= $url ?>';
           
            $("#tbl_" + tbl + " tr").hover(function(){
                $(this).find('td').css('background', "#c8d197");
            },
            function(){
                $(this).find('td').css('background', "#FFFFFF");
            })
            
            $("#tbl_" + tbl + " tr td").live('click',function(){
                if(td != this){

                        if(td) inp_destroy();
                        td = this;
                        var val = td.innerHTML;
                        td.innerHTML = "";
                        var inp = document.createElement("input");
                        inp.value = val;
                        td.appendChild(inp);
                        td.inp = inp;
                        inp.focus();

                }
            })
            
            function inp_destroy(){
                var val = td.inp.value;
                td.innerHTML = val;
                td = false;
            }
            
            $("#tbl_" + tbl + " tr td input").live('keyup', function(){
                var inp = this;
                var val = new Object();
                val[inp.parentNode.getAttribute('name')] = inp.value;
                var id = ~~ inp.parentNode.parentNode.id;
                if(! id)
                    $(inp.parentNode.parentNode).find('td').each(function(){
                        if(td != this && this.innerHTML != '') val[this.getAttribute('name')] = this.innerHTML;
                    })
                
	$.post(url_act, val, function(id){
                    if(id) {
                        inp.parentNode.parentNode.id = id;
                        //$(inp.parentNode.parentNode).find("td[name='<?= $key ?>']").html(id);
                    }
                })
            })
            
            function newrec(){
                var tr = $("#tbl_" + tbl + " tr");
	if(tr.length > 1){

                var tr1 = tr[1];
                if(tr1.id != undefined && tr1.id != ""){
                    var t = document.getElementById("tbl_" + tbl);
                    var newtr = t.insertRow(1);
                    newtr.innerHTML = "<?= $tds ?>";
                }

	}
	else{
		var t = document.getElementById("tbl_" + tbl);
		tr = document.createElement( 'tr' );
		tr.innerHTML = "<?= $tds ?>";
		t.appendChild( tr );
	}
            }
</script>
