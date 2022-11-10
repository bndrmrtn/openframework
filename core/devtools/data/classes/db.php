<?php

namespace DEV;

use \DB;

class Database extends ClassROOT {

    private static $args = [];
    private static $setup_dir = ROOT . '/app/tables/';

    public static function action($args){
        if(!DB::connected()) _e('The database is not connected',true);
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
                $m = false;
                if(isset($args['with']) && $args['with'] == 'model') $m = true;
                call_user_func(array(Database::class,$method),$args['name'], $m);
                return;
            } else {
                _e('Unknow create command :/',true);
            }
        }
        if(isset($args['delete'])){
            $method = 'delete' . ucfirst($args['delete']);
            if(method_exists(Database::class,$method)){
                call_user_func(array(Database::class,$method));
                return;
            } else {
                _e('Unknow delete command :/',true);
            }
        }
        _e('Unknow command :/',true);
    }

    private static function setupTables(){
        _e( 'Setting up database tables' . "\n" );
        $setup_dir = self::$setup_dir;
        $dirs = scanDirectory($setup_dir);
        foreach($dirs as $dir){
            if(str_ends_with($dir,'.php')){
                include $setup_dir . $dir;
            }
        }
        _e( 'DB setup files successfully executed' );
    }

    private static function createTable($name, $model = false){
        _e( 'Creating table setup file' . "\n" );
        $setup_dir = self::$setup_dir;
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
        if($model){
            Model::main([
                'name:' . $name,
                'table:' . $name,
            ]);
        }
    }

    private static function show_tables(){
        return DB::query('SHOW TABLES');
    }

    public static function deleteTables(){
        $tables = self::show_tables();
        
        if(choice('Are you sure you want to delete ', count($tables) . ' tables and all of it\'s data?')){
            $del = '';
            foreach($tables as $table){
                $del .= "DROP TABLE {$table};";
            }

            DB::exec('SET foreign_key_checks = 0;');
            $exec = DB::exec($del);
            DB::exec('SET foreign_key_checks = 1;');

            if($exec){
                _e(count($tables) . ' Tables successfully deleted.');
            } else {
                _e('Something went wrong.');
            }
        } else {
            _e('Exiting...');
        }
    }

}