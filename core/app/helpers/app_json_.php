<?php

function app_json_exit($msg_or_array,$ito_e = false){
    $data = [
        'status'=>[
            'code'=>200,
            'message'=>'error'
        ],
    ];
    if($ito_e){
        $data['errors'] = $msg_or_array;
    } else {
        $data['errors'] = [ $msg_or_array ];
    }
    echo json_encode($data);
    exit;
}



function app_json_success($msg_or_array,$ito_e = false){
    $data = [
        'status'=>[
            'code'=>200,
            'message'=>'error'
        ],
    ];
    if($ito_e){
        $data['data'] = $msg_or_array;
    } else {
        $data['data'] = [ $msg_or_array ];
    }
    echo json_encode($data);
    exit;
}