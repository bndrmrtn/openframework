<?php

namespace Framework\App;

use Framework\App\Request;
use Framework\Base\Controller;
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
            $props = Route::$props;

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

}