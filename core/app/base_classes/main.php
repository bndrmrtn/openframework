<?php

/**
 * Base class
 * You could implement your class with booted method and smth
 * Use the Base class or implements the BaseInterface
 * Create Abstarct classes or Interfaces under this folder
 */

namespace Core\Base;

use Core\Base\Abstracts\Main;
use Core\Base\Interfaces\BaseInterface;

abstract class Base extends Main implements BaseInterface { 
    /* The Class Root  */
    protected static array $booted = [];
    
    // load the self booter
    public static function classBooter($instance):void {
        // if the class boot method exists
        if(method_exists($instance,'boot')) {
            // call it without arguments if not booted already
            if(!in_array(get_class($instance), self::$booted)){
                call_user_func(array($instance, 'boot'));
                self::$booted[] = get_class($instance);
            }
        }
    }

    /**
     * The boot function
     */
    public static function boot():void {  }

    /**
     * The other implemented methods
     */
    
}