<?php

namespace Core\Base;

use Header;

abstract class Controller extends Base {

     public static function __authorize(){
          return true;
     }

     public static function __csrf(){
          return true;
     }

     public static function __onUse():void {
          Header::html();
     }

}