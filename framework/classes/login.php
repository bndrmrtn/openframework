<?php

namespace Framework\App\Auth;

use Framework\App\Auth\Auth;
use Framework\App\Error;
use DB;
use Framework\App\Accounts\User;
use Framework\App\Helpers\Dates;

class Login extends Auth {

    private static $table_name = '__logins';

    public static function save(){
        $uniqueId = self::uniqueId();
        $date = Dates::toString(Dates::addTo(['day' => 5],Dates::now()));
        $i = DB::insert(self::$table_name,[
            'user' => self::user('user'),
            'token' => $uniqueId,
            'useragent' => $_SERVER["HTTP_USER_AGENT"],
            'date' => $date,
        ]);
        if(isset($i['error'])) Error::ServerError();
        return $uniqueId;
    }

    private static function uniqueId(){
        $table = self::$table_name;
        do {
            $token = randomString(100);
        } while(DB::exists($table,['token' => $token]));
        return $token;
    }

    public static function token($token){
        $table = self::$table_name;
        $select = DB::_select("SELECT * FROM $table WHERE token = ? AND date > ? LIMIT 1",[$token,date('Y-m-d H:i:s')],[0]);
        if(isset($select['error'])) return false;
        User::login_user($select['user']);
        return $select;
    }

    protected static function destroy($token){
        DB::delete(self::$table_name,'token',$token);
        return true;
    }

}