<select name="dirs[]" id="dirs" multiple="multiple" size="<? echo count($dirs); ?>">
<?
if(is_array($dirs))
    foreach ($dirs as $dir){
        $name = (array)json_decode($dir['TABLE_COMMENT']);
        $name = (isset($name['name']))? $name['name']: $dir['TABLE_NAME'];
        echo "<option value=".$dir['TABLE_NAME'].">".$name."</option>";
        
    }
?>
</select>

<script tipe="text/javascript" src="/public/js/jquery.js"></script>
<script>
    $("#dirs option").dblclick(function(){
        location.href = "/kolap/workwithdir/" + this.value;
    });
</script>