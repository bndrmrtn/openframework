<?php
// use the routing

use Framework\App\Auth\Auth;
use Routing\Route;

// simple route for the index page, with an index controller
Route::get('/')->name('index')->control('index');

// a simple route with authorization required
Route::get('/dashboard')->auth(Auth::class)->name('dash')->control(function(){
    // simply return the dashboard view
    return view('dash');
});

// a route with params
Route::get('/user/{name}')->auth(Auth::class)->name('user')->control('user');

// a put request                            remember to remove the name param 
                                            // because it's a new route
Route::put('/user/{name}')->auth(Auth::class)/*->name('user')*/->control('user');