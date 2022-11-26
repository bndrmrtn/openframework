<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *\
 *                                                                             *
 *      simple hash function                                                   *
 *      function myHash( $password ){                                          *
 *          return hash('sha256','somesalt' . $password);                      *
 *      }                                                                      *
 *      NOTE: create this funtion in the /core/app/helpers/ folder             *
 *      if you need a custom hash function                                     *
 *                                                                             *
\* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


// the table used for users
$config['table'] = 'users';

// verify the emails 
$config['email-verification'] = false;

// if the email verification is on
// configure the auth email
$config['email-messages'] = [
    // you could use :field to add data from the user's input
    'subject' => _env('NAME') . ' Email verification',
    'body' => [
                    // like :username         // or the verification url by the :verificationUrl field
        'content' => 'Hello :username! <a href=":verificationUrl">Click here to verify your email!</a>',
        'html' => true,
    ],
];

// auth validation columns
$config['validation'] = [
    'user' => [ 'col' => 'username', 'validation' => [ 'regex:username', 'min:3', 'max:10' ] ],
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
    'email-verification' => 'Please verify your email before login',
    'email-register' => 'We\'ve emailed you a verification link. Please verify it before login.',
];

$config['salt'] = [
    'left' => '2O2G!4::nMXJYKZoe5G!L7#MZwgTMPd4N.j',
    'right' => 's1FaD#PUh+0OaJP6fMYNYPFvWJ6!1ceb8WR',
];

return $config;