<?php

class DB {
    
    protected static $PDO;
    protected static $connected = false;
    private static $querys = [];
    private static bool $logger_on = false;

    public static function createConnection($config){
        if($config['createconnection']){
            try {
                self::$PDO = new PDO("mysql:host=$config[host];port=$config[port];dbname=$config[dbname]", "$config[user]", "$config[password]");
                self::$PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(Exception $e){
                display_error($e);
                exit;
            }
            self::$connected = true;
        }
    }

    public static function connected():bool {
        return self::$connected;
    }

    public static function select($select = "*",$from = 'test',$where_array = NULL,$order = NULL,$array_key = NULL,$limit = NULL,$offset = NULL,array $like = NULL,array $or = NULL){
        if(self::$connected){
            if($where_array != NULL){
                $w = " WHERE ";
                foreach($where_array as $i => $wh){
                    if($i != array_key_last($where_array)){
                        $w .= "$i = :$i AND ";
                    } else {
                        $w .= "$i = :$i";
                    }
                }
            } else {
                $w = "";
            }
            $orsel = "";
            if($or != NULL){
                foreach($or as $i => $o){
                    $w .= " OR $i = :$i";
                }
            }
    
            if($order != NULL){
                $order = " ORDER BY ".$order;
            } else {
                $order = "";
            }
    
            if($limit != NULL){
                $limit = " LIMIT ".$limit;
            } else {
                $limit = "";
            }
            if($offset != NULL){
                $offset = " OFFSET ".$offset;
            } else {
                $offset = "";
            }
    
            if($like != NULL){
                $like_str = " WHERE ";
                foreach($like as $l => $v){
                    if(array_key_last($like) != $l){
                        $like_str .= "$l LIKE '%$v%' OR ";
                    } else {
                        $like_str .= "$l LIKE '%$v%'";
                    }
                }
            } else {
                $like_str = "";
            }
    
            $sql_str = "SELECT $select FROM $from $w $orsel $like_str $order $limit $offset";
            
            $sql = self::$PDO->prepare($sql_str);

            if($where_array != NULL){
                foreach($where_array as $key => $value){
                    $sql->bindValue(":$key",$value);
                }
            }
            if($or != NULL){
                foreach($or as $key => $value){
                    $sql->bindValue(":$key",$value);
                }
            }
            self::log_query($sql_str, [ 'where' => $where_array, 'or' => $or ]);
            try {
                $sql->execute();
            } catch(Exception $e){
                return array("error"=>$e->getMessage());
            }
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            if($array_key != NULL){
                if(isset($data[$array_key])){
                    return $data[$array_key];
                } else {
                    return array("error"=>"key_error");
                }
            } else {
                return $data;
            }
        } else {
            return array("error"=>"DB not connected");
        }
    }

    public static function insert($into,$datas){
        $data = "";
        $values = "";
        foreach($datas as $i => $d){
            if($i != array_key_last($datas)){
                $data .= "$i, ";
                $values .= ":$i, ";
            } else {
                $data .= "$i";
                $values .= ":$i";
            }
        }
        $sql_str = "INSERT INTO $into ($data) VALUES ($values)";
        $sql = self::$PDO->prepare($sql_str);
        foreach($datas as $key => $value){
            $sql->bindValue(":$key",$value);
        }
        try {
            self::log_query($sql_str, $datas);
            $insert = $sql->execute();
            return $insert;
        }
        catch(Exception $e){
            return array("error"=>$e->getMessage());
        }
    }

    public static function update($table,$set_array,$where,$wval = NULL){
        //$set = array_key_first($set_array);
        //$val = $set_array[$set];
        //$run = "UPDATE $table SET $set = :$set WHERE ";
        $sets = '';
        foreach($set_array as $key => $val){
            if(array_key_last($set_array) != $key){
                $sets .= " $key = :$key,";
            } else {
                $sets .= " $key = :$key";
            }
        }
        $run = 'UPDATE ' . $table . ' SET ' . $sets . ' WHERE ';
        if(!is_array($where) && $wval != NULL){
            $run .= "$where = :$where";
        } else {
            foreach($where as $i => $w){
                if($i != array_key_last($where)){
                    $run .= "$i = :$i AND ";
                } else {
                    $run .= "$i = :$i";
                }
            }
        }
        $sql = self::$PDO->prepare($run);
        foreach($set_array as $key => $val){
            $sql->bindValue(":$key",$val);
        }
        if(!is_array($where) && $wval != NULL){
            $sql->bindValue(":$where",$wval);
        } else {
            foreach($where as $i => $w){
                $sql->bindValue(":$i",$w);
            }
        }
        self::log_query($run, ['set' => $set_array, 'where' => $where ]);
        try {
            $u = $sql->execute();
            return $u;
        } catch(Exception $e){
            return array("error"=>$e->getMessage());
        }
    }


    public static function delete($table,$where,$wval = NULL){
        $run = "DELETE FROM $table WHERE ";
        if(!is_array($where) && $wval != NULL){
            $run .= "$where = :$where";
        } else {
            foreach($where as $i => $w){
                if($i != array_key_last($where)){
                    $run .= "$i = :$i AND ";
                } else {
                    $run .= "$i = :$i";
                }
            }
        }
        $sql = self::$PDO->prepare($run);
        if(!is_array($where) && $wval != NULL){
            $sql->bindValue(":$where",$wval);
        } else {
            foreach($where as $i => $w){
                $sql->bindValue(":$i",$w);
            }
        }
        try {
            $u = $sql->execute();
            self::log_query($run, $where);
            return $u;
        } catch(Exception $e){
            return array("error"=>$e->getMessage());
        }
    }

    public static function _select($sql_data,array $value_array = NULL,array $return_keys = NULL, $fetch_modes = []){
        $sql = self::$PDO->prepare($sql_data);
        if($value_array){
            foreach($value_array as $i => $value){
                $sql->bindValue($i+=1,$value);
            }
        }
        try {
            self::log_query($sql_data, $value_array);
            $sql->execute();
        } catch(Exception $e){
            return array("error"=>$e->getMessage());
        }
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        if($return_keys){
            $rdata = $data;
            foreach($return_keys as $i){
                if(isset($rdata["$i"])){
                    $rdata = $rdata["$i"];
                } else {
                    return ['error'=>'key_error'];
                }
            }
            return $rdata;
        }
        return $data;
    }

    public static function exec($sql){
        if(!self::$connected) return array("error"=>"DB not connected");
        $exec = self::$PDO->prepare($sql);
        try {
            $data = $exec->execute();
            self::log_query($sql);
            return $data;
        } catch(Exception $e){
            dd($e);
            display_error($e);
        }
    }

    public static function query($sql){
        if(!self::$connected) return array("error"=>"DB not connected");
        $exec = self::$PDO->query($sql);
        try {
            $data = $exec->fetchAll(PDO::FETCH_COLUMN);;
            self::log_query($sql);
            return $data;
        } catch(Exception $e){
            return array("error" => $e->getMessage());
            display_error($e);
        }
    }

    public static function exists(string $table,array $where){
        if(!self::$connected) return array("error"=>"DB not connected");
        $count = self::select('COUNT(*) as total',$table,$where,NULL,NULL)[0]['total'];
        return $count > 0;
    }

    public static function all(string $table,array $where = []){
        return self::select('*',$table,$where);
    }

    private static function log_query($query, $binds = NULL){
        if(self::$logger_on){
            self::$querys[] = new DB\Log($query, $binds);
        }
    }

    public static function logger(bool $on = true){
        if(!$on) self::$querys = [];
        self::$logger_on = $on;
    }

    public static function get_querys(){
        $q = self::$querys;
        self::$querys = [];
        return $q;
    }

}