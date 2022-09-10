<?php

namespace Framework\App;

class Error {
    
    public static function NotFound($title = 'Not Found',$message = 'This resource could not be found'){

        view('errors/index',[
            'code' => 404,
            'title' => $title ?: 'Not Found',
            'message' => $message ?: 'This resource could not be found',
        ],404);
        exit;
    }

    public static function ServerError($title = NULL,$message = NULL){
        view('errors/index',[
            'code' => 500,
            'title' => $title ?: 'Internal Server Error',
            'message' => $message ?: 'Something wrong happened on the server',
        ],500);
        exit;
    }

}