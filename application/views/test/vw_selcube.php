<form action="/kolap_test/cube_slices" method="post">

<select name="name" size="<? echo count($cubes); ?>">
<?
if(is_array($cubes))
    foreach ($cubes as $cube){
        echo "<option value=".$cube['TABLE_NAME'].">".$cube['TABLE_NAME']."</option>";
    }
?>
</select>
<br />
<input type="submit" value="Выбрать">

</form>