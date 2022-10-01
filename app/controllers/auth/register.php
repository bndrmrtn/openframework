<?php

/**
 * Use the important classes for the Auth
 * Read more in the documentation
 */

use Framework\App\Auth\Auth;
use Framework\App\Helpers\Dates;
use Framework\App\Accounts\User;

if(Auth::is_loggedin()) location('/');

response()->get(function(){
    return view('auth/register');
})->post(function(){
    // trying to register a user
    // use a function with an argument to get the requested data from the user
    Auth::register(function($data){

        // merge the data with server values if required
        $data = array_merge($data,[
            'uniqid' => uniqid(),
            'date' => Dates::now(true),
        ]);

        // check the database if a user already exists with a specific username
        // email or value                             // return false, the errors auto handled
        if(!Auth::notExists(['username','email'],$data)) return false;

        // try to insert the user to the auth table
        // if the insert is successful
        if($i = User::create($data)){
            // save the authenticated user to the db and to the session
            return Auth::saveRegister($data, function(){
                // replace the location to the home page
                location(BASE_URL);
            });
        };
        return $i;
    },[
        /**
         * Add custom field validations if needed
         * Or edit the /app/config/auth.php config file
         */
        'email' => ['email']
    ]);

    // just return the auth errors if has, read more about it in the login controller
    $data = [];

    if($errors = Auth::errors_array()) $data['errors'] = $errors;

    if($error = Auth::hasError()) $data['message'] = $error;

    return view('auth/register',$data);
});