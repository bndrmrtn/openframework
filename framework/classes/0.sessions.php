<?php

/**
 * The file name starts with . 
 * to boot before the other classes
 */

namespace Framework\App;

use Framework\Base\Base;

class Session extends Base {

    private static array $oneReqDatas = [];
    private static string $ordkey = 'only-request-data-storage';

    public static function boot() {
        $url_array = parse_url(BASE_URL);
        $url = $url_array['host'];
        if(isset($url['port'])){
            $url . ':' . $url['port'];
        }
        session_set_cookie_params(0, '/', $url, $url_array['scheme'] == 'https', true);
        if(_env('USE_SESSION')){
            session_id(self::getId());
            session_start();
        }
        self::checkoneReqDs();
    }

    public static function getId():string {
        if(isset($_COOKIE[ini_get('session.name')])){
            $id = $_COOKIE[ini_get('session.name')];
        } else {
            $id = randomString(250);
        }
        return $id;
    }

    public static function destroy():void {
        $_SESSION = [];
        self::$oneReqDatas = [];
        session_destroy(self::getId());
    }

    public static function all(){
        return array_merge($_SESSION, self::$oneReqDatas);
    }

    public static function oneReqData($key, $val){
        if(!isset($_SESSION[self::$ordkey])){
            $_SESSION[self::$ordkey] = [];
        }
        $_SESSION[self::$ordkey][$key] = $val;
    }

    private static function checkoneReqDs(){
        if(isset($_SESSION[self::$ordkey])){
            foreach($_SESSION[self::$ordkey] as $key => $val){
                self::$oneReqDatas[$key] = $val;
            }
            unset($_SESSION[self::$ordkey]);
        }
    }

}