<?php

class Auth {
    private static $user = [];

    public static function makeLogin(){
        if(isset($_COOKIE['token'])){
            $token = regex::escape($_COOKIE['token']);
            $select = DB::_select('SELECT * FROM admin_users WHERE token = ?',[$token],[0]);
            if(!isset($select['error'])){
                self::$user = $select;
                return [
                    'loggedin'=>true,
                    'user'=>$select,
                ];
            }
            return ['loggedin'=>false];
        }
        return ['loggedin'=>false];
    }

    public static function userdata(){
        return self::$user;
    }

}