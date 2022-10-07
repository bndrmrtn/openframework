<?php

namespace Framework\Base;

use DB;
use Framework\App\Error;
use Framework\App\Helpers\Dates;


abstract class ModelBase extends Base {

    /** @var array $__cache All the queryd data will stored there so it accessable without a new db query */
    private static array $__cache = [];

    /** @var array $all_field All the fields that the table has */
    protected array $all_field = [];

    /** @var array $_config The config for the fields, readable and writable properties */
    protected static array $_config = [];

    /** @var string $_table The database table name that the model uses */
    protected static string $_table;

    /** @var bool $exists Returns that the current row exists in the database or not */
    public bool $exists = false;

    /**
     * @param string|int|null $search The field data to search, like: id: 5, or name: Test
     * @param string $findBy Search the value in this table field
     * @param bool $fail Auto create an error if the specified row not found
     * @return self $this Returns a clean Model or a one with the database data
     */
    public function __construct(string|int|null $search = NULL, string $findBy = 'id', bool $fail = false) {
        if(is_null($search)) return $this;
        return $this->find($search,$findBy, $fail);
    }

    /**
     * @return bool Is updated or failed
     */
    public function update():bool {
        $userId = $this->all_field['id'];
        if(!$userId) return false;

        $conf_fields = !isset(static::$_config['writable']) ? static::$_config['fields'] : static::$_config['writable'];

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
        if($cache = self::cache_get(['id' => $userId], true)){
            self::$__cache[$cache['key']] = array_merge(['id' => $userId], $this->all_field);
        }

        $this->find($userId);

        return $success;
    }

    /**
     * @param string|int $search The field data to search, like: id: 5, or name: Test
     * @param string $findBy Search the value in this table field
     * @param bool $fail Auto create an error if the specified row not found
     * @return self $this Returns a model with database data
     */
    public function find(string|int $id, string $field = 'id', bool $fail = false):self {
        $fromc = true;
        $select = self::cache_get([ $field => $id ]);
        if(!$select){
            $fromc = false;
            $select = DB::_select('SELECT * FROM ' . static::$_table . ' WHERE ' . $field . ' = ?',[$id],[0]);
        }
        if(!isset($select['error'])){
            if($fromc) self::cache_store($select);
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

    /**
     * @return bool Returns if successfully deleted or not
     */
    public function delete():bool {
        $deleted = DB::delete(static::$_table,$this->all_field);
        if($key = self::cache_get($this->all_field, true)){
            unset(self::$__cache[$key['key']]);
        }
        $this->exists = !$deleted;
        return $deleted;
    }

    /**
     * @param array $data The data in an array for the model
     * @param bool $auto_retrieve Auto retrieve the inserted data
     * @return bool|array Return the inserted model data or false if failed to insert
     */
    public static function create(array $data, bool $auto_retrieve = false):bool|array {
        $created = [];

        $fields = !isset(static::$_config['writable']) ? static::$_config['fields'] : $fields = static::$_config['writable'];

        foreach($fields as $field){
            if(isset($data[$field])) $created[$field] = $data[$field];
        }

        if(in_array('date', static::$_config['fields']) && in_array('date', $fields) && !isset($created['date'])) $created['date'] = Dates::now(true);


        $i = DB::insert(static::$_table,$created);

        if($auto_retrieve) $new_created = DB::select('*', static::$_table, $created,NULL,'0');

        if(!$new_created || isset($new_created['error'])){
            $created = array_merge(['id' => '<id>'], $created);
        } else {
            $created = $new_created;
        }

        if(!isset($i['errors'])){
            self::cache_store($created);
            return $created;
        }

        return false;
    }

    private static function cache_get($search, $wk = false){
        if(empty(self::$__cache)) return false;
        $ckey = false;
        $found = false;
        $statuses = [];
        foreach(self::$__cache as $key => $cache){
            if(!$found){
                foreach($search as $field => $value){
                    if($cache[$field] == $value){
                        $statuses[] = 1;
                    } else {
                        $statuses[] = 0;
                    }
                    if(array_key_last($search) == $field){
                        if(!in_array(0, $statuses)){
                            $found = true;
                            $ckey = $key;
                            break;
                        }
                    }
                }
            }
            $statuses = [];
        }
        if($ckey !== false && isset(self::$__cache[$ckey])){
            if(!$wk) return self::$__cache[$ckey];
            return [ 'key' => $ckey, 'obj' => self::$__cache[$ckey] ];
        }

        return false;
    }

    private static function cache_store($all_field){
        self::$__cache[] = $all_field;
    }

    /**
     * @param int $count = 1
     * @return array|int|bool Returns an ID if the count is 1, otherwise an array of IDs, if something failed, it returns false
     */
    public static function random(int $count = 1):array|int|bool {
        $r = [];
        $random = DB::_select('SELECT * FROM ' . static::$_table . ' ORDER BY RAND() LIMIT ' . $count,[]);
        if(!$random['error']){
            foreach($random as $data){
                self::cache_store($data);
                $r[] = $data['id'];
            }
        } else {
            return false;
        }
        
        if($count == 1){
            return $r[0];
        }
        return $r;
    }

    /**
     * @return ?array Table fields from the database, or NULL
     */
    public static function getRealFields():?array {
        $fields = DB::query('DESCRIBE ' . static::$_table);
        if(!isset($fields['error'])) return $fields;
        return NULL;
    }

}