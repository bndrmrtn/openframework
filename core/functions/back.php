<?php

use Core\App\Error;
use Core\App\Session;

function back($message = ''){
     $prev_page = Session::get('framework.url.previous');
     if($prev_page){
          Session::SingleUse('back.message', $message);
          return location($prev_page);
     }
     return Error::Custom('Bad Request', 'Please reload the page' ,400);
}