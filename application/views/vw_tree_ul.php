<?
function nodes($tree){
    
} 
?>

<ul<? if(!empty($class)) echo " class='$class'"; if(!empty($id)) echo " id='$id'"; ?>>
    <?
    $html = "";
    foreach($arr as $val){
        $html .= "<li".(isset($val['open']) && $val['open'] == true? " class='open'": "").">";
        $html .= "<a href='".$val['url']."'".(isset($val['target'])? " target='".$val['target']."'": "")."><span>".$val['text']."</span></a>";
        if(isset($val['childs']) && is_array($val['childs']) && count($val['childs']) > 0){
            $html .= "<ul>";
            foreach($val['childs'] as $lev2){
                $html .= "<li".(isset($lev2['open']) && $lev2['open'] == true? " class='open'": "").">";
                $html .= "<a href='".$lev2['url']."'".(isset($lev2['target'])? " target='".$lev2['target']."'": "")."><span>".$lev2['text']."</span></a>";
                if(isset($lev2['childs']) && is_array($lev2['childs']) && count($lev2['childs']) > 0)
                    foreach($val['childs'] as $lev3)
                        $html .= "<li><a href='".$lev3['url']."'".(isset($lev3['target'])? " target='".$lev3['target']."'": "")."><span>".$lev3['text']."</span></a></li>";

                $html .= "</li>";
            }
            $html .= "</ul>";
        }
        $html .= "</li>";
    }
    echo $html;
    ?>
</ul>