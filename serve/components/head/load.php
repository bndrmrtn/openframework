<?php

class HeadComponent extends Components {

    private static $props = array(
        [
            'key'=>'title',
            'val'=>'test value',
            //'req'=>false
        ],
    );

    public static function load($props = NULL){
        if(self::validateProps(self::$props,$props)){
            self::render(__DIR__ . self::$component,$props);
        }
    }

}