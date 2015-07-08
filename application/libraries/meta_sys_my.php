<?

class meta_sys_my{

    var $info = new stdClass();
    var $table = new stdClass();

    public function table_info($name) {
        //$info = array();
        //$table = array('name' => $name, 'type' => 'dir', 'names' => array());
        
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
            $this->info->pos->name = $val['column_name'];
            $this->info->pos->type = $val['data_type'];
            if($val['referenced_table_name']) $this->info->pos->ref_table = $val['referenced_table_name'];
            if($val['referenced_column_name']) $this->info->pos->ref_column = $val['referenced_column_name'];
            if($val['column_comment']) $this->info->pos->comment = $val['column_comment'];
            switch ($val['constraint_type']){
                case 'PRIMARY KEY':
                    $this->info->pos->key = true;
                    $table['key'] = $val['column_name'];
                    break;
                case 'FOREIGN KEY':
                    $this->info->pos->fk = true;
                    break;
                case 'UNIQUE':
                    $this->info->pos->u = true;
                    break;
            }
            $this->info->pos += json_decode($val["column_comment"]); // ??

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

        // for table
        $name_c = $this->db->query("
            SELECT table_comment
            FROM information_schema.TABLES
            WHERE table_name = ".$this->db->escape($name) // ?? $name
        )->row_array();
        
        $table['info'] = (array)json_decode($name_c['table_comment']);
        $table['name_comment'] = (isset($table['info']['n']) && $table['info']['n'])? $table['info']['n']: $name;
        return array('table' => $table, 'info'=> $info);
    }
}
