<?php

// check authentication
// include_once __DIR__ . '/auth.php';
// check with $is_loggedin variable

// app means the root url
// controller::addRoute('app','app' /* use $is_loggedin to use this path only if authenticated, and if the user not authenticated include another file or change location to login */);
//                          include ROOT . '/serve/server/simple/app.php';

controller::addRoute('app','app');

controller::addRoute('upload/[any]','upload');




// Authentication routes

if(_env('USE_AUTH',false) && file_exists(__DIR__ . '/auth/routes.php')){
    require __DIR__ . '/auth/routes.php';
}