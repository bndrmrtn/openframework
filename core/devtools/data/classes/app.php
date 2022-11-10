<?php

namespace DEV;

class App {

    protected static $args = [];
    private static $config = [];

    public function initialize($args,array $extensions = [],$helpers = []){
        array_shift($args);
        if(!isset($args[0])) _e( 'Please define minimum 1 argument', true );
        static::$args = $args;
        $GLOBALS['e_req_enabled'] = $extensions;
        require CORE . '/extensions/e_configdir__/index.php';
        if(!defined('BASE_URL')) define('BASE_URL',_env('PRODUCTION_URL'));
        if(_env('USE_DB', false)) require CORE . '/database/loader.php';
        if(!empty($helpers)){
            foreach($helpers as $helper){
                require CORE . '/app/helpers/' . $helper . '.php';
            }
        }
        Serve::$config['port'] = _env('DEV_PORT', 7000);
    }

    protected static function mainCmd(){
        return static::$args[0];
    }

    public function config($config){
        static::$config = $config;
        static::$config['cmds'] = require DEVROOT . '/cmds.php';
    }

    public function handleCmd(){

        $cmds = static::$config['cmds'];
        if(!isset(static::$args[1])) {
            $cmds = $cmds['noargs'];
        } else {
            $cmds = $cmds['args'];
        }
        if(isset($cmds[static::mainCmd()])){
            $cmd = $cmds[static::mainCmd()];
            
            if(isset($cmd[0]) && isset($cmd[1])){
                $args = static::$args;
                array_shift($args);
                call_user_func(array($cmd[0], $cmd[1]),$args);
            }
        } else {
            headerPrintBg('Unknow command "' . static::mainCmd() . '" :/',true);
            exit;
        }

    }

    public static function api($k){
        if(isset(self::$config['api'][$k])) return self::$config['api'][$k];
        return NULL;
    }

}