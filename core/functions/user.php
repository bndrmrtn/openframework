<?php

use Core\App\Auth;
use Core\App\Accounts\User;

$GLOBALS['auth']['user'] = NULL;

function user(){
    if(Auth::is_loggedin()){
        return User::$data_login;
    }
    return false;
}