<?php

/**
 * The url is '/user/{name}'
 * use the name prop with a function argument starts with a dollar sign
 * Example:
 * the url is: /user/{name}/{age}
 * the function could be: function($age,$name){ ... }
 * The app returns all the props by it's name
 * Or the request by the $request variable
 * Like: function($name,\Core\App\Request $request){ ... }
 */

namespace App\Controller;

use Core\App\Accounts\User;
use Core\App\Request;
use Core\App\Session;
use Core\App\Validation;
use Core\Base\Controller;

class UserController extends Controller {

     public function index($name){
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

          $links = $this->navbar();

          // simply just add the active to the my profile link and check if the user is the currently logged in
          $is_mine = false;
          if($user->id == user()->id) {
               $is_mine = true;
               $links[2] = array_merge($links[2],['active']);
          }

          // just return a view, read more in the index controller
          return view('user', array_merge($fields, [ 'fields' => $fields, 'links' => $links, 'is_mine' => $is_mine ]));
     }

     public function navbar(){
          /**
          * Return the navbar links
          */
          return [
               [ 'href' => route('index'), 'title' => 'Home' ],
               [ 'href' => route('dash'), 'title' => 'Dashboard' ],
               [ 'href' => route('user', user()->username), 'title' => 'My Profile' ],
               [ 'href' => route('auth.logout'), 'title' => 'Logout' ],
          ];
     }

     public function update(Request $request){
                         // here we don't need the {name} param because
                         // the clients could only edit their accounts
                         // so we just edit the logged in user by the user() function

          // validate the incoming email
          $request->validate(new Validation([
               'email' => [ 'email' => 'Invalid email format' ],
          ],[
               'required' => 'This field is required',
          ]));

          // if the email is valid
          if($request->is_valid()){
               // create a variable for the user function
               // required to edit user data
               $user = user();
               // edit the email param by this
               $user->email = $request->getValid()['email'];
               // then update the user
               $user->update();
               // and redirect to the GET method page
               redirect(route('user', $user->username));
          } else {
               // if the sessions are enabled (required for the auth by default)
               // you could create a one request data that will stored and
               // automaticly being deleted after the next request
               Session::SingleUse('errors', $request->getErrors()); // this data will stored in the $_SESSION variable
               // and redirect to the GET method page
               redirect(route('user', user()->username));
          }
     }

}