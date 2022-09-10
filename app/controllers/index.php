<?php

/**
 * Make a response
 * If the response is get create a get method (or post, put, delete)
 * And inside a callable function
 * Then return a view with the view() function or use the json() method
 * The json() method always returns json
 * The view() method returns json if the accepted content type is 'application/json'
 * Easier than it looks like ;)
 */

response()->get(function() {
    /**
     * The navbar config
     */
    if(_env('USE_AUTH')){
        // if auth enabled
        if(!user()){
            // if the user is not logged in
            $links = [
                ['href' => route('index'), 'title' => 'Home', 'active'],
                ['href' => route('auth.login'), 'title' => 'Login'],
                ['href' => route('auth.register'), 'title' => 'Register'],
            ];
        } else {
            // if the user is logged in
            $links = [
                [ 'href' => route('index'), 'title' => 'Home', 'active' ],
                [ 'href' => route('dash'), 'title' => 'Dashboard' ],
                [ 'href' => route('user', user()->username), 'title' => 'My Profile' ],
                [ 'href' => route('auth.logout'), 'title' => 'Logout' ],
            ];
        }
    } else {
        // if the auth is not enabled
        $links = [
            ['href' => route('index'), 'title' => 'Home', 'active'],
        ];
    }

    return view('index',[
        // then return the links as links
        'links' => $links,
        // and some home page info
        /**
         * Every main array key converted to a variable
         * Like: view('demo',['id' => 1])
         * Then use the variable in the view like: Id: {{ $id }}
         */
        'title' => 'OpenFramework',
        'description' => 'A slim php framework by <a href="https://mrtn.vip">Martin Binder</a>,<br><a href="https://open.mrtn.vip/docs/#welcome">Documentation</a>.',
    ]);
});