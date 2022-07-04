<?php

class MErrors {

    private static $error_file = ROOT . '/assets/errors/index.php';

    public static function Unauthorized(){
        self::display(401,'Unauthorized');
    }

    public static function Forbidden(){
        self::display(403,'Forbidden');
    }

    public static function NotFound(){
        self::display(404,'Not Found');
    }

    public static function BadRequest(){
        self::display(400,'Bad Request');
    }

    public static function ServerError(){
        self::display(500,'Server Error');
    }

    private static function display($code,$title){
        include_once self::$error_file;
        exit;
    }

    public static function custom($code,$title){
        self::display($code,$title);
    }

}