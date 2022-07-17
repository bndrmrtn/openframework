<?php

class Auth {

    private static $token = NULL;
    protected static $config = [];
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
    private static $use_sessions = true;
    
    public static function setup(){
        $config = require FRAMEWORK . '/config/auth.php';
        if(!_env('USE_SESSION')){
            self::sessionSwitch(false);
        } else if(!_env('DB_NEED_CONNECTION')){
            die('Authentication requires database connection');
        }
        self::$config = $config;
        self::$usable = true;
        self::autologin();
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
                MErrors::custom(500,'Authentication not working');
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
        if(self::$autologin) self::autologin();
        return self::$loggedin;
    }

    public static function user_session($custom_key = false){
        if(count(self::$user_session) > 0){
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
        return [];
    }

    public static function user($custom_key = false){
        if(count(self::$user) > 0){
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
            self::$user = self::getByName(self::user_session('user'));
            if(count(self::$user) != 0){
                self::$loggedin = true;
            }
        } else if(static::$use_sessions) unset($_SESSION[self::$skey]);
        else self::$loggedin = false;
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

    public static function register(callable $register,array $formValidation = []){
        if(is_callable($register)){
            
            $form = new Form();
            $form->bindData(post());

            $userValidation = self::$config['validation']['user'];
            $passwordValidation = self::$config['validation']['password'];

            $data = $form->validate(new Validation(array_merge([
                $userValidation['col'] => $userValidation['validation'],
                $passwordValidation['col'] => [],
            ],$formValidation),self::$config['validation_errors']));
            if(!$data['errors']){
                if(DB::exists(self::getTableName(),[ $userValidation['col'] = $data[$userValidation['col']] ])){
                    self::$auth_error = 'user-exists';
                    return false;
                }
                $data = self::useHash($passwordValidation['col'],$data['valid']);
                if(!($reg = $register($data))) $reg = false;
                if(!$reg['error']){
                    return true;
                }
                self::$auth_error = $reg['error'];
                return;
            }
            self::$has_errors = true;
            //dd($data['errors']);
            self::$errors = $data['errors'];
            return false;
        } else {
            MErrors::custom(500,'The registration not configured properly');
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
