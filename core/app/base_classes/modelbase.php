<?php

namespace Core\Base;

use DB;
use Core\App\Error;
use Core\App\Helpers\Dates;

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

    protected $__select = [];

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
        $fields = $this->fields;
        if(isset($fields['json_config'])) unset($fields['json_config']);
        foreach($fields as $name => $value){
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
        $select = static::cache_get([ $field => $id ]);
        if(!$select){
            $fromc = false;
            DB::logger();
            $select = DB::_select('SELECT * FROM ' . static::$_table . ' WHERE ' . $field . ' = ?',[$id],[0]);
        }
        if(!isset($select['error'])){
            if(!$fromc) static::cache_store($select);
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
        $deleted = DB::delete(static::$_table,[
            'id' => $this->id,
        ]);
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
        if(!isset(self::$__cache[static::class])) return false;
        $_cvars = self::$__cache[static::class];

        $ckey = false;
        $found = false;
        $statuses = [];
        foreach($_cvars as $key => $cache){
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
        if($ckey !== false && isset($_cvars[$ckey])){
            if(!$wk) return $_cvars[$ckey];
            return [ 'key' => $ckey, 'obj' => $_cvars[$ckey] ];
        }

        return false;
    }

    private static function cache_store($all_field){
        self::$__cache[static::class][] = $all_field;
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

    /**
     * @return string Database table pointed to by the model
     */
    public static function getDBTable():?string {
        return static::$_table;
    }

    /**
     * @param ?array $where Specify the required fields to select (default=*)
     * @param int $limit Specify the select limit (default=unset[NULL])
     * @param int $offset Specify the select offset (default=unset[NULL])
     * @return ?array All the record from the database
     */
    public static function all(?array $where = NULL, int $limit = NULL, int $offset = NULL):?array {
        $data = DB::select('*', static::$_table, $where, 'id DESC', '0', $limit, $offset);
        if(!$data['errors']) return $data;
        return NULL;
    }

    /**
     * @param string|array $fields Specify the required fields to select (default=*)
     * @return self
     */
    public static function select(string|array $fields = '*') {
        $self = new static;
        $pushfield = '';

        if(is_array($fields)){
            foreach($fields as $key => $field) {
                $pushfield .= $field;
                if(array_key_last($fields) != $key) $pushfield .= ', ';
            }
        } else {
            $pushfield = $fields;
        }

        $self->__select['fields'] = $pushfield;
        return $self;
    }

    /**
     * @param array $where And statement, like: [ 'id' => [1,2,3], 'name' => 'test' ]
     * @return self
     */
    public function where(array $where = []) {
        $this->__select['where'] = $where;
        return $this;
    }

    /**
     * @param array $or Or statement, like: [ 'id' => [1,2,3], 'name' => 'test' ]
     * @return self
     */
    public function or(array $or = []) {
        $this->__select['or'] = $or;
        return $this;
    }

    /**
     * @param int $offset Sets the select offset (default=1)
     * @return self
     */
    public function offset(int $offset = 1) {
        $this->__select['offset'] = $offset;
        return $this;
    }

    /**
     * @param int $limit Sets the select limit (default=1)
     * @return self
     */
    public function limit(int $limit = 1) {
        $this->__select['limit'] = $limit;
        return $this;
    }

    /**
     * @param string $by Sets the select to desc by this field (default=id)
     * @return self
     */
    public function desc(string $by = 'id') {
        $this->__select['orderby'] = $by . ' DESC';
        return $this;
    }

    /**
     * @param string $by Sets the select to asc by this field (default=id)
     * @return self
     */
    public function asc(string $by = 'id') {
        $this->__select['orderby'] = $by . ' ASC';
        return $this;
    }

    //TODO with multiple models
    public function with(self $model, string $self_col, string $foreign_col = 'id') {
        $this->__select['with'] = $model::$_table;
        $this->__select['withModel'] = $model;
        $this->__select['with_config'] = [ $self_col, $foreign_col ];
        return $this;
    }

    // FIXME currently not working
    public function withWhere(array $where) {
        $this->__select['withWhere'] = $where;
        return $this;
    }

    /**
     * @return self (returns the first record from the database)
     */
    public function first(){
        $this->__select['limit'] = 1;
        $this->__select['firstval'] = true;
        $this->asc();
        return $this->get();
    }

    /**
     * @return self (returns the latest record from the database)
     */
    public function latest(){
        $this->__select['limit'] = 1;
        $this->__select['firstval'] = true;
        $this->desc();
        return $this->get();
    }

    public static function select_query($query, $select = '*', $only_exists = false){
        $q = 'SELECT ' . $select . ' FROM ' . static::$_table . ' ' . $query;
        $exec = DB::query($q);
        if(isset($exec['error']) || empty($exec)){
            return false;
        }
        if($only_exists) return true;
        return $exec;
    }

    /**
     * @return array Execute all the setted select config models
     */
    public function get($with_fast_var = true){
        $config = $this->__select;
        $binding = [];

        $select = $config['fields'] ?: '*';

        $firstval = isset($config['firstval']);

        $from = static::$_table;

        $addbfr = '';

        $where_raw = $config['where'] ?: NULL;
        $or_raw = $config['or'] ?: NULL;

        if($where_raw != NULL || $or_raw != NULL){
            $addbfr .= ' WHERE ';
        }

        $where = '';
        if($where_raw){
            foreach($where_raw as $field => $data){
                if(!is_array($data)){
                    $where .= ' ' . $field . ' = ?';
                    if($field !== array_key_last($where_raw)) $where .= ' AND';
                    $binding[] = $data;
                } else {
                    foreach($data as $k => $val){
                        $where .= ' ' . $field . ' = ?';
                        if($k !== array_key_last($data)) $where .= ' AND';
                        $binding[] = $val;
                    }
                }
                
            }
        }
        
        $or = '';
        if($or_raw){
            if($where != '') $or .= ' OR';
            foreach($or_raw as $field => $data){
                if(!is_array($data)){
                    $or .= ' ' . $field . ' = ?';
                    if($field !== array_key_last($or_raw)) $or .= ' AND';
                    $binding[] = $data;
                } else {
                    foreach($data as $k => $val){
                        $or .= ' ' . $field . ' = ?';
                        if($k !== array_key_last($data)) $or .= ' OR';
                        $binding[] = $val;
                    }
                }
                
            }
        }

        $query = "SELECT {$select} FROM {$from} {$addbfr} {$where} {$or}";
        while(str_contains($query ,'  ')) $query = str_replace('  ', ' ', $query);
        if(str_ends_with($query, ' ')) $query = substr($query, 0, -1);

        if(isset($config['orderby'])){
            $query .= ' ORDER BY ' . $config['orderby'];
        }

        if(isset($config['limit'])){
            $query .= ' LIMIT ' . $config['limit'];
        }

        $return_key = NULL;
        if($firstval) $return_key = [0];
        $data = DB::_select($query, $binding, $return_key);
        
        if(isset($data['error'])) return NULL;
        $models = [];
        if(!$firstval){
            foreach($data as $d){
                static::cache_store($d);
                $models[] = new static($d['id'], 'id');
            }
        } else {
            static::cache_store($data);
            $models = new static($data['id'], 'id');
        }

        if(isset($config['with']) && isset($config['with_config'])){
            $key1 = $config['with_config'][0];
            $key2 = $config['with_config'][1];

            $ids = array_unique(array_column($data, $key1));

            $relation = $config['withModel'];

            $relation::select('*')->or([$key2 => $ids])->get();

            if(!$firstval){
                foreach($models as $key => $model){
                    $model_data = new $relation($model->{$key1}, $key2);
                    if($with_fast_var) $models[$key]->{$relation::$_table} = $model_data;
                    if($with_fast_var) $models[$key]->all_fields[$relation::$_table] = $model_data;
                    $models[$key]->fields[$relation::$_table] = $model_data->fields;
                }
            } else {
                $model_data = new $relation($models->{$key1}, $key2);
                if($with_fast_var) $models->{$relation::$_table} = $model_data;
                if($with_fast_var) $models->all_fields[$relation::$_table] = $model_data;
                $models->fields[$relation::$_table] = $model_data->fields;
            }

        }

        if(!$with_fast_var){
            if(!$firstval){
                $ms = [];
                foreach($models as $i => $model){
                    $ms[$i] = $model->fields;
                }
                return $ms;
            } else {
                return $models->fields;
            }
        }

        return $models;

    }

}