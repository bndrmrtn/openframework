<?php

namespace DEV;

class Production extends ClassROOT {

    public static function mode($args){
        if(count($args) == 1){
            $arg = $args[0];
            if($arg == 'on'){
                headerPrintBg('Enabling production mode',true);
                _e();
                self::on();
            }
        } else {
            headerPrintBg('Bad command', true);
        }
    }

    private static function on(){
        Cache::modify(['clear']);
        _e();
        self::cpEnv();
    }

    private static function cpEnv(){
        _e('Configuring env for production mode.');
        $_env = eglob('env');

        $_env['APP_DEV'] = false;
        $_env['RERE_VIEWS'] = false;

        $_env['OPEN_TOKEN'] = hash('sha256', ROOT . '|' . microtime(true) . '|' . VERSION);

        $_exp_env = var_export($_env, true);
        $_exp_env = str_replace("'" . ROOT,"ROOT . '",$_exp_env);
        $_exp_env = str_replace('"' . ROOT,'ROOT . "',$_exp_env);

        $env_file = ROOT . '/.env.php';
        if(file_exists($env_file)){
            $_env_data = file_get_contents($env_file);
            file_put_contents(ROOT . '/.env.dev.php',$_env_data);
            unlink($env_file);
            file_put_contents($env_file,
            "<?php\n\n// This is an auto generated env file\n\nreturn _makeenv({$_exp_env});\n\n// Created at: " . date('Y-m-d H:i:s'));
        }
        _e('.env.php configured successfully');
    }

}