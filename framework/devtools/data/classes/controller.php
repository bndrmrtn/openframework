<?php

namespace DEV;

class Controller extends ClassROOT {

    private static $controller_root = ROOT . '/app/Controllers/';

    public static function main($args){
        $args = self::mkprops($args,true);
        if(isset($args['name'])){
            $mkname = self::mknPath($args['name']);

            $name = toCamelCase($mkname['name'], true);
            $controller_file = self::$controller_root . $mkname['path'] . $name . 'Controller.php';
            if(!file_exists($controller_file)){
                createPath(self::$controller_root . $mkname['path']);
                $content = file_get_contents(__DIR__ . '/controller.file');

                $content = str_replace('{ControllerName}',$name, $content);
                
                file_put_contents($controller_file, $content);
                headerPrintBg("{$name} Controller Successfully created!",true);
                exit;
            }
            headerPrintBg("{$name} Controller Already exists!", true);
            exit;
        } else {
            _e('Unknow model command :/',true);
        }
    }

    public static function mknPath($name){
        if(!str_contains($name, '/')) return [
            'path' => '',
            'name' => $name,
        ];
        $path = explode('/', $name);
        $built = '';
        foreach($path as $key => $value){
            if($key != array_key_last($path)) $built .= toCamelCase($value, true) . '/';
        }
        return [
            'path' => $built,
            'name' => $path[array_key_last($path)],
        ];
    }

}