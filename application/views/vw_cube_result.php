<?php

function I($arr, $beg = 0, $end = null){
    $I = 1;
    if($end === null) $end = count($arr);
    foreach ($arr as $key => $val)
        if($key >= $beg && $key <= $end)
            $I *= count($val);

    return $I;
}


function transposition($arr) {
    $newarr = array();
    foreach($arr as $key => $val)
        foreach($val as $kkey => $vval)
            $newarr[$kkey][$key] = $vval;
    return $newarr;
}

function array_repeat($arr, $n){
    $arr_res = $arr;
    for($i = 0; $i < $n - 1; $i++)
        for($j = 0; $j < count($arr); $j++)
            $arr_res[] = $arr[$j];

    return $arr_res;
}

$t = I($top);
$l = I($left);
?>

<table id="cube_result" border="1" >
    <tr>
        <td colspan="<? echo count($left); ?>"  rowspan="<?echo count($top); ?>">&nbsp;</td>

        <?php
        $html = "";
        foreach ($top as $key => $arr){
            if($key != 0) $html .= "<tr>";
            $td = "";
            foreach($arr as $val){
                $td .= "<th colspan='".I($top, $key + 1)."'>".$val."</th>";
            }
            $html .= str_repeat($td, I($top, 0, $key - 1))."</tr>";
        }
        echo $html;
        ?>

        <?php
        $j = 0;
        $mx = array();
        foreach ($left as $key => $arr){
            $c = count($arr);
            $k = ($l/$c) / I($left, 0, $key - 1);
            $line = array();
            for($i = 0; $i < $c; $i++){
                $line = array_merge($line, array_fill($i * $k/*($l/$c)*/, $k/*$l/$c*/, $i/*$arr[$i]*/));
            }
            $mx[$j] = array_repeat($line, I($left, 0, $key - 1));
            $j++;
        }
        $mx = transposition($mx);
        $html = "";
        for($i = 0; $i < $l; $i++){
            $html .= "<tr>";
            for($j = 0; $j < count($left); $j++){
                if($i == 0 || $mx[$i - 1][$j] != $mx[$i][$j]){
                    $html .= "<th rowspan='".I($left, $j + 1)."'>".$left[$j][$mx[$i][$j]]."</th>";
                }
            }
            // данные
            for($j = 0; $j < count($data[0]); $j++)
                $html .= "<td onclick='changeData(this);' i='{$i}' j='{$j}'>".($data[$i][$j]? $data[$i][$j]: "&nbsp;")."</td>";
                //$html .= "<td><input onchange='savedata(this);' type='text' value='".$data[$i][$j]."' vector='".""/*$vector[$i][$j]*/."' /></td>";
            

            $html .= "</tr>";
        }
        echo $html;
        ?>
        
    </tr>
</table>