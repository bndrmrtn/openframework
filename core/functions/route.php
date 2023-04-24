<?php

use Routing\Route;

function route(?string $name = NULL, $params = [], bool $by_path = false){
    if(is_null($name)) return Route::getName();
    if(!is_array($params)) $params = [$params];
    return Route::getRoute($name, $params, $by_path);
}

function routeName($exact = false){
    return Route::getName($exact);
}

function routeParams($with_key = false){
    return Route::getParams($with_key);
}

function thisRoute(){
    return route(routeName(true), routeParams());
}