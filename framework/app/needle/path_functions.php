<?php

function startStrSlash($str){
    if(str_starts_with('/',$str)){
        return $str;
    }
    return '/' . $str;
}

function root($path){
    return ROOT . startStrSlash($path);
}

function framework($path){
    return FRAMEWORK . startStrSlash($path);
}

function assets($path){
    return ROOT . '/assets' . startStrSlash($path);
}

function routes($path){
    return ROOT . '/routes' . startStrSlash($path);
}