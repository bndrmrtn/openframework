<?php

function getFunctionArgs($function){
    $f = new \ReflectionFunction($function);
    $args = array();
    foreach ($f->getParameters() as $param) {
        $args[] = $param->name;   
    }
    return $args;
}