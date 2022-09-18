<?php

namespace Framework\App;

use Routing\Route;
use Routing\Stream;
use Tools\THEN;

class Framework {

    public static function load(){
        // get the required directories to load
        $load_dir_files = require __DIR__ . '/dirs.php';

        // foreach all the dirs
        foreach($load_dir_files as $dir){
            // if it's a dir
            if(is_dir(FRAMEWORK . $dir)){
                // scan all the files inside that dir
                $files = scandir(FRAMEWORK . $dir);
                // if the dir has files, loop through all the files, and if it's a php file, require it
                if(!empty($files)) foreach($files as $file) if(str_ends_with($file ,'.php')) require FRAMEWORK . $dir . $file;
            }
        }

        // include the database if it's required
        if(_env('USE_DB', false)) require FRAMEWORK . '/database/loader.php';

        // boot all the classes that has that functionality
        self::bootClasses();

        if(file_exists(ROOT . '/app/app.php')) require ROOT . '/app/app.php';

        // return a simple then statement, that runs after that function
        return new THEN();
    }

    private static function bootClasses(){
        // get all the classes
        foreach( get_declared_classes() as $class ){
            // create a reflection to that class
            $reflected = new \ReflectionClass( $class );
            // and check if it's a framework base class
            if( $reflected->isSubclassOf( 'Framework\Base\Base' ) && !$reflected->isAbstract() ){
                // create a class instance without constructor
                $instance = $reflected->newInstanceWithoutConstructor();
                if(method_exists($instance,'classBooter')) {
                    // call it without arguments and boot it
                    call_user_func(array($instance, 'classBooter'),$instance);
                }
            }
        }
    }

    public static function loadRoute(){
        // try to get the route
        if($handle = Route::handle()){
            // if it's successful, stream it
            Stream::handle($handle);
        }
        
        // otherwise drop a 404 error
        return Error::NotFound();
    }

}