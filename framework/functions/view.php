<?php

function view($file,$data = [],?int $code = 200){
    if(!is_null($code)) Header::statuscode($code);
    if(Framework\App\Request::wantsJson() || !_env('USE_VIEWS',true)){
        json($data,$code);
    }
    if(!empty($data)){
        foreach($data as $var => $vardata){
            if(is_string($var)){
                ${$var} = $vardata;
            } else {
                $_bag[] = $vardata;
            }
        }
    }
    include Cache\View::include($file);
}

function json($data = [],?int $code = 200){
    if(!is_null($code)) Header::statuscode($code);
    Header::json();
    echo json_encode($data);
    exit;
}