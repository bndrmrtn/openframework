<?php

use Framework\App\Auth\Auth;
use Routing\Route;

// if the authorization is on
if(_env('USE_AUTH')){

    // login get routes to render the view
    Route::get('/login')->name('auth.login')->control('auth/login');

    Route::get('/register')->name('auth.register')->control('auth/register');

    // post routes to handle the request
    Route::post('/register')->control('auth/register');

    Route::post('/login')->control('auth/login');

    // logout is a "without-controller" route and instantly handles the request
    Route::get('/logout')->name('auth.logout')->control(function() {
        Auth::logout();
        location(route('auth.login'));
    });

}

/**
 * Tip:
 * Use the user() function to get the currently logged in user's data
 * Example: Hello {{ user()->username }}!
 */