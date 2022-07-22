<?php

class SQL extends DB {

    protected static $charset = [
        'charset' => 'utf8',
        'collate' => 'utf8_general_ci',
    ];

    public static function table($name){
        return self::create('table',$name);
    }

    private static function create($action,$data){
        if($action == 'table') return new DB_TABLE($data);
    }

}