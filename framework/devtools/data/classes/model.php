<?php

namespace DEV;


class Model extends ClassROOT {

    private static $model_root = ROOT . '/app/models/';

    public static function main($args){
        $args = self::mkprops($args,true);
        if(isset($args['name'])){
            $name = toCamelCase($args['name'], true);
            $model_file = self::$model_root . $args['name'] . '.php';
            if(!file_exists($model_file)){
                $table = $args['table'] ?: 'Please Fill Me :)';
                createPath(self::$model_root);
    
                $content = file_get_contents(__DIR__ . '/model.file');
                $content = str_replace('{ModelName}',$name,$content);
                $content = str_replace('{ModelTable}',$table,$content);
                
                file_put_contents($model_file, $content);
                headerPrintBg("{$name} Model Successfully created!",true);
                exit;
            }
            headerPrintBg("{$name} Model Already exists!", true);
            exit;
        } else {
            _e('Unknow model command :/',true);
        }
    }

}