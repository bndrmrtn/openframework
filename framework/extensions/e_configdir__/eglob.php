<?php

function eglob($key,$val = NULL){
    if(!$val){
        if(isset($GLOBALS['eglob'][$key])) return $GLOBALS['eglob'][$key];
        return NULL;
    } else {
        $GLOBALS['eglob'][$key] = $val;
    }
}