<?php

/**
 * The url is '/user/{name}'
 * use the name prop with a function argument starts with a dollar sign
 * Example:
 * the url is: /user/{name}/{age}
 * the function could be: function($age,$name){ ... }
 * The app returns all the props by it's name
 * Or the request by the $request variable
 * Like: function($name,\Framework\App\Request $request){ ... }
 */

use Framework\App\Accounts\User;

response()->get(function($name){
    // check if the user is the logged in
    if($name == user()->username){
        $user = user();
    } else {
        // else create a new user instance
                            // find the user by the 'username' key
        $user = new User($name,'username',true);
                                        // the true means if the user not found, it drops
                                        // a 404 not found error
    }

    // get the user fields and pass it to the view
    $fields = $user->fields;

    /**
     * Return the navbar links
     */
    $links = [
        [ 'href' => route('index'), 'title' => 'Home' ],
        [ 'href' => route('dash'), 'title' => 'Dashboard' ],
        [ 'href' => route('user', user()->username), 'title' => 'My Profile' ],
        [ 'href' => route('auth.logout'), 'title' => 'Logout' ],
    ];
                                // simply just add the active to the my profile link
    if($user->id == user()->id) $links[2] = array_merge($links[2],['active']);
    
    // just return a view, read more in the index controller
    return view('user',array_merge($fields, [ 'fields' => $fields, 'links' => $links ]));
});