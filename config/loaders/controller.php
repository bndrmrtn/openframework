<?php

class controller {
    private static $routes = [];

    public static function loadApp(){
        require ROOT . '/config/config.php';
        require ROOT . '/config/loaders/setup.php';
        self::loadFiles('/assets/functions/');
        self::loadFiles('/app/classes/');
        require ROOT . '/routes/simple.php';
        require ROOT . '/routes/routed.php';
    }

    protected static function loadFiles($from){
        $c = scanDirectory(ROOT . $from);
        if(is_array($c)){
            foreach($c as $i){
                if(str_ends_with($i,'.php')){
                    include_once(ROOT . $from . $i);
                }
            }
        }
    }

    public static function loadFunction($name){
        include_once ROOT . "/assets/functions/$name.php";
    }

    public static function loadClass($name){
        include_once ROOT . "/app/classes/$name.php";
    }

    public static function addRoute($path,$req_file,$allow = true,$custom_no_auth_file = NULL,$other_prms = []){
        self::$routes[$path] = array_merge(['from' => $req_file,'allow' => $allow,'cnlog'=>$custom_no_auth_file],$other_prms);
    }

    public static function addRouteGroup($same_path,array $routes,$same_dir = NULL,$same_allow = true,$same_custom_no_auth_file = NULL){
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

            self::addRoute($path,$file,$allow,$cnlfile);
        }
    }

    public static function inRG($path,$req_file = NULL,$allow = NULL,$custom_no_auth_file = NULL){
        if($req_file == NULL) $req_file = $path;
        return [
            'path'=>$path,
            'file'=>$req_file,
            'allow'=>$allow,
            'cnlog'=>$custom_no_auth_file
        ];
    }

    public static function getRoutes() {
        return self::$routes;
    }

    public static function loadDB() {
        require ROOT . '/database/loader.php';
    }

}