<?php

define('FAWESOME_ICONROOT',ROOT . '/assets/fawesome_icons/');

class Fawesome {

    public static function render($name){
        $icon = FAWESOME_ICONROOT . strtolower($name) . '.php';
        if(file_exists($icon)){
            include $icon;
        }
    }

}