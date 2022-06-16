<?php

class CSRF {
    private static $token = '';

    public static function create(){
        $token = randomString(100);
        unset($_COOKIE['csrftoken']);
        setcookie('csrftoken', null, -1, '/');
        setcookie('csrftoken',$token,time() + (86400 * 30),'/');
        self::$token = $token;
    }

    public static function verify(){
        if($_POST['anti-csrf-token'] == $_COOKIE['csrftoken']){
            return true;
        }
        return false;
    }

    public static function token(){
        return '<input type="hidden" name="anti-csrf-token" value="'.self::$token.'">' . "\n";
    }

}