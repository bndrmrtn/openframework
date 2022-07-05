<?php

class SQL extends DB {

    private $table = '';
    private $vals = [];
    private $charset = [
        'charset' => 'utf8',
        'collate' => 'utf8_general_ci',
    ];

    public static function table($name){
        $sql = new SQL;
        $sql->create('table',$name);
        return $sql;
    }

    private function create($action,$data){
        if($action == 'table') return $this->createTable($data);
    }

    private function createTable($name){
        $this->table = $name;
        return $this;
    }

    public function tableColumn($name,$type,$len = NULL,$nullable = false,$ai = false){
        $this->vals['columns'][$name] = [
            'type' => $type,
            'len' => $len,
            'nullable' => $nullable,
            'ai' => $ai,
        ];
        return $this;
    }

    public function tableSetPrimaryKey($name){
        if(isset($this->vals['columns'][$name])){
            $this->vals['columns'][$name]['primary'] = true;
        } else {
            echo 'Invalid column given for primary key';
            exit;
        }
        return $this;
    }

    public function saveTable(){
        $primary = false;
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->table}` (\n";
        foreach($this->vals['columns'] as $name => $column){
            $col = "`$name` " . strtoupper($column['type']);
            if($column['len']){
                $col .= '(' . $column['len'] . ')';
            }
            if($column['ai']){
                $col .= ' AUTO_INCREMENT';
            }
            if(!$column['nullable']){
                $col .= ' NOT NULL';
            }
            $col .= ',' . "\n";
            $sql .= $col;
            if(isset($column['primary']) && $column['primary']){
                $primary = $name;
            }
        }
        if($primary){
            $sql .= 'PRIMARY KEY (`' . $primary . '`)';
        }
        $sql .= ')';
        $sql .= "\n";
        $sql .= 'CHARACTER SET ' . $this->charset['charset'] . ' COLLATE ' . $this->charset['collate'];
        return static::exec($sql);
    }
    



}