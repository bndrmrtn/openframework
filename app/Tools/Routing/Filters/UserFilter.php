<?php

namespace App\Tools\Routing\Filter;

use Core\App\Accounts\User;
use Core\App\Error;
use Core\App\RegEx;
use Core\Base\Filter;
use Core\Base\FilterResult;

class UserFilter extends Filter {
        
     public function Match($value): FilterResult {
          $isValid = false;
          $data = $value;
          if(RegEx::is_username($value)){
               $model = new User($value);
               $isValid = $model->exists;
               $data = $model;
          }
          return new FilterResult(
               $isValid,
               $data,
          );
     }

     public function onFail($results):void {
          Error::NotFound();
     }

}