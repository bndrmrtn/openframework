<?php

/**
 * The file name starts with . 
 * to boot before the other classes
 */

namespace Core\App;

use Core\Base\Base;
use Core\Framework\Framework;

class Session extends Base {

    private static array $oneReqDatas = [];
    private static string $singlkey = 'single-requested-data-storage';
    public static string $id;

    public static function boot():void {
        if(_env('USE_SESSION') && Framework::isWeb()){
            $url_array = parse_url(BASE_URL);
            $url = $url_array['host'];
            if(isset($url['port'])){
                $url . ':' . $url['port'];
            }
            session_set_cookie_params(0, '/', $url, $url_array['scheme'] == 'https', true);
            
            $id = self::getId();
            session_id($id);
            session_start();
        }
        self::checkSingles();
    }

    private static function headerBearer():string|false {
        if(isset(headers()['Authorization'])){
            $auth = headers()['Authorization'];
            if(str_starts_with($auth, 'Bearer') && str_contains($auth, ' ')){
                $e = explode(' ', $auth);
                $token = $e[array_key_last($e)];
                if(RegEx::is_username($token) && strlen($token) >= 250) return $token;
            }
        }
        return false;
    }

    public static function getId():string {
        if($bearer = self::headerBearer()){
            $id = $bearer;
        } else if(isset($_COOKIE[ini_get('session.name')])){
            $id = $_COOKIE[ini_get('session.name')];
        } else {
            $id = randomString(250);
        }
        return $id;
    }

    public static function destroy():void {
        $_SESSION = [];
        self::$oneReqDatas = [];
        session_destroy(self::$id);
    }

    public static function all(){
        return array_merge($_SESSION, self::$oneReqDatas);
    }

    public static function get($key){
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        } else if(isset(self::$oneReqDatas[$key])){
            return self::$oneReqDatas[$key];
        }
        return NULL;
    }

    public static function set($key, $value):void {
        $_SESSION[$key] = $value;
    }

    public static function SingleUse($key, $val){
        if(!isset($_SESSION[self::$singlkey])){
            $_SESSION[self::$singlkey] = [];
        }
        $_SESSION[self::$singlkey][$key] = $val;
    }

    private static function checkSingles(){
        if(isset($_SESSION[self::$singlkey])){
            foreach($_SESSION[self::$singlkey] as $key => $val){
                self::$oneReqDatas[$key] = $val;
            }
            unset($_SESSION[self::$singlkey]);
        }
    }

}