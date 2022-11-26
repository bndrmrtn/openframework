<?php

use Core\App\Auth;
use App\Controller\AuthController;
use Routing\Route;

// if the authorization is on
if(_env('USE_AUTH')){

    // login get routes to render the view
    Route::get('/login')->name('auth.login')->control([AuthController::class, 'loginView']);

    Route::get('/register')->name('auth.register')->control([AuthController::class, 'registerView']);

    // post routes to handle the request
    Route::post('/login')->control([AuthController::class, 'login']);

    Route::post('/register')->control([AuthController::class, 'register']);

    // logout is a "without-controller" route and instantly handles the request
    Route::get('/logout')->name('auth.logout')->control(function() {
        Auth::logout();
        location(route('auth.login'));
    });

    Route::get('/register/token/{token}')->name('auth.verify')->control([AuthController::class, 'verify']);

}

/**
 * Hint:
 * Use the user() function to get the currently logged in user's data
 * Example: Hello {{ user()->username }}!
 */