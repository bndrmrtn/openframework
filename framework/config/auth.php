<?php

/**
 * 
 * function myHash( $password ){
 *  return hash('sha256',$password);
 * }
 * NOTE: create this funtion in the /assets/functions/ folder
 * if you need a custom hash function
 * 
 */

$config_auth['table'] = 'users';

$config_auth['validation'] = [
    'user' => [ 'col' => 'username', 'validation' => [ 'regex:username' ] ],
    'password' => [ 'col' => 'password',/* 'custom_hash_function' => 'myHash' */ ],
];

$config_auth[ 'validation_errors' ] = [
    'required' => 'This field is required',
    'regex' => 'No special chars allowed',
];

$config_auth[ 'error_msgs' ] = [
    'invalid-logins' => 'Invalid username or password',
    'unknow' => 'Something went wrong',
];

return $config_auth;