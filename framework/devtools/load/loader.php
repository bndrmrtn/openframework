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
        $c = scandir(DEVROOT . $from);
        if(is_array($c)){
            foreach($c as $i){
                if(str_ends_with($i,'.php')){
                    include_once(DEVROOT . $from . $i);
                }
            }
        }
    }

    public static function createApp(){
        echo chr(27).chr(91).'H'.chr(27).chr(91).'J';
        headerPrint(str_repeat(' ',exec('tput cols')));
        headerPrint(' ____             _____           _     ');
        headerPrint('|  _ \  _____   _|_   _|__   ___ | |___ ');
        headerPrint('| | | |/ _ \ \ / / | |/ _ \ / _ \| / __|');
        headerPrint('| |_| |  __/\ V /  | | (_) | (_) | \__ \\');
        headerPrint('|____/ \___| \_/   |_|\___/ \___/|_|___/');
        headerPrint('OpenFramework V' . VERSION);
        headerPrint(str_repeat(' ',exec('tput cols')));
        _e();
        return new App;
    }


}