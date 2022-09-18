<?php

namespace Framework\Base;

use DB;
use Framework\App\Error;
use Framework\App\Helpers\Dates;


abstract class ModelBase extends Base {

    protected array $all_field = [];
    protected static array $_config = [];
    protected static string $_table;
    public bool $exists = false;

    public function update(){
        $userId = $this->all_field['id'];
        if(!$userId) return false;

        $conf_fields = !isset(static::$_config['writable']) ? static::$_config['fields'] : $fields = static::$_config['writable'];

        foreach($this->fields as $name => $value){
            if(in_array($name,$conf_fields)){
                $new_val = $this->all_field[$name];
                
                if($this->all_field[$name] != $value){
                    $new_val = $value;
                } else if($this->all_field[$name] != $this->{$name}){
                    $new_val = $this->{$name};
                }

                $this->all_field[$name] = $new_val;
            }
        }
        unset($this->all_field['id']);
        $update = DB::update(static::$_table,$this->all_field,'id',$userId);
        $success = !isset($update['error']);

        $this->find($userId);

        return $success;
    }

    public function find($id, $field = 'id',$fail = false){
        $select = DB::_select('SELECT * FROM ' . static::$_table . ' WHERE ' . $field . ' = ?',[$id],[0]);
        if(!isset($select['error'])){
            $this->all_field = $select;
            foreach($select as $field_name => $value){
                if(in_array($field_name, static::$_config['readable'])){
                    $this->fields[$field_name] = $value;
                    $this->{$field_name} = $value;
                }
            }
            $this->exists = true;
            return $this;
        }
        if(!$fail) return $this;
        Error::NotFound();
    }

    public function delete(){
        $deleted = DB::delete(static::$_table,$this->all_field);
        $this->exists = !$deleted;
        return $deleted;
    }

    public static function create($data){
        $created = [];

        $fields = !isset(static::$_config['writable']) ? static::$_config['fields'] : $fields = static::$_config['writable'];

        foreach($fields as $field){
            if(isset($data[$field])) $created[$field] = $data[$field];
        }

        if(!isset($fields['fields']['date'])) $created['date'] = Dates::now(true);

        $i = DB::insert(static::$_table,$created);
        if(!isset($i['errors'])) return true;

        return false;
    }

}