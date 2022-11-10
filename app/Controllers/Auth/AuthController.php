<?php

namespace App\Controller;

use DB;
use Core\Accounts\User;
use Core\App\Auth;
use Core\Helpers\Dates;
use Core\Base\Controller;
use App\Model\EmailVerifications;

// uncomment if you need this
//if(Auth::is_loggedin()) location('/');

class AuthController extends Controller {

     /**
      * Login methods
      */
     public function loginView(){
          // simply return the login form without any data
          return view('auth/login');
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
     }

     /**
      * Register methods
      */

     public function registerView(){
          return view('auth/register');
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

          if($errors = Auth::errors_array()) $data['errors'] = $errors;

          if($error = Auth::hasError()) $data['message'] = $error;

          return view('auth/register',$data);
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

          // return to the verification view
          return view('auth/verify', compact('msg'));
     }

}

/**
 * How to update a user?
 * https://open.mrtn.vip/docs/accounts/user/#update
 */