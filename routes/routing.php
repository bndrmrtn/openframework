<?php
// use the routing

use Core\App\Auth;
use App\Controller\MainController;
use App\Controller\UserController;
use Core\App\Accounts\User;
use Routing\Route;

// simple route for the index page, with an index controller
Route::get('/')->name('index')->control([MainController::class, 'index']);

// a simple route with authorization required
Route::get('/dashboard')->auth(Auth::class)->name('dash')->control(function(){
    $links = [
        ['href' => route('index'), 'title' => 'Home'],
        ['href' => route('dash'), 'title' => 'Dashboard', 'active'],
        ['href' => route('user', user()->username), 'title' => 'My Profile'],
        ['href' => route('auth.logout'), 'title' => 'Logout'],
    ];

    // simply return the dashboard view with $links variable
    return view('dash', compact('links'));
});

// a route with params
Route::get('/user/{string:name}')->auth(Auth::class)->name('user')->control([UserController::class, 'index']);

// a put request                            remember to remove the name param 
                                            // because it's a new route
Route::put('/user/{string:name}')->auth(Auth::class)/*->name('user')*/->control([UserController::class, 'update']);

// Too many param validation rule in controllers? Opaque code?
// Use the new route filter instead.
// Files located in /app/Tools/Routing/Filters/
// To generate a new filter, run: php dev filter generate name:FilterName
// For more info, see https://open.mrtn.vip/docs/routing/filters
Route::get('/filter/user/{@UserFilter:userObject}')->control(function(User $userObject){
    json([
        'title' => 'Demo filter usage',
        'user' => $userObject->fields,
    ]);
});
