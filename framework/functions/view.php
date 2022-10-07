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
    if(_env('APP_DEV')){
        push_appd($data, [
            '__dev' => [
                'render_time' => getrtime(),
            ],
        ]);
    }
    push_appd($data,[
        'session' => \Framework\App\Session::getId(),
        'csrf-token' => \Framework\App\Security\Csrf::token(),
    ]);

    echo json_encode($data);
    exit;
}