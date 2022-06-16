<?php

class MdocComponent extends Components {

    protected static $props = array(
        [
            'key'=> 'title' ,
            'req' => false
        ],
    );

    public static function load($props = NULL){
        if(self::validateProps(self::$props,$props)){
            self::render(__DIR__ . self::$component,$props);
        }
    }

}