<?php

namespace Core\Base;

abstract class Controller extends Base {

     public static function __authorize(){
          return true;
     }

}