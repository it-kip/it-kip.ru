<style>
    ul {
        list-style: none;
        outside: none;
    }
    
    .sel{
        background: blue;
    }
</style>

<ul id="dirs">
<?
if(is_array($dirs))
    foreach ($dirs as $dir)
        echo "<li><input type='checkbox' name='one[]' value='{$dir['TABLE_NAME']}'><input type='checkbox' name='many[]' value='{$dir['TABLE_NAME']}'>{$dir['TABLE_NAME']}</li>";
?>
</ul>

<script tipe="text/javascript" src="/public/js/jquery.js"></script>
<script>
    $("#dirs li").hover(
        function(){
            this.addClass('sel');
        },
        function(){
            this.removeClass('sel');
        }
    )
    
    $("#dirs li input").change(function(){
        if(this.checked){
            $(this.parentNode).find('input').attr('checked', false);
            this.checked = true;
        }
        else{
            $(this.parentNode).attr('checked', false);
        }
    })
</script>