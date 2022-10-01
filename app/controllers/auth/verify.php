<?php

use Framework\App\Helpers\Dates;
use Framework\App\Mails\Mail;

response()->get(function($token){
     // message when the token not exists
     $msg = 'Invalid token';

     // try, select the latest email token
     $data = DB::_select('SELECT * FROM email_verifications WHERE token = ? ORDER BY id DESC', [ $token ],[0]);
     
     // check if the token is valid or some database error
     $valid = !isset($data['error']);
     
     // if the data is selected
     if($valid){
          /**
           * Check if the token isn't expired
           *                                         that means the token expires in 3 days
           * Then update the user's email verification date from null
           */
          if(Dates::toMicrotime($data['date']) > Dates::rmFrom([ 'day' => 3 ])){

               DB::update('users', [ 'email_verified_at' => Dates::now(true) ], 'id', $data['userid']);

               $msg = 'Your email successfully verified';
               
          } else {
               // else the app returns a token expired message
               $msg = 'This token is expired';
          }
          // delete all the tokens for the current user
          DB::delete('email_verifications', [ 'userid' => $data['userid']]);
     }

     // return to the verification view
     return view('auth/verify', compact('msg'));
});