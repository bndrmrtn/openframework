<?php

/**
 * // simple hash function
 * function myHash( $password ){
 *  return hash('sha256','somesalt' . $password);
 * }
 * NOTE: create this funtion in the /framework/app/helpers/ folder
 * if you need a custom hash function
 */


// the table used for users
$config['table'] = 'users';

// auth validation columns
$config['validation'] = [
    'user' => [ 'col' => 'username', 'validation' => [ 'regex:username' ] ],
    'password' => [ 'col' => 'password',/* 'custom_hash_function' => 'myHash' */ ],
];

// rewrite the errors in auth
$config[ 'validation_errors' ] = [
    'required' => 'This field is required',
    'regex' => 'No special chars allowed',
    'email' => 'Invalid email format',
];

// rewrite error messages
$config[ 'error_msgs' ] = [
    'invalid-logins' => 'Invalid username or password',
    'unknow' => 'Something went wrong',
];

$config['salt'] = [
    'left' => '2O2G!4::nMXJYKZoe5G!L7#MZwgTMPd4N.j',
    'right' => 's1FaD#PUh+0OaJP6fMYNYPFvWJ6!1ceb8WR',
];

return $config;