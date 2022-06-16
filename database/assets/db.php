<?php

class DB {
    public static $PDO;
    public static $connected = false;
    private static $errorFile = '/database/errors/error.txt';

    public static function createConnection($config){
        if($config['createconnection']){
            try {
                self::$PDO = new PDO("mysql:host=$config[host];port=$config[port];dbname=$config[dbname]", "$config[user]", "$config[password]");
                self::$PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //unlink(ROOT . self::$errorFile);
            } catch(Exception $e){
                display_error($e);
                exit;
            }
            self::$connected = true;
        }
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
            $insert = $sql->execute();
            return $insert;
        }
        catch(Exception $e){
            return array("error"=>$e->getMessage());
        }
    }

    public static function update($table,$set_array,$where,$wval = NULL){
        $set = array_key_first($set_array);
        $val = $set_array[$set];
        $run = "UPDATE $table SET $set = :$set WHERE ";
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
        $sql->bindValue(":$set",$val);
        if(!is_array($where) && $wval != NULL){
            $sql->bindValue(":$where",$wval);
        } else {
            foreach($where as $i => $w){
                $sql->bindValue(":$i",$w);
            }
        }
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
            return $u;
        } catch(Exception $e){
            return array("error"=>$e->getMessage());
        }
    }

    public static function _select($sql,array $value_array = NULL,array $return_keys = NULL){
        $sql = self::$PDO->prepare($sql);
        if($value_array){
            foreach($value_array as $i => $value){
                $sql->bindValue($i+=1,$value);
            }
        }
        try {
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

}