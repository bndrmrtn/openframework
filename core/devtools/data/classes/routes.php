<?php

namespace DEV;

use Routing\Route;

class Routes {

    public static function list(){

        require CORE . '/classes/routes.php';
        Route::load();
        dd(Route::class);

    }

    private static function print($routes){
        $msg = "";
        
        $msg .= "URL :dots: HANDLER\n\n";

        foreach($routes as $path => $data){
            if($path != '/') $path = "/$path";
            $path = str_replace('/[any]',' --routed',$path);
            $msg .= "{$path} :dots: /{$data['from']}.php\n";

        }
        textFillDots($msg);
    }

}