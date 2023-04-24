<?php

namespace Core\App;

use Core\App\Request;
use Core\Base\Controller;
use Core\Base\FilterResult;
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
                    if(str_starts_with($exp_name[0], '@')) {
                        $real[$exp_name[1]] = self::useFilter($value, $exp_name);
                        break;
                    }
                    switch(strtolower($exp_name[0])){
                        case 'int':
                            if(!is_numeric($value)) self::wrongProp();
                            $value = intval($value);
                        break;
                        case 'float':
                            if(!(is_numeric($value) && str_contains($value, '.'))) self::wrongProp();
                            $value = floatval($value);
                        break;
                        case 'bool':
                            if($value === '0' || $value === 'false') {
                                $value = false;
                            } else if($value === '1' || $value === 'true') {
                                $value = true;
                            } else self::wrongProp();
                        break;
                        case 'string':
                            $value = strval($value);
                        break;
                        case 'base64':
                            $value = base64_decode($value, true);
                            if($value === false) self::wrongProp();
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

    private static function useFilter($value, $name){
        $className = 'App\Tools\Routing\Filter\\' . substr($name[0],1);
        if(class_exists($className)){
            $filter = new $className;
            $result = $filter->Match($value);
            if($result instanceof FilterResult){
                $read = $result->read();
                if(!$read->isValid) {
                    $filter->onFail($read);
                    exit;
                }
                return $read->value;
            } else throw new \Exception("Invalid filter data, please check the FilterResult class");
        } else throw new \Exception("Filter class not found");
    }

    private static function wrongProp(){
        Error::NotFound();
    }

}