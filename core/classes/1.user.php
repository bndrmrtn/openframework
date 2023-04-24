<?php

/**
 * The file name starts with . 
 * to boot before the other classes
 */

namespace Core\App\Accounts;

use Core\Base\ModelBase;

class User extends ModelBase {

    protected static string $_table = 'users';
    protected static array $_config = [];

    protected array $all_field = [];
    public array $fields = [];
    public static User $data_login;
    public bool $exists = false;

    public function __construct($name = NULL, $findBy = 'username', $fail = false){
        if(is_null($name)) return $this;
        return $this->find($name,$findBy, $fail);
    }

    public static function boot():void {
        self::$_config = require config('user');
    }

    public static function login_user($name = NULL, $findBy = 'username'){
        $user = new User($name, $findBy);
        self::$data_login = $user;
        return true;
    }

}