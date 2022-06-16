<?php

class RR {
    private static $config = [];
    public static $props_url = [];

    public static function setup($config_array){
        if(isset($config_array['mainconfig']['after_any']) && isset($config_array['mainconfig']['before_any']) && isset($config_array['mainconfig']['include_path'])){
            self::$config = $config_array;
        } else {
            MErrors::custom(500,'Bad router configuration file. The application died.');
            exit;
        }
    }

    public static function route($add_slash = false){
        $r = self::$config['mainconfig']['after_any']['serialised'];
        if($r == ""){
            return "main";
        } else {
            if($add_slash){
                return $r."/";
            } else {
                return $r;
            }
        }
    }

    public static function route_exp(){
        $e = self::$config['mainconfig']['after_any']['exploded'];
        $re = [];
        foreach($e as $i){
            $re[] = $i;
        }
        return $re;
    }

    public static function home(){
        return self::$config['site_url'];
    }

    public static function addPath($name,$path){
        //self::$routes[$name] = $path;
        $path_raw = explode('/',$path);
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
        self::$props_url[$name] = $arr;
    }

    public static function badProps(){
        MErrors::NotFound();
        exit;
    }

    public static function getProps($autovalidate = false){
        $routes = self::route_exp();
        if($routes == 'main') return ['name'=>'main','uri'=>'/','props'=>[],];
        $props = [];
        $uri = '';
        $rkey = '';
        $n_names = [];
        $n_true = ['n'=>'','true'=>false];
        $alen = count($routes);
        foreach($routes as $i => $route){
            foreach(self::$props_url as $name => $path){
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
            return [
                'name'=>$rkey,
                'uri'=>$uri,
                'props'=>$props,
            ];
        } else {
            if($autovalidate){
                self::badProps();
            }
            return false;
        }
    }

}