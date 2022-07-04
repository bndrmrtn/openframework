<?php

class NavComponent extends Components {

    private static $props = array(
        [
            'key'=>'ok',
            'val'=>'test value',
            //'req'=>false
        ],
    );

    public static function load($props,$autorender = true){
        if(self::validateProps(self::$props,$props)){
            self::render(__DIR__ . self::$component,$props,$autorender);
        }
    }

}