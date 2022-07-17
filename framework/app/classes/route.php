<?php

class Route {
    private static $routes = [];
    private static $simple = true;

    public static function add($path,$req_file,$allow = true,$custom_no_auth_file = NULL,$other_prms = []){
        $type = '';
        if(!self::$simple) $type = '/[any]';
        
        if($path != '/'){
            if(str_starts_with($path,'/')) $path = substr($path,1);
            if(str_ends_with($path,'/')) $path = substr($path,0,-1);
        }

        $created_path = $path . $type;
        self::$routes[$created_path] = array_merge(['from' => $req_file,'allow' => $allow,'cnlog'=>$custom_no_auth_file],$other_prms);
    }

    public static function group($same_path,array $routes,$same_dir = NULL,$same_allow = true,$same_custom_no_auth_file = NULL){
        foreach($routes as $route){
            $path = $same_path . '/' . $route['path'];

            $file = $route['file'];
            if(!is_null($same_dir)){
                $file = $same_dir . DIRECTORY_SEPARATOR . $file;
            }

            $allow = $same_allow;
            if(!is_null($route['allow'])){
                $allow = $route['allow'];
            }

            $cnlfile = $same_custom_no_auth_file;
            if(!is_null($route['cnlog'])){
                $cnlfile = $route['cnlog'];
            }

            self::add($path,$file,$allow,$cnlfile);
        }
    }

    public static function inGroup($path,$req_file = NULL,$allow = NULL,$custom_no_auth_file = NULL){
        if($req_file == NULL) $req_file = $path;
        return [
            'path'=>$path,
            'file'=>$req_file,
            'allow'=>$allow,
            'cnlog'=>$custom_no_auth_file
        ];
    }

    public static function getArray() {
        return self::$routes;
    }

    public static function setType(bool $type){
        self::$simple = $type;
    }

}