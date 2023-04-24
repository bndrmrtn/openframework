<?php

namespace Core\Framework;

use Core\App\Error;
use Core\App\Session;
use Routing\Route;
use Routing\Stream;
use Tools\THEN;

class Framework {

    private static $runType = null;

    public static function load($type = 'web'){
        self::$runType = $type;

        // get the required directories to load
        $load_dir_files = require __DIR__ . '/dirs.php';

        // foreach all the dirs
        foreach($load_dir_files as $dir){
            // if it's a dir
            if(is_dir(CORE . $dir)){
                // scan all the files inside that dir
                $files = scandir(CORE . $dir);
                // if the dir has files, loop through all the files, and if it's a php file, require it
                if(!empty($files)) foreach($files as $file) if(str_ends_with($file ,'.php')) require CORE . $dir . $file;
            }
        }

        // load models
        self::loadAll(ROOT . '/app/Models');
        self::loadAll(ROOT . '/app/Tools');
        if(self::$runType == 'web') return self::loadWeb();
        else if(self::$runType == 'console') return self::loadConsole();
        echo "Failed to load framework, the specified run type [$type] is not supported";
    }

    public static function isWeb(){
        return self::$runType == 'web';
    }

    public static function isConsole(){
        return self::$runType == 'console';
    }

    private static function loadWeb(){
        // include the database if it's required
        if(_env('USE_DB', false)) require CORE . '/database/loader.php';

        // load controller classes
        self::loadAll(ROOT . '/app/Controllers');

        // boot all the classes that has that functionality
        self::bootClasses();

        require core('applock.generator.php');

        if(file_exists(ROOT . '/app/app.php')) require ROOT . '/app/app.php';

        Session::SingleUse('framework.url.previous', urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
        // return a simple then statement, that runs after that function
        return new THEN();
    }

    private static function loadConsole(){
        // Console Settings
        ini_set('display_errors', 1);
        error_reporting(E_ERROR);
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        
        require CORE . '/devtools/load/loader.php';

        \DEV\DEVLoader::load();

        // include the database if it's required
        if(_env('USE_DB', false)) require CORE . '/database/loader.php';
        else echo "WARNING: Database is not enabled. Enable it in the .env.php file\n";

        // boot all the classes that has that functionality
        self::bootClasses();

        require core('applock.generator.php');

        // return a simple then statement, that runs after that function
        return new Console();
    }

    private static function loadAll($dir){
        if(is_dir($dir)){
            $files = getDirContents($dir);
            if(is_array($files)){
                foreach($files as $file){
                    if(str_ends_with($file, '.php')) require $file;
                }
            }
        }
    }

    private static function bootClasses(){
        // get all the classes
        foreach( get_declared_classes() as $class ){
            // create a reflection to that class
            $reflected = new \ReflectionClass( $class );
            // and check if it's a framework base class
            if( $reflected->isSubclassOf( 'Core\Base\Base' ) && !$reflected->isAbstract() ){
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
