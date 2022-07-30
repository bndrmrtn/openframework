<?php

namespace DEV;



class DEVLoader {

    private static $from_app_classes = [ 'route', 'http' ];

    public static function load(){
        require __DIR__ . '/mainclasshandler.php';
        self::loadFiles('/data/functions/');
        self::loadFiles('/data/classes/');
        if(is_array(self::$from_app_classes) && self::$from_app_classes != []) foreach(self::$from_app_classes as $class){
            $class = FRAMEWORK . '/app/classes/' . $class . '.php';
            if(file_exists($class)) require_once $class;
        }
    }

    protected static function loadFiles($from){
        $c = scanDirectory(DEVROOT . $from);
        if(is_array($c)){
            foreach($c as $i){
                if(str_ends_with($i,'.php')){
                    include_once(DEVROOT . $from . $i);
                }
            }
        }
    }

    public static function createApp(){
        return new App;
    }
    
}