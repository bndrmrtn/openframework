<?php

namespace Core\App;

use Core\App\Request;
use Core\Base\Controller;
use Routing\Route;

class Response {

    private $request;

    public function __construct(Request $request){
        $this->request = $request;
        return $this;
    }

    public function handle(callable|Controller $handler, $method = NULL){
        if(is_callable($handler) || $handler instanceof Controller){
            $args = getFunctionArgs($handler, $method);
            $built_args = [];
            $props = self::realProps(Route::$props);

            if($args){
                foreach($args as $i => $arg){
                    if($arg == 'request' || $arg == 'req'){
                        $built_args[$i] = $this->request;
                    } else if(isset($props[$arg])){
                        $built_args[$i] = $props[$arg];
                    }
                }
            }
            
            if(is_callable($handler)) {
                $data = $handler(...$built_args);
            } else {
                $data = call_user_func([$handler, $method], ...$built_args);
            }
            if(is_string($data)) echo $data;
            exit;
        }
    }

    private static function realProps(?array $props){
        $real = [];
        foreach($props as $name => $value){
            if(str_contains($name, ':')){
                $exp_name = explode(':', $name);
                if(count($exp_name) == 2){
                    switch(strtolower($exp_name[0])){
                        case 'int':
                            $value = intval($value);
                        break;
                        case 'float':
                            $value = floatval($value);
                        break;
                        case 'bool':
                            $value = boolval($value);
                        break;
                        case 'string':
                            $value = strval($value);
                        break;
                        case 'base64':
                            $value = base64_decode($value, true);
                        break;
                    }
                    $real[$exp_name[1]] = $value;
                } else {
                    $real[$name] = $value;
                }
            } else {
                $real[$name] = $value;
            }
        }
        return $real;
    }

}