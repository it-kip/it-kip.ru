<script type="text/javascript" src="/public/js/main.js"></script>
<script type="text/javascript" src="/public/js/webexcel.js"></script>

<form method="POST">
    <table id="dirs">
        <tr>
            <td>table name: </td>
            <td><input type="text" name="name" /></td>
            <td></td>
        </tr>
        <tr style="vertical-align: top;">
            <td>dir:</td>
            <td><?php echo $dirs; ?></td>
            <td>
                <!--input type="button" onclick="t.setSel(this.parentNode.parentNode.rowIndex); t.delRow();" value="del" />
                <input type="button" onclick="t.setSel(this.parentNode.parentNode.rowIndex); t.rowCopy();" value="add" /-->
            </td>
        </tr>
        <tr>
            <td>coordinat vector: </td>
            <td><input type='text' name='out[]'></td>
            <td>
                <input type="button" onclick="t.setSel(this.parentNode.parentNode.rowIndex); t.delRow();" value="del" />
                <input type="button" onclick="t.setSel(this.parentNode.parentNode.rowIndex); t.rowCopy();" value="add" />
            </td>
        </tr>
    </table>

    <input type="submit" value="ok" />
</form>

<script type="text/javascript">
var t = new WebExcel({table: "dirs"});
</script>
