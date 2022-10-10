<?php

namespace Framework\App;

use Framework\App\Request;
use Routing\Route;

class Response {

    private $request;

    public function __construct(Request $request){
        $this->request = $request;
        return $this;
    }

    public function get(callable $call){
        if($this->request->method() == 'get'){
            return $this->handle($call);
        }
        return $this;
    }

    public function post(callable $call){
        if($this->request->method() == 'post'){
            return $this->handle($call);
        }
        return $this;
    }

    public function put(callable $call){
        if($this->request->method() == 'put'){
            return $this->handle($call);
        }
        return $this;
    }

    public function delete(callable $call){
        if($this->request->method() == 'delete'){
            return $this->handle($call);
        }
        return $this;
    }

    private function handle($handler){
        if(is_callable($handler)){
            $args = getFunctionArgs($handler);
            $built_args = [];
            $props = Route::$props;
            
            if($args){
                foreach($args as $i => $arg){
                    if($arg == 'request'){
                        $built_args[$i] = $this->request;
                    } else if(isset($props[$arg])){
                        $built_args[$i] = $props[$arg];
                    }
                }
            }

            $data = $handler(...$built_args);
            if(is_string($data)) echo $data;
            exit;
        }
    }

}