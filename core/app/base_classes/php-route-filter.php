<?php

namespace Core\Base;

use Core\App\Error;

abstract class Filter extends Base {

     public function Match($value):mixed {}

     public function onFail($results):void {
          Error::NotFound();
     }

}

class FilterResult {

     private bool $isValid = false;
     private $value = NULL;

     public function __construct(bool $isValid, $value){
          $this->isValid = $isValid;
          $this->value = $value;
     }

     public function read() {
          return (object) [
               'isValid' => $this->isValid,
               'value' => $this->value
          ];
     }

}