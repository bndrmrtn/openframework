<?php

namespace DEV;

class Routes {

    public static function list(){

        require ROOT . '/routes/simple.php';
        \Route::setType(false);
        require ROOT . '/routes/routed.php';
        \Route::setType(true);

        $routes = \Route::getArray();

        self::print($routes);

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