<?php

use Framework\App\Auth\Auth;
use Framework\App\Accounts\User;

$GLOBALS['auth']['user'] = NULL;

function user(){
    if(Auth::is_loggedin()){
        return User::$data_login;
    }
    return false;
}