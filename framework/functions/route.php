<?php

use Routing\Route;

function route(?string $name = NULL, $params = [], bool $by_path = false){
    if(is_null($name)) return Route::getName();
    if(!is_array($params)) $params = [$params];
    return Route::getRoute($name, $params, $by_path);
}