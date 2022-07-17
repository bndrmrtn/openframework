<?php

// check authentication
include_once __DIR__ . '/auth.php';
// check with $is_loggedin variable

// / means the root url
// Route::add('/','app' /* use $is_loggedin to use this path only if authenticated, and if the user not authenticated include another file or change location to login */);
//                          include ROOT . '/serve/server/simple/app.php';

Route::add('/','app',/*$is_loggedin*/);

// Authentication routes

if(_env('USE_AUTH',false) && file_exists(FRAMEWORK . '/auth/routes.php')){
    require FRAMEWORK . '/auth/routes.php';
}