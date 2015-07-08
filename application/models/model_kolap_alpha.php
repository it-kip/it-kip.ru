<?php

class model_kolap_alpha extends CI_Model{
    
    var $tbl_info;
    
    function getInfo($tbl, $refresh = false){
        if($refresh || $this->tbl_info['table']['name'] != $tbl)
            $this->tbl_info = $this->table_info ($tbl);
        
        return $this->tbl_info;
    }
    
    public function table_info($name) {
        
        $info = array();
        $table = array('name' => $name, 'type' => 'dir', 'names' => array());
        
        $data = $this->db->query("
            SELECT c.column_name
                , c.ordinal_position
                , c.data_type
                , c.column_comment
                , kcu.referenced_table_name
                , kcu.referenced_column_name
                , kcu.constraint_name
                , tc.constraint_type
            FROM information_schema.`COLUMNS` c
            LEFT JOIN information_schema.`KEY_COLUMN_USAGE` kcu
                ON c.table_schema = kcu.table_schema
                AND c.table_name = kcu.table_name
                AND c.column_name = kcu.column_name
            LEFT JOIN information_schema.TABLE_CONSTRAINTS tc
                ON c.table_schema = tc.table_schema
                AND c.table_name = tc.table_name
                AND kcu.constraint_name = tc.constraint_name
            WHERE c.table_name = '".$name."' AND c.table_schema = '".$this->db->database."'
            ORDER BY c.ordinal_position, kcu.referenced_table_name
        ")->result_array();
        
        foreach($data as $val){
            $pos = $val['ordinal_position'];
            $info[$pos]['name'] = $val['column_name'];
            $info[$pos]['type'] = $val['data_type'];
            if($val['referenced_table_name']) $info[$pos]['ref_table'] = $val['referenced_table_name'];
            if($val['referenced_column_name']) $info[$pos]['ref_column'] = $val['referenced_column_name'];
            if($val['column_comment']) $info[$pos]['comment'] = $val['column_comment'];
            switch ($val['constraint_type']){
                case 'PRIMARY KEY':
                    $info[$pos]['key'] = true;
                    $table['key'] = $val['column_name'];
                    break;
                case 'FOREIGN KEY':
                    $info[$pos]['fk'] = true;
                    break;
                case 'UNIQUE':
                    $info[$pos]['u'] = true;
                    break;
            }

            $info[$pos] = array_merge($info[$pos], (array)json_decode($val["column_comment"]));
            //$comment[$val["column_name"]] = ($val["column_comment"] != "")? $val["column_comment"]: $val["column_name"];

            if(isset($info[$pos]['fk']) && isset($info[$pos]['u']) && $val['referenced_table_name'] != $name){
                $table['type'] = 'cube';
                $table['in']['num'][] = $val['ordinal_position'];
                $table['in']['where'][] = $val['column_name'];
                $table['in']['who_table'][] = $val['referenced_table_name'];
                $table['in']['who_column'][] = $val['referenced_column_name'];
                if(isset($table['out'][$val['column_name']])) unset($table['out'][$val['column_name']]);
            }
            elseif(!isset($info[$pos]['key']))
                if(isset($info[$pos]['fk']) && $val['referenced_table_name'] == $name && $val['referenced_column_name'] == $table['key']){ // $val['referenced_column_name'] == $table['key'] в общем случае ключ может идти после данного поля, в частности обычно ставят 1м и он уже определён. можно отсортировать основной запрос сначало по ключу
                    $table['type'] = 'tree';
                    $table['tree'] = $val['column_name'];
                    unset($table['out'][$val['column_name']]);
                    $k = array_search($val['column_name'], $table['names']);
                    unset($table['names'][$k]);
                }
                else{
                    $table['out'][$val['column_name']] = $val['data_type'];
                    if(isset($info[$pos]['u'])) $table['names'][] = $val['column_name'];
                }
        }

        $name_c = $this->db->query("
            SELECT table_comment
            FROM information_schema.TABLES
            WHERE table_name = ".$this->db->escape($name) // ?? $name
        )->row_array();
        
        $table['info'] = (array)json_decode($name_c['table_comment']);
        $table['name_comment'] = (isset($table['info']['name']) && $table['info']['name'])? $table['info']['name']: $name;
        return array('table' => $table, 'info'=> $info);
    }
}