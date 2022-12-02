<?php

namespace App\Controller;

use DB;
use Core\App\Accounts\User;
use Core\App\Auth;
use Core\App\Helpers\Dates;
use Core\Base\Controller;
use App\Model\EmailVerifications;
use Routing\Route;

// uncomment if you need this
//if(Auth::is_loggedin()) location('/');

class AuthController extends Controller {

     /**
      * Login methods
      */
     public function loginView(){
          // simply return the login form without any data
          return view('.src/:auth/auth', ['links' => $this->navbar(), 'name' => 'Login', 'form' => $this->form()]);
     }

     public function login(){
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

          // just the page config
          $data['links'] = $this->navbar();
          $data['name'] = 'Login';
          $data['form'] = $this->form();

          /**
          * if the auth has error array
          * add it to the created array
          */
          if($errors = Auth::errors_array()){
               $data['form']['errors'] = $errors;
          }

          /**
          * And if it has an error message
          * add it to the array
          */
          if($error = Auth::hasError()){
               $data['form']['message'] = $error;
          }

          /**
          * Then return the view with the array of data
          */
          return view('.src/:auth/auth',$data);
     }

     /**
      * Register methods
      */

     public function registerView(){
          return view('.src/:auth/auth', ['links' => $this->navbar('register'), 'name' => 'Register', 'form' => $this->form('register')]);
     }

     public function register(){
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

          // just the page config
          $data['links'] = $this->navbar('register');
          $data['name'] = 'Register';
          $data['form'] = $this->form('register');

          if($errors = Auth::errors_array()) $data['form']['errors'] = $errors;

          if($error = Auth::hasError()) $data['form']['message'] = $error;

          return view('.src/:auth/auth',$data);
     }

     /**
      * Email verification
      */
     public function verify($token){
          // message when the token not exists
          $msg = 'Invalid token';
          $data = EmailVerifications::select('*')->where(['token' => $token])->latest();

          if(!is_null($data)){
               /**
                * Check if the token isn't expired
                *                                         that means the token expires in 3 days
                * Then update the user's email verification date from null
                */
               if(Dates::toMicrotime($data->date) > Dates::rmFrom([ 'day' => 3 ])){
     
                    DB::update('users', [ 'email_verified_at' => Dates::now(true) ], 'id', $data->user_id);
     
                    $msg = 'Your email successfully verified';
                    
               } else {
                    // else the app returns a token expired message
                    $msg = 'This token is expired';
               }
               // delete all the tokens for the current user
               DB::delete('email_verifications', [ 'user_id' => $data->user_id]);
          }

          $links = $this->navbar();

          // return to the verification view
          return view('.src/:auth/verify', compact('msg', 'links'));
     }

     private function navbar($type = 'login'){
          if($type == 'login'){
               return [
                    ['href' => route('index'), 'title' => 'Home'],
                    ['href' => route('auth.login'), 'title' => 'Login', 'active'],
                    ['href' => route('auth.register'), 'title' => 'Register'],
               ];
          } else if($type == 'register'){
               return [
                    ['href' => route('index'), 'title' => 'Home'],
                    ['href' => route('auth.login'), 'title' => 'Login'],
                    ['href' => route('auth.register'), 'title' => 'Register', 'active'],
               ];
          } else {
               return [
                    ['href' => route('index'), 'title' => 'Home'],
                    ['href' => route('auth.login'), 'title' => 'Login'],
                    ['href' => route('auth.register'), 'title' => 'Register'],
               ];
          }
     }

     private function form($type = 'login'){
          if($type == 'login'){
               return [
                    'route' => route('auth.login'),
                    'fields' => [
                    'username' => [ ],
                    'password' => [ 'type' => 'password' ]
                    ],
                    'message' => '',
                    'errors' => NULL,
                    'form_submit' => 'Login'
               ];
          } else if($type == 'register'){
               return [
                    'route' => route('auth.register'),
                    'fields' => [
                    'username' => [ ],
                    'email' => [ 'type' => 'email' ],
                    'password' => [ 'type' => 'password' ]
                    ],
                    'message' => '',
                    'errors' => NULL,
                    'form_submit' => 'Register'
               ];
          }
          return false;
     }

}

/**
 * How to update a user?
 * https://open.mrtn.vip/docs/accounts/user/#update
 */