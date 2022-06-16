<?php

class Vue {
    public static function getRoutes($_vue_route_file){
        if(!_env('APP_DEV')){
            include ROOT . '/storage/vue/routes.php';
        } else {
            $routes = file_get_contents($_vue_route_file);
            Header::json();
            $routes = str_replace("{\n  ",'',string_between($routes,'/*_ROUTES_START*/[',']/*_ROUTES_END*/'));
            var_dump(explode('}',$routes));
        }
    }
}