<?php

class controller {

    public static function loadApp(){
        require FRAMEWORK . '/config/config.php';
        require FRAMEWORK . '/config/loaders/setup.php';
        self::loadFiles('/framework/app/needle/');
        self::loadFiles('/assets/functions/');
        self::loadFiles('/framework/app/classes/');
        require FRAMEWORK . '/app/session.php';
        if(_env('USE_AUTH',false)) Auth::setup();
        
        require routes('simple.php');
        Route::setType(false);
        require routes('routed.php');
        Route::setType(true);
    }

    protected static function loadFiles($from){
        $c = scanDirectory(ROOT . $from);
        if(is_array($c)){
            foreach($c as $i){
                if(str_ends_with($i,'.php')){
                    include_once(ROOT . $from . $i);
                }
            }
        }
    }

    public static function loadFunction($name){
        include_once ROOT . "/assets/functions/$name.php";
    }

    public static function loadClass($name){
        include_once FRAMEWORK . "/app/classes/$name.php";
    }

    public static function loadDB() {
        require FRAMEWORK . '/database/loader.php';
    }

}