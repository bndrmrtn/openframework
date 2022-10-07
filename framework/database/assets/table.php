<?php

class DB_TABLE extends SQL {

    private $table = '';
    private $vals = [];

    public function __construct($name)
    {
        $this->table = $name;
        return $this;
    }

    public function col($name,$type,$len = NULL,$nullable = false,$ai = false,$default = NULL){
        $this->vals['columns'][$name] = [
            'type' => $type,
            'len' => $len,
            'nullable' => $nullable,
            'ai' => $ai,
            'defa' => $default
        ];
        return $this;
    }

    public function foreignCol($name,$type,$len = 255,$ftable,$fcol = 'id'){
        $this->vals['columns'][$name] = [
            'type' => $type,
            'len' => $len,
            'foreign_table' => $ftable,
            'foreign_column' => $fcol,
        ];
        return $this;
    }

    public function bool($name,bool $default = false){
        if($default) $default = 1; else $default = 0;
        return $this->col($name,'int',1,false,false,$default);
    }

    public function createdAt(){
        if(!in_array('date', array_keys($this->vals))){
            return $this->col('date', 'datetime');
        }
        throw new Exception('Date already added to this table');
    }

    public function setPrimaryKey($name){
        if(isset($this->vals['columns'][$name])){
            $this->vals['columns'][$name]['primary'] = true;
        } else {
            echo 'Invalid column given for primary key';
            exit;
        }
        return $this;
    }

    public function save(){
        $primary = false;
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->table}` (\n";
        foreach($this->vals['columns'] as $name => $column){
            $col = "`$name` " . strtoupper($column['type']);
            if(!isset($column['foreign_table']) || !isset($column['foreign_column'])){
                if($column['len']){
                    $col .= '(' . $column['len'] . ')';
                }
                if(!is_null($column['defa'])){
                    $col .= ' DEFAULT ' . self::strWithCommas($column['defa']);
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
            } else {
                if($column['len']){
                    $col .= '(' . $column['len'] . ')';
                }
                $col .= ",\n";
                $col .= "FOREIGN KEY (`$name`) REFERENCES `" . $column['foreign_table'] . "` (`" . $column['foreign_column'] . "`)";
                $col .= ',' . "\n";
                $sql .= $col;
            }
        }
        if($primary){
            $sql .= 'PRIMARY KEY (`' . $primary . '`)';
        }
        $sql .= ')';
        $sql .= "\n";
        $sql .= 'CHARACTER SET ' . self::$charset['charset'] . ' COLLATE ' . self::$charset['collate'];
        var_dump($sql);echo "\n";
        return static::exec($sql);
    }
    
    private static function strWithCommas($str){
        if(is_string($str)) return "'$str'";
        return $str;
    }


}