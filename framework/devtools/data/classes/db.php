<?php

namespace DEV;

use \DB;

class Database extends ClassROOT {

    private static $args = [];
    private static $setup_dir = FRAMEWORK . '/database/db_setup_scripts/';

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
        if(isset($args['create']) && isset($args['name'])){
            $method = 'create' . ucfirst($args['create']);
            if(method_exists(Database::class,$method)){
                call_user_func(array(Database::class,$method),$args['name']);
                return;
            } else {
                _e('Unknow setup command :/',true);
            }
        }
        _e('Unknow command :/',true);
    }

    private static function setupTables(){
        _e( 'Setting up database tables' . "\n" );
        $setup_dir = self::$setup_dir;
        if(!DB::connected()) _e('The database is not connected',true);
        $dirs = scanDirectory($setup_dir);
        foreach($dirs as $dir){
            if(str_ends_with($dir,'.php')){
                include $setup_dir . $dir;
            }
        }
        _e( 'DB setup files successfully executed' );
    }

    private static function createTable($name){
        _e( 'Creating table setup file' . "\n" );
        $setup_dir = self::$setup_dir;
        if(!DB::connected()) _e('The database is not connected',true);
        $files = scanDirectory($setup_dir);
        $fnum = 0;
        if(!empty($files)){
            $file = $files[array_key_last($files)];
            if(str_contains($file,'-')){
                $num = intval(explode('-',$file)[0]);
                $fnum = $num+1;
            }
        }

        $name = strtolower($name);

        $data = "<?php

\$table = SQL::table('{$name}');

\$table->col('id','bigint',255,false,true);

\$table->setPrimaryKey('id');
\$table->save();";

        file_put_contents($setup_dir . $fnum . '-' . $name . '.php',$data);


        _e( 'Table successfully created at setup_scripts' );
    }

}