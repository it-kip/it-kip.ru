<?php

class model_work extends CI_Model{



/*
    
    var $tbl_info;
    
    function getInfo($tbl, $refresh = false){
        if($refresh || $this->tbl_info['table']['name'] != $tbl)
            $this->tbl_info = $this->table_info ($tbl);
        
        return $this->tbl_info;
    }
        
    function create_dir($Name, $fields = ""){
        
        $Name = trim($Name);
        $DirName = $this->translit($Name);
        $str = array();
        //$db = ($this->db->database != "")? $this->db->database.".": "";
        
        if(is_array($fields))
            foreach ($fields as $value){
                $value = trim($value);
                if($value != "")
                    $str[] = "{$DirName}_{$this->translit($value)} VARCHAR(32)".(($this->db->dbdriver == 'mysql')? " COMMENT '{$value}'": "");
            }
        
        if($this->db->dbdriver == 'mysql'){
            $query = "CREATE TABLE `{$DirName}` (
                `{$DirName}_id` INT(11) NOT NULL AUTO_INCREMENT,
                `{$DirName}_name` VARCHAR(32) NOT NULL,
                ".implode(", ", $str).((!empty($str))? ", ": "")."
                PRIMARY KEY  (`{$DirName}_id`),
                UNIQUE KEY `un_{$DirName}` (`{$DirName}_name`)
            ) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='".json_encode(array('name' => $Name))."'";
            
            $this->db->query($query);
            //echo $this->db->last_query();
        }
        elseif($this->db->dbdriver == 'postgre'){
            // записать в комент $name
            $query = 'CREATE TABLE "'.$DirName.'" (
                "'.$DirName.'_id" SERIAL,
                "'.$DirName.'_name" VARCHAR(32) NOT NULL,
                '.implode(", ", $str).((!empty($str))? ", ": "").'
                CONSTRAINT "'.$DirName.'_pkey" PRIMARY KEY("'.$DirName.'_id"),
                CONSTRAINT "'.$DirName.'_name_key" UNIQUE("'.$DirName.'_name")
            ) WITHOUT OIDS';
            
            $this->db->query($query);
            
            if(is_array($fields))
                foreach ($fields as $value){
                    $value = trim($value);
                    if($value != "")
                        $this->db->query("COMMENT ON COLUMN {$DirName}.{$DirName}_{$this->translit($value)} IS '{$value}'");
                }
        }
    }
    
    function create_cube($Name, $One, $Many, $Out){
        
        $Name = trim($Name);
        $CubeName = $this->translit($Name);
        
        if($CubeName != "" && isset($One)){
            
            $dirfield = array();
            $fk =  array();
            $constr =  array();
            
            foreach($One as $dir){
                $cname = $CubeName."_".$dir;
                $dirfield[] = $cname." INT NOT NULL";
                $fk[] = "KEY FK_".$cname." (".$cname.") ";
                $constr[] = "CONSTRAINT FK_".$cname." FOREIGN KEY (".$cname.") REFERENCES ".$dir." ({$this->keyTable($dir)}) ON DELETE RESTRICT ON UPDATE CASCADE".(($this->db->dbdriver == 'postgre')? " NOT DEFERRABLE":"");
            }
            
            if(!empty($Many))
                foreach($Many as $dir){
                    $cname = $CubeName."_".$CubeName."_".$dir;
                    $dirfield[] = $cname." INT NOT NULL";
                    //$fk[] = "KEY FK_".$cname." (".$cname.") ";
                    //$constr[] = "CONSTRAINT FK_".$cname." FOREIGN KEY (".$cname.") REFERENCES ".$dir." ({$this->keyTable($dir)}) ON DELETE RESTRICT ON UPDATE CASCADE".(($this->db->dbdriver == 'postgre')? " NOT DEFERRABLE":"");
                }
            
            if($this->db->dbdriver == 'mysql'){
                foreach($Out as $key => &$o)
                    if($o != "")
                        $o = " `".$CubeName."_".$this->translit($o)."` INT COMMENT '{$o}'";
                    else
                        unset($Out[$key]);

                // не хватает many
                $query = "CREATE TABLE `$CubeName` (
                            `{$CubeName}_id` INT NOT NULL AUTO_INCREMENT,
                            ".implode(", ",$dirfield).",
                            `value` INT DEFAULT NULL,
                            ".((count($Out) > 0)? implode(", ", $Out).",":"")."
                            CONSTRAINT ".$CubeName."_pkey PRIMARY KEY(".$CubeName."_id),
                            
                            UNIQUE KEY `unq_$CubeName` (".$CubeName."_".implode(", ".$CubeName."_", $One)."),
                            ".implode(", ", $fk).",
                            ".implode(", ", $constr)."
                            ) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='".json_encode(array('name' => $Name))."'";

                $this->db->query($query);

                // многий ко многим
                if(!empty($Many))
                    foreach($Many as $dir){
                        $query = "CREATE TABLE {$CubeName}_{$dir} (
                                    `{$CubeName}_{$dir}_id` INT NOT NULL AUTO_INCREMENT,
                                    {$CubeName}_{$dir} INT NOT NULL,
                                    {$dir}_{$CubeName} INT NOT NULL,
                                    PRIMARY KEY (`{$CubeName}_{$dir}_id`),
                                    UNIQUE KEY `unq_{$CubeName}_{$dir}` ({$CubeName}_{$dir}, {$dir}_{$CubeName}),
                                    KEY `FK_{$CubeName}_{$dir}` (`".$CubeName."_".$dir."`),
                                    KEY `FK_{$dir}_{$CubeName}` (`".$dir."_".$CubeName."`),
                                    CONSTRAINT `FK_{$CubeName}_{$dir}` FOREIGN KEY (`".$CubeName."_".$dir."`) REFERENCES `".$CubeName."` (`id_$CubeName`) ON DELETE CASCADE ON UPDATE CASCADE,
                                    CONSTRAINT `FK_{$dir}_{$CubeName}` FOREIGN KEY (`".$dir."_".$CubeName."`) REFERENCES `".$dir."` (`{$this->keyTable($dir)}`) ON DELETE RESTRICT ON UPDATE CASCADE
                                ) ENGINE=INNODB DEFAULT CHARSET=utf8
                        ";  // id_$dir

                        $this->db->query($query);
                    }
            }
            elseif($this->db->dbdriver == 'postgre'){ // нет многий ко многим
                
                foreach($Out as $key => &$o)
                    if($o != "")
                        $o = $CubeName."_".$this->translit($o)." INTEGER";
                    else
                        unset($Out[$key]);

                $query = "CREATE TABLE `$CubeName` (
                            `{$CubeName}_id` SERIAL,
                            ".implode(", ",$dirfield).",
                            `value` MONEY DEFAULT NULL,
                            ".((count($Out) > 0)? implode(", ", $Out).",":"")."
                            PRIMARY KEY  (`{$CubeName}_id`),
                            UNIQUE KEY `unq_$CubeName` (".$CubeName."_".implode(", ".$CubeName."_", $One)."),
                            ".implode(", ", $fk).",
                            ".implode(", ", $constr)."
                            ) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='".json_encode(array('name' => $Name))."'";

                $this->db->query($query);
            }
        }
    }
    
    public function select_dir(){ // прикрепить права
	return $this->db->query("
            SHOW TABLE STATUS
            WHERE NAME NOT IN (
                SELECT DISTINCT TABLE_NAME
                FROM `information_schema`.`KEY_COLUMN_USAGE`
                WHERE REFERENCED_TABLE_NAME IS NOT NULL
                    AND REFERENCED_TABLE_NAME <> TABLE_NAME)
        ")->result_array();
    }
    
    function select_cube(){ // прикрепить права
        return $this->db->query("
            SELECT DISTINCT TABLE_NAME
            FROM `information_schema`.`KEY_COLUMN_USAGE`
            WHERE REFERENCED_TABLE_NAME IS NOT NULL
            AND REFERENCED_TABLE_NAME <> TABLE_NAME
            AND CONSTRAINT_SCHEMA = '{$this->db->database}'
        ")->result_array();
    }
    
    function keyTable($table){
        $res = $this->db->query("
            SELECT COLUMN_NAME 
            FROM information_schema.`COLUMNS` 
            WHERE table_name = '{$table}' 
                and table_schema = '{$this->db->database}' 
                and column_key = 'PRI'
        ")->row_array();
                
        return $res['COLUMN_NAME'];
    }
    
    function translit($str) {
        $tr = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '',    'ы' => 'y',   'ъ' => '',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
            
            ' ' => '_'
        );
        return strtr($str, $tr);
    }
    
    
    // получение среза
    function cube_slices($cube){
        $c = $this->getInfo($cube);
        $in = array();
        foreach($c['table']['in']['who_table'] as $dir){
            $inf = $this->getInfo($dir);
            $in_inf[$dir]['key'] = $inf['table']['key'];
            $in_inf[$dir]['inf'] = $inf['table']['out'];
            $this->db->select($in_inf[$dir]['key']." as id, concat_ws(' | ', ".implode(', ', array_keys($in_inf[$dir]['inf'])).") as name", false);
            $in[$dir] = $this->db->get($dir)->result_array();
        }
        
        $data['name'] = $cube;
        $data['in'] = $in;
        $data['in_inf'] = $in_inf;
        $data['out'] = $c['table']['out'];

        return $data;
    }
    
    public function table_info($name) {
        
        $info = array();
        $table = array('name' => $name);
        
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

            $comment[$val["column_name"]] = ($val["column_comment"] != "")? $val["column_comment"]: $val["column_name"];

            if(isset($info[$pos]['fk']) && isset($info[$pos]['u'])){
                $table['type'] = 'cube';
                $table['in']['num'][] = $val['ordinal_position'];
                $table['in']['where'][] = $val['column_name'];
                $table['in']['who_table'][] = $val['referenced_table_name'];
                $table['in']['who_column'][] = $val['referenced_column_name'];
                if(isset($table['out'][$val['column_name']])) unset($table['out'][$val['column_name']]);
            }
            elseif(!isset($info[$pos]['key'])/* && !isset($info[$pos]['u'])*/)
                $table['out'][$val['column_name']] = $val['data_type'];
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
    
    
    // *------------------------------------------------------------------------* //
    function getDataCube($cube, &$top, &$left, $fixed, $val){
        $arr = $this->elementsQuery($cube, $top, $left);
        $fix = $this->wherefix($cube, $fixed);
        //$vectors = $this->vectors($cube);

        $qry = "SELECT *, c.".$val." as result
            FROM ".implode(" CROSS JOIN ", $arr['name']);        // array_reverse($arr['name']);
        $qry.= " LEFT JOIN ".$cube." c ON ".implode(" AND ", $arr['on']);
        if(isset($arr['in']) || isset($fix))
            $qry.= " WHERE ".implode(" AND ", $arr['in']).((isset($arr['in']) && !empty($fix))?" AND ":"").implode(" AND ", $fix);

        $qry.= " ORDER BY id_".implode(", ", $arr['who_column']);

        $res = $this->db->query($qry)->result_array();

        $header = $arr['header'];
        $row = 0;
        $i = 0;
        $arr = array();
        foreach($res as $rec){
            if($i >= $header){
                $row++;
                $i = 0;
            }
            $arr[$row][$i] = $rec['result'];
            $i++;
        }
        
        return array('res' => $arr, 'header' => $header);
    }
    
    function elementsQuery($cube, &$top, &$left){
        $info = $this->getInfo($cube);
        
        $who_column = array();
        $names = array();
        $on = array();
        $in = array();

        $l = array();
        $t = array();

        foreach($left as $dir){
            $dir = (array)$dir;
            $col = array_search($info['table']['in']['who_table'], $dir['name']);
            $name = $info['table']['in']['where'][$col];
            $names[] = $dir['name'];
            $who_column[] = $info['table']['in']['who_column'][$col];
            $on[]= " c.".$name." = ".$dir['name'].".".$info['table']['in']['who_column'][$col];
            
            if(isset($dir['value'])){
                $this->db->where_in($info['table']['in']['who_column'][$col], $dir['value']);
                $in[] = $dir['name'].".".$info['table']['in']['who_column'][$col]." IN (".implode(", ", $dir['value']).")";
            }

            // для рисовки
            $res = $this->db->get($dir['name'])->result_array();
            $l[count($l)] = array();
            foreach($res as $val){
                unset($val[$info['table']['in']['who_column'][$col]]);
                $l[count($l) - 1][] = implode(" | ", $val);                     // убрать -1 ??
            }
        }
        $left = $l;

        $header = 1;
        foreach($top as $key => $dir){
            $dir = (array)$dir;
            $col = array_search($info['table']['in']['who_table'], $dir['name']);
            $name = $info['table']['in']['where'][$col];
            $names[] = $dir['name'];
            $who_column[] = $info['table']['in']['who_column'][$col];
            $on[]= " c.".$name." = ".$dir['name'].".".$info['table']['in']['who_column'][$col];
            if(isset($dir['value'])){
                $this->db->where_in($info['table']['in']['who_column'][$col], $dir['value']);
                $in[] = $dir['name'].".".$info['table']['in']['who_column'][$col]." IN (".implode(", ", $dir['value']).")";
            }

            // для рисовки
            $header*= (isset($dir['value']))?count($dir['value']): $this->db->count_all($dir['name']);

            $res = $this->db->get($dir['name'])->result_array();
            $t[count($t)] = array();
            foreach($res as $val){
                unset($val[$info['table']['in']['who_column'][$col]]);
                $t[count($t) - 1][] = $implode(" | ", $val);                    // убрать -1 ??
            }
        }
        $top = $t;

        return array('name' => $names, 'who_column' => $who_column, 'on' => $on, 'in' => $in, 'header' => $header);
    }

    function wherefix($cube, $fixed){
        $info = $this->getInfo($cube);
        $arr = array();
        foreach($fixed as $dir){
            $col = array_search($info['table']['in']['who_table'], $dir['name']);
            $dir = (array)$dir;
            $arr[]= $info['table']['in']['where'][$col]." = ".$dir['value'][0];
        }

        return $arr;
    }

    // получение графа
    function fill($tbl_name){
        $matrix = array();
        $res = $this->getReferenced($tbl_name);

        foreach($res as $val){
            $matrix[$tbl_name][$val['REFERENCED_TABLE_NAME']] = array($val['COLUMN_NAME'] => $val['REFERENCED_COLUMN_NAME']);
            if(!array_key_exists($val['REFERENCED_TABLE_NAME'], $matrix)) // !isset($val['REFERENCED_TABLE_NAME']) - возможно не так круто, но возможно быстрее
                $this->fill($val['REFERENCED_TABLE_NAME']);
        }
        
        return $matrix;
    }

    function getReferenced($tbl_name){
        $this->db->select('COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME');
        $this->db->where("referenced_table_name IS NOT NULL");
        return $this->db->get_where('information_schema.KEY_COLUMN_USAGE', array(
                'table_schema' => $this->db->database,
                'table_name' => $tbl_name
            )
        )->result_array();
    }
    */
}