<?php

namespace Routing;

class Route {

    // route data
    private ?string $name = NULL;
    private string $method = '';
    private array $route = [];
    private string $fullpath = '';
    // is authorized
    private $authorize;

    private static array $used = [];
    private static $routes = [];
    public static ?array $props = [];
    private static $route_methods = [];
    private static $route_name;
    private static string $prefix = '';

    // supported request methods
    private static array $supported_methods = [ 'get', 'post', 'put', 'delete' ];

    public function __construct($method, $uri)
    {
        $this->method = $method;
        if($uri != '/'){
            if(!str_starts_with($uri,'/')) $uri = '/' . $uri;
            if(str_ends_with($uri,'/')) $uri = substr($uri, 0, -1);
        }
        $uri = self::$prefix . $uri;
        $this->route = static::makeUri($uri);

        // check if route not declared twice
        if(array_search($uri,array_column(self::$used,$method)) !== false){
            throw new \Exception('This route[' . $uri . '] is already declared');
        }

        $this->fullpath = $uri;
        if(!isset(static::$route_methods[$uri])){
            self::$route_methods[$uri] = [ $method ];
        } else {
            array_push(self::$route_methods[$uri],$method);
        }
        return $this;
    }

    public function name(string $name){
        if(isset(static::$routes[$name])) {
            throw new \Exception('This route name(' . $name . ') is already exists');
        }
        $this->name = $name;
        return $this;
    }

    public function auth($auth){
        $this->authorize = $auth;
        return $this;
    }

    public function control(array|callable $file){
        $key = $this->name;
        if(!$this->name) $key = count(static::$routes) + 1;
        static::$used[] = [ $this->method => $this->fullpath, 'key' => $key ];
        $call = false;
        if(!is_array($file) && is_callable($file)) $call = true;

        static::$routes[$key] = [
            'key' => $key,
            'method' => $this->method,
            'array' =>  $this->route,
            'fullpath' => $this->fullpath,
            'call' => $file,
            'call-function' => $call,
            'authorize' => $this->authorize,
        ];
        return true;
    }

    public static function get($uri){
        return new Route('get',$uri);
    }

    public static function post($uri){
        return new Route('post',$uri);
    }

    public static function put($uri){
        return new Route('put',$uri);
    }

    public static function delete($uri){
        return new Route('delete',$uri);
    }

    public static function all($uri){
        return new RouteAll($uri);
    }

    private static function makeUri($uri){
        $path_raw = explode('/',$uri);
        $path = [];
        foreach($path_raw as $i => $j){
            if($j != ''){
                $path[] = $j;
            }
        }
        $arr = [];
        foreach($path as $i => $j){
            $is_prop = false;
            $prop = string_between($j,'{','}');
            if($prop != ''){
                $is_prop = true;
                $j = $prop;
            }
            $arr[$i] = [
                'path'=>$j,
                'is_prop'=>$is_prop,
            ];
        }
        return $arr;
    }

    public static function handle(){
        $method = \Core\App\Request::method();
        self::load();

        $routes = Router::exploded();
        if($routes == '/') return self::routeByKey(array_search('/',array_column(self::$used,$method,'key')));
        $props = [];
        $uri = '';
        $rkey = '';
        $n_names = [];
        $n_true = ['n'=>'','true'=>false];
        $alen = count($routes);
        foreach($routes as $i => $route){
            foreach(self::$routes as $name => $r){
                $path = $r['array'];
                if(!in_array($name,$n_names) && $alen == count($path)){
                    if(($n_true['n'] == $name && $n_true['true'] == true) || ($n_true['n'] != $name && $n_true['true'] != true)){
                        $n_true['n'] = $name;
                        if(isset($path[$i])){
                            $p = $path[$i]['path'];
                            $is_prop = $path[$i]['is_prop'];
                            if(!$is_prop && $route == $p){
                                $uri .= "/$route";
                                $rkey = $name;
                                $n_true['true'] = true;
                            } else if($is_prop) {
                                $uri .= '/{'.$p.'}';
                                $props[$p] = $route;
                                $rkey = $name;
                                $n_true['true'] = true;
                            } else {
                                $n_true['true'] = false;
                                $uri = '';
                                $rkey = '';
                                $props = [];
                                array_push($n_names,$name);
                            }
                        }
                    }
                }
            }
        }

        if($uri != ''){
            self::$props = $props;
            $route = self::routeByKey($rkey);
            if($route['method'] == $method){
                self::$route_name = $route['key'];
                return $route;
            }
            $fp = $route['fullpath'];
            
            if(array_search($method,self::$route_methods[$fp])){
                $_routes = array_filter(self::$routes, function($x) use($method) {
                    return $x['method'] === $method;
                });
                $k = array_search($fp,array_column($_routes,'fullpath','key'));
                if($k !== false){
                    $route = self::routeByKey($k);
                    self::$route_name = $route['key'];
                    return $route;
                }
            } else {
                if(_env('APP_DEV')) throw new \Exception(strtoupper($method) . ' method not supported on this resource');
                view('errors/index',[
                    'code' => 405,
                    'title' => 'Method Not Allowed',
                    'message' => strtoupper($method) . ' method not allowed at this resource',
                ],405);
                exit;
            }
        }

        return false;
    }

    public static function load(){
        $dir = ROOT . '/routes/';
        if(is_dir($dir)){
            $files = scanDirectory($dir);
            if(!empty($files)) foreach($files as $file){
                self::$prefix = '';
                require endStrSlash($dir) . $file;
            }
        }
    }

    private static function routeByKey($key,$exception = false){
        if(isset(self::$routes[$key])) return self::$routes[$key];
        if(!$exception) return false;
        
        $ex = new \Exception();
        $trace = $ex->getTrace();
        $final_call = $trace[1];
        log_error(0,'Invalid route name (' . $key . ')',$final_call['file'],$final_call['line']);
    }

    public static function find($key){
        return self::routeByKey($key, true);
    }

    public static function getRoute(?string $name,array $props = [],?bool $by_path = false){
        if($by_path){
            if($name != '/'){
                if(!str_starts_with($name,'/')) $name = '/' . $name;
                if(str_ends_with($name,'/')) $name = substr($name, 0, -1);
            }
            $name = array_search($name,array_column(self::$routes,'fullpath','key'));
        }
        $route = self::routeByKey($name, true);
        return self::fillProps($route,$props);
    }

    private static function fillProps($route,array $props = []){
        $build = '/';
        if($route == $build) return url($route);
        foreach($route['array'] as $i => $r){
            if($r['is_prop']) {
                if($prop = $props[array_key_first($props)]){
                    $build .= $prop;
                    array_shift($props);
                } else {
                    throw new \Exception('Route params not passed properly');
                }
            } else {
                $build .= $r['path'];
            }
            if(array_key_last($route['array']) != $i) $build .= '/';
        }
        return url($build);
    }

    public static function getName($exact = true){
        if(!$exact) return toCamelCase(str_replace('.','-',self::$route_name),true);
        return self::$route_name;
    }

    public static function getParams($with_key = false){
        if($with_key) return self::$props;
        return array_values(self::$props);
    }

    public static function devGetRoutes(){
        return self::$routes;
    }

    public static function prefix($pref){
        $pref = startStrSlash($pref);
        if(str_ends_with($pref, '/')) $pref = substr($pref, 0, -1);
        self::$prefix = $pref;
    }

}