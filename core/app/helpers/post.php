<?php

use Core\Framework\Framework;

if(Framework::isWeb()){
    $GLOBALS['functions']['post'] = NULL;

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $_P = file_get_contents('php://input');
        $_P = json_decode($_P,true);
        if($_P == NULL){
            $_P = $_POST;
        }
    } else {
        $_P = [];
    }

    $GLOBALS['functions']['post'] = $_P;
    if($GLOBALS['functions']['post'] == []){
        $GLOBALS['functions']['post'] = NULL;
    }

    function post($only_req_mode = false){
        if(!$only_req_mode) return $GLOBALS['functions']['post'];
        return $_SERVER["REQUEST_METHOD"] == "POST";
    }
}


/**
 * the post() function is the new $_POST variable, it could read info from php://input
 * so it don't required to send data with multipart formdata
 * it also return null if no sent data or the request is not POST
 */