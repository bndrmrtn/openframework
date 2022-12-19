<?php

function view(string $file,array $data = [],?int $code = 200){
    $GLOBALS['views_data__info'][] = [
        'file' => $file,
        'data' => $data,
        'code' => $code,
    ];
    if(!is_null($code)) Header::statuscode($code);
    if(Core\App\Request::wantsJson() || !_env('USE_VIEWS',true)){
        json($data,$code);
    }
    if(!empty($data)){
        foreach($data as $var => $vardata){
            if(is_string($var)){
                ${$var} = $vardata;
                $_bag[$var] = $vardata;
            } else {
                $_bag[] = $vardata;
            }
        }
    }
    include Core\Cache\View::include($file);
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
        'session' => \Core\App\Session::getId(),
        'csrf-token' => \Core\App\Security\Csrf::token(),
    ]);

    echo json_encode($data);
    exit;
}