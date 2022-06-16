<?php

class _COMPONENTNAME_Component extends Components {

    protected static $props = array(
        [
            'key'=>'test',
            'req'=>false
        ],
    );

    public static function load($props = NULL){
        if(self::validateProps(self::$props,$props)){
            self::render(__DIR__ . self::$component,$props);
        }
    }

}