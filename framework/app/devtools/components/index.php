<?php

namespace App;

class Component {

    private static $root = FRAMEWORK . '/extensions/use_components/data';
    private static $clone = FRAMEWORK . '/extensions/use_components/data/clone';
    private static $dir = ROOT . '/serve/components';
    private static $args = [];

    public static function action($args){
        self::$args = $args;
        if(count($args) == 2){
            if($args[0] == 'create' || $args[0] == 'c'){
                self::create(self::toCamelCase($args[1],true));
            } else if($args[0] == 'delete' || $args[0] == 'd'){
                self::delete(self::toCamelCase($args[1],true));
            }
        } else {
            echo 'Too few arguments given';
        }
    }

    private static function isExists($name){
        return is_dir(self::$dir . '/' . strtolower($name));
    }

    private static function create($name){
        if(self::isExists($name)){
            echo 'Component already found under "' . $name . '" name';
            return;
        } 
        echo "Generating component...\n";
        $dirName = strtolower($name);
        $dirFullPath = self::$dir . '/' . $dirName;
        //create component dir
        mkdir($dirFullPath);

        //create component loader file
        $loader = file_get_contents(self::$clone . '/load.php');
        $loader = str_replace('_COMPONENTNAME_',$name,$loader);
        file_put_contents($dirFullPath . '/load.php',$loader);

        //create component file
        $component = file_get_contents(self::$clone . '/component.php');
        file_put_contents($dirFullPath . '/component.php',$component);

        // require the component in the components config file
        file_put_contents(self::$root . '/components.php', "\nComponents::create('{$name}','/{$dirName}');\n", FILE_APPEND);
        echo 'Component successfully created';
    }

    private static function delete($name){
        // check if the directory exists
        if(!self::isExists($name)){
            echo 'The "'.$name.'" component does not exist';
            return;
        }
        // require the directory deleter function
        require ROOT . '/assets/functions/deletedir.php';
        // set dir name and path
        $dirName = strtolower($name);
        $dirFullPath = self::$dir . '/' . $dirName;
        // delete the dir
        deleteDir($dirFullPath);

        // remove the component from the config file
        //require the file string replacer
        require ROOT . '/assets/functions/replace_string_in_file.php';
        replace_string_in_file(self::$root . '/components.php',"\n\nComponents::create('{$name}','/{$dirName}');",'');
        echo 'Component successfully deleted';
    }

    private static function toCamelCase($string, $capitalizeFirstCharacter = false){
        $str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }

}