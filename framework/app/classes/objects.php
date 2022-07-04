<?php

class Objects {

    private static $objects = [];

    public static function save(object $object):void {
        self::$objects[get_class($object)] = $object;
    }

    public static function get(string $name):object {
        if(isset(self::$objects[$name])){
            return self::$objects[$name];
        }
        return false;
    }

}