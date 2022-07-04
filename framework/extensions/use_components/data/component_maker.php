<?php

const COMPONENT_ROOT = ROOT . '/serve/components';

class Components {
    private static $comps = [];
    protected static $component = '/component.php';

    public static function create($name,$dir_path){
        self::$comps[$name] = [
            'dir'=>COMPONENT_ROOT . $dir_path
        ];
    }

    public static function import($name){
        if(isset(self::$comps[$name])){
            include_once self::$comps[$name]['dir'] . '/load.php';
        } else {
            echo 'The "' . $name . '" component not found. The components are case sensitive so check it and try again.';exit;
        }
    }

    protected static function render($file,$props = NULL,$autorender = true){
        if($props != NULL){
            foreach($props as $key => $val){
                ${$key} = $val;
            }
        }
        if($autorender){
            include $file;
        } else {
            return $file;
        }
        echo "\n";
    }

    protected static function validateProps($array,$props){
        return true;
    }

}