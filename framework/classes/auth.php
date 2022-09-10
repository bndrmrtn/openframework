<?php

namespace Framework\App\Auth;

use Framework\App\Error;
use DB;
use Framework\App\Accounts\User;
use Framework\App\Validation;
use Framework\Base\Base;

class Auth extends Base {

    private static $token = NULL;
    protected static $config = [];
    private static $usable = false;
    private static $loggedin = false;
    private static $user = [];
    private static $user_session = [];
    private static $skey = 'auth-token';
    private static $autologin = true;
    private static $salt;
    private static $has_errors = false;
    private static $errors = [];
    private static $auth_error = NULL;
    private static $use_sessions = true;
    
    public static function boot(){
        if(_env('USE_AUTH')){
            $config = require ROOT . '/app/config/auth.php';
            self::$salt = $config['salt'];
            unset($config['salt']);
            if(!_env('USE_SESSION')){
                self::sessionSwitch(false);
            } else if(!_env('USE_DB')){
                throw new \Exception('Authentication requires database connection');
            }
            self::$config = $config;
            self::$usable = true;
            self::autologin();
        }
    }

    public static function sessionSwitch(bool $enable){
        self::$use_sessions = $enable;
    }

    public static function getTableName(){
        return self::$config['table'];
    }

    public static function tryLogin(callable $callback = NULL){
        if(!self::$usable) return false;
        if(self::$loggedin) return true;

        $userValidation = self::$config['validation']['user'];
        $passwordValidation = self::$config['validation']['password'];

        $login = request()->validate(new Validation([
           $userValidation['col'] => $userValidation['validation'],
           $passwordValidation['col'] => [],
        ],self::$config['validation_errors']));

        if($login->is_valid()){
            $data = self::useHash($passwordValidation['col'],$login->getValid());
            self::safeLogin($data);
            if($callback && is_callable($callback) && self::$loggedin){
                $callback();
            }
            return;
        }
        self::$has_errors = true;
        self::$errors = $login->getErrors();
        return;
    }

    public static function Token($token){
        return self::autologin();
    }

    private static function safeLogin($login){
        $table = self::$config['table'];
        $select = DB::select('*',$table,$login,NULL,'0',1);
        if(!isset($select['error'])){
            unset($select[self::$config['validation']['password']['col']]);
            self::$user = $select;
            self::$loggedin = true;
            if($token = Login::save()){
                self::storeSession($token);
            } else {
                self::$auth_error = 'unknow';
            }
        } else {
            if($select['error'] != 'key_error'){
                self::$loggedin = false;
                throw new \Exception('Authentication not working! Maybe try to run "php dev db setup:tables" and try again');
            }
            self::$auth_error = 'invalid-logins';
            self::$loggedin = false;
        }
    }

    private static function storeSession($token){
        self::$token = $token;
        if(self::$use_sessions) $_SESSION[self::$skey] = $token;
    }

    public static function getSessionToken(){
        if(self::$token) return self::$token;
        return false;
    }

    private static function useHash($pw_col,$data){
        $rawpass = $data[$pw_col];
        if(isset(self::$config['validation']['password']['custom_hash_function'])){
            $pass = call_user_func(self::$config['validation']['password']['custom_hash_function'],[$rawpass]);
        } else {
            $pass = self::hash($rawpass);
        }
        $data[$pw_col] = $pass;
        return $data;
    }

    private static function hash($password){
        return hash('sha256',self::$salt['left'] . $password . self::$salt['right']);
    }

    public static function is_loggedin(){
        self::autologin();
        return self::$loggedin;
    }

    public static function user_session($custom_key = false){
        if(count(self::$user_session) > 0){
            $user_session = self::$user_session;
            if($custom_key){
                if($custom_key == 'user'){
                    $user_session = $user_session['user'];
                } else if(isset($user_session[$custom_key])){
                    $user_session = $user_session[$custom_key];
                }
            }
            return $user_session;
        } else {
            self::is_loggedin();
            self::user_session($custom_key);
        }
        return [];
    }

    public static function user($custom_key = false){
        if(count(self::$user) > 0){
            $user = self::$user;
            if($custom_key){
                if($custom_key == 'user'){
                    $user = $user[self::$config['validation']['user']['col'] ];
                } else if(isset($user[$custom_key])){
                    $user = $user[$custom_key];
                }
            }
            return $user;
        } else if(self::is_loggedin()) {
            self::user($custom_key);
        }
        return false;
    }

    private static function autologin(){
        if(!self::$autologin) return;
        self::$user = [];
        self::$loggedin = false;
        $login = false;
        if(self::$use_sessions && isset($_SESSION[self::$skey])){
            $login = Login::token($_SESSION[self::$skey]);
        } else if(isset(headers()['Authorization'])){
            $token = headers()['Authorization'];
            $login = Login::token($token);
        }
        if($login != false){
            self::$token = $login['token'];
            self::$user_session = $login;
            if(User::$data_login){
                self::$loggedin = true;
            }
        } else if(static::$use_sessions){
            unset($_SESSION[self::$skey]);
        } else {
            self::$loggedin = false;
        }
        self::$autologin = false;
    }

    public static function logout(){
        if(self::$use_sessions && isset($_SESSION[self::$skey])){
            Login::destroy($_SESSION[self::$skey]);
            unset($_SESSION[self::$skey]);
        } else {
            Login::destroy(self::$token);
        }
        return true;
    }

    public static function hasError(){
        if($error = self::$auth_error){
            if(isset(self::$config['error_msgs'][$error])){
                return self::$config['error_msgs'][$error];
            } else {
                return $error;
            }
        }
        return false;
    }

    public static function register(callable $register,array $formValidation = []){
        if(is_callable($register)){

            $userValidation = self::$config['validation']['user'];
            $passwordValidation = self::$config['validation']['password'];

            $data = request()->validate(new Validation(array_merge([
                $userValidation['col'] => $userValidation['validation'],
                $passwordValidation['col'] => [],
            ],$formValidation),self::$config['validation_errors']));

            if($data->is_valid()){
                $data = $data->getValid();
                if(DB::exists(self::getTableName(),[ $userValidation['col'] => $data[$userValidation['col']] ])){
                    self::$auth_error = 'user-exists';
                    return false;
                }
                $data = self::useHash($passwordValidation['col'],$data);
                if(!($reg = $register($data))) $reg = false;
                if(!$reg['error']){
                    return true;
                }
                self::$auth_error = $reg['error'];
                return;
            }
            self::$has_errors = true;
            self::$errors = $data->getErrors();
            return false;
        } else {
            Error::ServerError(NULL,'The registration not configured properly');
        }
    }

    public static function saveRegister($data){
        return self::safeLogin($data);
    }

    public static function notExists(array $data, array $values){
        $where = '';
        $vals = [];
        foreach($data as $key => $val){
            if(isset($values[$val])){
                $where .= "$val = ?";
                $vals[] = $values[$val];
            }
            if(array_key_last($data) != $key){
                $where .= ' OR ';
            }
        }
        DB::logger();
        $select = DB::_select('SELECT COUNT(*) as total FROM ' . self::getTableName() . ' WHERE ' . $where . ' LIMIT 1',$vals,[0]);
        if($select['total'] == 0) return true;
        self::$auth_error = 'already-taken';
        return false;
    }

    public static function errors_array(){
        if(self::$has_errors){
            return self::$errors;
        }
        return false;
    }

}
