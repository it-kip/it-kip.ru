<style>
	table#fields td{ border: none; }
</style>
<form method="POST">
    <table id="fields">
        <tr>
            <td>Древовидная структура: </td>
            <td><input type="checkbox" name="tree" value="1" /></td>
            <td></td>
        </tr>
        <tr>
            <td>Имя справочника: </td>
            <td><input type="text" name="name" /></td>
            <td></td>
        </tr>
        <tr>
            <td>Дополнительная информация: </td>
            <td><input type="text" name="fields[]" /></td>
            <td>
                <input type="button" onclick="t.setSel(this.parentNode.parentNode.rowIndex); t.rowCopy();" value="Добавить поле" />
                <input type="button" onclick="delrow(this);" value="Удалить поле" />
            </td>
        </tr>
    </table>
    <br /><br />
    <input type="submit" value="ok" />
</form>

<script type="text/javascript" src="/public/js/main.js"></script>
<script tipe="text/javascript" src="/public/js/WebExcel.js"></script>
<script type="text/javascript">
var t = new WebExcel({table: "fields", editcell: false});

function delrow(ths){
	if(t.rowcount() > 3){
		t.setSel(ths.parentNode.parentNode.rowIndex);
		t.delRow();
	}
}
</script>