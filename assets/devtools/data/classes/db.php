<?php

namespace DEV;

use \DB;

class Database extends ClassROOT {

    private static $args = [];

    public static function action($args){
        $args = self::mkprops($args,true);
        self::$args = $args;
        if(isset($args['setup'])){
            $method = 'setup' . ucfirst($args['setup']);
            if(method_exists(Database::class,$method)){
                call_user_func(array(Database::class,$method));
                return;
            } else {
                _e('Unknow setup command :/',true);
            }
        }
        _e('Unknow command :/',true);
    }

    private static function setupTables(){
        _e( 'Setting up database tables' . "\n" );
        $setup_dir = FRAMEWORK . '/database/setup_scripts/';
        if(!DB::connected()) _e('The database is not connected',true);
        $dirs = scanDirectory($setup_dir);
        foreach($dirs as $dir){
            if(str_ends_with($dir,'.php')){
                include $setup_dir . $dir;
            }
        }
        _e( 'DB setup files successfully executed' );
    }

}