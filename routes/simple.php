<?php

// / means the root url
// Route::add('/','app' /* use loggedin() to use this path only if authenticated, and if the user not authenticated include another file or change location to login */);
//                          include ROOT . '/serve/server/simple/app.php';

Route::add('/','app',/*loggedin()*/);

// Authentication routes

if(_env('USE_AUTH',false) && file_exists(FRAMEWORK . '/auth/routes.php')){
    require FRAMEWORK . '/auth/routes.php';
}