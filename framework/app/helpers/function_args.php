<?php

function getFunctionArgs($function, $method = NULL){
    if($method == NULL) $f = new \ReflectionFunction($function);
    else $f = new \ReflectionMethod($function, $method);
    $args = array();
    foreach ($f->getParameters() as $param) {
        $args[] = $param->name;   
    }
    return $args;
}