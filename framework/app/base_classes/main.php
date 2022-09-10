<?php

/**
 * Base class
 * You could implement your class with booted method and smth
 * Use the Base class or implements the BaseInterface
 * Create Abstarct classes or Interfaces under this folder
 */

namespace Framework\Base;

use Framework\Base\Abstracts\Main;
use Framework\Base\Interfaces\BaseInterface;

abstract class Base extends Main implements BaseInterface { 
    /* The Class Root  */
    
    // load the self booter
    public static function classBooter($reflected):void {
        // if the class boot method exists
        if(method_exists($reflected,'boot')) {
            // call it without arguments
            call_user_func(array($reflected, 'boot'));
            $reflected::$booted = true;
        }
    }

    /**
     * The boot function
     */
    public static function boot() {  }

    /**
     * The other implemented methods
     */
    
}