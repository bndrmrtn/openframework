<?php

namespace Routing;

class Router {

    public static function route(){
        $route = substr($_SERVER['REQUEST_URI'],1);
        if(str_starts_with($route,'?')) $route = '';
        $route = substr($route,URL_SUBSTR_COUNT);
        $route = strtok($route,'?');
        if($route == '') $route = '/';
        return $route;
    }

    public static function exploded(){
        $route = self::route();
        if($route == '/') return $route;
        return(array_filter(explode('/',$route),function($var){
            return $var !== '';
        }));
    }

}