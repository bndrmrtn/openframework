<?php

use Framework\App\Auth\Auth;

/**
 * Configure the auth in this file: '/app/config/auth.php'
 */

response()->get(function(){
    // simply return the login form without any data
    return view('auth/login');
})->post(function(){
    /**
     * Just try to login, add a callable method to
     * the auth, when the authentication is successful
     * the auth calls the added method
     */
    Auth::tryLogin(function(){
        return location(route('index'));
    });

    /**
     * But when the auth fails
     * The easiest way is to create a data array
     */
    $data = [];

    /**
     * if the auth has error array
     * add it to the created array
     */
    if($errors = Auth::errors_array()){
        $data['errors'] = $errors;
    }

    /**
     * And if it has an error message
     * add it to the array
     */
    if($error = Auth::hasError()){
        $data['message'] = $error;
    }

    /**
     * Then return the view with the array of data
     */
    return view('auth/login',$data);
});

/**
 * How to update a user?
 * https://open.mrtn.vip/docs/accounts/user/#update
 */