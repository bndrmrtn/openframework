<?php

/**
 * The file name starts with . 
 * to boot before the other classes
 */

namespace Core\App\Security;

use Core\App\Error;
use Core\Base\Base;

class Csrf extends Base {
    
    protected static $token;
    protected static $request_valid = false;

    public static function boot():void {
        if(_env('USE_CSRF')){
            $token = randomString(100,true,true,true,true);
            if(isset($_COOKIE['csrftoken'])){
                if($_COOKIE['csrftoken'] === html_entity_decode(post()['csrf-token'])) self::$request_valid = true;
            }
            setcookie('csrftoken',$token, time() + (86400 * 30), url('/'));
            self::$token = $token;
        }
    }

    public static function is_valid(){
        return self::$request_valid;
    }

    public static function token(){
        return self::$token;
    }

    public static function tokenInput(){
        return '<input type="hidden" name="csrf-token" value="' . htmlentities(self::$token) . '">';
    }

    public static function autoExit(){
        if(!self::$request_valid) Error::Custom('Bad Request','Invalid CSRF Token passed',400);
    }

}