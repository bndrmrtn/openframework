<?php

namespace Framework\App;

use Exception;

class CallController {

     public static function call(string $controller, string $method){
          $reflected = new \ReflectionClass( $controller );
          if( $reflected->isSubclassOf( 'Framework\Base\Controller' ) && !$reflected->isAbstract() ){
               // create a class instance without constructor
               $instance = $reflected->newInstanceWithoutConstructor();
               if(method_exists($instance, $method)) {
                   // return the instance
                   return $instance;
               }
               throw new Exception("{$reflected->getShortName()} class '{$method}' method does not exists");
          }
          throw new Exception("{$reflected->getShortName()} class is not a controller");
     }

}