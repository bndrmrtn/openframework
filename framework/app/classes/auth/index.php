<?php

class Auth {

    private static $token = NULL;
    private static $config = [];
    private static $usable = false;
    private static $loggedin = false;
    private static $user = [];
    private static $user_session = [];
    private static $skey = 'auth-token';
    private static $autologin = true;
    private static $salt = [
        'left' => '2O2G!4::nMXJYKZoe5G!L7#MZwgTMPd4N.j',
        'right' => 's1FaD#PUh+0OaJP6fMYNYPFvWJ6!1ceb8WR',
    ];
    private static $has_errors = false;
    private static $errors = [];
    private static $auth_error = NULL;
    
    public static function setup(){
        $config = require FRAMEWORK . '/config/auth.php';
        if(!_env('USE_SESSION')){
            die('Authentication requires sessions');
        }
        self::$config = $config;
        self::$usable = true;
        self::autologin();
    }

    public static function tryLogin(callable $callback = NULL){
        if(!self::$usable) return false;
        if(self::$loggedin) return true;
        $form = new Form();
        $form->bindData(post());

        $userValidation = self::$config['validation']['user'];
        $passwordValidation = self::$config['validation']['password'];

        $login = $form->validate(new Validation([
           $userValidation['col'] => $userValidation['validation'],
           $passwordValidation['col'] => [],
        ],self::$config['validation_errors']));

        if(!$login['errors']){
            $data = self::useHash($passwordValidation['col'],$login['valid']);
            self::safeLogin($data);
            if($callback && is_callable($callback) && self::$loggedin){
                $callback();
            }
            return;
        }
        self::$has_errors = true;
        self::$errors = $login['errors'];
        return;
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
                MErrors::custom(500,'Authentication not working');
            }
            self::$auth_error = 'invalid-logins';
            self::$loggedin = false;
        }
    }

    private static function storeSession($token){
        self::$token = $token;
        $_SESSION[self::$skey] = $token;
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
        if(self::$autologin) self::autologin();
        return self::$loggedin;
    }

    public static function user_session($custom_key = false){
        if(array_count_values(self::$user_session) > 0){
            $user_session = self::$user_session;
            if($custom_key){
                if($custom_key == 'user'){
                    $user_session = $user_session['user'];
                }
            }
            return $user_session;
        } else {
            self::is_loggedin();
            self::user_session($custom_key);
        }
        return false;
    }

    public static function user($custom_key = false){
        if(array_count_values(self::$user) > 0){
            $user = self::$user;
            if($custom_key){
                if($custom_key == 'user'){
                    $user = $user[self::$config['validation']['user']['col'] ];
                }
            }
            return $user;
        } else {
            self::is_loggedin();
            self::user($custom_key);
        }
        return false;
    }

    private static function getByName($name){
        $user = DB::select('*',self::$config['table'],[self::$config['validation']['user']['col'] => $name],NULL,'0',1);
        if(!isset($user['error'])){
            return $user;
        } else if($user['error'] != 'key_error'){
            MErrors::ServerError();
        } else {
            return [];
        }
    }

    private static function autologin(){
        if(!self::$autologin) return;
        self::$user = [];
        self::$loggedin = false;
        if(isset($_SESSION[self::$skey])){
            $login = Login::token($_SESSION[self::$skey]);
            if($login != false){
                self::$user_session = $login;
                self::$user = self::getByName(self::user_session('user'));
                if(count(self::$user) != 0){
                    self::$loggedin = true;
                }
            } else {
                unset($_SESSION[self::$skey]);
            }
        }
        self::$autologin = false;
    }

    public static function logout(){
        if(isset($_SESSION[self::$skey])){
            Login::destroy($_SESSION[self::$skey]);
            unset($_SESSION[self::$skey]);
        }
        return true;
    }

    public static function html_error($field,$html = ':msg:'){
        if(self::$has_errors){
            if(isset(self::$errors[$field])){
                return str_replace(':msg:',self::$errors[$field],$html);
            }
        }
        return false;
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

}
