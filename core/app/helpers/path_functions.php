<?php

function startStrSlash($str){
    if(str_starts_with($str,'/')){
        return $str;
    }
    return '/' . $str;
}

function endStrSlash($str){
    if(str_ends_with($str,'/')){
        return $str;
    }
    return $str . '/';
}

function root($path){
    return ROOT . startStrSlash($path);
}

function core($path){
    return CORE . startStrSlash($path);
}

function routes($path){
    return ROOT . '/routes' . startStrSlash($path);
}

function url($path = '/'){
    return BASE_URL . startStrSlash($path);
}

function redirect($to = '/'){
    if(!filter_var($to, FILTER_VALIDATE_URL)) $to = url($to);
    header('Location: ' . $to);
    exit;
}

function asset($asset){
    return url($asset);
}

function cache($path){
    return CORE . '/cache' . startStrSlash($path);
}

function urlPath(){
    return urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
}