<?php

namespace Core\App;

class RegEx {

    public static function get($type){
        switch($type){
            case 'username':
                return "/^[a-zA-Z0-9_]+$/";
            break;
            case 'name':
                return "/^[a-zA-Z -]+$/";
            break;
            case 'number':
                return "/^[0-9]+$/";
            break;
            case 'url':
                return "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
            break;
            case 'phone':
                return '%^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$%i';
            break;
            default:
                return "/^[a-zA-Z0-9_]+$/";
            break;
        }
    }

    public static function is_username($username){
        if(preg_match(self::get('username'),$username)){
            return true;
        } else {
            return false;
        }
    }

    public static function is_name($name){
        if(preg_match(self::get('name'),$name)){
            return true;
        } else {
            return false;
        }
    }

    public static function is_number($number){
        if(preg_match(self::get('number'),$number)){
            return true;
        } else {
            return false;
        }
    }

    public static function is_email($email){
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    public static function is_url($url){
        if(preg_match(self::get('url'),$url)) {
            return true;
        } else {
            return false;
        }
    }

    public static function escape($string, $type = 'str-num'){
        if($type == 'str-num'){
            $replaced = preg_replace("/[^A-Za-z0-9_ ]/", '', $string);
        } else if($type == "num"){
            $replaced = preg_replace("/[^0-9]/", '', $string);
        } else if($type == "str"){
            $replaced = preg_replace("/[^A-Za-z]/", '', $string);
        }
        return $replaced;
    }

}